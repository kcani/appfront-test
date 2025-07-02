@extends('layouts.admin')

@section('title')
    {{ __('modules/product.edit_product') }}
@endsection


@section('content')
    @include('admin.products._form', ['product' => $product])
@endsection
