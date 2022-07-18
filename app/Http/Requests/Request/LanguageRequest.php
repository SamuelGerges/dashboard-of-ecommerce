<?php

namespace App\Http\Requests\Request;

use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
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
            'name'      =>'required|string|max:100',
            'abbr'      =>'required|string|max:10',
            'direction' =>'required|in:rtl,ltr',
//            'active'    =>'required|in:0,1',
        ];
    }
    public function messages()
    {
        return [
            'required'               => 'هذا الحقل مطلووب',
            'name.string'            => 'اسم اللغة لابد أن يكون أحرف',
            'name.max'               => 'اسم اللغة لابد الا يزيد عن 100 احرف',
            'abbr.string'            => 'اختصار اسم اللغة لابد أن يكون أحرف',
            'abbr.max'               => 'هذا الحقل لابد الا يزيد عن 10 احرف',
            'in'                     => 'القيم المدخلة غير صحيحة ',

        ];
    }
}
