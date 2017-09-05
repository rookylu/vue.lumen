<?php
namespace App\Transformers;

use App\Models\Folder;
use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;

class FolderTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['comments'];
    public function transform(Folder $folder)
    {
        return $folder->attributesToArray();
    }

	public function includeFiles(Folder $folder, ParamBag $params = null)
    {
        $limit = 10;
        if ($params->get('limit')) {
            $limit = (array) $params->get('limit');
            $limit = (int) current($limit);
        }

        $files = $folder->files()->limit($limit)->get();
        $total = $folder->files()->count();

        return $this->collection($files, new FileTransformer())
            ->setMeta([
                'limit' => $limit,
                'count' => $files->count(),
                'total' => $post->files()->count(),
            ]);
    }

}
