<?php
namespace App\Transformers;

use App\Models\File;
use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;

class FileTransformer extends TransformerAbstract
{
    public function transform(File $file)
    {
        return $file->attributesToArray();
    }
}
