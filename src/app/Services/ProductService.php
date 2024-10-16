<?php

namespace App\Services;

use App\Http\Requests\ProductSearchRequest;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use LaravelIdea\Helper\App\Models\_IH_Product_C;
use LaravelIdea\Helper\App\Models\_IH_Product_QB;

class ProductService
{
    public function search(ProductSearchRequest $request): array|LengthAwarePaginator|\Illuminate\Pagination\LengthAwarePaginator|_IH_Product_C
    {
        $query = Product::query();

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        return $query->paginate(10);

    }

}
