<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_expense';
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
    
    public function store(){
         return $this->belongsTo('App\Models\Store', 'store_id', 'id');
    }

    public function category(){
         return $this->belongsTo('App\Models\ExpenseCategory', 'category_id', 'id');
    }
}
