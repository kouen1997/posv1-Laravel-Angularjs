<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_purchase';
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
         return $this->belongsTo('App\Models\Store', 'store_id', 'id');
    }

    public function coupon(){
         return $this->belongsTo('App\Models\Coupon', 'coupon_id', 'id');
    }
}
