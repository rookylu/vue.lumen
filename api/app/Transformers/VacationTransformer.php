<?php
namespace App\Transformers;

use App\Models\Vacation;
use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;

class VacationTransformer extends TransformerAbstract
{
    public function transform(Vacation $vacation)
    {
        return $vacation->attributesToArray();
    }
}
