<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_store';
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

        return $this->hasMany('App\Models\User' , 'store_id', 'id');
                
    }

    public function product() {

        return $this->hasMany('App\Models\Products' , 'store_id', 'id');
                
    }   


}
