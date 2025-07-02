<?php

namespace App\Http\Requests\Product;

class ProductStoreRequest extends ProductUpdateRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['name'] = 'required|min:3|max:255';
        $rules['price'] = 'required|numeric';

        return $rules;
    }
}
