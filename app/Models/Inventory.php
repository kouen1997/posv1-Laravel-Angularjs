<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_inventory';
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
    
    public function user(){
         return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function store(){
         return $this->hasOne('App\Models\Store', 'id', 'store_id');
    }

    public function product(){
         return $this->hasOne('App\Models\Products', 'id', 'product_id');
    }

}