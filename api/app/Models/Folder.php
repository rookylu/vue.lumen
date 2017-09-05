<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends BaseModel
{
    // 软删除
    use SoftDeletes;

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
