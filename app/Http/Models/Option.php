<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $table    = 'options';
    protected $fillable = [
		'form_id', 'form_field_id', 'created_by', 'modified_by'
	];

    public static function getOptionByField($id){
        return Option::where('form_field_id', $id)->pluck('type_value', 'id')->all();
    }
}
