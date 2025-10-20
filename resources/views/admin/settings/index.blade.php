@extends('layouts.admin')

@section('title', 'Quản Lý Cài Đặt - Hudson Furnishing')
@section('page-title', 'Quản Lý Cài Đặt')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0">
            <div class="card-header py-4 bg-gradient-primary">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-white text-primary me-3">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <div>
                        <h5 class="m-0 font-weight-bold text-white">Cài Đặt Website</h5>
                        <small class="text-white-50">Cấu hình cai đặt website và thiết lập</small>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class= "form-confirm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Settings Tabs Navigation -->
                    <ul class="nav nav-pills nav-fill mb-4" id="settingsTabs" role="tablist">
                        <!--Tab cài đặt cơ bản-->
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="general-tab" data-bs-toggle="pill" data-bs-target="#general" type="button" role="tab">
                                <i class="fas fa-info-circle me-2"></i>Cơ Bản
                            </button>
                        </li>
                        <!--Tad  Home Section -->
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="home-section-tab" data-bs-toggle="pill" data-bs-target="#home-section" type="button" role="tab">
                                <i class="fas fa-home me-2"></i>Home Section
                            </button>
                        </li>
                        <!--Tab liên hệ-->
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="pill" data-bs-target="#contact" type="button" role="tab">
                                <i class="fas fa-address-book me-2"></i>Liên Hệ
                            </button>
                        </li>
                        <!--Tab socials-->
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="social-tab" data-bs-toggle="pill" data-bs-target="#social" type="button" role="tab">
                                <i class="fas fa-share-alt me-2"></i>Mạng Xã Hội
                            </button>
                        </li>
                        <!-- Tab SEO -->
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="seo-tab" data-bs-toggle="pill" data-bs-target="#seo" type="button" role="tab">
                                <i class="fas fa-search me-2"></i>Cài Đặt SEO
                            </button>
                        </li>
                        <!-- Tab Advanced -->
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="advanced-tab" data-bs-toggle="pill" data-bs-target="#advanced" type="button" role="tab">
                                <i class="fas fa-sliders-h me-2"></i>Cài Đặt Khác
                            </button>
                        </li>
                    </ul>

                    <!-- Settings Tabs Content -->
                    <div class="tab-content" id="settingsTabContent">
                        <!-- General Settings Tab -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="settings-section">
                                        <div class="section-header mb-3">
                                            <h6 class="text-primary mb-1">
                                                <i class="fas fa-info-circle me-2"></i>Thông Tin Cơ Bản
                                            </h6>
                                            <small class="text-muted">Cấu hình thông tin cơ bản của website</small>
                                        </div>
                                        <!-- site name -->
                                        <div class="mb-3">
                                            <label for="site_name" class="form-label">
                                                <i class="fas fa-tag text-primary me-1"></i>Tên Website
                                            </label>
                                            <input type="text" class="form-control" id="site_name" name="site_name" 
                                                   value="{{ $settings['site_name'] ?? 'Hudson Furnishing' }}"
                                                   placeholder="Nhập tên website">
                                        </div>
                                        <!-- tagline -->
                                        <div class="mb-3">
                                            <label for="site_tagline" class="form-label">
                                                <i class="fas fa-quote-right text-primary me-1"></i>Tagline Website
                                            </label>
                                            <input type="text" class="form-control" id="site_tagline" name="site_tagline" 
                                                   value="{{ $settings['site_tagline'] ?? 'Nội thất cao cấp cho mọi không gian' }}"
                                                   placeholder="Nhập tagline website">
                                        </div>
                                        <!-- description -->
                                        <div class="mb-3">
                                            <label for="site_description" class="form-label">
                                                <i class="fas fa-align-left text-primary me-1"></i>Mô Tả Website
                                            </label>
                                            <textarea class="form-control" id="site_description" name="site_description" rows="3" 
                                                      placeholder="Nhập mô tả website">{{ $settings['site_description'] ?? 'Hudson Furnishing cung cấp nội thất cao cấp cho mọi phòng trong ngôi nhà của bạn. Chất lượng thủ công gặp gỡ thiết kế hiện đại.' }}</textarea>
                                        </div>
                                        <!-- keywords -->                                        
                                        <div class="mb-3">
                                            <label for="site_keywords" class="form-label">
                                                <i class="fas fa-tags text-primary me-1"></i>Từ Khóa Website
                                            </label>
                                            <input type="text" class="form-control" id="site_keywords" name="site_keywords" 
                                                   value="{{ $settings['site_keywords'] ?? 'nội thất, furniture, cao cấp, phòng ngủ, phòng khách, phòng ăn, văn phòng' }}"
                                                   placeholder="Nhập từ khóa website">
                                            <small class="form-text text-muted">Separate keywords with commas</small>
                                        </div>
                                        <!--logo-->
                                        <div class="mb-3">
                                            <label for="logo" class="form-label">Logo website</label>
                                            <input type="file" name="logo" id="logo" class="form-control" accept="image/*" onchange="previewLogo(event)">                                            
                                        </div>
                                        <!-- google map -->
                                        <div class="mb-3">
                                        <label for="google_map" class="form-label">
                                            <i class="fa-solid fa-map-location-dot text-primary me-1"></i>Dán link hoặc iframe Google Map
                                        </label>
                                        <textarea 
                                            name="google_map" 
                                            id="google_map" 
                                            class="form-control" 
                                            rows="3"
                                            placeholder="Ví dụ: https://www.google.com/maps/embed?pb=!1m18!... hoặc dán toàn bộ thẻ <iframe>">{{ $settings['google_map'] ?? '' }}</textarea>
                                        <small class="text-muted">
                                            Bạn vui lòng dán toàn bộ thẻ <code>&lt;iframe&gt;</code> từ Google Maps vào.<br>
                                            Hướng dẫn: Vào <a href="https://maps.google.com" target="_blank">Google Maps</a> → chọn địa điểm → Chia sẻ → Nhúng bản đồ → Sao chép iframe.
                                        </small>
                                    </div>
                                    </div>
                                </div>
                                <!-- preview -->
                                <div class="col-md-6">
                                    <div class="settings-preview">
                                        <div class="section-header mb-3">
                                            <h6 class="text-primary mb-1">
                                                <i class="fas fa-eye me-2"></i>Xem Trước
                                            </h6>
                                            <small class="text-muted">Cách thức hiển thị thông tin website</small>
                                        </div>
                                        
                                        <div class="preview-card p-3 bg-light rounded">
                                            <div class="d-flex align-items-center mb-2">
                                                <img src="{{ isset($siteSettings['logo']) && $siteSettings['logo']
                                                        ? Storage::url($siteSettings['logo']) 
                                                        : asset('images/logo.png') }}" 
                                                alt="Logo" class="me-2" style="height: 32px;" onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                                                <div>
                                                    <h6 class="mb-0" id="preview-name">{{ $settings['site_name'] ?? 'Hudson Furnishing' }}</h6>
                                                    <small class="text-muted" id="preview-tagline">{{ $settings['site_tagline'] ?? 'Nội thất cao cấp cho mọi không gian' }}</small>
                                                </div>
                                            </div>
                                            <p class="small mb-0" id="preview-description">{{ Str::limit($settings['site_description'] ?? 'Hudson Furnishing cung cấp nội thất cao cấp cho mọi phòng trong ngôi nhà của bạn...', 100) }}</p>
                                        </div>

                                        <hr>
                                            <h5 class="mt-4 mb-3">Hero Carousel</h5>
                                            <div class="mb-3">
                                                <label class="form-label">Hình ảnh Carousel 1</label>
                                                <input type="file" name="hero_image_1" class="form-control" accept="image/*">
                                                @if(isset($siteSettings['hero_image_1']) && $siteSettings['hero_image_1'])
                                                    <div class="mt-2">
                                                        <img src="{{ Storage::url($siteSettings['hero_image_1']) }}" alt="Hero Image 1" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Hình ảnh Carousel 2</label>
                                                <input type="file" name="hero_image_2" class="form-control" accept="image/*">
                                                @if(isset($siteSettings['hero_image_2']) && $siteSettings['hero_image_2'])
                                                    <div class="mt-2">
                                                        <img src="{{ Storage::url($siteSettings['hero_image_2']) }}" alt="Hero Image 2" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Hình ảnh Carousel 3</label>
                                                <input type="file" name="hero_image_3" class="form-control" accept="image/*">
                                                @if(isset($siteSettings['hero_image_3']) && $siteSettings['hero_image_3'])
                                                    <div class="mt-2">
                                                        <img src="{{ Storage::url($siteSettings['hero_image_3']) }}" alt="Hero Image 3" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                                    </div>
                                                @endif
                                            </div>
                                    </div>
                                </div>                                
                            </div>
                        </div>

                        <!-- Home Section Settings Tab -->
                        <div class="tab-pane fade" id="home-section" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="settings-section">
                                        <div class="section-header mb-3">
                                            <h6 class="text-primary mb-1">
                                                <i class="fas fa-home me-2"></i>Home Page Section
                                            </h6>
                                            <small class="text-muted">Cấu hình các section hiển thị trên trang chủ</small>
                                        </div>
                                        
                                        @foreach($sections as $key => $label)
                                        <div class="mb-4 p-3 border rounded">
                                            <!-- Checkbox Section -->
                                            <div class="form-check form-switch mb-3 text-center">
                                                <input class="form-check-input" type="checkbox" id="home_section_active_{{ $key }}" 
                                                    name="home_section[{{ $key }}][is_active]" value="1"
                                                    {{ isset($homeSections[$key]) && $homeSections[$key]->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label text-primary mt-1" for="home_section_active_{{ $key }}">
                                                    <i class="fas fa-home me-2"></i>{{ $label }}
                                                </label>
                                            </div>

                                            <!-- Limit Input -->
                                            <div class="mb-3 d-flex align-items-center gap-2">
                                                <label for="home_section_limit_{{ $key }}" class="form-label mb-0">Số lượng</label>
                                                <input type="number" class="form-control" id="home_section_limit_{{ $key }}" 
                                                    name="home_section[{{ $key }}][limit]" value="{{ $homeSections[$key]->limit ?? 4 }}" min="1">
                                            </div>

                                            <!-- Section Title -->
                                            <div class="mb-3">
                                                <label for="home_section_title_{{ $key }}" class="form-label">Nhập tiêu đề Section</label>
                                                <input type="text" class="form-control home-title-input" id="home_section_title_{{ $key }}" 
                                                    name="home_section[{{ $key }}][title]" value="{{ $homeSections[$key]->title ?? $label }}" 
                                                    data-section="{{ $key }}">
                                            </div>

                                            <!-- Product Selection -->
                                            <div class="mb-3">
                                                <label for="home_section_products_{{ $key }}" class="form-label">Chọn sản phẩm hiển thị</label>
                                                <select name="home_section[{{ $key }}][products][]" id="home_section_products_{{ $key }}" class="form-select" multiple>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" 
                                                            {{ isset($homeSections[$key]) && $homeSections[$key]->products->contains($product->id) ? 'selected' : '' }}>
                                                            {{ $product->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="form-text text-muted">Chọn các sản phẩm sẽ hiển thị trong section</small>
                                            </div>
                                        </div>

                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="home-preview">
                                        <div class="section-header mb-3">
                                            <h6 class="text-primary mb-1">
                                                <i class="fas fa-eye me-2"></i>Xem Trước Home Section
                                            </h6>
                                            <small class="text-muted">Xem trước các section trên trang chủ</small>
                                        </div>

                                        <div class="preview-card p-3 bg-light rounded">
                                            @foreach($sections as $key => $label)
                                                <div class="mb-3">
                                                    <h6 id="preview-home-title_{{ $key }}">{{ $homeSections[$key]->title ?? $label }}</h6>
                                                    <div class="d-flex gap-2 flex-wrap">
                                                        @if(isset($homeSections[$key]))
                                                            @foreach($homeSections[$key]->products as $product)
                                                                <div class="p-2 border rounded">{{ $product->name }}</div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Settings Tab -->
                        <div class="tab-pane fade" id="contact" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="settings-section">
                                        <div class="section-header mb-3">
                                            <h6 class="text-primary mb-1">
                                                <i class="fas fa-address-book me-2"></i>Thông Tin Liên Hệ
                                            </h6>
                                            <small class="text-muted">Cấu hình thông tin liên hệ</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="contact_email" class="form-label">
                                                <i class="fas fa-envelope text-primary me-1"></i>Email Liên Hệ
                                            </label>
                                            <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                                   value="{{ $settings['contact_email'] ?? 'info@hudsonfurnishing.vn' }}"
                                                   placeholder="Nhập email liên hệ">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="contact_phone" class="form-label">
                                                <i class="fas fa-phone text-primary me-1"></i>Số Điện Thoại Liên Hệ
                                            </label>
                                            <input type="text" class="form-control" id="contact_phone" name="contact_phone" 
                                                   value="{{ $settings['contact_phone'] ?? '+84 (0) 123 45 67 89' }}" 
                                                   placeholder="Nhập số điện thoại liên hệ">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="contact_address" class="form-label">
                                                <i class="fas fa-map-marker-alt text-primary me-1"></i>Địa Chỉ Liên Hệ
                                            </label>
                                            <textarea class="form-control" id="contact_address" name="contact_address" rows="3" 
                                                      placeholder="Nhập địa chỉ liên hệ">{{ $settings['contact_address'] ?? "123 Đường Nội Thất\nQuận Thiết Kế, TP.HCM 700000" }}</textarea>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="business_hours" class="form-label">
                                                <i class="fas fa-clock text-primary me-1"></i>Giờ Làm Việc
                                            </label>
                                            <textarea class="form-control" id="business_hours" name="business_hours" rows="3" 
                                                      placeholder="Nhập giờ làm việc">{{ $settings['business_hours'] ?? "T2 - T6: 8:00 - 18:00\nT7: 9:00 - 17:00\nCN: Nghỉ" }}</textarea>
                                        </div>
                                        <hr>
                                        <h5 class="mt-4 mb-3">Hero Content</h5>

                                        <div class="mb-3">
                                            <label for="hero_title" class="form-label">Tiêu đề Hero</label>
                                            <input type="text" class="form-control" id="hero_title" name="hero_title" 
                                                value="{{ $siteSettings['hero_title'] ?? '' }}" placeholder="Nhập tiêu đề hero">
                                        </div>

                                        <div class="mb-3">
                                            <label for="hero_subtitle" class="form-label">Mô tả Hero</label>
                                            <textarea class="form-control" id="hero_subtitle" name="hero_subtitle" rows="3"
                                                    placeholder="Nhập mô tả hero">{{ $siteSettings['hero_subtitle'] ?? '' }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="hero_button_text" class="form-label">Text nút CTA</label>
                                            <input type="text" class="form-control" id="hero_button_text" name="hero_button_text"
                                                value="{{ $siteSettings['hero_button_text'] ?? '' }}" placeholder="Nhập text nút CTA">
                                        </div>

                                        <div class="mb-3">
                                            <label for="hero_button_link" class="form-label">Link nút CTA</label>
                                            <input type="text" class="form-control" id="hero_button_link" name="hero_button_link"
                                                value="{{ $siteSettings['hero_button_link'] ?? '' }}" placeholder="Nhập link nút CTA">
                                        </div>

                                        <div class="form-check mb-3">
                                            <input type="hidden" name="hero_button_enabled" value="0">
                                            <input class="form-check-input" type="checkbox" name="hero_button_enabled" id="hero_button_enabled"
                                                value="1" {{ !empty($siteSettings['hero_button_enabled']) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="hero_button_enabled">
                                                Hiển thị nút CTA
                                            </label>
                                        </div>

                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="contact-preview">
                                        <div class="section-header mb-3">
                                            <h6 class="text-primary mb-1">
                                                <i class="fas fa-address-card me-2"></i>Xem Trước Liên Hệ
                                            </h6>
                                            <small class="text-muted">Cách thức hiển thị thông tin liên hệ</small>
                                        </div>
                                        
                                        <div class="contact-card p-3 bg-light rounded">
                                            <h6 class="mb-3">Liên Hệ</h6>
                                            <div class="contact-item mb-2">
                                                <i class="fas fa-envelope text-primary me-2"></i>
                                                <span id="preview-email">{{ $settings['contact_email'] ?? 'info@hudsonfurnishing.vn' }}</span>
                                            </div>
                                            <div class="contact-item mb-2">
                                                <i class="fas fa-phone text-primary me-2"></i>
                                                <span id="preview-phone">{{ $settings['contact_phone'] ?? '+84 909 090 909' }}</span>
                                            </div>
                                            <div class="contact-item mb-2">
                                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                <span id="preview-address">{{ Str::limit($settings['contact_address'] ?? '36/5 Đường D5, Quận Bình Thạnh, TP.HCM', 50) }}</span>
                                            </div>
                                            <div class="contact-item">
                                                <i class="fas fa-clock text-primary me-2"></i>
                                                <span id="preview-hours">{{ Str::limit($settings['business_hours'] ?? 'T2 - T7: 8:00 - 18:00', 30) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Social Media Settings Tab -->
                        <div class="tab-pane fade" id="social" role="tabpanel">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="settings-section">
                                        <div class="section-header mb-3">
                                            <h6 class="text-primary mb-1">
                                                <i class="fas fa-share-alt me-2"></i>Liên Kết Mạng Xã Hội
                                            </h6>
                                            <small class="text-muted">Cấu hình liên kết mạng xã hội</small>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="facebook_url" class="form-label">
                                                        <i class="fab fa-facebook text-primary me-1"></i>Facebook
                                                    </label>
                                                    <input type="url" class="form-control" id="facebook_url" name="facebook_url" 
                                                           value="{{ $settings['facebook_url'] ?? 'https://facebook.com/hudsonfurnishing' }}"
                                                           placeholder="https://facebook.com/hudsonfurnishing">
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="instagram_url" class="form-label">
                                                        <i class="fab fa-instagram text-primary me-1"></i>Instagram
                                                    </label>
                                                    <input type="url" class="form-control" id="instagram_url" name="instagram_url" 
                                                           value="{{ $settings['instagram_url'] ?? 'https://instagram.com/hudsonfurnishing' }}"
                                                           placeholder="https://instagram.com/hudsonfurnishing">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="twitter_url" class="form-label">
                                                        <i class="fab fa-twitter text-primary me-1"></i>Twitter
                                                    </label>
                                                    <input type="url" class="form-control" id="twitter_url" name="twitter_url" 
                                                           value="{{ $settings['twitter_url'] ?? 'https://twitter.com/hudsonfurnishing' }}"
                                                           placeholder="https://twitter.com/hudsonfurnishing">
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="linkedin_url" class="form-label">
                                                        <i class="fab fa-linkedin text-primary me-1"></i>LinkedIn
                                                    </label>
                                                    <input type="url" class="form-control" id="linkedin_url" name="linkedin_url" 
                                                           value="{{ $settings['linkedin_url'] ?? 'https://linkedin.com/company/hudsonfurnishing' }}"
                                                           placeholder="https://linkedin.com/company/hudsonfurnishing">
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="my-4">

                                        <div class="settings-section">
                                            <div class="section-header mb-3">
                                                <h6 class="text-primary mb-1">
                                                    <i class="fas fa-comment-alt me-2"></i>Floating Chat
                                                </h6>
                                                <small class="text-muted">Cấu hình các nút chat nổi</small>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="chat_zalo_url" class="form-label">
                                                        <i class="fab fa-zalo text-primary me-1"></i>Zalo
                                                    </label>
                                                    <input type="url" class="form-control" id="chat_zalo_url" name="chat_zalo_url" 
                                                        value="{{ $settings['chat_zalo_url'] ?? '' }}" 
                                                        placeholder="https://zalo.me/0909123456">
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="chat_messenger_url" class="form-label">
                                                        <i class="fab fa-facebook-messenger text-primary me-1"></i>Messenger
                                                    </label>
                                                    <input type="url" class="form-control" id="chat_messenger_url" name="chat_messenger_url" 
                                                        value="{{ $settings['chat_messenger_url'] ?? '' }}" 
                                                        placeholder="https://m.me/username">
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="chat_phone_number" class="form-label">
                                                        <i class="fas fa-phone text-primary me-1"></i>Gọi điện
                                                    </label>
                                                    <input type="text" class="form-control" id="chat_phone_number" name="chat_phone_number" 
                                                        value="{{ $settings['chat_phone_number'] ?? '' }}" 
                                                        placeholder="0909123456">
                                                </div>                                            
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="social-preview">
                                        <div class="section-header mb-3">
                                            <h6 class="text-primary mb-1">
                                                <i class="fas fa-thumbs-up me-2"></i>Xem Trước Mạng Xã Hội
                                            </h6>
                                            <small class="text-muted">Cách thức hiển thị liên kết mạng xã hội</small>
                                        </div>
                                        
                                        <div class="social-links p-2 bg-primary  rounded">
                                            <h6 class="d-flex align-items-center mb-2 ms-2 text-light">Theo Dõi Chúng Tôi</h6>
                                            <div class="d-flex flex-wrap gap-2 mt-2">
                                                <a href="#" class="social-link  me-3 mb-2">
                                                    <i class="fab fa-facebook me-0"></i>
                                                </a>
                                                <a href="#" class="social-link me-3 mb-2">
                                                    <i class="fab fa-twitter me-0"></i>
                                                </a>
                                                <a href="#" class="social-link me-3 mb-2">
                                                    <i class="fab fa-instagram me-0"></i>
                                                </a>
                                                <a href="#" class="social-link me-3 mb-2">
                                                    <i class="fab fa-linkedin me-0"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- SEO Settings Tab -->
                        <div class="tab-pane fade" id="seo" role="tabpanel">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="settings-section">
                                        <div class="section-header mb-3">
                                            <h6 class="text-primary mb-1">
                                                <i class="fas fa-search me-2"></i>Cài Đặt SEO
                                            </h6>
                                            <small class="text-muted">Tối ưu hóa website cho các công cụ tìm kiếm</small>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="google_analytics" class="form-label">
                                                        <i class="fab fa-google text-primary me-1"></i>Google Analytics
                                                    </label>
                                                    <input type="text" class="form-control" id="google_analytics" name="google_analytics" 
                                                           value="{{ $settings['google_analytics'] ?? '' }}"
                                                           placeholder="G-XXXXXXXXXX">
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="meta_author" class="form-label">
                                                        <i class="fas fa-user text-primary me-1"></i>Meta Author
                                                    </label>
                                                    <input type="text" class="form-control" id="meta_author" name="meta_author" 
                                                           value="{{ $settings['meta_author'] ?? 'Hudson Furnishing' }}"
                                                           placeholder="Enter author name">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="google_maps_api" class="form-label">
                                                        <i class="fas fa-map text-primary me-1"></i>Google Maps API Key
                                                    </label>
                                                    <input type="text" class="form-control" id="google_maps_api" name="google_maps_api" 
                                                           value="{{ $settings['google_maps_api'] ?? '' }}"
                                                           placeholder="Enter Google Maps API key">
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="meta_robots" class="form-label">
                                                        <i class="fas fa-robot text-primary me-1"></i>Meta Robots
                                                    </label>
                                                    <select class="form-select" id="meta_robots" name="meta_robots">
                                                        <option value="index,follow" {{ ($settings['meta_robots'] ?? 'index,follow') == 'index,follow' ? 'selected' : '' }}>Index, Follow</option>
                                                        <option value="noindex,nofollow" {{ ($settings['meta_robots'] ?? '') == 'noindex,nofollow' ? 'selected' : '' }}>No Index, No Follow</option>
                                                        <option value="index,nofollow" {{ ($settings['meta_robots'] ?? '') == 'index,nofollow' ? 'selected' : '' }}>Index, No Follow</option>
                                                        <option value="noindex,follow" {{ ($settings['meta_robots'] ?? '') == 'noindex,follow' ? 'selected' : '' }}>No Index, Follow</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="seo-preview">
                                        <div class="section-header mb-3">
                                            <h6 class="text-primary mb-1">
                                                <i class="fas fa-chart-line me-2"></i>Trạng Thái SEO
                                            </h6>
                                            <small class="text-muted">Trạng thái cài đặt SEO</small>
                                        </div>
                                        
                                        <div class="seo-status p-3 bg-light rounded">
                                            <div class="status-item mb-2">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                <small>Meta tags đã được cấu hình</small>
                                            </div>
                                            <div class="status-item mb-2">
                                                <i class="fas fa-{{ $settings['google_analytics'] ?? '' ? 'check-circle text-success' : 'times-circle text-warning' }} me-2"></i>
                                                <small>Google Analytics {{ $settings['google_analytics'] ?? '' ? 'đã được kích hoạt' : 'chưa được cấu hình' }}</small>
                                            </div>
                                            <div class="status-item">
                                                <i class="fas fa-{{ $settings['google_maps_api'] ?? '' ? 'check-circle text-success' : 'times-circle text-warning' }} me-2"></i>
                                                <small>Google Maps {{ $settings['google_maps_api'] ?? '' ? 'đã được kích hoạt' : 'chưa được cấu hình' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Advanced Settings Tab -->
                        <div class="tab-pane fade" id="advanced" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="settings-section">
                                        <div class="section-header mb-3">
                                            <h6 class="text-primary mb-1">
                                                <i class="fas fa-sliders-h me-2"></i>Cài Đặt Nâng Cao
                                            </h6>
                                            <small class="text-muted">Cấu hình cài đặt nâng cao website</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="items_per_page" class="form-label">
                                                <i class="fas fa-list text-primary me-1"></i>Số Lượng Sản Phẩm Trên Trang
                                            </label>
                                            <input type="number" class="form-control" id="items_per_page" name="items_per_page" 
                                                   value="{{ $settings['items_per_page'] ?? 12 }}" min="1" max="100"
                                                   placeholder="12">
                                            <small class="form-text text-muted">Số lượng sản phẩm hiển thị trên mỗi trang</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="maintenance_mode" class="form-label">
                                                <i class="fas fa-tools text-primary me-1"></i>Trạng Thái Bảo Trì
                                            </label>
                                            <select class="form-select" id="maintenance_mode" name="maintenance_mode">
                                                <option value="0" {{ ($settings['maintenance_mode'] ?? '0') == '0' ? 'selected' : '' }}>Vô Hiệu Hóa</option>
                                                <option value="1" {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'selected' : '' }}>Kích Hoạt</option>
                                            </select>
                                            
                                            <div class="mb-3">
                                                <label for="maintenance_end" class="form-label">Thời Gian Kết Thúc</label>
                                                <input type="datetime-local" class="form-control" id="maintenance_end" name="maintenance_end" 
                                                    value="{{ $settings['maintenance_end'] ?? '' }}">
                                            </div>
                                            <small class="form-text text-muted">Kích hoạt để hiển thị trang bảo trì cho khách truy cập</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="system-info">
                                        <div class="section-header mb-3">
                                            <h6 class="text-primary mb-1">
                                                <i class="fas fa-info-circle me-2"></i>Thông Tin Hệ Thống
                                            </h6>
                                            <small class="text-muted">Trạng thái hiện tại của hệ thống</small>
                                        </div>
                                        
                                        <div class="system-card p-3 bg-light rounded">
                                            <div class="info-item mb-2">
                                                <strong>Laravel Version:</strong> {{ app()->version() }} - Phiên Bản Laravel
                                            </div>
                                            <div class="info-item mb-2">
                                                <strong>PHP Version:</strong> {{ PHP_VERSION }} - Phiên Bản PHP
                                            </div>
                                            <div class="info-item mb-2">
                                                <strong>Environment:</strong> {{ ucfirst(app()->environment()) }} - Môi Trường      
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Lưu Thay Đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add CSS for icon circles and enhanced styling -->
<style>
.icon-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.settings-section, .settings-preview, .contact-preview, .social-preview, .seo-preview, .system-info {
    height: 100%;
}

.preview-card, .contact-card, .social-links, .seo-status, .system-card {
    border: 1px solid #e3e6f0;
    transition: all 0.3s ease;
}

.preview-card:hover, .contact-card:hover, .social-links:hover, .seo-status:hover, .system-card:hover {
    box-shadow: 0 4px 15px rgba(51, 92, 103, 0.1);
}

.nav-pills .nav-link {
    border-radius: 25px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.nav-pills .nav-link.active {
    background: linear-gradient(135deg, var(--admin-primary), #4a7c8a);
}

.contact-item, .status-item, .info-item {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
}

.section-header {
    border-bottom: 1px solid #e3e6f0;
    padding-bottom: 0.5rem;
}
</style>

<!-- Add JavaScript for real-time preview updates -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update previews in real-time
    document.getElementById('site_name').addEventListener('input', function() {
        document.getElementById('preview-name').textContent = this.value || 'Hudson Furnishing';
    });
    
    document.getElementById('site_tagline').addEventListener('input', function() {
        document.getElementById('preview-tagline').textContent = this.value || 'Nội thất cao cấp cho mọi không gian';
    });
    
    document.getElementById('site_description').addEventListener('input', function() {
        const text = this.value || 'Hudson Furnishing cung cấp nội thất cao cấp cho mọi phòng trong ngôi nhà của bạn...';
        document.getElementById('preview-description').textContent = text.substring(0, 100) + (text.length > 100 ? '...' : '');
    });
    
    // Contact preview updates
    document.getElementById('contact_email').addEventListener('input', function() {
        document.getElementById('preview-email').textContent = this.value || 'info@hudsonfurnishing.vn';
    });
    
    document.getElementById('contact_phone').addEventListener('input', function() {
        document.getElementById('preview-phone').textContent = this.value || '+84 (0) 123 45 67 89';
    });
    
    document.getElementById('contact_address').addEventListener('input', function() {
        const text = this.value || '123 Đường Nội Thất, Quận Thiết Kế, TP.HCM';
        document.getElementById('preview-address').textContent = text.substring(0, 50) + (text.length > 50 ? '...' : '');
    });
    
    document.getElementById('business_hours').addEventListener('input', function() {
        const text = this.value || 'T2 - T6: 8:00 - 18:00';
        document.getElementById('preview-hours').textContent = text.substring(0, 30) + (text.length > 30 ? '...' : '');
    });
});

    // preview logo khi chọn file
    function previewLogo(event) {
        const input = event.target;
        if (!input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.querySelector('.preview-card img');
            if (img) img.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }

    // preview hinh anh khi chon file
    function previewImage(event) {
        const input = event.target;
        if (!input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.querySelector('.preview-card img');
            if (img) img.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
    // Lấy tất cả các input tiêu đề section
    const titleInputs = document.querySelectorAll('.home-title-input');

    titleInputs.forEach(input => {
        input.addEventListener('input', function() {
            const sectionKey = this.getAttribute('data-section');
            const preview = document.getElementById(`preview-home-title_${sectionKey}`);

            if (preview) {
                preview.textContent = this.value.trim() || '(Chưa đặt tiêu đề)';
            }
        });
    });
});


    // preview hinh anh khi chon file
document.querySelectorAll('select[id^="home_section_products_"]').forEach(select => {
    select.addEventListener('change', function() {
        const sectionKey = this.id.replace('home_section_products_', '');
        const previewContainer = document.querySelector(`#preview-home-title_${sectionKey}`).nextElementSibling;

        previewContainer.innerHTML = '';

        Array.from(this.selectedOptions).forEach(option => {
            const div = document.createElement('div');
            div.className = 'p-2 border rounded';
            div.textContent = option.text;
            previewContainer.appendChild(div);
        });
    });
});


</script>
@endsection
