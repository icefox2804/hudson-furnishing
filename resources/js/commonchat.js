// common-chat.js
document.addEventListener("DOMContentLoaded", () => {
    const socket = new WebSocket("ws://127.0.0.1:8080");

    socket.onopen = () => console.log("✅ Admin đã kết nối WebSocket");

    // Xử lý tất cả form gửi tin nhắn (cả index lẫn show)
    const forms = document.querySelectorAll(
        ".send-message-form, #chat-input-form"
    );
    forms.forEach((form) => {
        form.addEventListener("submit", (e) => {
            e.preventDefault();

            let session_id =
                form.dataset.session ||
                form.querySelector('input[name="session_id"]')?.value;
            let input = form.querySelector(
                'input[name="message"], #chat-input'
            );
            let message = input.value.trim();
            if (!message || !session_id) return;

            // Gửi tin nhắn
            socket.send(
                JSON.stringify({
                    type: "message",
                    from: "admin",
                    session_id,
                    content: message,
                })
            );

            input.value = "";
        });
    });

    // Lắng nghe tin nhắn
    socket.onmessage = (event) => {
        const data = JSON.parse(event.data);
        if (data.type !== "message") return;

        // Tìm đúng container chat
        let container;
        if (
            document.querySelector(`#chat-messages`) &&
            data.session_id ===
                document.querySelector("#chat-messages").dataset.session
        ) {
            container = document.querySelector("#chat-messages");
        } else {
            container = document
                .querySelector(
                    `.send-message-form[data-session="${data.session_id}"]`
                )
                ?.closest(".card")
                ?.querySelector(".card-body");
        }

        if (!container) return;

        const div = document.createElement("div");
        div.className = "chat-message " + (data.from || "");
        let name =
            data.from === "admin"
                ? "Admin"
                : data.from === "user"
                ? "Bạn"
                : "Trợ Lý Ảo";
        div.textContent = `${name}: ${data.content}`;
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    };
});
