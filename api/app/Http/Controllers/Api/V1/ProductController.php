<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Transformers\ProductTransformer;
use DB;

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

        DB::beginTransaction();

        $attributes = $request->only('product_name', 'signed_at', 'payment_at', 'unit_price', 'num', 'total_money', 'customer_id', 'first_phase');
        $attributes['sys_no'] = get_unique_id();

        $first_phase = $request->input('first_phase', 0);
        $payment_at = $request->input('payment_at');

        // 红茶批次时间获取
        $hongchas = get_hongcha_phases($payment_at, $first_phase);

        $payment_at = date('m-d', strtotime($payment_at));
        $phases = [
            '04-30',
            '08-31',
            '12-31',
        ];

        $today = date('m-d');
        $first_at = '';
        if($first_phase === 0) { // 按合同规定
            if($payment_at <= '03-31') { // 3月31日前全额付款的, 享受当年全年茶叶交付
                $year = date('Y');
                $day = $phases[0];
            } else if($payment_at <= '07-31') { // 享受当年2、3期
                $year = date('Y');
                $day = $phases[1];
            } else if($payment_at <= '11-30') { // 享受当年3期
                $year = date('Y');
                $day = $phases[2];
            } else if($payment_at >= '12-01') { // 从次年开始
                $year = date('Y', strtotime('+1 year'));
                $day = $phases[0];
            }

            $first_at = $year . '-' . $day;
        } else {
            $year = date('Y');
            $first_at = date('Y') . '-' . $phases[$first_phase - 1];
        }

        $attributes['first_at'] = $first_at;
        $attributes['created_at'] = date('Y-m-d H:i:s');
        $attributes['updated_at'] = date('Y-m-d H:i:s');

        $product_id = DB::table('products')->insertGetId($attributes);

        // 红茶交付
        $teas = [];
        foreach($hongchas as $index => $day) {
            $teas[] = [
                'product_id' => $product_id,
                'year' => date('Y', strtotime($day)),
                'period_index' => $index + 1,
                'delivery_time_deadline' => $day,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        DB::table('teas')->insert($teas);

        // 别墅度假
        $vacations = [];
        for($i = 0; $i < 10; $i++) {
            $vacations[] = [
                'product_id' => $product_id,
                'year' => $year + $i,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        DB::table('vacations')->insert($vacations);
        $product = $this->product->find($product_id);

        DB::commit();
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
