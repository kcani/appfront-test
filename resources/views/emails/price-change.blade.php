<!DOCTYPE html>
<html>
<head>
    <title>{{ __('modules/product.price_change_email.title_1') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .price-change {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .old-price {
            color: #dc3545;
            text-decoration: line-through;
        }
        .new-price {
            color: #28a745;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ __('modules/product.price_change_email.title_2') }}</h2>
        </div>

        <p>{{ __('modules/product.price_change_email.hello') }},</p>

        <p>{{ __('modules/product.price_change_email.p1') }}</p>

        <h3>{{ $product->name }}</h3>

        <div class="price-change">
            <p><strong>{{ __('modules/product.price_change_email.old_price') }}:</strong> <span class="old-price">${{ number_format($oldPrice, 2) }}</span></p>
            <p><strong>{{ __('modules/product.price_change_email.new_price') }}:</strong> <span class="new-price">${{ number_format($product->price, 2) }}</span></p>
        </div>

        <p>{{ __('modules/product.price_change_email.p2') }}</p>

        <p>{{ __('modules/product.price_change_email.best_regards') }},<br>{{ __('modules/product.price_change_email.your_store_team') }}</p>
    </div>
</body>
</html>
