// Product Modal Handler
document.addEventListener("DOMContentLoaded", function () {
    // Handle product detail modal
    const productModal = document.getElementById("productModal");
    const viewDetailButtons = document.querySelectorAll(".view-details");

    viewDetailButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const productId = this.getAttribute("data-product");
            loadProductDetails(productId);
        });
    });

    // Load product details via AJAX
    function loadProductDetails(productId) {
        fetch(`/product/${productId}`)
            .then((response) => response.json())
            .then((product) => {
                updateProductModal(product);
            })
            .catch((error) => {
                console.error("Error loading product details:", error);
            });
    }

    // Update modal content
    function updateProductModal(product) {
        document.getElementById("productModalTitle").textContent = product.name;
        document.getElementById("productModalBody").innerHTML =
            generateProductHTML(product);
    }

    // Generate product HTML
    function generateProductHTML(product) {
        return `
            <div class="row">
                <div class="col-md-6">
                    <img src="/uploads/products/${
                        product.images[0].url
                    }" class="img-fluid rounded" alt="${product.name}">
                </div>
                <div class="col-md-6">
                    <h4>${product.name}</h4>
                    <p><strong>Brand:</strong> ${product.brand.name}</p>
                    <p><strong>Material:</strong> ${product.material.name}</p>
                    <p><strong>Section:</strong> ${product.section.name}</p>
                    <p><strong>Price:</strong> $${product.price}</p>
                    ${
                        product.sale_price
                            ? `<p><strong>Sale Price:</strong> $${product.sale_price}</p>`
                            : ""
                    }
                    <p>${product.description}</p>
                    
                    <!-- Reviews -->
                    <div class="mt-3">
                        <h6>Reviews</h6>
                        ${product.reviews
                            .map(
                                (review) => `
                            <div class="border-bottom pb-2 mb-2">
                                <strong>${review.name}</strong>
                                <div class="text-warning">
                                    ${"★".repeat(review.rating)}${"☆".repeat(
                                    5 - review.rating
                                )}
                                </div>
                                <p class="mb-0">${review.comment}</p>
                            </div>
                        `
                            )
                            .join("")}
                    </div>
                </div>
            </div>
        `;
    }
});

// Contact Form Handler
document.addEventListener("DOMContentLoaded", function () {
    const contactForm = document.getElementById("contactForm");
    if (contactForm) {
        contactForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch("/contact", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        alert(data.message);
                        this.reset();
                    } else {
                        alert("Error sending message. Please try again.");
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    alert("Error sending message. Please try again.");
                });
        });
    }
});

// Review Form Handler
// document.addEventListener("DOMContentLoaded", function () {
//     const reviewForm = document.getElementById("reviewForm");
//     if (reviewForm) {
//         reviewForm.addEventListener("submit", function (e) {
//             e.preventDefault();

//             const formData = new FormData(this);

//             fetch("/reviews", {
//                 method: "POST",
//                 body: formData,
//                 headers: {
//                     "X-CSRF-TOKEN": document
//                         .querySelector('meta[name="csrf-token"]')
//                         .getAttribute("content"),
//                 },
//             })
//                 .then((response) => response.json())
//                 .then((data) => {
//                     if (data.success) {
//                         alert(data.message);
//                         this.reset();
//                     } else {
//                         alert("Error submitting review. Please try again.");
//                     }
//                 })
//                 .catch((error) => {
//                     console.error("Error:", error);
//                     alert("Error submitting review. Please try again.");
//                 });
//         });
//     }
// });

// Visitor Counter
document.addEventListener("DOMContentLoaded", function () {
    const visitorCountElement = document.getElementById("visitor-count");
    if (visitorCountElement) {
        fetch("/visitor-stats", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    visitorCountElement.textContent = data.total_visits;
                }
            })
            .catch((error) => {
                console.error("Error updating visitor count:", error);
            });
    }
});

// Time Update
document.addEventListener("DOMContentLoaded", function () {
    const currentTimeElement = document.getElementById("current-time");
    if (currentTimeElement) {
        function updateTime() {
            const now = new Date();
            currentTimeElement.textContent = now.toLocaleString();
        }
        updateTime();
        setInterval(updateTime, 1000);
    }
});

// Smooth Scrolling
document.addEventListener("DOMContentLoaded", function () {
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach((link) => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute("href"));
            if (target) {
                target.scrollIntoView({
                    behavior: "smooth",
                });
            }
        });
    });
});

// Loading States
function showLoading(element) {
    element.classList.add("loading");
    element.disabled = true;
}

function hideLoading(element) {
    element.classList.remove("loading");
    element.disabled = false;
}

// Toast Notifications (removed duplicate - using enhanced version below)

// Form Validation
function validateForm(form) {
    const requiredFields = form.querySelectorAll("[required]");
    let isValid = true;

    requiredFields.forEach((field) => {
        if (!field.value.trim()) {
            field.classList.add("is-invalid");
            isValid = false;
        } else {
            field.classList.remove("is-invalid");
        }
    });

    return isValid;
}

// Image Lazy Loading
document.addEventListener("DOMContentLoaded", function () {
    const images = document.querySelectorAll("img[data-src]");
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove("lazy");
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach((img) => imageObserver.observe(img));
});

// Search Functionality
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener("input", function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Implement search functionality here
                console.log("Searching for:", this.value);
            }, 300);
        });
    }
});

// Filter Functionality
document.addEventListener("DOMContentLoaded", function () {
    const filterForm = document.querySelector('form[method="GET"]');
    if (filterForm) {
        const filterInputs = filterForm.querySelectorAll("select, input");
        filterInputs.forEach((input) => {
            input.addEventListener("change", function () {
                // Auto-submit form when filters change
                filterForm.submit();
            });
        });
    }
});

// Product Card Hover Effects
document.addEventListener("DOMContentLoaded", function () {
    const productCards = document.querySelectorAll(".product-card");
    productCards.forEach((card) => {
        card.addEventListener("mouseenter", function () {
            this.style.transform = "translateY(-5px)";
        });

        card.addEventListener("mouseleave", function () {
            this.style.transform = "translateY(0)";
        });
    });
});

// Mobile Menu Toggle
// document.addEventListener('DOMContentLoaded', function() {
//     const navbarToggler = document.querySelector('.navbar-toggler');
//     const navbarCollapse = document.querySelector('.navbar-collapse');

//     if (navbarToggler && navbarCollapse) {
//         navbarToggler.addEventListener('click', function() {
//             navbarCollapse.classList.toggle('show');
//         });
//     }
// });

// Back to Top Button
document.addEventListener("DOMContentLoaded", function () {
    // Tạo nút
    const backToTopButton = document.createElement("button");
    backToTopButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
    backToTopButton.className = "btn-back-to-top";
    backToTopButton.setAttribute("aria-label", "Back to top");
    document.body.appendChild(backToTopButton);

    // Hàm ẩn/hiện nút
    function toggleBackToTop() {
        if (window.scrollY > 300) {
            backToTopButton.classList.add("show", "bounce-in");
        } else {
            backToTopButton.classList.remove("show", "bounce-in");
        }
    }

    window.addEventListener("scroll", toggleBackToTop);
    toggleBackToTop(); // kiểm tra khi load trang

    // Click scroll lên đầu trang
    backToTopButton.addEventListener("click", function () {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
});

// Wishlist Functionality
function addToWishlist(productId) {
    // Check if user is logged in
    const isLoggedIn = document.querySelector('meta[name="user-logged-in"]');
    if (!isLoggedIn || isLoggedIn.getAttribute("content") === "false") {
        showToast("Please login to add items to wishlist", "warning");
        return;
    }

    fetch("/wishlist/add", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({ product_id: productId }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                showToast("Added to wishlist!", "success");
            } else {
                showToast(data.message || "Error adding to wishlist", "danger");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Error adding to wishlist", "danger");
        });
}

// Enhanced Toast Notifications
function showToast(message, type = "info") {
    const toastContainer =
        document.getElementById("toast-container") || createToastContainer();

    const toast = document.createElement("div");
    toast.className = `toast align-items-center text-white bg-${type} border-0 shadow-lg`;
    toast.setAttribute("role", "alert");
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${getToastIcon(type)} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;

    toastContainer.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast, { delay: 4000 });
    bsToast.show();

    toast.addEventListener("hidden.bs.toast", function () {
        toastContainer.removeChild(toast);
    });
}

function createToastContainer() {
    const container = document.createElement("div");
    container.id = "toast-container";
    container.className = "toast-container position-fixed top-0 end-0 p-3";
    container.style.zIndex = "1055";
    document.body.appendChild(container);
    return container;
}

function getToastIcon(type) {
    const icons = {
        success: "check-circle",
        danger: "exclamation-triangle",
        warning: "exclamation-circle",
        info: "info-circle",
    };
    return icons[type] || "info-circle";
}

// Enhanced Form Validation with Real-time Feedback
document.addEventListener("DOMContentLoaded", function () {
    const forms = document.querySelectorAll("form");
    forms.forEach((form) => {
        const inputs = form.querySelectorAll("input, textarea, select");
        inputs.forEach((input) => {
            input.addEventListener("blur", function () {
                validateField(this);
            });

            input.addEventListener("input", function () {
                if (this.classList.contains("is-invalid")) {
                    validateField(this);
                }
            });
        });
    });
});

function validateField(field) {
    const value = field.value.trim();
    const type = field.type;
    const required = field.hasAttribute("required");

    // Remove existing validation classes
    field.classList.remove("is-valid", "is-invalid");

    // Check if field is required and empty
    if (required && !value) {
        field.classList.add("is-invalid");
        return false;
    }

    // Email validation
    if (type === "email" && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            field.classList.add("is-invalid");
            return false;
        }
    }

    // Phone validation
    if (type === "tel" && value) {
        const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
        if (!phoneRegex.test(value.replace(/[\s\-\(\)]/g, ""))) {
            field.classList.add("is-invalid");
            return false;
        }
    }

    // If we get here, field is valid
    if (value) {
        field.classList.add("is-valid");
    }
    return true;
}

// Smooth Page Transitions
document.addEventListener("DOMContentLoaded", function () {
    const links = document.querySelectorAll('a[href^="/"], a[href^="http"]');
    links.forEach((link) => {
        link.addEventListener("click", function (e) {
            // Only apply to internal links
            if (this.hostname === window.location.hostname) {
                e.preventDefault();
                const href = this.getAttribute("href");

                // Add loading state
                document.body.classList.add("loading");

                // Navigate after a short delay for smooth transition
                setTimeout(() => {
                    window.location.href = href;
                }, 150);
            }
        });
    });
});

// Enhanced Product Card Interactions
document.addEventListener("DOMContentLoaded", function () {
    const productCards = document.querySelectorAll(".product-card");
    productCards.forEach((card) => {
        const image = card.querySelector(".product-image img");
        const buttons = card.querySelectorAll(".btn");

        // Add hover effects
        card.addEventListener("mouseenter", function () {
            this.style.transform = "translateY(-8px)";
            this.style.boxShadow = "0 8px 25px rgba(51, 92, 103, 0.2)";

            if (image) {
                image.style.transform = "scale(1.05)";
            }
        });

        card.addEventListener("mouseleave", function () {
            this.style.transform = "translateY(0)";
            this.style.boxShadow = "0 4px 15px rgba(51, 92, 103, 0.1)";

            if (image) {
                image.style.transform = "scale(1)";
            }
        });

        // Add click effects to buttons
        buttons.forEach((button) => {
            button.addEventListener("click", function (e) {
                this.style.transform = "scale(0.95)";
                setTimeout(() => {
                    this.style.transform = "scale(1)";
                }, 150);
            });
        });
    });
});

// Loading Animation for Forms
function showFormLoading(form) {
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) {
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML =
            '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
        submitBtn.disabled = true;

        // Store original text for restoration
        submitBtn.dataset.originalText = originalText;
    }
}

function hideFormLoading(form) {
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn && submitBtn.dataset.originalText) {
        submitBtn.innerHTML = submitBtn.dataset.originalText;
        submitBtn.disabled = false;
    }
}

// Enhanced Contact Form
document.addEventListener("DOMContentLoaded", function () {
    const contactForm = document.getElementById("contactForm");
    if (contactForm) {
        contactForm.addEventListener("submit", function (e) {
            e.preventDefault();

            if (!validateForm(this)) {
                showToast(
                    "Please fill in all required fields correctly",
                    "warning"
                );
                return;
            }

            showFormLoading(this);

            const formData = new FormData(this);

            fetch("/contact", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    hideFormLoading(this);
                    if (data.success) {
                        showToast(data.message, "success");
                        this.reset();
                        // Remove validation classes
                        this.querySelectorAll(".is-valid, .is-invalid").forEach(
                            (field) => {
                                field.classList.remove(
                                    "is-valid",
                                    "is-invalid"
                                );
                            }
                        );
                    } else {
                        showToast(
                            data.message ||
                                "Error sending message. Please try again.",
                            "danger"
                        );
                    }
                })
                .catch((error) => {
                    hideFormLoading(this);
                    console.error("Error:", error);
                    showToast(
                        "Error sending message. Please try again.",
                        "danger"
                    );
                });
        });
    }
});

// Keyboard Navigation Enhancement
document.addEventListener("keydown", function (e) {
    // ESC key to close modals
    if (e.key === "Escape") {
        const modals = document.querySelectorAll(".modal.show");
        modals.forEach((modal) => {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) {
                bsModal.hide();
            }
        });
    }

    // Enter key on buttons
    if (e.key === "Enter" && e.target.tagName === "BUTTON") {
        e.target.click();
    }
});

// Performance Optimization - Debounce Search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Enhanced Search with Debouncing
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        const debouncedSearch = debounce(function (value) {
            if (value.length >= 2) {
                // Implement search functionality here
                console.log("Searching for:", value);
                // You can add AJAX search here
            }
        }, 300);

        searchInput.addEventListener("input", function () {
            debouncedSearch(this.value);
        });
    }
});
