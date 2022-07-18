<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request\MainCategoryRequest;
use App\Models\Admin;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MainCategoryController extends Controller
{

    public function GetMainCategory()
    {
        try {

            $default_lang = get_default_lang();
            $categories = MainCategory::where('translation_lang',$default_lang)->select()->paginate(PAGINATION_COUNT);
            return view('admin.mainCategories.index',compact('categories'));
        }catch (\Exception $ex){
            return redirect()->route('admin.show_all_category')->with(['error' => 'هناك خطأ ما يرجي المحاولة مرة أخري']);
        }
    }

    public function AddCategory()
    {
        return view('admin.mainCategories.create');
    }
    public function StoreCategory(MainCategoryRequest $request)
    {
        try{
            $main_categories = collect($request->category) ;
            $filter = $main_categories->filter(function ($value,$key){
                return $value['abbr'] == get_default_lang();
            });
            $filePath ="";
            if($request->has('image')){
                $filePath = uploadImage('mainCategories',$request->image);
            }
            DB::beginTransaction();
                $default_category = array_values($filter->all())[0];
                $default_category_id =MainCategory::insertGetId([
                    'translation_lang'  => $default_category['abbr'],
                    'translation_of'    => 0,
                    'name'              => $default_category['name'],
                    'slug'              => $default_category['name'],
                    'image'             => $filePath,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
                $categories = $main_categories->filter(function ($value,$key){
                    return $value['abbr'] != get_default_lang();
                });
                if(isset($categories) && $categories->count()){
                    $categories_array = [];
                    foreach ($categories as $category){
                        $categories_array[]= [
                            'translation_lang'  => $category['abbr'],
                            'translation_of'    => $default_category_id,
                            'name'              => $category['name'],
                            'slug'              => $category['name'],
                            'image'             => $filePath,
                            'created_at'        => now(),
                            'updated_at'        => now(),
                        ];
                    }
                    MainCategory::insert($categories_array);
                }
            DB::commit();
            return redirect()->route('admin.show_all_category')->with(['success' => 'تم إضافة قسم جديد بنجاح']);
        }catch (\Exception $ex){
            DB::rollBack();
            return redirect()->route('admin.show_all_category')->with(['error' => 'هناك خطأ ما يرجي المحاولة مرة أخري']);
        }
    }

    public function EditCategory($main_cat_id)
    {
        // TODO :: gets specific categories and its translations
        $mainCategory = MainCategory::with('categories')->selection()->find($main_cat_id);
        if(!$mainCategory)
            return redirect()->route('admin.show_all_category')->with(['error' => 'هذه القسم غير موجودة']);
        return view('admin.mainCategories.edit',compact('mainCategory'));
    }
    public function UpdateCategory($main_cat_id,MainCategoryRequest $request)
    {
        try {
            $mainCategory = MainCategory::find($main_cat_id);
            if(!$mainCategory)
                return redirect()->route('admin.show_all_category')->with(['error' => 'هذه القسم غير موجودة']);

            // TODO::THIS CODE IS USED TO UPDATE ACTIVE
            if(!$request->has('category.0.active')){
                $request->request->add(['active' => 0]);
            }else{
                $request->request->add(['active' => 1]);
            }

            // TODO:: UPDATE MAIN CATEGORY
            $category = array_values($request->category)[0];
            MainCategory::where('id',$main_cat_id)
                ->update([
                    'name'   =>$category['name'],
                    'active' =>$request->active,
                    'updated_at' => now(),

                ]);

            // TODO::THIS CODE IS USED TO UPDATE IMAGE
            if($request->has('image')) {
                Storage::disk('assets')->delete(MainCategory::getImage($main_cat_id)->image);
                $filePath_image = uploadImage('mainCategories',$request->image);
                MainCategory::where('id',$main_cat_id)
                    ->update([
                        'image'  => $filePath_image,
                    ]);
            }

            return redirect()->route('admin.show_all_category')->with(['success' => 'تم تحديث أسم القسم بنجاح']);
        }catch (\Exception $ex){
            return redirect()->route('admin.show_all_category')->with(['error' => 'فشل في تحديث القسم']);
        }
    }

    public function DeleteCategory($id)
    {
        try {

            // TODO:: Validate category is existed
            $mainCategory = MainCategory::find($id);
            if(!$mainCategory)
                return redirect()->route('admin.show_all_category')->with(['error' => 'هذه القسم غير موجودة']);

            // TODO:: check category have vendor or not
            $vendors = $mainCategory->vendors();
            if(isset($vendors) && $vendors->count() > 0)
                return redirect()->route('admin.show_all_category')->with(['error' => 'لا يمكن حذف هذا القسم']);

            $image =Str::after($mainCategory->image,'assets/');
            $image = public_path('assets/'.$image);
            unlink($image);   // delete from folder
            $mainCategory->delete();
            return redirect()->route('admin.show_all_category')->with(['success' => 'تم حذف القسم بنجاح']);
        }catch (\Exception $ex){
            return redirect()->route('admin.show_all_category')->with(['error' => 'فشل في حذف القسم']);
        }
    }
    public function ChangeStatusOfActive($id)
    {
        try {
            // TODO :: gets specific categories and its translations
            $mainCategory = MainCategory::find($id);
            if(!$mainCategory)
                return redirect()->route('admin.show_all_category')->with(['error' => 'هذه القسم غير موجودة']);

            $active = $mainCategory->active == 0 ? 1 : 0 ;
            $mainCategory->update(['active' => $active]);
            return redirect()->route('admin.show_all_category')->with(['success' => 'تم تغير الحالة بنجاح']);

        }catch (\Exception $ex){
            return redirect()->route('admin.show_all_category')->with(['error' => 'هناك خطأ ما يرجي المحاولة مرة أخري']);
        }
    }




}
