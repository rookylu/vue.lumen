<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Tea extends BaseModel
{
    // 软删除
    use SoftDeletes;
}
