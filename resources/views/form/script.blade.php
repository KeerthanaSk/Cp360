
<script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        //submit form
        $('#createForm').submit(function(event)
        {
            event.preventDefault();
            $("#submit_loader").text("{{'Loading.....'}}");
            
            $.ajax(
            {
                url : "{{ route('addForms') }}",
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function(){
                    $('.submitBtn').attr("disabled","disabled");
                    $('#createForm').css("opacity",".5");
                },
                success:function(response){
                    $("#submit_loader").text('');
                    if(response.status == "success"){
                        $('#createForm').css("opacity","");
                        $(".submitBtn").removeAttr("disabled");
                        alert(response.message)
                        var id  = response.id;
                        var url = '<?php echo url('/addFormFields'); ?>'+'/'+id; 
                        window.location.href = url;
                        
                    }else if(response.status == "warning"){
                        alert(response.message)
                    }
                    else{
                        $('#createForm').css("opacity","");
                        $(".submitBtn").removeAttr("disabled");
                        var errors  = response.errors;
                        $(".is-invalid").removeClass("is-invalid");
                        $.each(errors, function(index, value){
                            $("#"+index).addClass('is-invalid');
                            $("#"+index+"-error").text(value);
                        }); 
                    }
                },
            });
        });

        //submit form fields
        $('#createFormFields').submit(function(event)
        {
            event.preventDefault();
            $("#submit_loader").text("{{'Loading.....'}}");
            $.ajax(
            {
                url : "{{ route('addFormFields') }}",
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function(){
                    $('.submitBtn').attr("disabled","disabled");
                    $('#createFormFields').css("opacity",".5");
                },
                success:function(response){
                    $("#submit_loader").text('');
                    if(response.status == "success"){
                        $('#createFormFields').css("opacity","");
                        $(".submitBtn").removeAttr("disabled");
                        alert(response.message)

                        $("#createFormFields")[0].reset();
                        location.reload();
                        
                    }else if(response.status == "warning"){
                        alert(response.message)
                    }
                    else{
                        $('#createFormFields').css("opacity","");
                        $(".submitBtn").removeAttr("disabled");
                        var errors  = response.errors;
                        $(".is-invalid").removeClass("is-invalid");
                        $.each(errors, function(index, value){
                            $("#"+index).addClass('is-invalid');
                            $("#"+index+"-error").text(value);
                        }); 
                    }
                },
            });
        });

        //add more section display
        $('.option').hide();
        $('#type').change(function() {
            $(".add_more_row").each(function() {
                $(this).remove();
            });
            if($(this).val() == 4 || $(this).val() == 5 || $(this).val() == 6) {
                update_view(0);
                $('.option').show();
            }else{
                $('.option').hide();
            }
        })

        //get field type 
        function getType(id){
            switch(id){
                case '1' : return 'Text'; break;
                case '2' : return 'Number'; break;
                case '3' : return 'Text Area'; break;
                case '4' : return 'Drop Down'; break;
                case '5' : return 'Radio Button'; break;
                case '6' : return 'Check Box'; break;
                case '7' : return 'File'; break;
            }
        }

        //edit form field
        $('.editBtn').on('click', function(e) {
            e.preventDefault();
            var fid = $( this ).data('field-id');
            var url = '<?php echo url('/getFields'); ?>'+'/'+fid; 
            $.ajax({
                url: url,
                type: "Get",
                dataType: 'json',
                data:{id:fid}, 
                success: function(response){ 

                    $("#label").val(response.field[0].label); 
                    $("#type").val(response.field[0].type); 
                    $(".add_more_row").each(function() {
                        $(this).remove();
                    });
                    if(response.field[0].type == 4 || response.field[0].type == 5 || response.field[0].type == 6){
                        $('.option').show();
                        update_view(response.field[0].id);
                    }else{
                        $('.option').hide();
                    }
                    $("#is_required").val(response.field[0].is_required);  
                    $('.submitBtn').html('Update Field');
                    $('#sub_type').val('edit');
                    $('#id').val(fid);
                }
            });
        });

        //delete form field
        $('.deleteBtn').on('click', function(e) {
            e.preventDefault();
            if(confirm("{{'Are you sure want to delete this form field?'}}")){
                var fid = $( this ).data('field-id');
                var url = '<?php echo url('/deleteField'); ?>'+'/'+fid; 
                $.ajax({
                    url: url,
                    type: "Get",
                    dataType: 'json',//this will expect a json response
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

        //sort order of form field
        $('.list_order').on('change',function(){			
            var order = $(this).val();
            var id    = $(this).attr('data_value');
            var url = '<?php echo url('/updateList'); ?>'+'/'+id; 
            $.ajax({
                'type': 'GET',
                'url' : url,
                'data': {'order': order, 'id': id},
                'cache': false,
                'success': function (response) {}
            });
        });

        //update form field status
        $('.status').on('change',function(){
            var stat  = $(this).val();
            var id    = $(this).attr('data_value');
            var url = '<?php echo url('/updateStatus'); ?>'+'/'+id; 
            $.ajax({
                'type': 'GET',
                'url' : url,
                'data': {'stat': stat, 'id': id},
                'cache': false,
                'success': function (response) {
                    if(response.status== "success"){
                        alert('Status updated successfully')
                    }
                }
            });
        });

        //update form name
        $('#updateForm').submit(function(event)
        {
            event.preventDefault();
            $("#submit_loader").text("{{'Loading.....'}}");
            $.ajax(
            {
                url : "{{ route('editForm') }}",
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function(){
                    $('.submitBtn').attr("disabled","disabled");
                    $('#createForm').css("opacity",".5");
                },
                success:function(response){
                    $("#submit_loader").text('');
                    if(response.status == "success"){
                        $('#createForm').css("opacity","");
                        $(".submitBtn").removeAttr("disabled");
                        alert(response.message)
                        var id  = response.id;
                        var url = '<?php echo url('/home'); ?>'; 
                        window.location.href = url;
                        
                    }else if(response.status == "warning"){
                        alert(response.message)
                    }
                    else{
                        $('#createForm').css("opacity","");
                        $(".submitBtn").removeAttr("disabled");
                        var errors  = response.errors;
                        $(".is-invalid").removeClass("is-invalid");
                        $.each(errors, function(index, value){
                            $("#"+index).addClass('is-invalid');
                            $("#"+index+"-error").text(value);
                        }); 
                    }
                },
            });
        });

        //option view in create and edit
        function update_view(id) {
			var item_no = parseInt($('.add_more_row').length+1);
            var count   = parseInt($('.add_more_row').length);
            var id_no = 1;
            if(count==1){
            	id_no = 1;
            }else{
            	id_no = count;
            }
		    var row   = item_no - 1;
		    $.ajax({
		        url  : '{{ route("optionRows")}}',
		        type : 'GET',
		        data : {'row':row,'item_no':item_no,'id_no':id_no, 'field_id':id},
		        dataType:"json",
		        beforeSend: function(response){
		        },
		        success: function(response){ 
		            if(response.status=="success"){
		                var data    = response.data;
			            $(".option").append(data);
		            }
		        }
		    });
		}

        //add more option
        $(document).on("click",".add_row_btn",function(e) {
            e.preventDefault();
            var item_no = parseInt($('.add_more_row').length+1);
            var count   = parseInt($('.add_more_row').length);
            var id_no = 1;
            if(count==1){
                id_no = 1;
            }else{
                id_no = count;
            }
            $.ajax({
                url  : '{{ route("addOption")}}',
                type : 'GET',
                data : {'row':item_no,'item_no':item_no,'id_no':id_no},
                dataType:"json",
                success: function(response){ 
                    if(response.status=="success"){
                        var data    = response.data;
                        $(".option").append(data);
                    }
                }
            });
        });

        //remove option
        $(document).on("click",".rem-dep",function(e) {
            e.preventDefault();
            var row 	 = parseInt($(this).attr("data-row"));
            var numItems = $('.add_more_row').length
            if(confirm("{{'Are you sure ?'}}")){
                $("#"+row).remove();
                $.each($(".add_more_row"),function (i,el){
                    $(this).attr('id',(i));
                    $(this).attr('data-row',(i));
                    $(this).find('.type_value').attr('id','type_value_'+(i+1));
                    $(this).find('.rem-dep').attr('data-row',(i));
                });
            }
        });
    });
</script>