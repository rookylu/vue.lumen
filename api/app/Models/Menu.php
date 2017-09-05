<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends BaseModel
{
    // 软删除
    use SoftDeletes;
}
