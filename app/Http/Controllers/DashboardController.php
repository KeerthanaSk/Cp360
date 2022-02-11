<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Models\Option;
use App\Http\Models\StaticData;
use App\Http\Models\DynamicForm;
use App\Http\Models\DynamicFormField;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $modelForm  = DynamicForm::all();
            return view('dashboard.index', compact('modelForm'));
        }catch (Exception $e) {
            return (new Responses)->sendError(404);
        }
    }

    //view form
    public function view(Request $request)
    {
        try {
            $modelForm  = DynamicForm::findOrFail($request->id);
            $modelFormFields = DynamicFormField::where('form_id', $request->id)->orderBy('id','ASC')->get();
            return view('dashboard._view', compact('modelFormFields', 'modelForm'));
        }catch (Exception $e) {
            return (new Responses)->sendError(404);
        }
    }
}
