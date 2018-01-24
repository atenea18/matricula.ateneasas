$(document).ready(function(){

	var student_id = $('#student_id').val(),
        AppUrl = $("#AppUrl").val();

        console.log(AppUrl);
	$('.chosen-select').chosen({width: "100%"});

	$('.chosen-group, .chosen-it, .chosen-ice, .chosen-icb, .chosen-ig, .chosen-ac, .chosen-az, .chosen-ri').chosen({width: "100%"});

	$('.chosen-select-deselect').chosen({ allow_single_deselect: true });

    // LOAD THE WORKINGDAY 
    $('#workingday_id, #headquarter_id').change(function(e){

    	var group_select = $('#group_id'),
    	workingday_id = $('#workingday_id').val(),
    	headquarter_id = $('#headquarter_id').val();

    	$.get(AppUrl+"/ajax/group/getByWorkingday", {workingday_id, headquarter_id}, function($response){

    		console.log($response);

    		group_select.empty();
    		$(".chosen-group").trigger("chosen:updated");

    		if($response.length > 0){
    			$.each($response, function(key, element){

    				group_select.append('<option value='+element.id+'>'+element.name+'<option>');
    			});

    			$(".chosen-group").trigger("chosen:updated");
    		}

    	}, "json");
    });

	// DATE 
	$('.datepicker').datepicker({
		format: 'yyyy/mm/dd',
		startDate: '-3d'
	});

	// LOAD FAMILY TABLE
	var table = $('#tableFamily').DataTable({
		"lengthChange": false,
		"pageLength": 6,
		retrieve: true,
		language: {
			url: AppUrl+'/json/Spanish.json'
		},
		"ajax": {
			"method": "GET",
			"url": AppUrl+"/ajax/student/getFamily/"+student_id
		},
		"columns": [
		{ "data": "name" },
		{ "data": "last_name" },
		{ "data": "type" },
        // { "data": "identification_number" },
        { "data": "address" },
        { "data": "mobil" },
        { "data": "phone" },
        // { "data": "email" },
        {
        	"render": function(data, type, full, meta){
        		return '<a class="btn btn-danger btn-sm" data-method="delete" data-id="'+full.id+'" data-action="deleteFamily" title="Eliminar familiar"><i class="fa fa-trash"></i></a> '+
        		' <a class="btn btn-primary btn-sm" data-method="edit" data-id="'+full.id+'" data-action="updateFamily" title="Editar familiar"><i class="fa fa-edit"></i></a>'+
                ' <a class="btn btn-warning btn-sm" data-method="unlink" data-id="'+full.id+'" data-action="dettachFamily" title="Desvincular familiar"><i class="fa fa-unlink"></i></a>';
        	}
        }
        ]
    });

	// ADD EVENT TO BUTTON EDIT AND DELTE
	$('#tableFamily').on( 'click', 'a', function (e) {

		e.preventDefault();

		var that = $(this),
		modalEdit = $('#modalEditFamily'),
		modalDelete = $('#modalDeleteFamily'),
        modalDetach = $("#modalDetachFamily");
		url = AppUrl+'/institution/student/'+that.data('action')+'/'+that.data('id');

		

		switch(that.attr('data-method')){
			case "edit":
				$.get(AppUrl+'/ajax/student/getFamilyById/'+that.data('id'), function(response){

					var form = modalEdit.find('form');
					form.attr('action', url);

					form.find('#family_id').val(response.id);
					form.find('#student_id').val(student_id);
					form.find('#relationship_id').val(response.students[0].pivot.relationship_id);
					form.find('#name').val(response.name);
					form.find('#last_name').val(response.last_name);
					form.find('#identification_type_id').val(response.identification.identification_type_id);
					form.find('#identification_number').val(response.identification.identification_number);
					form.find('#id_city_expedition').val(response.identification.id_city_expedition);
					form.find('#id_city_of_birth').val(response.identification.id_city_of_birth);
					form.find('#gender_id').val(response.identification.gender_id);
					form.find('#address').val(response.address.address);
					form.find('#neighborhood').val(response.address.neighborhood);
					form.find('#phone').val(response.address.phone);
					form.find('#mobil').val(response.address.mobil);
					form.find('#email').val(response.address.email);
					form.find('#id_city_address').val(response.address.id_city_address);
					form.find('#zone_id').val(response.address.zone_id);

					$(".chosen-it, .chosen-ice, .chosen-icb, .chosen-ig, .chosen-ac, .chosen-az, .chosen-ri").trigger("chosen:updated");

					modalEdit.modal({
						show: true,
						backdrop: 'static',
						keyboard: false
					});

				}, "json");
			break;

			case "delete":
				$.get(AppUrl+'/ajax/student/getFamilyById/'+that.data('id'), function(response){
					
					var formD = modalDelete.find('form');
					formD.attr('action', url);

					formD.find('#family_id').val(response.id);
					formD.find('#student_id').val(student_id);
					formD.find('#relationship_id').val(response.students[0].pivot.relationship_id);

					$('#text_delte').text(response.name+' '+response.last_name);

					modalDelete.modal({
						show: true,
						backdrop: 'static',
						keyboard: false
					});


				}, 'json');
			break;

            case "unlink":
                $.get(AppUrl+'/ajax/student/getFamilyById/'+that.data('id'), function(response){
                    
                    console.log(response.name);
                    var formD = modalDetach.find('form');
                    // formD.attr('action', url);

                    formD.find('#family_id').val(response.id);
                    formD.find('#student_id').val(student_id);
                    formD.find('#relationship_id').val(response.students[0].pivot.relationship_id);

                    formD.find('#text_detach').text(response.name+' '+response.last_name);

                    modalDetach.modal({
                        show: true,
                        backdrop: 'static',
                        keyboard: false
                    });


                }, 'json');
            break;
			default:
				console.log("Defecto");
		}
	});

    var listenSearchTable = function(){
            $('#familySearchTable').on( 'click', 'a', function (e) {
            
            e.preventDefault();

            var form = $("#SendAttachFamily");

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data:form.serialize(),
                beforeSend:function(){
                    // prev.prop('disabled',true);
                    console.log(form.serialize());
                },
                success:function(data){
                    // prev.prop('disabled',false);
                    console.log(data);
                    $('#modalSearchFamily').modal('hide');
                    table.ajax.reload( null, false );
                },
                error:function(xhr){
                    // prev.prop('disabled',false);
                    console.log(xhr);
                }
            });
        });
    };

	// SHOW FORM ADD FAMILY
	$('#addFamily').click(function(){

		$('#modalAddFamily').modal({
			show: true,
			backdrop: 'static',
			keyboard: false
		});
	});

    // SEND DATA FORM ADD DAMILY
    $('#formAddFamily').submit(function(e){

    	e.preventDefault();

    	var that = $(this),
    	url	=	that.attr('action');

    	$.ajax({

    		type: that.attr('method'),
    		url: url,
    		data: that.serialize(),
    		dataType: 'json',
    		success: function(data){
    			
    			if(data.state){
    				$('#modalAddFamily').modal('hide');
    				table.ajax.reload( null, false );
    			}

    		},
    		error: function(jqXhr){
    			
    			if( jqXhr.status === 422 )
    			{
    				//process validation errors here.
    				var errors = jqXhr.responseJSON;

    				errorsHtml = '<div class="alert alert-danger"><ul>';

    				$.each( errors , function( key, value ) {
			            errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
			        });
    				errorsHtml += '</ul></di>';

    				$( '#formerrors' ).html( errorsHtml );

    			}
    		}
    	});
    });

    // SEND DATA FORM EDIT FAMILY
    $('#formEditFamily').submit(function(e){
    	e.preventDefault();

    	var that = $(this),
    	url	=	that.attr('action');

    	$.ajax({

    		type: that.attr('method'),
    		url: url,
    		data: that.serialize(),
    		dataType: 'json',
    		success: function(data){
    			
    			if(data.state){
    				$('#modalEditFamily').modal('hide');
    				table.ajax.reload( null, false );
    			}

    		},
    		error: function(jqXhr){
    			
    			if( jqXhr.status === 422 )
    			{
    				//process validation errors here.
    				var errors = jqXhr.responseJSON;

    				errorsHtml = '<div class="alert alert-danger"><ul>';

    				$.each( errors , function( key, value ) {
			            errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
			        });
    				errorsHtml += '</ul></di>';

    				$( '#formerrorsEdit' ).html( errorsHtml );

    			}
    		}
    	});
    });

    // SEND DATA FORM DELTE FAMILY
    $('#formDeleteFamily').submit(function(e){

    	e.preventDefault();

    	var that = $(this),
    		url	=	that.attr('action');

    	$.ajax({

    		type: that.attr('method'),
    		url: url,
    		data: that.serialize(),
    		dataType: 'json',
    		success: function(data){
    			
    			console.log(data);
    			
    			if(data.state){
    				$('#modalDeleteFamily').modal('hide');
    				table.ajax.reload( null, false );
    			}
    		}
    	});
    });

    // SEND DATA FORM DETACH FAMILY
    $('#formDetachFamily').submit(function(e){

        e.preventDefault();

        var that = $(this),
            url =   that.attr('action');

        $.ajax({

            type: that.attr('method'),
            url: url,
            data: that.serialize(),
            dataType: 'json',
            success: function(data){
                
                console.log(data);
                
                if(data.state){
                    $('#modalDetachFamily').modal('hide');
                    table.ajax.reload( null, false );
                }
            }
        });
    });

    // SHOW FORM SEARCH FAMILY
    $('#searchFamily').click(function(){

        $('#modalSearchFamily').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    })

    // 
    $("#formSearchFamily").submit(function(e){

        e.preventDefault();

        var that = $(this);

        $.ajax({

            type: that.attr('method'),
            url: that.attr('action'),
            data: that.serialize(),
            // dataType: 'json',
            beforeSend:function(){
                $("#formerrorsFamilySearch").empty();
                that.find('button').prop('disabled',true);
            },
            success: function(data){
                that.find('button').prop('disabled',false);
                $("#contentFamilySearch").empty().append(data);
                listenSearchTable();
            },
            error: function(jqXhr){
                
                that.find('button').prop('disabled',false);
                if( jqXhr.status === 422 )
                {
                    //process validation errors here.
                    var errors = jqXhr.responseJSON.errors;

                    console.log(errors);
                    errorsHtml = '<div class="alert alert-danger"><ul>';

                    $.each( errors , function( key, value ) {
                        console.log(key);
                        console.log(value);
                        errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                    });
                    errorsHtml += '</ul></di>';

                    $( '#formerrorsFamilySearch' ).html( errorsHtml );

                }
            }
        });
    });

});