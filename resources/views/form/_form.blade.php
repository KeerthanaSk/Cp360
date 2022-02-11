{{Form::model($model, array('id'=>'createForm','files'=>true))}}
@csrf 
    <h3>Add dynamic form</h3>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                {{ Form::label('Form Name*','',array('for'=>'name')) }}
                {{ Form::text('name', old('name', ucfirst($model->name)), [ 'class'=>'form-control', 'id'=>'name','placeholder'=>'Form Name']) }}<br>
                <span id="name-error" class="error invalid-feedback" style="color:red;margin-left: 7%;font-size: 13px;"></span>
            </div>
        </div><br>

        {{ Form::hidden('id', '',array('id' => 'id'))}}
        {{ Form::hidden('sub_type', 'create', array('id' => 'sub_type'))}}
       
        <div class="card-footer">
            <button type="submit" class="btn btn-primary submitBtn" value="">Add Form</button>
            <span id="submit_loader"></span>
        </div>
    </div>
{{ Form::close()}}
@include('form.script')