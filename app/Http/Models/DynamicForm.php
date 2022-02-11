<?php

namespace App\Http\Models;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class DynamicForm extends Model
{
    use SoftDeletes;

    protected $table    = 'dynamic_forms';
    protected $fillable = [
		'name', 'created_by', 'modified_by'
	];

    public static function validateRequest(Request $request)
    {
    	$validator  = Validator::make($request->all(), [
                'name'  => 'required', 
            ],
    	);
    	return $validator;
    }
}
