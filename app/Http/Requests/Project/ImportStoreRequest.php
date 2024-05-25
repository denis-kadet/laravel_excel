<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ImportStoreRequest extends FormRequest
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
     * @return array<string, mixed>
     * @throws ValidationException
     */
    public function rules()
    {
        if(!in_array($this->file->getClientOriginalExtension(), ['xlsx', 'xls', 'csv'])) {
          throw ValidationException::withMessages(['Invalid file format.']);
        }

        return [
            'file' => 'required|file'
        ];
    }
}
