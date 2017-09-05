<?php
namespace App\Transformers;

use App\Models\Menu;
use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;

class MenuTransformer extends TransformerAbstract
{
    public function transform(Menu $menu)
    {
        return $menu->attributesToArray();
    }
}
