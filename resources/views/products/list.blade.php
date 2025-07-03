@extends('layouts.guest')

@section('title')
{{ __('modules/product.products') }}
@endsection

@section('content')
<div class="container">
        <h1>{{ __('modules/product.products') }}</h1>

        <div class="products-grid">
            @forelse ($products as $product)
                <div class="product-card">
                    @if ($product->image_url)
                        <img src="{{ $product->image_url }}" class="product-image" alt="{{ $product->name }}">
                    @endif
                    <div class="product-info">
                        <h2 class="product-title">{{ $product->name }}</h2>
                        <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
                        <div class="price-container">
                            <span class="price-{{ strtolower($exchangeRate['from']) }}">{{ $exchangeRate['from_symbol'] }}{{ number_format($product->price, 2) }}</span>
                            <span class="price-{{ strtolower($exchangeRate['to']) }}">{{ $exchangeRate['to_symbol'] }}{{ number_format($product->price * $exchangeRate['value'], 2) }}</span>
                        </div>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-primary">{{ __('general.view_details') }}</a>
                    </div>
                </div>
            @empty
                <div class="empty-message">
                    <p>{{ __('modules/product.no_products_found') }}</p>
                </div>
            @endforelse
        </div>
        @if($products->total())
        {!! $products->links() !!}
        @endif

        <div class="exchange-rate centered">
            <p>{{ __('modules/product.exchange_rate', ['from' => $exchangeRate['from'], 'to' => $exchangeRate['to'], 'value' => number_format($exchangeRate['value'], 4)]) }}</p>
        </div>
    </div>
@endsection
