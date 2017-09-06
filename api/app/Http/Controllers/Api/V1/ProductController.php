<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Transformers\ProductTransformer;

class ProductController extends BaseController
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function index(Request $request)
    {

        $limit          = $request->input('limit', 20);          // 每页条目数
        $page           = $request->input('page',  1);           // 当前请求页
        $keyword        = $request->input('keyword', '');        // 搜索关键词
        $sort           = $request->input('sort', '+id');        // 结果排序方式
        $products = $this->product;

        if($keyword) {
            $products = $products->where('product_name', 'like', '%' . $keyword . '%');
        }

        $products = $products->paginate($limit);
        return $this->response->paginator($products, new ProductTransformer());
    }
    public function show($id)
    {
        $product = $this->product->findOrFail($id);

        return $this->response->item($product, new ProductTransformer());
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'product_name' => 'required|string|max:40',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $attributes = $request->only('product_name', 'signed_at', 'payment_at', 'money');
        $attributes['sys_no'] = get_unique_id();
        $product = $this->product->create($attributes);

        // 返回 201 加数据
        return $this->response
            ->item($product, new ProductTransformer())
            ->setStatusCode(201);
    }

    public function update($id, Request $request)
    {
        $product = $this->product->findOrFail($id);


        $validator = \Validator::make($request->input(), [
            'product_name' => 'required|string|max:40',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $product->update($request->only('product_name', 'signed_at', 'payment_at', 'money'));

        return $this->response->noContent();
    }

    public function destroy($id)
    {
        $product = $this->product->findOrFail($id);

        $product->delete();

        return $this->response->noContent();
    }
}
