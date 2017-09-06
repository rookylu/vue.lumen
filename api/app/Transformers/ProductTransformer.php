<?php
namespace App\Transformers;

use App\Models\Product;
use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    public function transform(Product $product)
    {
        return $product->attributesToArray();
    }
}
