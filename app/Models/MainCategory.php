<?php

namespace App\Models;

use App\Observers\MainCategoryObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MainCategory extends Model
{
    protected $table ='main_categories';
    protected $fillable =['translation_lang','translation_of','name','slug','image','active','created_at','updated_at'];


    protected static function boot()
    {
        parent::boot();
        MainCategory::observe(MainCategoryObserver::class);
    }
    public function scopeActive($query)
    {
        return $query->where('active','=',1);
    }

    public function scopeSelection($query)
    {
        return $query->select('id','translation_lang','translation_of','name','slug','image','active') ;
    }

    public function getActive(){
        return   $this -> active == 1 ? 'مفعل'  : 'غير مفعل';
    }

    public function getImageAttribute($val)
    {
        return $val !== null ? asset('assets/'.$val) : "";
    }

    public static function getImage($id){
        $image = DB::table('main_categories')
            ->select('image')
            ->where('id','=',$id)->first();
        return $image;
    }

    public function categories()
    {
        return $this->hasMany(self::class,'translation_of');
    }

    public function vendors()
    {
        return $this->hasMany('App\Models\Vendor', 'category_id','id');
    }


}
