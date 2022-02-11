@php
use App\Http\Models\Option;
@endphp
<h1>{{ucfirst($modelForm->name)}}</h1>
<a href="{{route('/')}}">back</a>

<form>
    @if($modelFormFields->isNotEmpty())
        @foreach ($modelFormFields as $field)
            @php 
                if($field->type == 1){//Text
                    $type = 'text';
                }
                else if ($field->type == 2){//Number
                    $type = 'number';
                }
                else if ($field->type == 3){//Text Area
                    $type = 'textarea';
                }
                else if ($field->type == 4){//Drop Down
                    $type = 'select';
                }
                else if ($field->type == 5){//Radio Button
                    $type = 'radio';
                }
                else if ($field->type == 6){//Check Box
                    $type = 'checkbox';
                }
                else if ($field->type == 7){//File
                    $type = 'file';
                }
                
                $requiredSymbol  = $field->is_required == 1? '*': '';
                $required  = $field->is_required == 1? 'required': '';

                if($type == 'text' || $type == 'number' || $type == 'file'){
                    @endphp
                    <label for="{{$field->label}}">{{ucfirst($field->label)}}{{$requiredSymbol}}:</label><br>
                    <input type="{{$type}}" id="{{$field->label}}" name="{{$field->label}}" placeholder="{{ucfirst($field->label)}}" {{$required}}><br><br>
                    @php
                }
                else if($type == 'textarea'){
                    @endphp
                    <label for="{{$field->label}}">{{ucfirst($field->label)}}{{$requiredSymbol}}:</label><br>
                    <textarea id="{{$field->label}}" name="{{$field->label}}" rows="4" cols="50" placeholder="{{ucfirst($field->label)}}"></textarea><br><br>
                    @php
                }
                else if($type == 'select'){
                    $options  = Option::getOptionByField($field->id);
                    @endphp
                    <label for="{{$field->label}}">{{ucfirst($field->label)}}{{$requiredSymbol}}:</label><br>
                    <select name="{{$field->label}}" id="{{$field->label}}">
                        <option value="">Select {{ucfirst($field->label)}}</option>
                        @foreach($options as $key=>$option)
                            <option value="{{$key}}">{{$option}}</option>
                        @endforeach
                    </select><br><br>
                    @php
                }
                else if($type == 'radio' || $type == 'checkbox'){
                    $options  = Option::getOptionByField($field->id);
                    @endphp
                    <label for="{{$field->label}}">{{ucfirst($field->label)}}{{$requiredSymbol}}:</label><br>
                        @foreach($options as $key=>$option)
                            <input type="{{$type}}" id="{{$option.$key}}" name="{{$field->label}}" value="{{$option}}" required="{{$required}}">
                            <label for="{{$option}}">{{ucfirst($option)}}</label><br>
                        @endforeach<br><br>
                    @php
                }
            @endphp
        @endforeach
        <input type="submit" value='submit'>
    @endif
</form>