@extends('layouts.admin')

@section('title')
Admin - Products
@endsection


@section('content')
    <div class="admin-list-container">
        <div class="admin-header">
            <h1>Admin - Products</h1>
            <div>
                <a href="{{ route('admin.add.product') }}" class="btn btn-primary">Add New Product</a>
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
                        <a href="{{ route('admin.edit.product', $product->id) }}" class="btn btn-primary">Edit</a>
                        <a href="{{ route('admin.delete.product', $product->id) }}" class="btn btn-secondary" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

