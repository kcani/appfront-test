@extends('layouts.admin')

@section('title')
{{ __('modules/product.add_new_product') }}
@endsection


@section('content')
    @include('admin.products._form', ['product' => null])
@endsection
