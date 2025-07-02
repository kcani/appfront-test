<div class="admin-container">
    <h1>{{ $product ? __('modules/product.edit_product') : __('modules/product.add_product') }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ $product ? route('admin.products.update', $product->id) : route('admin.products.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @if($product)
            @method('PATCH')
        @endif
        <div class="form-group">
            <label for="name">{{ __('models/product.product_name') }}</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $product?->name) }}" required>
            @error('name')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">{{ __('models/product.description') }}</label>
            <textarea id="description" name="description" class="form-control" required>{{ old('description', $product?->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="price">{{ __('models/product.price') }}</label>
            <input type="number" id="price" name="price" step="0.01" class="form-control" value="{{ old('price', $product?->price) }}" required>
        </div>

        <div class="form-group">
            @if($product)
                <label for="image">{{ __('modules/product.current_image') }}</label>
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" class="product-image" alt="{{ $product->name }}">
                @endif
            @else
                <label for="image">{{ __('models/product.product_image') }}</label>
            @endif
            <input type="file" id="image" name="image" class="form-control">
            <small>{{ __('modules/product.empty_image_current_message') }}</small>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                {{ $product ? __('modules/product.update_product') : __('modules/product.add_product') }}
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">{{ __('general.cancel') }}</a>
        </div>
    </form>
</div>
