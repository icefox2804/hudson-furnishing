@extends('layouts.guest')

@section('title', $product->name . ' - Hudson Furnishing')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6">
            @if($product->images->count() > 0)
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($product->images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/uploads/' . $image->url) }}" 
                                     class="d-block w-100" 
                                     alt="{{ $image->alt_text }}"
                                     style="height: 400px; object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                    @if($product->images->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    @endif
                </div>
            @else
                <img src="{{ asset('images/HF_Product_1.jpg') }}" 
                     alt="{{ $product->name }}" 
                     class="img-fluid"
                     style="height: 400px; object-fit: cover;">
            @endif
        </div>

        <!-- Product Info -->
        <div class="col-lg-6">
            <h1 class="h2 mb-3">{{ $product->name }}</h1>
            <div class="product-meta mb-3">
                <span class="badge bg-secondary me-2">{{ $product->section->name }}</span>
                <span class="badge bg-info me-2">{{ $product->category->name }}</span>
                <span class="badge bg-warning me-2">{{ $product->brand->name }}</span>
                <span class="badge bg-success">{{ $product->material->name }}</span>
            </div>

            <div class="product-price mb-3">
                @if($product->sale_price)
                    <div class="sale-price-display d-flex flex-column gap-1">
                        <div class="sale-price-large text-danger fw-semibold fs-3">{{ number_format($product->sale_price, 0, ',', ',') }}₫</div>
                        <div class="original-price-small text-muted text-decoration-line-through fs-5">{{ number_format($product->price, 0, ',', ',') }}₫</div>
                    </div>
                @else
                    <div class="price-display d-flex flex-column">
                        <div class="price-large text-danger fw-semibold fs-3">{{ number_format($product->price, 0, ',', ',') }}₫</div>
                    </div>
                @endif
            </div>

            <div class="product-description mb-4">
                <p>{{ $product->description }}</p>
            </div>
            
            <div class="product-stock mb-4">
                @if($product->stock > 0)
                    <span class="text-success"><i class="fas fa-check-circle me-1"></i>Có Sẵn ({{ $product->stock }} sản phẩm)</span>
                @else
                    <span class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>Sản phẩm tạm thời hết hàng</span>
                @endif
            </div>

            <div class="product-actions d-flex gap-3 mb-4">
                @php $isFavorited = auth()->check() && auth()->user()->favorites->contains('product_id', $product->id); @endphp

                <button class="btn {{ $isFavorited ? 'btn-secondary' : 'btn-outline-secondary' }} btn-lg btn-favorite"
                        data-url="{{ route('favorites.toggle', $product->slug) }}"
                        {{ $isFavorited ? 'disabled' : '' }}>
                    <i class="fas fa-heart me-1"></i>
                    {{ $isFavorited ? 'Đã Yêu Thích' : 'Yêu Thích' }}
                </button>
                <!-- Button Interest -->
        <button id="btn-chat-interest" 
            data-product-name="{{ $product->name }}" 
            data-product-link="{{ route('product.show', $product->id) }}" 
            class="btn btn-primary btn-lg">
                    Quan tâm
                </button>
            </div>           
        </div>
    </div>

    <!-- Full Description -->
    
        <div class="col-md-12 mt-5">
            <!-- Nav Tabs -->
            <ul class="nav nav-tabs border-bottom border-primary" id="descTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold text-dark" id="description-tab" data-bs-toggle="tab"
                        data-bs-target="#description" type="button" role="tab">
                        DESCRIPTION
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark" id="reviews-tab" data-bs-toggle="tab"
                        data-bs-target="#reviews" type="button" role="tab">
                        REVIEWS
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content p-4 border border-top-0 rounded-bottom shadow-sm bg-white" id="descTabContent">
                <!-- DESCRIPTION TAB -->
                @if(!empty($product->full_description))
                <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                    <div class="product-full-description fs-6 lh-lg">
                        {!! $product->full_description !!}
                    </div>
                </div>
                @else
                <div>
                    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                        <div class="product-full-description fs-6 lh-lg">
                            {!! $product->description !!}
                        </div>
                    </div>
                </div>
                @endif

                <!-- REVIEWS TAB -->
                <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                    <div class="row mt-5">
                        <div class="col-12">
                            <h3>Đánh Giá Khách Hàng</h3>
                            @if($product->reviews->count() > 0)
                                <div class="row">
                                    @foreach($product->reviews as $review)
                                        <div class="col-md-6 mb-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="card-title">{{ $review->name }}</h6>
                                                        <div class="text-warning">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    <p class="card-text">{{ $review->comment }}</p>
                                                    <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">Không có đánh giá nào. Hãy là người đầu tiên đánh giá sản phẩm này!</p>
                            @endif

                            <!-- Review Form -->
                            <div class="card mt-4">
                                <div class="card-body">
                                    <h5>Viết Đánh Giá</h5>
                                    <form id="reviewForm" method="POST" action="{{ route('reviews.store') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <div class="row">
                                            {{-- Nếu người dùng đã đăng nhập --}}
                                            @auth
                                                <div class="col-md-6 mb-3">
                                                    <label for="name" class="form-label">Tên *</label>
                                                    <input type="text" class="form-control" id="name" name="name"
                                                        value="{{ auth()->user()->name }}" readonly>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="email" class="form-label">Email *</label>
                                                    <input type="email" class="form-control" id="email" name="email"
                                                        value="{{ auth()->user()->email }}" readonly>
                                                </div>
                                            @else
                                                {{-- Nếu chưa đăng nhập --}}
                                                <div class="col-md-6 mb-3">
                                                    <label for="name" class="form-label">Tên *</label>
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        id="name" name="name" value="{{ old('name') }}">
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="email" class="form-label">Email *</label>
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        id="email" name="email" value="{{ old('email') }}">
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            @endauth
                                        </div>

                                        <div class="mb-3">
                                            <label for="comment" class="form-label">Nội dung *</label>
                                            <textarea class="form-control @error('comment') is-invalid @enderror"
                                                    id="comment" name="comment" rows="3">{{ old('comment') }}</textarea>
                                            @error('comment')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror

                                            @if(session('status'))
                                                <div class="alert alert-success mt-2">{{ session('status') }}</div>
                                            @endif
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Đánh Giá *</label>
                                            <div class="star-rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star star" data-value="{{ $i }}"></i>
                                                @endfor
                                                <input type="hidden" id="rating-value" name="rating" value="{{ old('rating') }}">
                                            </div>
                                            @error('rating')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary">Gửi Đánh Giá</button>

                                        @if(session('success'))
                                            <div class="alert alert-success mt-3">{{ session('success') }}</div>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            
</div>
<div id="chat-popup">
    <div id="chat-header" class="d-flex justify-content-between align-items-center">
        Bạn Cần Hổ Trợ Gì
        <span id="close-chat">
            <i class="fa-regular fa-circle-xmark fa-lg"></i>
        </span>
    </div>

    <div id="chat-admin-option">
        <label>
            <input type="checkbox" id="chat-with-admin"> Tôi muốn nói chuyện với Admin
        </label>
    </div>

    <div id="chat-messages"></div>

    <div id="chat-input-wrapper">
        <textarea id="chat-input" placeholder="Nhập tin nhắn..."></textarea>
        <button id="send-chat"><i class="fa-solid fa-paper-plane"></i></button>
    </div>
</div>

@endsection

@push('styles')

<style>

    
.product-full-description {
    max-width: 100%;
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
}

.product-full-description img {
    max-width: 100%;
    height: auto;
    border-radius: 6px;
    display: block;
    margin: 15px auto;
}

.product-description-body table {
    width: 100% !important;
    border-collapse: collapse;
}

.product-description-body iframe {
    max-width: 100%;
    height: auto;
}

.product-description-body p {
    margin-bottom: 1rem;
}

.product-description-body ul, 
.product-description-body ol {
    padding-left: 1.5rem;
}




.star-rating .star {
    font-size: 1.2rem;
    color: #ccc;
    cursor: pointer;
    transition: color 0.2s;
}
.star-rating .star.hover,
.star-rating .star.selected {
    color: #ffc107;
}

/* Thêm style riêng cho phần mô tả chi tiết */
.product-full-description {
    background: #fafafa;
}
.product-full-description h3 {
    color: #333;
    border-bottom: 2px solid #ffc107;
    display: inline-block;
    padding-bottom: 5px;
}
.product-full-description .content p {
    margin-bottom: 1rem;
    text-align: justify;
}

/* Popup chat */
#chat-popup {
    display: none;
    position: fixed;
    bottom: 30px;
    right: 120px;
    width: 320px;
    height: 450px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.25);
    z-index: 9999;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    font-family: sans-serif;
    font-size: 14px;
}

/* Header */
#chat-header {
    background: #37616D;
    color: #fff;
    padding: 10px 15px;
    font-weight: 500;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Close icon */
#close-chat {
    cursor: pointer;
}

/* Checkbox chat with admin */
#chat-admin-option {
    padding: 5px 15px;
    border-bottom: 1px solid #eee;
    font-size: 13px;
}

#chat-admin-option input[type="checkbox"] {
    margin-right: 5px;
}

/* Chat messages area */
#chat-messages {
    flex: 1;
    padding: 10px 15px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 8px;
    background: #f9f9f9;
}

/* Message bubbles */
.chat-message {
    padding: 8px 12px;
    border-radius: 20px;
    max-width: 80%;
    word-wrap: break-word;
    font-size: 14px;
}

/* User messages */
.chat-message.user {
    align-self: flex-end;
    background-color: #007bff;
    color: white;
}

/* AI messages */
.chat-message.ai {
    align-self: flex-start;
    background-color: #f1f1f1;
    color: #333;
}

/* Admin messages */
.chat-message.admin {
    align-self: flex-start;
    background-color: #ffe0b2;
    color: #333;
}

/* Input wrapper */
#chat-input-wrapper {
    display: flex;
    align-items: center;
    padding: 8px 10px;
    border-top: 1px solid #eee;
    gap: 5px;
}

/* Textarea */
#chat-input {
    flex: 1;
    height: 50px;
    border-radius: 20px;
    border: 1px solid #ccc;
    padding: 10px 15px;
    resize: none;
    font-size: 14px;
}

/* Send button */
#send-chat {
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 50%;
    background: #37616D;
    color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    transition: background 0.3s;
}

#send-chat:hover {
    background: #43727F;
}

#send-chat i {
    font-size: 16px;
}

/* nội dung bên trong tin nhắn */
#chat-messages {
    max-height: 300px;
    overflow-y: auto;
    padding: 10px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.chat-message {
    padding: 8px 12px;
    border-radius: 12px;
    max-width: 80%;
    word-wrap: break-word;
    font-size: 14px;
}

/* Tin nhắn user (bên phải) */
.chat-message.user {
    align-self: flex-end;
    background-color: #007bff;
    color: white;
}

/* Tin nhắn AI (bên trái) */
.chat-message.ai {
    align-self: flex-start;
    background-color: #f1f1f1;
    color: #333;
}

</style>
@endpush


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-rating .star');
    const ratingInput = document.getElementById('rating-value');

    stars.forEach(star => {
        star.addEventListener('mouseover', () => {
            const val = parseInt(star.getAttribute('data-value'));
            stars.forEach(s => s.classList.remove('hover'));
            stars.forEach(s => {
                if (parseInt(s.getAttribute('data-value')) <= val) s.classList.add('hover');
            });
        });

        star.addEventListener('mouseout', () => {
            stars.forEach(s => s.classList.remove('hover'));
        });

        star.addEventListener('click', () => {
            const val = parseInt(star.getAttribute('data-value'));
            ratingInput.value = val;
            stars.forEach(s => s.classList.remove('selected'));
            stars.forEach(s => {
                if (parseInt(s.getAttribute('data-value')) <= val) s.classList.add('selected');
            });
        });
    });
});


// favorite
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-favorite').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            fetch(this.dataset.url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            }).then(res => res.json())
              .then(data => {
                  alert(data.message);
                  if (data.success) {
                      location.reload(); // reload để cập nhật trạng thái nút
                  }
              });
        });
    });
});

// popup chat
document.addEventListener('DOMContentLoaded', () => {
    const btnChat = document.getElementById('btn-chat-interest');
    const chatPopup = document.getElementById('chat-popup');
    const chatMessages = document.getElementById('chat-messages');
    const chatInput = document.getElementById('chat-input');
    const sendChat = document.getElementById('send-chat');
    const closeChat = document.getElementById('close-chat');
    const adminCheckbox = document.getElementById('chat-with-admin');

    // Tạo session_id duy nhất cho user nếu chưa có
    let sessionId = localStorage.getItem('chat_session_id');
    if (!sessionId) {
        sessionId = crypto.randomUUID();
        localStorage.setItem('chat_session_id', sessionId);
    }

    let ws; // WebSocket sẽ được tạo khi popup mở

    // Hàm xử lý tin nhắn từ server
    function handleMessage(event) {
        const data = JSON.parse(event.data);
        if (data.type !== 'message') return;
        if (data.session_id !== sessionId) return;

        const div = document.createElement('div');
        div.classList.add('chat-message');

        if (data.from === 'ai') {
            div.classList.add('ai');
            div.textContent = `Trợ Lý Ảo: ${data.content}`;
        } else if (data.from === 'user') {
            div.classList.add('user');
            div.textContent = `Bạn: ${data.content}`;
        } else if (data.from === 'user_to_admin') {
            div.classList.add('user');
            div.textContent = `Bạn → Admin: ${data.content}`;
        } else if (data.from === 'admin') {
            div.classList.add('admin');
            div.textContent = `Admin: ${data.content}`;
        }

        chatMessages.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Mở popup khi nhấn nút "Quan tâm"
    btnChat.addEventListener('click', () => {
        const productName = btnChat.dataset.productName;
        const productLink = btnChat.dataset.productLink;

        chatPopup.style.display = 'flex';
        chatInput.value = `Tôi quan tâm sản phẩm: ${productName}\nLink: ${productLink}`;

        // Kết nối WebSocket nếu chưa mở
        if (!ws || ws.readyState === WebSocket.CLOSED) {
            ws = new WebSocket('ws://127.0.0.1:8080');
            ws.onmessage = handleMessage;
        }

        loadChatHistory();
    });

    // Đóng popup
    closeChat.addEventListener('click', () => {
        chatPopup.style.display = 'none';
    });

    // Gửi tin nhắn
    sendChat.addEventListener('click', () => {
        const message = chatInput.value.trim();
        if (!message || !ws || ws.readyState !== WebSocket.OPEN) return;

        const toAdmin = adminCheckbox.checked;

        ws.send(JSON.stringify({
            type: 'message',
            session_id: sessionId,
            username: 'Khách',
            from: toAdmin ? 'user_to_admin' : 'user',
            content: message
        }));

        chatInput.value = '';
    });

    // Load lịch sử chat session hiện tại
    function loadChatHistory() {
        fetch(`/chat/history/${sessionId}`)
            .then(res => res.json())
            .then(messages => {
                chatMessages.innerHTML = '';
                messages.forEach(msg => {
                    const div = document.createElement('div');
                    div.classList.add('chat-message');

                    if (msg.sender === 'user') {
                        div.classList.add('user');
                        div.textContent = `Bạn: ${msg.message}`;
                    } else if (msg.sender === 'ai') {
                        div.classList.add('ai');
                        div.textContent = `Trợ Lý Ảo: ${msg.message}`;
                    } else if (msg.sender === 'user_to_admin') {
                        div.classList.add('user');
                        div.textContent = `Bạn → Admin: ${msg.message}`;
                    } else if (msg.sender === 'admin') {
                        div.classList.add('admin');
                        div.textContent = `Admin: ${msg.message}`;
                    }

                    chatMessages.appendChild(div);
                });
                chatMessages.scrollTop = chatMessages.scrollHeight;
            });
    }
});


</script>
@endpush
