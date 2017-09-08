<?php
namespace App\Transformers;

use App\Models\Product;
use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['customer'];

    public function transform(Product $product)
    {
        return $product->attributesToArray();
    }

    public function includeCustomer(Product $product)
    {
        if (! $product->customer) {
            return $this->null();
        }

        return $this->item($product->customer, new ManorOwnerTransformer());
    }
}
