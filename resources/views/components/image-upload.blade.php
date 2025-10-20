@props([
    'name' => 'images',
    'label' => 'Hình ảnh',
    'multiple' => true,
    'required' => false,
    'maxFiles' => 5,
    'acceptedTypes' => 'image/*',
    'maxSize' => '2MB',
    'existingImages' => [],
    'deleteRoute' => null,
    'class' => ''
])

<div class="image-upload-component {{ $class }}">
    <label class="form-label fw-bold">{{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    
    <!-- Upload Area -->
    <div class="upload-area border border-2 border-dashed rounded-3 p-4 text-center mb-3" 
         style="border-color: #dee2e6; background-color: #f8f9fa;">
        <div class="upload-content">
            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
            <h5 class="text-muted mb-2">Kéo thả hình ảnh vào đây</h5>
            <p class="text-muted mb-3">hoặc</p>
            <label for="{{ $name }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Chọn hình ảnh
            </label>
            <input type="file" 
                   id="{{ $name }}" 
                   name="{{ $name }}[]" 
                   class="d-none" 
                   {{ $multiple ? 'multiple' : '' }}
                   accept="{{ $acceptedTypes }}"
                   {{ $required ? 'required' : '' }}>
            <div class="mt-2">
                <small class="text-muted">
                    Tối đa {{ $maxFiles }} hình, mỗi hình không quá {{ $maxSize }}
                </small>
            </div>
        </div>
    </div>

    <!-- Existing Images -->
    @if(count($existingImages) > 0)
        <div class="existing-images mb-3">
            <h6 class="text-muted mb-2">Hình ảnh hiện tại:</h6>
            <div class="row g-2">
                @foreach($existingImages as $index => $image)
                    <div class="col-md-3 col-sm-4 col-6">
                        <div class="image-preview-card position-relative">
                            <img src="{{ is_string($image) ? asset('storage/' . $image) : $image->url }}" 
                                alt="{{ is_string($image) ? 'Logo' : ($image->alt_text ?? 'Product Image') }}"
                                class="img-fluid rounded border"
                                style="width: 100%; height: 120px; object-fit: cover;">                            
                            @if($deleteRoute)
                                <button type="button" 
                                        class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
                                        onclick="deleteImage({{ $image->id }})"
                                        title="Xóa hình ảnh">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                            
                            <div class="image-info p-2">
                                <small class="text-muted d-block">{{ $image->alt_text ?? 'Không có mô tả' }}</small>
                                @if(isset($image->is_primary) && $image->is_primary)
                                    <span class="badge bg-success">Ảnh chính</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Preview Area -->
    <div class="preview-area" style="display: none;">
        <h6 class="text-muted mb-2">Hình ảnh mới:</h6>
        <div class="row g-2" id="image-preview-container">
            <!-- Preview images will be inserted here -->
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="upload-progress mt-3" style="display: none;">
        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                 role="progressbar" style="width: 0%"></div>
        </div>
        <small class="text-muted">Đang tải lên...</small>
    </div>
</div>

<style>
.image-upload-component .upload-area {
    transition: all 0.3s ease;
    cursor: pointer;
}

.image-upload-component .upload-area:hover {
    border-color: #0d6efd !important;
    background-color: #e7f1ff !important;
}

.image-upload-component .upload-area.dragover {
    border-color: #0d6efd !important;
    background-color: #e7f1ff !important;
    transform: scale(1.02);
}

.image-preview-card {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    overflow: hidden;
    transition: all 0.3s ease;
}

.image-preview-card:hover {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transform: translateY(-2px);
}

.image-info {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

.preview-image {
    position: relative;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    overflow: hidden;
}

.preview-image img {
    width: 100%;
    height: 120px;
    object-fit: cover;
}

.remove-preview {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(220, 53, 69, 0.9);
    color: white;
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    cursor: pointer;
}

.remove-preview:hover {
    background: #dc3545;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.querySelector('.upload-area');
    const fileInput = document.getElementById('{{ $name }}');
    const previewContainer = document.getElementById('image-preview-container');
    const previewArea = document.querySelector('.preview-area');
    const progressBar = document.querySelector('.upload-progress');
    const progressBarInner = document.querySelector('.progress-bar');
    
    let selectedFiles = [];

    // Drag and drop functionality
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = Array.from(e.dataTransfer.files);
        handleFiles(files);
    });

    // Click to upload
    uploadArea.addEventListener('click', function() {
        fileInput.click();
    });

    // File input change
    fileInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        handleFiles(files);
    });

    // Handle selected files
    function handleFiles(files) {
        const validFiles = files.filter(file => {
            // Check file type
            if (!file.type.startsWith('image/')) {
                alert('Chỉ được chọn file hình ảnh!');
                return false;
            }
            
            // Check file size (2MB = 2 * 1024 * 1024 bytes)
            if (file.size > 2 * 1024 * 1024) {
                alert('Kích thước file không được vượt quá 2MB!');
                return false;
            }
            
            return true;
        });

        if (validFiles.length === 0) return;

        // Check max files limit
        if (selectedFiles.length + validFiles.length > {{ $maxFiles }}) {
            alert('Không được chọn quá {{ $maxFiles }} hình ảnh!');
            return;
        }

        selectedFiles = [...selectedFiles, ...validFiles];
        updatePreview();
        updateFileInput();
    }

    // Update preview
    function updatePreview() {
        if (selectedFiles.length === 0) {
            previewArea.style.display = 'none';
            return;
        }

        previewArea.style.display = 'block';
        previewContainer.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'col-md-3 col-sm-4 col-6';
                previewDiv.innerHTML = `
                    <div class="preview-image">
                        <img src="${e.target.result}" alt="Preview ${index + 1}">
                        <button type="button" class="remove-preview" onclick="removePreview(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                previewContainer.appendChild(previewDiv);
            };
            reader.readAsDataURL(file);
        });
    }

    // Remove preview
    window.removePreview = function(index) {
        selectedFiles.splice(index, 1);
        updatePreview();
        updateFileInput();
    };

    // Update file input
    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
    }

    // Delete existing image
    @if($deleteRoute)
    window.deleteImage = function(imageId) {
        if (confirm('Bạn có chắc muốn xóa hình ảnh này?')) {
            fetch('{{ $deleteRoute }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ image_id: imageId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra khi xóa hình ảnh!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi xóa hình ảnh!');
            });
        }
    };
    @endif
});
</script>
