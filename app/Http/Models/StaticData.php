<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class StaticData extends Model
{
    public static function fieldTypes($key=NULL)
    {
    	$types  = array(
        				1 => 'Text',
                        2 => 'Number',
                        3 => 'Text Area',
                        4 => 'Drop Down',
                        5 => 'Radio Button',
                        6 => 'Check Box',
                        7 => 'File',
    				);

    	if($key != NULL){
    		return $types[$key];
    	}else{
    		return $types;
    	}
    }

    public static function isRequired($key=NULL)
    {
    	$types  = array(
        				1 => 'Yes',
                        2 => 'No',
    				);

    	if($key != NULL){
    		return $types[$key];
    	}else{
    		return $types;
    	}
    }

	public static function retrieveType($type)
	{
		if($type == 1){//Text
			$html = 'text';
		}
		else if ($type == 2){//Number
			$html = 'number';
		}
		else if ($type == 3){//Text Area
			$html = 'textarea';
		}
		else if ($type == 4){//Drop Down
			$html = 'select';
		}
		else if ($type == 5){//Radio Button
			$html = 'radio';
		}
		else if ($type == 6){//Check Box
			$html = 'checkbox';
		}
		else if ($type == 7){//File
			$html = 'file';
		}

		return $html;
	}
}
