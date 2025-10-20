@extends('layouts.app')

@section('title', 'Sản phẩm yêu thích')

@section('content')
    <div class="container mt-4">
        <h3>💖 Danh sách sản phẩm yêu thích</h3>

        @if ($favorites->count() > 0)
            <div class="row mt-3">
                @foreach ($favorites as $product)
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm">
                            <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
                            <div class="card-body">
                                <h6 class="card-title">{{ $product->name }}</h6>
                                <p>{{ number_format($product->price, 0, ',', '.') }} đ</p>
                                <form action="{{ route('favorites.toggle', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">💔 Bỏ yêu thích</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>Bạn chưa có sản phẩm yêu thích nào.</p>
        @endif
    </div>
@endsection
