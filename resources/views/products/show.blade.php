@extends('layouts.guest')

@section('title')
{{ $product->name }}
@endsection

@section('content')
<div class="container">
        <div class="product-detail">
            <div>
                @if ($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-detail-image">
                @endif
            </div>
            <div class="product-detail-info">
                <h1 class="product-detail-title">{{ $product->name }}</h1>
                <p class="product-id">{{ __('models/product.product_id') }}: {{ $product->id }}</p>

                <div class="price-container">
                    <span class="price-{{ strtolower($exchangeRate['from']) }}">{{ $exchangeRate['from_symbol'] }}{{ number_format($product->price, 2) }}</span>
                    <span class="price-{{ strtolower($exchangeRate['to']) }}">{{ $exchangeRate['to_symbol'] }}{{ number_format($product->price * $exchangeRate['value'], 2) }}</span>
                </div>

                <div class="divider"></div>

                <div class="product-detail-description">
                    <h4 class="description-title">{{ __('models/product.description') }}</h4>
                    <p>{{ $product->description }}</p>
                </div>

                <div class="action-buttons">
                    <a href="{{ url('/') }}" class="btn btn-secondary">{{ __('modules/product.back_to_products') }}</a>
                    <button class="btn btn-primary">{{ __('modules/product.add_to_cart') }}</button>
                </div>

                <p class="exchange-rate">
                    {{ __('modules/product.exchange_rate', ['from' => $exchangeRate['from'], 'to' => $exchangeRate['to'], 'value' => number_format($exchangeRate['value'], 4)]) }}
                </p>
            </div>
        </div>
    </div>
@endsection
