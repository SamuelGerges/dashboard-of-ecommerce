<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Admin extends Authenticatable
{

    use Notifiable;
    protected $table ='admins';
    protected $fillable =['name','image','email','password','created_at','updated_at'];
    protected $hidden = ['password','remember_token'];




    public function scopeSelection($query)
    {
        return $query->select('name','email');
    }









}
