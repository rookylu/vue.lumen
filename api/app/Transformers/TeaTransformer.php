<?php
namespace App\Transformers;

use App\Models\Tea;
use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;

class TeaTransformer extends TransformerAbstract
{
    public function transform(Tea $tea)
    {
        return $tea->attributesToArray();
    }
}
