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
                <p class="product-id">Product ID: {{ $product->id }}</p>

                <div class="price-container">
                    <span class="price-{{ strtolower($exchangeRate['from']) }}">{{ $exchangeRate['from_symbol'] }}{{ number_format($product->price, 2) }}</span>
                    <span class="price-{{ strtolower($exchangeRate['to']) }}">{{ $exchangeRate['to_symbol'] }}{{ number_format($product->price * $exchangeRate['value'], 2) }}</span>
                </div>

                <div class="divider"></div>

                <div class="product-detail-description">
                    <h4 class="description-title">Description</h4>
                    <p>{{ $product->description }}</p>
                </div>

                <div class="action-buttons">
                    <a href="{{ url('/') }}" class="btn btn-secondary">Back to Products</a>
                    <button class="btn btn-primary">Add to Cart</button>
                </div>

                <p class="exchange-rate">
                    Exchange Rate: 1 {{ $exchangeRate['from'] }} = {{ number_format($exchangeRate['value'], 4) }} {{ $exchangeRate['to'] }}
                </p>
            </div>
        </div>
    </div>
@endsection
