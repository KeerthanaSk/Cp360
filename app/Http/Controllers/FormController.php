<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Models\Option;
use App\Http\Models\StaticData;
use App\Http\Models\DynamicForm;
use App\Http\Models\DynamicFormField;

use DB;
use Redirect;
use Validator;

class FormController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Create Form
    public function create(Request $request)
    {
    	try{
            $model   = new DynamicForm;
    		if($request->method() == 'POST'){
                $validator    = DynamicForm::validateRequest($request);
                
                if($validator->fails()){
                    $errors   = $validator->errors();
                    $response = [
                                    'errors'=>$errors,
                                    'status'=>'error'
                                ];
                    return response()->json($response);
                }else{
                    DB::beginTransaction();
                    $model->fill($request->all());
                    $model->created_by  = Auth::user()->id;
                    $model->modified_by = Auth::user()->id;
                    if($model->save()){
                        DB::commit();
                        $message  = 'Data added successfully';
                        $response = [
                                    'message'=>$message,
                                    'status'=>'success',
                                    'id'=>$model->id
                                ];
                    }else{
                        DB::rollback();
                        $response = [
                                    'message'=>'Something went wrong !!!',
                                    'status'=>'warning'
                                ];
                    }
                    return response()->json($response);
                }
    		}
    		return view('form._form', compact('model'));
    	} catch (Exception $e) { 
            return (new Responses)->sendError(404);
        }
    }

    //get form field data for edit field
    public function fieldDetail(Request $request)
    {
        $id     = $request->id;
        $field  = DB::select(DB::raw("SELECT * from dynamic_form_fields where id = '$id'"));
        $option = Option::where('form_field_id', $field[0]->id)->get();
        $data   = array(
            'field' => $field,
            'options' => $option
        );
        echo json_encode($data);
    }

    //add more rows 
    public static function addOptions(Request $request)
    {
        $data = array(
            'row'       => $request->row,
            'model'     => new Option,
            'status'    => 'success',
            'item_num'  => $request->item_no,
            'id_no'     => $request->id_no,
        );
        if (request()->ajax()) {
            $html = \View::make('form.optionForm')->with($data)->render();
            return \Response::json([ 'row' => $request->row, 'data'=> $html, 'status' => 'success']);
        }
    }

    //manage form fields
    public function fields(Request $request){
        try{
            $modelForm   = DynamicForm::where('id', $request->form_id)->first();
            $model       = new DynamicFormField;
            $modelOption = new Option;
            $fields     = DynamicFormField::where('form_id', $request->form_id)->get();
            $types      = StaticData::fieldTypes();
            $isRequired = StaticData::isRequired();
    		if($request->method() == 'POST'){
                $validator    = DynamicFormField::validateRequest($request);
                $validator->after(function($validator)use($request)
                {
                    if($request->sub_type == '' && $request->label != '' && DynamicFormField::where('label', $request->label)->exists())
                    {
                        $validator->errors()->add('label', 'Label '.ucfirst($request->label).' already exists');
                    }
                });
                if($validator->fails()){
                    $errors   = $validator->errors();
                    $response = [
                                    'errors'=>$errors,
                                    'status'=>'error'
                                ];
                    return response()->json($response);
                }else{
                    DB::beginTransaction();

                    if($request->id != null){
                        $model  = DynamicFormField::where('id', $request->id)->first();
                        $model->modified_by = Auth::user()->id;
                    }else{
                        $model->modified_by = Auth::user()->id;
                        $model->created_by  = Auth::user()->id;
                    }
                    $options       = $request->type_value;
                    $filtered_data = array_filter($options);
                    $count         = count(array_filter($options));

                    $model->fill($request->all());
                    if($model->save()){
                        $old_option_id = Option::where('form_field_id',$model->id)->get()->pluck('type_value','type_value')->toArray();

                        if($count>0){
                            foreach ($filtered_data as $key=> $value) {
                                $id = $request->type_value[$key];
                                if(in_array($id, $old_option_id)){ 
                                    $modelOption  = Option::find($id);
                                    if($modelOption){
                                        $modelOption->type_value    = $value;
                                        $modelOption->save();
                                    }
                                    unset($old_option_id[$id]);
                                }else{
                                    $modelOption                = new Option;
                                    $modelOption->form_field_id = $model->id;
                                    $modelOption->type_value    = $value;
                                    $modelOption->save();
                                }
                            }
                        }
                        if($old_option_id != NULL){
                            foreach ($old_option_id as $value) {
                                Option::where(['form_field_id'=>$model->id,'type_value'=>$value])->delete();
                            }
                        }
                        DB::commit();
                        $form_fields = DynamicFormField::where('form_id', $request->form_id)->get();
                        $message  = $request->id != null? 'Data updated successfully!': 'Data added successfully';
                        $response = [
                                    'message'=>$message,
                                    'status'=>'success',
                                    'fields'=>$form_fields
                                ];
                    }else{
                        DB::rollback();
                        $response = [
                                    'message'=>'Something went wrong !!!',
                                    'status'=>'warning'
                                ];
                    }
                    return response()->json($response);
                }
    		}
    		return view('form._form_field', compact('model', 'types', 'isRequired', 'fields', 'modelOption', 'modelForm'));
    	} catch (Exception $e) { 
            return (new Responses)->sendError(404);
        }
    }

    //delete form field
    public function deleteField(Request $request)
    {
        try {
            $response = array('status'=>'error');
            if($request->id !='' ){
                $response = DynamicFormField::findOrFail($request->id);
                $options  = Option::where('form_field_id',$response->id)->get();

                if($response->delete()){
                    if($options->isNotEmpty()){
                        foreach($options as $option){
                            $option->delete();
                        }
                    }
                    $response['status']  = 'success';
                    \Session::flash('message', 'Successfully deleted'); 
                }else{
                    $response['status']  = 'error';
                    \Session::flash('error', 'Something went wrong'); 
                }
                return response()->json($response);
            }
        } catch (Exception $e) {
            return (new Responses)->sendError(404);
        }
    } 

    //view form
    public function view(Request $request)
    {
        try {
            $modelForm  = DynamicForm::findOrFail($request->form_id);
            $modelFormFields = DynamicFormField::where('form_id', $request->form_id)
                                ->where('status', 1)->orderBy('sort_order','ASC')->get();
            return view('form._view', compact('modelFormFields', 'modelForm'));
        }catch (Exception $e) {
            return (new Responses)->sendError(404);
        }
    }

    //delete form field
    public function deleteForm(Request $request)
    {
        try {
            $response = array('status'=>'error');
            if($request->id !='' ){
                $response = DynamicForm::findOrFail($request->id);
                $fields   = DynamicFormField::where('form_id', $request->id)->get();

                if($response->delete()){
                    if($fields->isNotEmpty()){
                        foreach($fields as $field){
                            $options  = Option::where('form_field_id',$field->id)->get();
                            if($field->delete()){
                                foreach($options as $option){
                                    $option->delete();
                                }
                            }
                        }
                    }
                    $response['status']  = 'success';
                    \Session::flash('message', 'Successfully deleted'); 
                }else{
                    $response['status']  = 'error';
                    \Session::flash('error', 'Something went wrong'); 
                }
                return response()->json($response);
            }
        } catch (Exception $e) {
            return (new Responses)->sendError(404);
        }
    } 

    //update sort order
    public function updateList(Request $request)
    {
        try {
            if($request->method() == 'GET'){
                $id    = (int)$_GET['id'];
                $order = (int)$_GET['order'];
                $model = DynamicFormField::where('id', $id)->first();
                if(!empty($model)){
                    $model->sort_order = $order;
                    $model->save();
                    $response['status']  = 'success';
                }
                else{
                    $response['status']  = 'error';
                }
                return response()->json($response);
            }    
        }catch (Exception $e) {
            return (new Responses)->sendError(404);
        }
    }

    //update status
    public function updateStatus(Request $request)
    {
        try {
            if($request->method() == 'GET'){
                $id    = (int)$_GET['id'];
                $val   = (int)$_GET['stat'];
                $model = DynamicFormField::where('id', $id)->first();
                if(!empty($model)){
                    $model->status = $val;
                    $model->save();
                    $response['status']  = 'success';
                }
                else{
                    $response['status']  = 'error';
                }
                return response()->json($response);
            }    
        }catch (Exception $e) {
            return (new Responses)->sendError(404);
        }
    }

    //edit form
    public function edit(Request $request)
    {
        try{
            $model   = DynamicForm::findOrFail($request->form_id);
    		if($request->method() == 'POST'){
                $validator    = DynamicForm::validateRequest($request);
                
                if($validator->fails()){
                    $errors   = $validator->errors();
                    $response = [
                                    'errors'=>$errors,
                                    'status'=>'error'
                                ];
                    return response()->json($response);
                }else{
                    DB::beginTransaction();
                    $model->fill($request->all());
                    $model->modified_by = Auth::user()->id;
                    if($model->save()){
                        DB::commit();
                        $message  = 'Data updated successfully';
                        $response = [
                                    'message'=>$message,
                                    'status'=>'success',
                                    'id'=>$model->id
                                ];
                    }else{
                        DB::rollback();
                        $response = [
                                    'message'=>'Something went wrong !!!',
                                    'status'=>'warning'
                                ];
                    }
                    return response()->json($response);
                }
    		}
    		return view('form._edit', compact('model'));
    	} catch (Exception $e) { 
            return (new Responses)->sendError(404);
        }
    }

    //dynamically update/save option to db
    public function optionRows(Request $request)
    {
        $html   = '';
        if($request->field_id != 0){
            $model  = Option::where('form_field_id',$request->field_id)->get();
            $count  = count($model);
            if($count>0){
                $j = 0;
                for ($i=1; $i <= $count; $i++) { 
                    $model_data = Option::findOrFail($model[$j]->id);
                    $data = array(
                        'row'       => $j,
                        'model'     => $model_data,
                        'status'    => 'success',
                        'item_num'  => $i,
                    );
                    $html .= \View::make('form.optionForm')->with($data)->render();
                    $j++;
                }
            }
        }else{
            $data = array(
                'row'       => $request->row,
                'model'     => new Option,
                'status'    => 'success',
                'item_num'  => $request->item_no,
                'id_no'     => $request->id_no,
            );
            $html = \View::make('form.optionForm')->with($data)->render();
        }
        if (request()->ajax()) {
            return \Response::json([ 'row' => $request->row, 'data'=> $html, 'status' => 'success']);
        }
    }
}
