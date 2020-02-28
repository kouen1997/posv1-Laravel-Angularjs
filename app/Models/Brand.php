<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_brand';
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

    public function user() {

        return $this->hasOne('App\Models\User' , 'user_id', 'id');
                
    }

    public function products() {

        return $this->hasMany('App\Models\Products' , 'brand_id', 'id');
                
    }
    

}
