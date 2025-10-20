// Admin Panel JavaScript

document.addEventListener("DOMContentLoaded", function () {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    var popoverTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="popover"]')
    );
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll(".alert");
    alerts.forEach((alert) => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Confirm delete actions
    const deleteButtons = document.querySelectorAll("[data-confirm-delete]");
    deleteButtons.forEach((button) => {
        button.addEventListener("click", function (e) {
            if (!confirm("Are you sure you want to delete this item?")) {
                e.preventDefault();
            }
        });
    });

    // Form validation
    const forms = document.querySelectorAll("form[data-validate]");
    forms.forEach((form) => {
        form.addEventListener("submit", function (e) {
            if (!validateForm(form)) {
                e.preventDefault();
            }
        });
    });

    // Image preview for file inputs
    const imageInputs = document.querySelectorAll(
        'input[type="file"][accept*="image"]'
    );
    imageInputs.forEach((input) => {
        input.addEventListener("change", function (e) {
            const files = e.target.files;
            const previewContainer = document.getElementById("image-preview");

            if (previewContainer && files.length > 0) {
                previewContainer.innerHTML = "";

                Array.from(files).forEach((file) => {
                    if (file.type.startsWith("image/")) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const img = document.createElement("img");
                            img.src = e.target.result;
                            img.className = "img-thumbnail me-2 mb-2";
                            img.style.maxWidth = "100px";
                            img.style.maxHeight = "100px";
                            previewContainer.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    });

    // Auto-save form data
    const autoSaveForms = document.querySelectorAll("form[data-auto-save]");
    autoSaveForms.forEach((form) => {
        const formId =
            form.id || "form-" + Math.random().toString(36).substr(2, 9);
        const inputs = form.querySelectorAll("input, textarea, select");

        // Load saved data
        const savedData = localStorage.getItem("form-" + formId);
        if (savedData) {
            const data = JSON.parse(savedData);
            Object.keys(data).forEach((key) => {
                const input = form.querySelector(`[name="${key}"]`);
                if (input) {
                    input.value = data[key];
                }
            });
        }

        // Save data on input change
        inputs.forEach((input) => {
            input.addEventListener("input", function () {
                const formData = new FormData(form);
                const data = {};
                for (let [key, value] of formData.entries()) {
                    data[key] = value;
                }
                localStorage.setItem("form-" + formId, JSON.stringify(data));
            });
        });

        // Clear saved data on form submit
        form.addEventListener("submit", function () {
            localStorage.removeItem("form-" + formId);
        });
    });

    // Bulk actions (support forms named with 'ids[]' or 'selected[]', and forms without a select)
    const bulkActionForms = document.querySelectorAll(
        "form[data-bulk-action], form.bulk-action-form"
    );
    bulkActionForms.forEach((form) => {
        // support multiple checkbox name conventions
        const checkboxes = form.querySelectorAll(
            'input[type="checkbox"][name="selected[]"], input[type="checkbox"][name="ids[]"]'
        );
        const selectAllCheckbox = form.querySelector(
            'input[type="checkbox"][data-select-all]'
        );
        const actionSelect = form.querySelector('select[name="bulk_action"]');
        const submitButtons = form.querySelectorAll('button[type="submit"]');

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener("change", function () {
                checkboxes.forEach((checkbox) => {
                    checkbox.checked = this.checked;
                });
                updateBulkActionButton();
            });
        }

        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener("change", updateBulkActionButton);
        });

        function updateBulkActionButton() {
            const checkedCount = form.querySelectorAll(
                'input[type="checkbox"][name="selected[]"]:checked, input[type="checkbox"][name="ids[]"]:checked'
            ).length;

            submitButtons.forEach((btn) => {
                if (actionSelect) {
                    // If there's a select controlling the action, require it to have a value
                    btn.disabled = checkedCount === 0 || !actionSelect.value;
                } else {
                    // If actions are provided via individual buttons (formaction), enable when any checked
                    btn.disabled = checkedCount === 0;
                }
            });

            // Optionally update text of the first submit button if it's intended to show count
            if (submitButtons.length > 0) {
                const firstBtn = submitButtons[0];
                if (!actionSelect) {
                    // don't override icon buttons; only change text if there is no inner HTML tags
                    if (!firstBtn.querySelector("*")) {
                        firstBtn.textContent =
                            checkedCount > 0
                                ? `Apply to ${checkedCount} item${
                                      checkedCount > 1 ? "s" : ""
                                  }`
                                : "Apply Action";
                    }
                } else {
                    if (!firstBtn.querySelector("*")) {
                        firstBtn.textContent =
                            checkedCount > 0
                                ? `Apply to ${checkedCount} item${
                                      checkedCount > 1 ? "s" : ""
                                  }`
                                : "Apply Action";
                    }
                }
            }
        }

        if (actionSelect) {
            actionSelect.addEventListener("change", updateBulkActionButton);
        }

        // initialize state
        updateBulkActionButton();
    });

    // Search functionality
    const searchInputs = document.querySelectorAll("input[data-search]");
    searchInputs.forEach((input) => {
        let searchTimeout;
        input.addEventListener("input", function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch(input);
            }, 300);
        });
    });

    // Sort functionality
    const sortSelects = document.querySelectorAll("select[data-sort]");
    sortSelects.forEach((select) => {
        select.addEventListener("change", function () {
            const url = new URL(window.location);
            url.searchParams.set("sort_by", this.value);
            url.searchParams.set("sort_order", this.dataset.sortOrder || "asc");
            window.location.href = url.toString();
        });
    });

    // Filter functionality
    const filterInputs = document.querySelectorAll(
        "input[data-filter], select[data-filter]"
    );
    filterInputs.forEach((input) => {
        input.addEventListener("change", function () {
            const form = this.closest("form");
            if (form) {
                form.submit();
            }
        });
    });

    // Toggle sidebar on mobile
    const sidebarToggle = document.querySelector('[data-toggle="sidebar"]');
    const sidebar = document.querySelector(".sidebar");

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener("click", function () {
            sidebar.classList.toggle("show");
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener("click", function (e) {
        if (
            window.innerWidth <= 768 &&
            sidebar &&
            sidebar.classList.contains("show")
        ) {
            if (
                !sidebar.contains(e.target) &&
                !sidebarToggle.contains(e.target)
            ) {
                sidebar.classList.remove("show");
            }
        }
    });

    // Chart initialization
    const chartElements = document.querySelectorAll("canvas[data-chart]");
    chartElements.forEach((canvas) => {
        const chartType = canvas.dataset.chart;
        const chartData = JSON.parse(canvas.dataset.chartData || "{}");

        new Chart(canvas, {
            type: chartType,
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: "top",
                    },
                },
            },
        });
    });

    // Data table initialization
    const dataTables = document.querySelectorAll("table[data-table]");
    dataTables.forEach((table) => {
        // Add sorting, filtering, and pagination functionality
        initializeDataTable(table);
    });

    // File upload progress
    const fileUploads = document.querySelectorAll(
        'input[type="file"][data-upload-progress]'
    );
    fileUploads.forEach((input) => {
        input.addEventListener("change", function () {
            const files = this.files;
            if (files.length > 0) {
                showUploadProgress(files.length);
            }
        });
    });

    // Real-time notifications
    if (window.Echo) {
        // Listen for real-time events
        Echo.channel("admin-notifications")
            .listen("NewContact", (e) => {
                showNotification("New contact message received", "info");
            })
            .listen("NewReview", (e) => {
                showNotification("New review submitted", "warning");
            });
    }
});

// Utility Functions
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

function performSearch(input) {
    const searchTerm = input.value;
    const targetTable = document.querySelector(input.dataset.search);

    if (targetTable) {
        const rows = targetTable.querySelectorAll("tbody tr");
        rows.forEach((row) => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm.toLowerCase())) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }
}

function initializeDataTable(table) {
    // Add sorting functionality
    const headers = table.querySelectorAll("th[data-sort]");
    headers.forEach((header) => {
        header.style.cursor = "pointer";
        header.addEventListener("click", function () {
            sortTable(table, this.dataset.sort);
        });
    });
}

function sortTable(table, column) {
    const tbody = table.querySelector("tbody");
    const rows = Array.from(tbody.querySelectorAll("tr"));

    rows.sort((a, b) => {
        const aVal = a.querySelector(`td:nth-child(${column})`).textContent;
        const bVal = b.querySelector(`td:nth-child(${column})`).textContent;
        return aVal.localeCompare(bVal);
    });

    rows.forEach((row) => tbody.appendChild(row));
}

function showUploadProgress(fileCount) {
    const progressContainer = document.getElementById("upload-progress");
    if (progressContainer) {
        progressContainer.innerHTML = `
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
            </div>
            <small class="text-muted">Uploading ${fileCount} file${
            fileCount > 1 ? "s" : ""
        }...</small>
        `;

        // Simulate progress
        let progress = 0;
        const interval = setInterval(() => {
            progress += 10;
            const progressBar =
                progressContainer.querySelector(".progress-bar");
            progressBar.style.width = progress + "%";

            if (progress >= 100) {
                clearInterval(interval);
                setTimeout(() => {
                    progressContainer.innerHTML = "";
                }, 1000);
            }
        }, 100);
    }
}

function showNotification(message, type = "info") {
    const notification = document.createElement("div");
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText =
        "top: 20px; right: 20px; z-index: 9999; min-width: 300px;";
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        const bsAlert = new bootstrap.Alert(notification);
        bsAlert.close();
    }, 5000);
}

function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

function showLoading(element) {
    element.classList.add("loading");
    element.disabled = true;
}

function hideLoading(element) {
    element.classList.remove("loading");
    element.disabled = false;
}

// Export functions for global use
window.AdminPanel = {
    showNotification,
    confirmAction,
    showLoading,
    hideLoading,
    validateForm,
};

//SweetAlert2
document.addEventListener("DOMContentLoaded", function () {
    const forms = document.querySelectorAll(".form-confirm");
    forms.forEach((form) => {
        // Track last clicked submit button (for formaction)
        const submitButtons = form.querySelectorAll(
            'button[type="submit"], input[type="submit"]'
        );
        submitButtons.forEach((btn) => {
            btn.addEventListener("click", function (ev) {
                // store reference to the clicked button on the form
                form.__lastSubmitButton = ev.currentTarget;
            });
        });

        form.addEventListener("submit", function (e) {
            e.preventDefault();

            Swal.fire({
                title: "<strong>Bạn có chăc mình muốn thực hiện thao tác này</strong>",
                html: "Vẫn muốn tiếp tục?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-trash"></i> xác nhận',
                cancelButtonText: '<i class="fas fa-times"></i> Hủy',
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup: "swal2-modern-popup",
                    title: "swal2-modern-title",
                    confirmButton: "swal2-modern-confirm",
                    cancelButton: "swal2-modern-cancel",
                    htmlContainer: "swal2-modern-html",
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    // Determine which button initiated the submit
                    const submitter = e.submitter || form.__lastSubmitButton;
                    if (submitter) {
                        const fa = submitter.getAttribute("formaction");
                        if (fa) {
                            form.setAttribute("action", fa);
                        }
                        const fm = submitter.getAttribute("formmethod");
                        if (fm) {
                            form.setAttribute("method", fm);
                        }
                    }

                    // Clean up stored submitter
                    delete form.__lastSubmitButton;

                    form.submit();
                }
            });
        });
    });
});

// livechatadmin.js
const socket = new WebSocket("ws://localhost:8080");

socket.onopen = () => {
    socket.send(JSON.stringify({ type: "init", role: "admin" }));
};

socket.onmessage = (event) => {
    const data = JSON.parse(event.data);
    if (data.type === "message") {
        // hiển thị tin nhắn của từng khách (theo session_id)
        console.log(`[${data.session_id}] ${data.from}: ${data.message}`);
    }
};

function sendToUser(sessionId, msg) {
    socket.send(
        JSON.stringify({
            type: "message",
            role: "admin",
            session_id: sessionId,
            message: msg,
        })
    );
}
