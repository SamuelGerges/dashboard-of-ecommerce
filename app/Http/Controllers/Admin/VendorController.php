<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request\VendorRequest;
use App\Models\MainCategory;
use App\Models\Vendor;
use App\Notifications\VendorCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
    use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VendorController extends Controller
{

    public function GetVendors()
    {
//        $a = 'Samuel' ;
//        $b = "mena";
//        [$a,$b] = [$b,$a];
//
//        echo $a .'<br>' ;
//        echo $b;

        $vendors = Vendor::select()->paginate(PAGINATION_COUNT);
        return view('admin.vendors.index',compact('vendors'));
    }

    public function AddVendor()
    {
        $categories = MainCategory::where('translation_lang',get_default_lang())->active()->get();
        return view('admin.vendors.create',compact('categories'));
    }
    public function StoreVendor(VendorRequest $request)
    {
        try{

            $filePath ="";
            // TODO::THIS CODE IS USED TO get path of Logo
            if($request->has('logo')){
                $filePath = uploadImage('vendors',$request->logo);
            }
            // TODO::THIS CODE IS USED TO UPDATE ACTIVE
            if(!$request->has('active')){
                $request->request->add(['active' => 0]);
            }else{
                $request->request->add(['active' => 1]);
            }

            $vendor = Vendor::create([
                'logo'            => $filePath ,
                'name'            => $request-> name,
                'category_id'     => $request-> category_id,
                'mobile'          => $request-> mobile ,
                'email'           => $request-> email ,
                'password'        => $request-> password ,
                'active'          => $request-> active ,
                'address'         => $request-> address ,
                'latitude'        => $request-> latitude ,
                'longitude'       => $request-> longitude ,


            ]);

            Notification::send($vendor,new VendorCreated($vendor));   // send(toUser ,Message )
            return redirect()->route('admin.show_all_vendors')->with(['success' => '???? ?????????? ???????? ???????? ??????????']);
        }catch (\Exception $ex){
            return redirect()->route('admin.show_all_vendors')->with(['error' => '???????? ?????? ???? ???????? ???????????????? ?????? ????????']);
        }
    }

    public function EditVendor($id)
    {
        try {
            $vendor = Vendor::select()->find($id);
            if(!$vendor)
                return redirect()->route('admin.show_all_vendors')->with(['error' => '?????? ???????????? ?????? ?????????? ???? ???????? ???????? ????????????']);

            $categories = MainCategory::where('translation_lang',get_default_lang())->active()->get();
            return view('admin.vendors.edit',compact('vendor','categories'));
        }catch (\Exception $ex){
            return redirect()->route('admin.show_all_vendors')->with(['error' => '???????? ?????? ???? ???????? ???????????????? ?????? ????????']);
        }
    }
    public function UpdateVendor($id,VendorRequest $request)
    {
        try{

            $vendor = Vendor::select()->find($id);

            if(!$vendor)
                return redirect()->route('admin.show_all_vendors')->with(['error' => '?????? ???????????? ?????? ?????????? ???? ???????? ???????? ????????????']);

            DB::beginTransaction();
            // TODO::THIS CODE IS USED TO UPDATE LOGO
            if($request->has('logo')) {
                Storage::disk('assets')->delete(Vendor::getImage($id)->logo);
                $filePath_image = uploadImage('vendors',$request->logo);
                Vendor::where('id',$id)
                    ->update([
                        'logo'  => $filePath_image,
                    ]);
            }

            $data = $request->except('_token','id','logo','password');
            if($request->has('password'))
                $data['password'] = $request->password;

            Vendor::where('id',$id)->update($data);
            DB::commit();
            return redirect()->route('admin.show_all_category')->with(['success' => '???? ??????????  ??????????']);
        }catch (\Exception $ex){
            DB::rollBack();
            return redirect()->route('admin.show_lang')->with(['error' => '?????? ???? ?????????? ????????????']);

        }
    }
    public function ChangeStatusOfActive($id)
    {
        try {
            // TODO :: gets specific vendors
            $vendor = Vendor::find($id);
            if(!$vendor)
                return redirect()->route('admin.show_all_vendors')->with(['error' => '?????? ?????????? ?????? ????????????']);

            $active = $vendor->active == 0 ? 1 : 0 ;
            $vendor->update(['active' => $active]);
            return redirect()->route('admin.show_all_vendors')->with(['success' => '???? ???????? ???????????? ??????????']);

        }catch (\Exception $ex){
            return redirect()->route('admin.show_all_vendors')->with(['error' => '???????? ?????? ???? ???????? ???????????????? ?????? ????????']);
        }
    }

    public function DeleteVendor($id)
    {
        try {

            // TODO:: Validate category is existed
            $vendor = Vendor::find($id);
            if(!$vendor)
                return redirect()->route('admin.show_all_vendors')->with(['error' => '?????? ?????????? ?????? ????????????']);

            $logo =Str::after($vendor->logo,'assets/');
            $logo = public_path('assets/'.$logo);
            unlink($logo);   // delete from folder

            $vendor->delete();

            return redirect()->route('admin.show_all_vendors')->with(['success' => '???? ?????? ?????????? ??????????']);
        }catch (\Exception $ex){
            return redirect()->route('admin.show_all_vendors')->with(['error' => '?????? ???? ?????? ??????????']);
        }
    }
}
