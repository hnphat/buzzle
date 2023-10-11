<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NhomAnh extends Model
{
    //
    protected $table = "nhom_anh";
    public function anh() {
        return $this->hasMany('App\HinhAnh','id_nhom','id');
    }
}
