<?php

namespace App\Services\Product;

use App\Models\Product;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductReadService
{
    public function paginate(int $perPage = 9, int $page = 1): LengthAwarePaginator
    {
        return Product::query()->paginate($perPage, page: $page);
    }
}
