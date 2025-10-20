// floating
document.addEventListener("DOMContentLoaded", function () {
    const chatFloat = document.getElementById("chatFloat");
    const mainBtn = document.getElementById("chatMainBtn");
    const icon = mainBtn.querySelector("i");

    mainBtn.addEventListener("click", () => {
        chatFloat.classList.toggle("open");
        mainBtn.classList.toggle("active");

        // Đổi icon (comment <-> X)
        if (mainBtn.classList.contains("active")) {
            icon.classList.remove("fa-comment");
            icon.classList.add("fa-xmark");
        } else {
            icon.classList.remove("fa-xmark");
            icon.classList.add("fa-comment");
        }
    });
});

// ==================== Popup chat universal ====================
window.openChatPopup = function (prefillMessage = "") {
    const popup = document.getElementById("chat-popup");
    if (!popup) return;

    const messageInput = popup.querySelector("textarea");
    if (messageInput && prefillMessage) {
        messageInput.value = prefillMessage;
    }

    popup.classList.add("show");
};

// Đóng popup
function closeChatPopup() {
    const popup = document.getElementById("chat-popup");
    if (popup) popup.classList.remove("show");
}

// Gửi tin nhắn
function sendChatMessage() {
    const messageInput = document.querySelector("#chat-popup textarea");
    const message = messageInput.value.trim();
    if (!message) return;

    // TODO: Gọi API gửi message, hiện tại log ra console
    console.log("Send message:", message);

    messageInput.value = "";
    closeChatPopup();
}

// ==================== Event listeners ====================
document.addEventListener("DOMContentLoaded", () => {
    // Nút chat floating
    const chatBtn = document.getElementById("chat-button");
    if (chatBtn) {
        chatBtn.addEventListener("click", () => {
            openChatPopup();
        });
    }

    // Nút đóng popup
    const chatClose = document.getElementById("chat-close");
    if (chatClose) chatClose.addEventListener("click", closeChatPopup);

    // Nút gửi
    const chatSend = document.getElementById("chat-send");
    if (chatSend) chatSend.addEventListener("click", sendChatMessage);

    // Nút "Quan tâm" (trên trang sản phẩm)
    const btnInterest = document.getElementById("btn-interest");
    if (btnInterest) {
        btnInterest.addEventListener("click", () => {
            const productLink = window.location.href;
            const prefillMessage = `Tôi quan tâm sản phẩm này: ${productLink}`;
            openChatPopup(prefillMessage);
        });
    }
});
