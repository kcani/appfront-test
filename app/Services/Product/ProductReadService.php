<?php

namespace App\Services\Product;

use App\Models\Product;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductReadService
{
    /**
     * Get the paginated result for the Product records.
     *
     * @param int $perPage
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 9, int $page = 1): LengthAwarePaginator
    {
        return Product::query()->paginate($perPage, page: $page);
    }
}
