<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'tbl_category';
    

    public function parent(){
         return $this->hasOne('App\Models\Category', 'parent_id', 'id');
    }

    public function child(){
         return $this->hasOne('App\Models\Category', 'id', 'parent_id');
    }

    public function children(){
         return $this->hasMany('App\Models\Category', 'parent_id', 'id');
    }

    public function product_parent() {
        return $this->hasMany('App\Models\Products' , 'id', 'parent_id');           
    }

    public function product_child() {
        return $this->hasMany('App\Models\Products' , 'id', 'child_id');
    }

}
