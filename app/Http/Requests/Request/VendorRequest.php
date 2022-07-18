<?php

namespace App\Http\Requests\Request;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        return [
            'logo'              =>'required_without:id|mimes:jpg,jpeg,png',
            'name'              =>'required|string|max:100',
            'mobile'            =>'required|numeric|unique:vendors,mobile,'.$this->id,
            'email'             =>'required|email|max:100|unique:vendors,email,'.$this->id,
            'password'          =>'required_without:id',
            'category_id'       =>'required|exists:main_categories,id',
            'address'           =>'required|string|max:500',

        ];
    }
    public function messages()
    {
        return [
            'required'                    => 'هذا الحقل مطلووب',
            'max'                         => 'انتبه قد وصلت للحد لادني من الحروف',
            'string'                      => '   أن يكون أحرف',
            'email.email'                 => 'اكتب الأيميل بشكل صحيح',
            'category_id.exists'          => 'هذا القسم ليس موجود',
            'logo.required_without'       => 'يجب أن تضع لوجو',
            'password.required_without'   => 'يجب أن تدخل كلمة السر',
            'email.unique'                => 'هذا البريد الالكتروني مستخدم من قبل',
            'mobile.unique'               => 'هذا الهاتف مستخدم من قبل',





        ];
    }
}
