<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Folder;
use Illuminate\Http\Request;
use App\Transformers\FolderTransformer;

class FolderController extends BaseController
{
    private $folder;

    public function __construct(Folder $folder)
    {
        $this->folder = $folder;
    }

    public function index()
    {
        $folders = $this->folder->paginate(10);

        return $this->response->paginator($folders, new FolderTransformer());
    }
    public function show($id)
    {
        $folder = $this->folder->findOrFail($id);

        return $this->response->item($folder, new FolderTransformer());
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $attributes = $request->only('name', 'desc', 'pid');
        $folder = $this->folder->create($attributes);

        // 返回 201 加数据
        return $this->response
            ->item($folder, new FolderTransformer())
            //->withHeader('Location', $location) // 可加可不加，参考 Http协议，但是大家一般不适用
            ->setStatusCode(201);
    }

    public function update($id, Request $request)
    {
        $folder = $this->folder->findOrFail($id);


        $validator = \Validator::make($request->input(), [
            'name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $folder->update($request->only('name', 'desc'));

        return $this->response->noContent();
    }

    public function destroy($id)
    {
        $folder = $this->folder->findOrFail($id);

        $folder->delete();

        return $this->response->noContent();
    }
}
