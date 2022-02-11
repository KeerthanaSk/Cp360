<?php

namespace App\Http\Models;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class DynamicFormField extends Model
{
    use SoftDeletes;

    protected $table    = 'dynamic_form_fields';
    protected $fillable = [
		'form_id', 'label', 'type', 'is_required', 'sort_order', 'status', 'created_by', 'modified_by'
	];

    public static function validateRequest(Request $request)
    {
    	$validator  = Validator::make($request->all(), [
                'form_id'      => 'required', 
                'label'        => 'required', 
                'type'         => 'required', 
                'is_required'  => 'required', 
            ],
            [
                'is_required.required' => 'The Is Required field required',
            ]
    	);
    	return $validator;
    }
}
