<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\File as FileModel;
use App\Models\Folder;
use Illuminate\Http\Request;
use App\Transformers\FileTransformer;
use GrahamCampbell\Flysystem\Facades\Flysystem;
use Illuminate\Support\Facades\File;

class FileController extends BaseController
{
    private $file;

    public function __construct(FileModel $file, Folder $folder)
    {
        $this->file = $file;
		$this->folder = $folder;
    }

    public function index($fid, Request $request)
    {
		$folder = $this->folder->findOrFail($fid);
        $files = $this->file->where(['fid' => $fid]);

        $currentCursor = $request->get('cursor');

        if ($currentCursor !== null) {
            $currentCursor = (int) $request->get('cursor', null);
            // how to use previous ??
            // $prevCursor = $request->get('previous', null);
            $limit = $request->get('limit', 10);

            $files = $files->where([['id', '>', $currentCursor]])->limit($limit)->get();

            $nextCursor = $files->last()->id;
            $prevCursor = $currentCursor;

            $cursorPatination = new Cursor($currentCursor, $prevCursor, $nextCursor, $files->count());

            return $this->response->collection($files, new FileTransformer(), [], function ($resource) use ($cursorPatination) {
                $resource->setCursor($cursorPatination);
            });
        } else {
            $files = $files->paginate(10);

            return $this->response->paginator($files, new FileTransformer());
        }

        return $this->response->paginator($files, new FileTransformer());
    }
    public function show($id)
    {
        $file = $this->file->findOrFail($id);

        return $this->response->item($file, new FileTransformer());
    }

    public function store($fid, Request $request)
    {
		$file = $request->file('file');
		$extension = $file->getClientOriginalExtension();
		$filename = $file->getFilename();
		$name = $file->getClientOriginalName();
		$filename = $filename . '.' . $extension;
		$mime = $file->getClientMimeType();
		$size = $file->getSize();
		$path = storage_path('files');
		Flysystem::connection('local')->put($name,  File::get($file));
		$md5 = md5_file($path . '/' . $name);
		$entry = [
			'ext' => $extension,
			'fid' => $fid,
			'name' => $name,
			'filename' => $filename,
			'mime' => $mime,
			'size' => $size,
			'path' => $path,
			'md5' => $md5,
		];
		
		$fileRecord = $this->file->create($entry);
        return $this->response
            ->item($fileRecord, new FileTransformer())
            ->setStatusCode(201);
    }

    public function update($id, Request $request)
    {
        $file = $this->file->findOrFail($id);


        $validator = \Validator::make($request->input(), [
            'name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $file->update($request->only('name', 'desc'));

        return $this->response->noContent();
    }

    public function destroy($id)
    {
        $file = $this->file->findOrFail($id);

        $file->delete();

        return $this->response->noContent();
    }
}
