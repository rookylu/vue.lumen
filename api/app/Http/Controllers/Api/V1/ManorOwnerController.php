<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ManorOwner;
use Illuminate\Http\Request;
use App\Transformers\ManorOwnerTransformer;

class ManorOwnerController extends BaseController
{
    private $owner;

    public function __construct(ManorOwner $owner)
    {
        $this->owner = $owner;
    }

    public function index(Request $request)
    {

        $limit          = $request->input('limit', 20);          // 每页条目数
        $page           = $request->input('page',  1);           // 当前请求页
        $keyword        = $request->input('keyword', '');        // 搜索关键词
        $sort           = $request->input('sort', '+id');        // 结果排序方式
        $all            = $request->input('all', 0);             // 是否查询全部
        $owners = $this->owner;

        if($keyword) {
            $owners = $owners->where('real_name', 'like', '%' . $keyword . '%');
        }
        if($all) {
            $owners = $owners->get();
            return $this->response->collection($owners, new ManorOwnerTransformer());
        } else {
            $owners = $owners->paginate($limit);
            return $this->response->paginator($owners, new ManorOwnerTransformer());
        }
    }
    public function show($id)
    {
        $owner = $this->owner->findOrFail($id);

        return $this->response->item($owner, new ManorOwnerTransformer());
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'real_name' => 'required|string|max:40',
            'cellphone' => 'required|string|max:11',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $attributes = $request->only('real_name', 'company_name', 'gender', 'cellphone', 'desc');
        $owner = $this->owner->create($attributes);

        // 返回 201 加数据
        return $this->response
            ->item($owner, new ManorOwnerTransformer())
            ->setStatusCode(201);
    }

    public function update($id, Request $request)
    {
        $owner = $this->owner->findOrFail($id);


        $validator = \Validator::make($request->input(), [
            'real_name' => 'required|string|max:40',
            'cellphone' => 'required|string|max:11',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $owner->update($request->only('cellphone', 'gender', 'desc'));

        return $this->response->noContent();
    }

    public function destroy($id)
    {
        $owner = $this->owner->findOrFail($id);

        $owner->delete();

        return $this->response->noContent();
    }
}
