@php
use App\Http\Models\StaticData;
@endphp
{{Form::model($model, array('id'=>'createFormFields','files'=>true))}}
@csrf 
    <h3>Manage fields for {{$modelForm->name}}</h3> <a href="{{route('home')}}">back</a>
    <a href="{{route('viewForm', ['form_id'=>$modelForm->id])}}">view form</a>
    <i>both create and update managed in same form</i>
    <hr>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                {{ Form::label('Field Label*','',array('for'=>'label')) }}
                {{ Form::text('label', old('label', ucfirst($model->label)), [ 'class'=>'form-control', 'id'=>'label','placeholder'=>'Field Label']) }}<br>
                <span id="label-error" class="error invalid-feedback" style="color:red;margin-left: 6%;font-size: 13px;"></span>
            </div>
        </div><br>

        <div class="col-sm-4">
            <div class="form-group">
                {{ Form::label('Field Type','',array('for'=>'type')) }}
                {{ Form::select('type', $types,old('type',$model->type),array('class'=>'form-control','placeholder'=>'Select Field Type', 'id'=>'type')) }}<br>
                <span id="type-error" class="error invalid-feedback" style="color:red;margin-left: 5%;font-size: 13px;"></span>
            </div>
        </div><br>

        {{-- dynamic rows section statrt--}}
        <div role="tabpanel" class="tab-pane fade in show active option" id="option"></div>
        {{-- dynamic rows section end --}}

        <div class="col-sm-4">
            <div class="form-group">
                {{ Form::label('Is Required','',array('for'=>'is_required')) }}
                {{ Form::select('is_required', $isRequired,old('is_required',$model->is_required),array('class'=>'form-control','placeholder'=>'Select','id'=>'is_required')) }}<br>
                <span id="is_required-error" class="error invalid-feedback" style="color:red;margin-left: 5%;font-size: 13px;"></span>
            </div>
        </div><br>

        {{ Form::hidden('id', '',array('id' => 'id'))}}
        {{ Form::hidden('form_id', $modelForm->id)}}
        {{ Form::hidden('sub_type', 'create', array('id' => 'sub_type'))}}
       
        <div class="card-footer">
            <button type="submit" class="btn btn-primary submitBtn" value="">Add Field</button>
            <span id="submit_loader"></span>
        </div>
    </div>
{{ Form::close()}}
<hr>
@if($fields->isNotEmpty() )
    <h3>Field List</h3>
    <table id="fieldTable" style="font-size: 15px;width:50%" border="1" cellspacing="">
        <thead>
            <tr>
                <th>#</th>
                <th>Label</th>
                <th>Type</th>
                <th>Required</th>
                <th>Sort Order</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @php
            $i=1;
            @endphp
            @foreach ($fields as $row)
                <tr>
                    <td>{{$i}}</td>
                    <td>{{ucfirst($row->label)}}</td>
                    <td>{{StaticData::fieldTypes($row->type)}}</td>
                    <td>{{StaticData::isRequired($row->is_required)}}</td>
                    <td><input type="number" min="1" id="review_<?= $row->id ?>" class="list_order" data_value="<?= $row->id ?>" step="1" value="<?= $row->sort_order ?>" oninput="validity.valid||(value='');" style="width:20%"></td>
                    <td>
                        <select id="status_<?= $row->id ?>" name="status" data_value="<?= $row->id ?>" class="status">
                            <option value="1" <?=$row->status == '1' ? "selected" : ''?>>Active</option>
                            <option value="0" <?=$row->status == '0' ? "selected" : ''?>>Inactive</option>
                        </select>
                    </td>
                    <td>
                        <a href="javascript:void(0)" class="button button1 editBtn" id="edit-btn-{{$row->id}}" data-field-id="{{$row->id}}"> edit </a>
                        <a href="javascript:void(0)" class="button button4 deleteBtn" id="delete-btn-{{$row->id}}" data-field-id="{{$row->id}}"> Delete </a>
                    </td>
                </tr>
                @php
                $i++;
                @endphp
            @endforeach
        </tbody>
    </table>
@endif
@include('form.script')