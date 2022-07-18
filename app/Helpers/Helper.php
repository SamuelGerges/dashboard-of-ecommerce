<?php


use Illuminate\Support\Facades\Config;


define('PAGINATION_COUNT',10);


function get_languages(){
    return \App\Models\Language::active() -> Selection() -> get();
}

function get_default_lang(){
    return Config::get('app.locale');
}

function get_name_of_admin()
{
    if(auth()->guard('admin') ){
        $data= \App\Models\Admin::select('name')->first();
        return $data->name;
    }
}

function uploadImage($folder,$image)
{
    $image->store('/',$folder);
    $fileName = $image->hashName();
    $path = 'images/' . $folder . '/' .$fileName;
    return $path;
}

