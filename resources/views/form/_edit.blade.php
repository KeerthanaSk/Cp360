{{Form::model($model, array('id'=>'updateForm'))}}
    @csrf 
    <h3>Update {{$model->name}}</h3>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                {{ Form::label('Form Name*','',array('for'=>'name')) }}
                {{ Form::text('name', old('name', ucfirst($model->name)), [ 'class'=>'form-control', 'id'=>'name','placeholder'=>'Form Name']) }}<br>
                <span id="name-error" class="error invalid-feedback" style="color:red;margin-left: 7%;font-size: 13px;"></span>
            </div>
        </div><br>

        {{ Form::hidden('form_id', $model->id,array('id' => 'id'))}}
        {{ Form::hidden('sub_type', 'edit', array('id' => 'sub_type'))}}
       
        <div class="card-footer">
            <button type="submit" class="btn btn-primary submitBtn" value="">Update Form</button>
            <span id="submit_loader"></span>
        </div>
    </div>
{{ Form::close()}}
@include('form.script')