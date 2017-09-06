<?php
namespace App\Transformers;

use App\Models\ManorOwner;
use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;

class ManorOwnerTransformer extends TransformerAbstract
{
    public function transform(ManorOwner $owner)
    {
        return $owner->attributesToArray();
    }
}
