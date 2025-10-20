<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use App\Models\LiveChatSession;
use App\Models\LiveChatMessage;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class LiveChatServer extends Command implements MessageComponentInterface
{
    protected $clients;
    protected $signature = 'ratchet:serve';
    protected $description = 'Run Live Chat WebSocket server';

    public function __construct()
    {
        parent::__construct();
        $this->clients = new \SplObjectStorage;

        // Kiểm tra nếu cột last_product_id chưa tồn tại, tạo luôn (nếu muốn)
        if (!Schema::hasColumn('live_chat_sessions', 'last_product_id')) {
            Schema::table('live_chat_sessions', function (Blueprint $table) {
                $table->unsignedBigInteger('last_product_id')->nullable()->after('username');
            });
        }
    }

    public function handle()
    {
        $server = IoServer::factory(
            new HttpServer(new WsServer($this)),
            8080
        );

        $this->info("WebSocket server chạy tại ws://127.0.0.1:8080");
        $server->run();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "Kết nối mới: {$conn->resourceId}\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        if (!$data) return;

        if ($data['type'] !== 'message') return;

        $productId = null;
        // Hỗ trợ cả /product/123 và /products/123
        if (preg_match('/\/product(?:s)?\/(\d+)/', $data['content'], $matches)) {
            $productId = $matches[1];
        }

        $productInfo = '';
        if ($productId) {
            $product = Product::find($productId);
            if ($product) {
                $productInfo = "Thông tin sản phẩm:\nTên: {$product->name}\nGiá: {$product->price}\nMô tả: {$product->description}";
            }
        }

        // Tạo hoặc lấy session
        $session = LiveChatSession::firstOrCreate(
            ['session_id' => $data['session_id']],
            ['username' => $data['username'] ?? null]
        );

        // Lưu sản phẩm cuối cùng user quan tâm
        if ($productId) {
            $session->last_product_id = $productId;
            $session->save();
        } elseif ($session->last_product_id) {
            $product = Product::find($session->last_product_id);
            if ($product) {
                $stockText = $product->stock > 0 ? "Còn hàng" : "Hết hàng";
                $productInfo = "Tên: {$product->name}\n
                Giá: {$product->price}\n
                Mô tả: {$product->description}\n
                Số lượng còn: {$product->stock}";
            }
        }

        // Lưu sản phẩm vào session nếu có
            if ($productId) {
                $session->last_product_id = $productId;
                $session->save();
            } elseif ($session->last_product_id) {
                $product = Product::find($session->last_product_id);
                if ($product) {
                    $stockText = $product->stock > 0 ? "Còn hàng" : "Hết hàng";
                    $productInfo = "Thông tin sản phẩm:\nTên: {$product->name}\nGiá: {$product->price} VNĐ\nMô tả: {$product->description}\nTình trạng: {$stockText}";
                }
            }

        // Lưu tin nhắn user/Admin/AI
        $message = LiveChatMessage::create([
            'session_id' => $session->session_id,
            'sender' => $data['from'],
            'message' => $data['content']
        ]);

        // Gửi tin nhắn cho tất cả client
        foreach ($this->clients as $client) {
            $client->send(json_encode([
                'type' => 'message',
                'from' => $data['from'],
                'content' => $data['content'],
                'session_id' => $session->session_id,
                'timestamp' => $message->created_at->toISOString()
            ]));
        }

        // Nếu là user gửi đến AI
        if ($data['from'] === 'user') {
            $aiReply = $this->askAI($data['content'], $productInfo);

            $aiMessage = LiveChatMessage::create([
                'session_id' => $session->session_id,
                'sender' => 'ai',
                'message' => $aiReply
            ]);

            foreach ($this->clients as $client) {
                $client->send(json_encode([
                    'type' => 'message',
                    'from' => 'ai',
                    'content' => $aiReply,
                    'session_id' => $session->session_id,
                    'timestamp' => $aiMessage->created_at->toISOString()
                ]));
            }
        }

        // Nếu gửi trực tiếp cho Admin: chỉ cần lưu tin nhắn, broadcast chung ở trên đã gửi tới tất cả client
        if ($data['from'] === 'admin') {
            // Lấy session (nếu chưa có) và lưu tin nhắn
            $session = LiveChatSession::firstOrCreate(
                ['session_id' => $data['session_id']],
                ['username' => $data['username'] ?? null]
            );

            LiveChatMessage::create([
                'session_id' => $session->session_id,
                'sender' => 'admin',
                'message' => $data['content']
            ]);
            // Không broadcast thêm ở đây để tránh duplicate - broadcast đã thực hiện ở trên
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Ngắt kết nối: {$conn->resourceId}\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Lỗi: {$e->getMessage()}\n";
        $conn->close();
    }

    private function askAI($userMessage, $productInfo = '')
    {
        $systemPrompt = 'Bạn là trợ lý tư vấn sản phẩm thân thiện, luôn trả lời ngắn gọn và hữu ích.';
        if ($productInfo) {
            $userMessage .= "\n\n" . $productInfo;
        }

        try {
            $response = Http::withOptions(['verify' => base_path('cacert.pem')])
                ->withHeaders([
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userMessage],
                    ],
                    'max_tokens' => 150,
                ]);

            $data = $response->json();
            return $data['choices'][0]['message']['content'] ?? 'Xin lỗi, tôi chưa hiểu ý bạn.';
        } catch (\Exception $e) {
            return 'Lỗi khi gọi AI: ' . $e->getMessage();
        }
    }
}
