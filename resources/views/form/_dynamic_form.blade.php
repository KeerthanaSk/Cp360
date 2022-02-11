<tr id="{{$row}}">
	<td class="td" width="80">
        <div class="form-group">
            {{ Form::text('type_value[]', old('type_value', $modelOption->type_value) , ['class' => 'form-control type_value', 'id'=>'type_value'.$id_no , 'data-toggle'=>$row, 'placeholder'=>'Option'] ) }}
        </div>
	</td>
	<td  class="td">
        @php
        if($row != 0){
            @endphp
                <div class="col-md-1 add-btn" style="margin-top: 3%">
                    <div class="form-group">
                        <button class="btn btn-default btn-round btn-icon delete-i md-btn rem-acc" id="rem-acc" value="Submit" data-row="{{$row}}">{{'-'}}</button>
                    </div>
                </div> 
            @php
        }else{
            @endphp
                <div class="col-md-1 add-btn" style="margin-top: 3%">
                    <div class="form-group"></div>
                </div>                     
            @php
        }
        @endphp
	</td>
</tr>