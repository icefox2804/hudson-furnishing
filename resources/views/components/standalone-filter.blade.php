@props([
    'formAction' => '',
    'filterConfig' => [],
    'columns' => 4 // mặc định 3 cột (12/3 = 4 filters mỗi hàng)
    
])

<div class="card mb-4 standalone-filter">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-light">
            <i class="fas fa-filter me-2"></i>Bộ Lọc Nâng Cao
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ $formAction }}" id="standaloneFilterForm">
            <div class="row">
                @if(!empty($filterConfig['filters']) && is_array($filterConfig['filters']))
                    @foreach($filterConfig['filters'] as $filter)
                    @php
                        $colClass = 12 / $columns;
                    @endphp
                    <div class="col-md-{{ $colClass }} col-sm-6 col-12 mb-3">


                            <label class="form-label">{{ $filter['label'] ?? 'Filter' }}</label>

                            @php
                                $filterValue = request($filter['name']) ?? '';
                            @endphp

                            @if($filter['type'] == 'text')
                                <input type="text" name="{{ $filter['name'] }}" class="form-control" 
                                       placeholder="{{ $filter['placeholder'] ?? '' }}" value="{{ $filterValue }}">
                            @elseif($filter['type'] == 'select' || $filter['type'] == 'price_range' || $filter['type'] == 'stock_range')
                                <select name="{{ $filter['name'] }}" class="form-select">
                                    <option value="">{{ $filter['placeholder'] ?? 'Tất cả' }}</option>
                                    @if(isset($filter['options']) && is_array($filter['options']))
                                        @foreach($filter['options'] as $value => $option)
                                            <option value="{{ $value }}" {{ $filterValue == $value ? 'selected' : '' }}>
                                                {{ is_array($option) ? ($option['label'] ?? $value) : $option }}
                                            </option>
                                        @endforeach
                                    @elseif($filter['type'] == 'price_range')
                                        @php
                                            $ranges = [
                                                '0-500000' => 'Dưới 500K',
                                                '500000-1000000' => '500K - 1M',
                                                '1000000-2000000' => '1M - 2M',
                                                '2000000-5000000' => '2M - 5M',
                                                '5000000-10000000' => '5M - 10M',
                                                '10000000-999999999' => 'Trên 10M'
                                            ];
                                        @endphp
                                        @foreach($ranges as $value => $label)
                                            <option value="{{ $value }}" {{ $filterValue == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    @elseif($filter['type'] == 'stock_range')
                                        @php
                                            $stocks = [
                                                '0' => 'Hết hàng',
                                                '1-10' => 'Ít (1-10)',
                                                '11-50' => 'Trung bình (11-50)',
                                                '51-100' => 'Nhiều (51-100)',
                                                '101-999999' => 'Rất nhiều (100+)'
                                            ];
                                        @endphp
                                        @foreach($stocks as $value => $label)
                                            <option value="{{ $value }}" {{ $filterValue == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            @elseif($filter['type'] == 'date')
                                <input type="text" name="{{ $filter['name'] }}" class="form-control date-input" 
                                       value="{{ $filterValue }}" 
                                       placeholder="dd/mm/yyyy"
                                       pattern="\d{2}/\d{2}/\d{4}"
                                       title="Nhập ngày theo định dạng dd/mm/yyyy">
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center">                    
                        <a href="{{ $formAction }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Reset bộ lọc
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.standalone-filter .form-control,
.standalone-filter .form-select {
    width: 100% !important;
    height: 38px !important;
    font-size: 14px !important;
    padding: 0.375rem 0.75rem !important;
    line-height: 1.5 !important;
    border: 1px solid #ced4da !important;
    border-radius: 0.375rem !important;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out !important;
}

.standalone-filter .form-control:focus,
.standalone-filter .form-select:focus {
    border-color:rgb(44, 97, 110) !important;
    box-shadow: 0 0 0 0.2rem rgb(141, 161, 167) !important;
    outline: 0 !important;
}

.standalone-filter .form-label {
    font-size: 14px !important;
    font-weight: 500 !important;
    margin-bottom: 0.5rem !important;
    color: #447481 !important;
}

.standalone-filter .form-select option:hover {
    background-color: #ffe0cc; /* màu hover option */
    color: #000; 
}

.date-input {
    text-align: left;
    font-family: inherit;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('standaloneFilterForm');

    // Tự động gửi cho Select (Thường được giữ lại để tiện lợi)
    filterForm.querySelectorAll('select').forEach(select => {
        select.addEventListener('change', () => filterForm.submit());
    });

    // Debounced auto-submit cho text input (ngoại trừ ngày)
    // Giữ lại logic 500ms debounce cho text input
    filterForm.querySelectorAll('input[type="text"]:not(.date-input)').forEach(input => {
        let timeout;
        input.addEventListener('input', () => {
            clearTimeout(timeout);
            // Chỉ gửi nếu có giá trị
            if (input.value.trim().length > 0) {
                timeout = setTimeout(() => filterForm.submit(), 500);
            }
        });
    });

    // Xử lý input ngày
    filterForm.querySelectorAll('.date-input').forEach(input => {

        // Auto-format khi nhập
        input.addEventListener('input', function(e) {
            let v = e.target.value.replace(/\D/g,'').slice(0,8); // chỉ lấy 8 số: ddmmyyyy

            if(v.length >= 5){
                v = v.slice(0,2)+'/'+v.slice(2,4)+'/'+v.slice(4);
            } else if(v.length >= 3){
                v = v.slice(0,2)+'/'+v.slice(2);
            }

            e.target.value = v;
        });

        // Chỉ cho phép nhập số
        input.addEventListener('keypress', function(e){
            // Cho phép số (0-9), Backspace (8), Delete/Tab/Enter/Arrow keys (0)
            if(!/[0-9]/.test(String.fromCharCode(e.which)) && e.which !== 8 && e.which !== 0){
                e.preventDefault();
            }
        });

        // Paste: auto format
        input.addEventListener('paste', function(e){
            setTimeout(() => {
                let v = e.target.value.replace(/\D/g,'').slice(0,8);
                if(v.length >= 5){
                    v = v.slice(0,2)+'/'+v.slice(2,4)+'/'+v.slice(4);
                } else if(v.length >= 3){
                    v = v.slice(0,2)+'/'+v.slice(2);
                }
                e.target.value = v;
            },0);
        });

        // **LOGIC ĐÃ SỬA:** Submit khi rời input (blur) nếu đủ 10 ký tự VÀ đúng định dạng
        input.addEventListener('blur', function(e){
            const value = e.target.value;
            // Kiểm tra đủ 10 ký tự (dd/mm/yyyy) VÀ khớp với định dạng dd/mm/yyyy
            if(value.length === 10 && /^\d{2}\/\d{2}\/\d{4}$/.test(value)){
                filterForm.submit();
            }
        });

    });
});
</script>
