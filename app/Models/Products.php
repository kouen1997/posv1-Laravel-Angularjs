<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_products';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['name','username', 'email','password'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    
    public function parent(){
         return $this->belongsTo('App\Models\Category', 'parent_id', 'id');
    }

    public function childrens(){
         return $this->hasMany('App\Models\Category', 'parent_id', 'parent_id');
    }

    public function child(){
         return $this->belongsTo('App\Models\Category', 'child_id', 'id');
    }

    public function brand(){
         return $this->belongsTo('App\Models\Brand', 'brand_id', 'id');
    }

}