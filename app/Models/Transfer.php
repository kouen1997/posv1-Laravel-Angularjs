<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_transfer';
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

    
    public function store_from(){
         return $this->belongsTo('App\Models\Store', 'from_store_id', 'id');
    }

    public function store_to(){
         return $this->belongsTo('App\Models\Store', 'to_store_id', 'id');
    }
    

}
