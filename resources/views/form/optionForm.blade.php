@php
use App\Http\Models\StaticData;
@endphp
<div class="row new_row add_more_row" id="{{$row}}">
    <div class="col-sm-2">
        <div class="form-group">
            {{ Form::label('Option','',array('for'=>'type_value')) }}
            {{ Form::text('type_value[]', old('type_value', ($model->type_value)), ['class' => 'form-control type_value', 'id'=>'type_value_'.$item_num,'placeholder'=>'Enter Option']) }}
            <span id="type_value-error" class="error invalid-feedback"></span>
        </div>
    </div> 
    <div class="col-sm-2">
        @if($row==0)
        <div class="form-group">
            <button type="button" id="add-dep" class="btn btn-primary add_row_btn" style="margin-top: 32px;">
            {{'Add More +'}}</button>
        </div>
        @else
        <div class="form-group">
            <button type="button" id="" class="btn btn-danger rem-dep" data-row="{{$row}}" style="margin-top: 32px;">
            {{'Remove -'}}</button>
        </div>
        @endif
    </div>
</div>