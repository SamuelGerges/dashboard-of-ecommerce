<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request\LanguageRequest;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function GetLang()
    {
        $languages = Language::select()->paginate(PAGINATION_COUNT);
        return view('admin.languages.index',compact('languages'));
    }
    public function AddLang()
    {
        return view('admin.languages.create');
    }
    public function StoreLang(LanguageRequest $request)
    {
        try{
            Language::create($request->except('_token'));
            return redirect()->route('admin.show_lang')->with(['success' => 'تم حفظ اللغة بنجاح']);
        }catch (\Exception $ex){
            return redirect()->route('admin.show_lang')->with(['error' => 'هناك خطأ ما يرجي المحاولة مرة أخري']);
        }
    }


    public function EditLang($id)
    {
        $language = Language::select()->find($id);
        if(!$language){
            return redirect()->route('admin.show_lang')->with(['error' => 'هذه اللغة غير موجودة']);
        }
        return view('admin.languages.edit',compact('language'));
    }
    public function UpdateLang($id,LanguageRequest $request)
    {
        try{
            $language = Language::select()->find($id);
            if(!$language){
                return redirect()->route('admin.edit_lang',$id)->with(['error' => 'هذه اللغة غير موجودة']);
            }
            if(!$request->has('active'))
                $request->request->add(['active' => 0]);
            $language->update($request->except('_token'));
            return redirect()->route('admin.show_lang')->with(['success' => 'تم التحديث اللغة بنجاح']);
        }catch (\Exception $ex){
            return redirect()->route('admin.show_lang')->with(['error' => 'فشل في تحديث اللغة']);

        }
    }
    public function DeleteLang($id)
    {
        try{
            $language = Language::select()->find($id);
            if(!$language){
                return redirect()->route('admin.show_lang',$id)->with(['error' => 'هذه اللغة غير موجودة']);
            }
            $language->delete($id);
            return redirect()->route('admin.show_lang')->with(['success' => 'تم حذف اللغة بنجاح']);
        }catch (\Exception $ex){
            return redirect()->route('admin.show_lang')->with(['error' => 'فشل في حذف اللغة']);

        }
    }
}
