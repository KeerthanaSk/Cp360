
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
                        // $('.option').hide();
                        // var len = 0;
                        // $('#fieldTable tbody').empty(); // Empty <tbody>
                        // if(response.fields != null){
                        //     var data = response.fields;
                        //     len = data.length;
                        // }

                        // if(len > 0){
                        //     for(var i=0; i<len; i++){
                        //         var id          = data[i].id;
                        //         var label       = data[i].label;
                        //         var type        = data[i].type;
                        //         var is_required = data[i].is_required == 1 ? 'Yes': 'No';
                        //         var sort_order  = data[i].sort_order == ''? 0 : data[i].sort_order;
                        //         var status      = data[i].status == 1? 'Active' : 'Inactive';
                                
                        //         var edit_url     = '<?php echo url('/editFormFields'); ?>'+'/'+id; 
                        //         var delete_url   = '<?php echo url('/editFormFields'); ?>'+'/'+id; 

                        //         var tr_str = "<tr>" +
                        //         "<td>" + (i+1) + "</td>" +
                        //         "<td>" + label + "</td>" +
                        //         "<td>" + getType(type) + "</td>" +
                        //         "<td>" + is_required + "</td>" +
                        //         "<td>" + sort_order + "</td>" +
                        //         "<td>" + status + "</td>" +
                        //         "<td>" + '<a href="javascript:void(0)" class="button button1 editBtn" id="edit-btn-'+id+'" data-field-id="'+id+'"> edit </a>' + 
                        //             '<a href="javascript:void(0)" class="button button4 deleteBtn" id="edit-btn-'+id+'" data-field-id="'+id+'"> delete </a>'+"</tr>";
                        //         $("#fieldTable tbody").append(tr_str);
                        //     }
                        // }else{
                        //     var tr_str = "<tr>" +
                        //         "<td align='center' colspan='7'>No record found.</td>" +
                        //     "</tr>";

                        //     $("#fieldTable tbody").append(tr_str);
                        // }
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
            if($(this).val() == 4 || $(this).val() == 5 || $(this).val() == 6) {
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
                    if(response.field[0].type == 4 || response.field[0].type == 5 || response.field[0].type == 6){
                        $('.option').show();
                        $.each(response.options, function(index, value) {
                            $("#type_value").val(value.type_value); 
                            // Will stop running after "three"
                        });
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

        //addmore rows
        $('#add-acc').click(function(e){  
			e.preventDefault();
			var count   = parseInt($('.add_more_row').length);
			var item_no = parseInt($('.rem-acc').length);
            var id_no   = 1;
            if(count  == 1){
            	id_no = 1;
            }else{
            	id_no = count;
            }
		    var row   = parseInt($(".add_row_btn").val() + 1);
		    $.ajax({
		        url  : '{{ route("addOption")}}',
		        type : 'GET',
		        data : {'row':row, 'id_no':id_no},
		        dataType:"json",
		        beforeSend: function(response){
		        },
		        success: function(response){ 
		            if(response.status=="success"){
		                var data    = $(response.data);
		                $("#addAcc .addholder").append(data);
		            }
		        }
		    });
		});
       //remove dynamic rows 
        $(document).on("click",".rem-acc",function(e) {
			e.preventDefault();
    		var row 	 = parseInt($(this).attr("data-row"));
    		var numItems = $('.add_more_row').length
		    if(confirm("{{'Are you sure ?'}}")){
		        $("#"+row).remove();
		        $.each($(".add_more_row"),function (i,el){
			        $(this).attr('id',(i));
			        $(this).attr('data-row',(i));
			        $(this).find('#rem-acc').attr('data-row',(i));
			        $(this).find('.option').attr('id','option_'+(i));
			    });
			    $.each($(".option"),function (i,el){
			    	$(this).val(i + 1);
			    });
		    }
		});

        //sort_order
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

        //update form 
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
    });
</script>