<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Tea;
use Illuminate\Http\Request;
use App\Transformers\TeaTransformer;

class TeaController extends BaseController
{
    private $tea;

    public function __construct(Tea $tea)
    {
        $this->tea = $tea;
    }

    public function index(Request $request)
    {

        $limit          = $request->input('limit', 20);          // 每页条目数
        $page           = $request->input('page',  1);           // 当前请求页
        $keyword        = $request->input('keyword', '');        // 搜索关键词
        $sort           = $request->input('sort', '+id');        // 结果排序方式
        $all            = $request->input('all', 0);             // 是否查询全部
        $teas = $this->tea->join('products', 'products.id', 'product_id')
            ->join('manor_owners', 'manor_owners.id', 'products.customer_id')
            ->select(
                'products.product_name',
                'products.signed_at',
                'products.total_money',
                'manor_owners.real_name',
                'teas.*'
            );

        $now = date('m-d');
        $year = date('Y');
        if($now <= '04-30') {
            $nextDelivery = $year . '-04-30';
        } else if($now <= '08-31') {
            $nextDelivery = $year . '-08-31';
        } else if($now <= '12-31') {
            $nextDelivery = $year . '-12-31';
        }

        $teas = $teas->where('delivery_time_deadline', '<=', $nextDelivery);
        $teas = $teas->paginate($limit);
        return $this->response->paginator($teas, new TeaTransformer());
    }

    public function deliver($id)
    {
        $tea = $this->tea->findOrFail($id);
        $user = $this->user();

        $tea->is_deliveried = 1;
        $tea->updator_id = $user->id;
        $tea->updator_name = $user->real_name;

        $tea->save();

        return $this->response->noContent();
    }
}
