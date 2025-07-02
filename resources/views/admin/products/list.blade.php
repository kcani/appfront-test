@extends('layouts.admin')

@section('title')
Admin - Products
@endsection


@section('content')
<div class="admin-list-container">
        <div class="admin-header">
            <h1>Admin - Products</h1>
            <div>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add New Product</a>
                <a href="{{ route('logout') }}" class="btn btn-secondary">Logout</a>
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
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        @if($product->image)
                            <img src="{{ env('APP_URL') }}/{{ $product->image }}" width="50" height="50" alt="{{ $product->name }}">
                        @endif
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>${{ number_format($product->price, 2) }}</td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">Edit</a>
                        <a
                            href="#"
                            class="btn btn-secondary"
                            onclick="event.preventDefault(); deleteProduct({{ $product->id }});"
                        >
                            Delete
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        function deleteProduct (id) {
            if (confirm('Are you sure you want to delete this product?')) {
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


