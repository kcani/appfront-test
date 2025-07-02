@extends('layouts.admin')

@section('title')
    {{ __('modules/product.admin_products') }}
@endsection


@section('content')
<div class="admin-list-container">
        <div class="admin-header">
            <h1>{{ __('modules/product.admin_products') }}</h1>
            <div>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">{{ __('modules/product.add_new_product') }}</a>
                <a href="{{ route('logout') }}" class="btn btn-secondary">{{ __('general.logout') }}</a>
            </div>
        </div>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <table class="admin-table">
            <thead>
            <tr>
                <th>{{ __('models/product.id') }}</th>
                <th>{{ __('models/product.image') }}</th>
                <th>{{ __('models/product.name') }}</th>
                <th>{{ __('models/product.price') }}</th>
                <th>{{ __('general.actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" width="50" height="50" alt="{{ $product->name }}">
                        @endif
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>${{ number_format($product->price, 2) }}</td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">{{ __('general.edit') }}</a>
                        <a
                            href="#"
                            class="btn btn-secondary"
                            onclick="event.preventDefault(); deleteProduct({{ $product->id }});"
                        >
                            {{ __('general.delete') }}
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div>
            {!! $products->links() !!}
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function deleteProduct (id) {
            if (confirm('{{ __('modules/product.delete_product_question') }}')) {
                const form = document.createElement('form');
                form.action = '{{ route('admin.products.destroy', ':id') }}'.replace(':id', id);
                form.method = 'POST';
                form.style.display = 'none';
                const deleteInputMethod = document.createElement('input');
                deleteInputMethod.name = '_method';
                deleteInputMethod.value = 'DELETE';
                const csrfInputMethod = document.createElement('input');
                csrfInputMethod.name = '_token';
                csrfInputMethod.value = '{{ csrf_token() }}';
                form.appendChild(deleteInputMethod);
                form.appendChild(csrfInputMethod);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection


