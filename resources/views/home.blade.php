@extends('layouts.app')
<style>
    #forms {
      font-family: Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }
    
    #forms td, #forms th {
      border: 1px solid #ddd;
      padding: 8px;
    }
    
    #forms tr:nth-child(even){background-color: #f2f2f2;}
    
    #forms tr:hover {background-color: #ddd;}
    
    #forms th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
      background-color: #04AA6D;
      color: white;
    }

    .button {
        border: none;
        color: white;
        padding: 6px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 12px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 8px;
    }

    .button1 {background-color: #4CAF50;}
    .button2 {background-color: #814106;}
    .button3 {background-color: #0542c5;}
    .button4 {background-color: #f00c0c;}
</style>
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
<hr>
                    <br><br><br>
                    <a href="{{ url('addForms')}}"><b>Add Form</b></a>
                    
                    @if($model->isNotEmpty())
                        <h3>List of forms</h3>
                        <table id="forms" style="font-size: 15px;width:50%">
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                            @foreach ($model as $row)
                                <tr>
                                    <td>{{ucfirst($row->name)}}</td>
                                    <td>
                                        <a href="{{ url('addFormFields', ['id'=>$row->id])}}" class="button button1"> Manage Form Fields </a>
                                        <a href="{{ url('viewForm', ['id'=>$row->id])}}" class="button button2"> View Form </a>
                                        <a href="{{ url('editForm', ['id'=>$row->id])}}" class="button button3"> Edit Form </a>
                                        <a href="javascript:void(0)" class="button button4 deleteBtn" id="delete-btn-{{$row->id}}" data-form-id="{{$row->id}}"> Delete </a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.deleteBtn').on('click', function(e) { 
        e.preventDefault();
        if(confirm("{{'Deleting the form will delete all related data. Are you sure you want to continue?'}}")){
            var fid = $( this ).data('form-id');
            var url = '<?php echo url('/deleteForm'); ?>'+'/'+fid; 
            $.ajax({
                url: url,
                type: "Get",
                dataType: 'json',
                data:{id:fid}, 
                success: function(response){ 
                    if(response.status=='success'){
                        alert('Successfully deleted');
                        location.reload();
                    }else{
                        alert('Something went wrong');
                        location.reload();
                    }
                }
            });
        }
    });
});
</script>