@php
use App\Http\Models\Option;
use App\Http\Models\StaticData;
@endphp
<h1>{{ucfirst($modelForm->name)}}</h1>
<a href="{{route('home')}}">back</a>

<form>
    @if($modelFormFields->isNotEmpty())
        @foreach ($modelFormFields as $field)
            @php 
                $type  = StaticData::retrieveType($field->type);
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
                        @if(!empty($options))
                            @foreach($options as $key=>$option)
                                <option value="{{$key}}">{{$option}}</option>
                            @endforeach
                        @endif
                    </select><br><br>
                    @php
                }
                else if($type == 'radio' || $type == 'checkbox'){
                    $options  = Option::getOptionByField($field->id);
                    @endphp
                    <label for="{{$field->label}}">{{ucfirst($field->label)}}{{$requiredSymbol}}:</label><br>
                        @if(!empty($options))
                            @foreach($options as $key=>$option)
                                <input type="{{$type}}" id="{{$option.$key}}" name="{{$field->label}}" value="{{$option}}" required="{{$required}}">
                                <label for="{{$option}}">{{ucfirst($option)}}</label><br>
                            @endforeach<br><br>
                        @endif
                    @php
                }
            @endphp
        @endforeach
        <input type="submit" value='submit'>
    @endif
</form>