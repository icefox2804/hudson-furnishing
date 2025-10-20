@extends('layouts.app')

@section('title', 'Dashboard người dùng')

@section('content')
    <div class="container mt-4">
        <h3>Xin chào, {{ $user->name }}</h3>
        <p>Email: {{ $user->email }}</p>

        <hr>

        <h4>Sản phẩm bạn yêu thích</h4>

        @if ($favorites->isEmpty())
            <p>Bạn chưa có sản phẩm yêu thích nào.</p>
        @else
            <div class="row" id="favorites-list">
                @foreach ($favorites as $favorite)
                    @php $product = $favorite->product; @endphp
                    <div class="col-md-3 mb-4 favorite-item" data-id="{{ $product->id }}">
                        @include('components.product-card', ['product' => $product])

                        <button class="btn btn-outline-danger btn-sm btn-unfavorite"
                            data-url="{{ route('favorites.toggle', $product->slug) }}">
                            <i class="fa-solid fa-heart-crack"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

<style>
    /* Đặt container cha ở vị trí tương đối để nút có thể định vị tuyệt đối bên trong */
    .favorite-item {
        position: relative;
    }

    /* Tùy chỉnh nút bỏ yêu thích */
    .favorite-item .btn-unfavorite {
        position: absolute;
        top: 10px;
        right: 20px;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        background-color: ;
        color: #E39F44;
        border: 1px solid #E39F44;
        transition: all 0.3s ease;
        z-index: 10;
    }

    /* Hiệu ứng hover */
    .favorite-item .btn-unfavorite:hover {
        background-color: #E39F44;
        color: #fff;
        transform: scale(1.1);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-unfavorite').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const btn = this;
                fetch(btn.dataset.url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.message);
                        if (data.success) {
                            // Ẩn card sản phẩm khỏi dashboard
                            const item = btn.closest('.favorite-item');
                            if (item) item.remove();
                        }
                    });
            });
        });
    });
</script>
