<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Vacation;
use Illuminate\Http\Request;
use App\Transformers\VacationTransformer;
use DB;
class VacationController extends BaseController
{
    private $Vacation;

    public function __construct(Vacation $vacation)
    {
        $this->vacation = $vacation;
    }

    public function index(Request $request)
    {

        $limit          = $request->input('limit', 20);          // 每页条目数
        $page           = $request->input('page',  1);           // 当前请求页
        $keyword        = $request->input('keyword', '');        // 搜索关键词
        $sort           = $request->input('sort', '+id');        // 结果排序方式
        $all            = $request->input('all', 0);             // 是否查询全部
        $vacations = $this->vacation->join('products', 'products.id', 'product_id')
            ->join('manor_owners', 'manor_owners.id', 'products.customer_id')
            ->select(
                'products.product_name',
                'products.signed_at',
                'products.total_money',
                'manor_owners.real_name',
                'vacations.*'
            );

        $year = date('Y');

        $vacations = $vacations->where('year', '=', $year);
        $vacations = $vacations->paginate($limit);
        return $this->response->paginator($vacations, new VacationTransformer());
    }
    public function show($id)
    {
        $vacation = $this->vacation->findOrFail($id);

        $details = DB::table('vacation_details')
            ->where('vid', '=', $id)
            ->get();

        return response()->json([
            'data' => $details,
        ]);
    }

    public function update($id, Request $request)
    {
        $vacation = $this->vacation->findOrFail($id);

        $stayed_time = $request->input('stayed_time');
        $stayed_at = $request->input('stayed_at');
        $remark = $request->input('remark');

        $user = $this->user();

        if($vacation->remain_time <= $stayed_time) {
            return $this->response->errorBadRequest('剩余住宿时间不够');
        }

        DB::beginTransaction();

        $vacation->stayed_time += $stayed_time;
        $vacation->remain_time -= $stayed_time;
        $vacation->updator_id = $user->id;
        $vacation->updator_name = $user->real_name;

        $detail = [
            'vid' => $vacation->id,
            'stayed_time' => $stayed_time,
            'stayed_at' => $stayed_at,
            'remark' => $remark,
            'creator_id' => $user->id,
            'creator_name' => $user->real_name,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $vacation->save();
        DB::table('vacation_details')->insert($detail);
        DB::commit();

        return $this->response->noContent();
    }
}
