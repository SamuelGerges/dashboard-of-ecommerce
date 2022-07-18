<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Vendor extends Model
{
    use Notifiable;
    protected $table ='vendors';
    protected $fillable =['name','logo','mobile','address','email','active','password','category_id','created_at','updated_at'];
    protected $hidden = ['category_id','password'];


    public function scopeActive($query)
    {
        return $query->where('active','=',1);
    }

    public function scopeSelection($query)
    {
        return $query->select('id','name','category_id','logo','mobile','address','email','active') ;
    }

    public function getActive(){
        return   $this -> active == 1 ? 'مفعل'  : 'غير مفعل';
    }

    public function getLogoAttribute($val)
    {
        return $val !== null ? asset('assets/'.$val) : "";
    }

    public function setPasswordAttribute($password)
    {
        if(!empty($password))
            $this->attributes['password'] = bcrypt($password);
    }

    public static function getImage($id){
        $logo = DB::table('vendors')
            ->select('logo')
            ->where('id','=',$id)->first();
        return $logo;
    }

    public function category()
    {
        return $this->belongsTo('App\Models\MainCategory', 'category_id','id');
    }


}
