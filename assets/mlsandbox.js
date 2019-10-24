//alert();
$(document).ready(function() {
	$("#ml-page").on('click', '.link', function() {
		if(!$(this).hasClass('active')) {
			$.fn.toggle_sections($(this).attr('section'));
			$('.header-links li').removeClass('active');
			$(this).addClass('active');
		}
	});

	$("#ml-page").on('click', '#btn-crt-field', function() {
	    var field_name = $('#field-name').val();
	    var field_type = $('#field-type').val(); 

	    if (field_name == '' || field_type == '') {
	    	$('#field-error-msg-block').show();
			$('.field-error-text').html('Error: All the fields are mandatory*');
			$('#field-success-msg-block').hide();
    	} else {
    		var action = "createNewField";
    		var fields = {
    			'field_name': field_name,
    			'field_type': field_type
    		}
			$.ajax({
				url: "AppController.php",
				type: "POST",
				data: {
					action: action,
					fields: JSON.stringify(fields)
				},
				datatype: 'json',
				success: function(output) {
					output = JSON.parse(output);
					//console.log(output['status']);
					if (output['status'] == 'db_error') {
						$('#field-error-msg-block').show();
						$('.field-error-text').html('Error: '+output['result']);
						$('#field-success-msg-block').hide();
					} else if (output['status'] == 'duplicate_error') {
						$('#field-error-msg-block').show();
						$('.field-error-text').html('Error: '+output['result']);
						$('#field-success-msg-block').hide();
					} else if (output['status'] == 'success') {
						$('#field-error-msg-block').hide();
						$('.field-error-text').html('');
						$('#field-success-msg-block').show();
					}
					$.fn.get_all_fields();
				}
			});
			
  		}
	});

	$("#field-type-dropdown-options").on('click', '.dropdown-item', function() {
	    $(this).parents(".dropdown").find('.btn').html($(this).text());
	    var field_type = $(this).attr('data-value');
	    $('#field-type').val(field_type);
	});

	$.fn.toggle_sections = function(section) {
  		$('.section').fadeOut();
  		$('.'+section).fadeIn();
  	}

  	$.fn.get_all_fields = function(section) {
  		var action = "getAllFields";
		$.ajax({
			url: "AppController.php",
			type: "GET",
			data: {
				action: action
			},
			success: function(output) {
				console.log(output);
				output = JSON.parse(output);
				if (output['status'] == 'success') {
					var all_fields = output['result'];
					var html_content = '';
					for (var field in all_fields) {
						var field_id = all_fields[field]['field_id'];
						var field_title = all_fields[field]['title'];
						var field_type = all_fields[field]['type'];
						var field_created_at = all_fields[field]['created_at'];

						html_content = html_content+'<tr>';
		        		html_content = html_content+'<input type="hidden" class="field_id_table" value="'+field_id+'">';
		        		html_content = html_content+'<td align="left" style="border-color: #f2f2f2;" class="field_title_table" data-val="'+field_title+'">'+field_title+'</td>';
		        		html_content = html_content+'<td align="left" style="border-color: #f2f2f2;" class="field_type_table">'+field_type+'</td>';
		        		html_content = html_content+'<td align="left" style="border-color: #f2f2f2;" class="field_created_at_table">'+field_created_at+'</td>';
						html_content = html_content+'<td align="left" style="border-color: #f2f2f2;" class="delete_field"><button class="btn btn-table btn-delete-field">Delete</button><p class="field-del-msg" style="padding: 10px;"></p></td>';
						html_content = html_content+'</tr>';
					}

					$('#fields-table tbody').html(html_content);         
				} 

			}, 
			error: function(error) {
				console.log(error);
			}
		});
  	}
  	$.fn.get_all_fields();

  	$.fn.get_all_subscribers = function(section) {
  		var action = "getAllSubscribers";
		$.ajax({
			url: "AppController.php",
			type: "GET",
			data: {
				action: action
			},
			success: function(output) {
				console.log(output);
				output = JSON.parse(output);
				if (output['status'] == 'success') {
					var all_subscribers = output['result'];
					var html_content = '';
					for (var subscriber in all_subscribers) {
						var subscriber_id = all_subscribers[subscriber]['subscriber_id'];
						var email_address = all_subscribers[subscriber]['email_address'];
						var name = all_subscribers[subscriber]['name'];
						var state = all_subscribers[subscriber]['state'];
						var source = all_subscribers[subscriber]['source'];
						var created_at = all_subscribers[subscriber]['created_at'];

						html_content = html_content+'<tr>';
		        		html_content = html_content+'<input type="hidden" class="subscriber_id_table" value="'+subscriber_id+'">';
		        		html_content = html_content+'<td align="left" style="border-color: #f2f2f2;" class="subscriber_email_table" data-val="'+email_address+'">'+email_address+'<br/>created on: '+created_at+'</td>';
		        		html_content = html_content+'<td align="left" style="border-color: #f2f2f2;" class="subscriber_name_table">'+name+'</td>';
						html_content = html_content+'<td align="left" style="border-color: #f2f2f2;" class="subscriber_state_table">'+state+'</td>';
						html_content = html_content+'<td align="left" style="border-color: #f2f2f2;" class="subscriber_source_table">'+source+'</td>';
						html_content = html_content+'<td align="left" style="border-color: #f2f2f2;" class="delete_subscriber"><button class="btn btn-table btn-edit-subscriber">Edit</button> &nbsp;&nbsp; <button class="btn btn-table btn-delete-subscriber">Delete</button><p class="subscriber-del-msg" style="padding: 10px;"></p></td>';
						html_content = html_content+'</tr>';
					}

					$('#subscriber-table tbody').html(html_content);         
				} 

			}, 
			error: function(error) {
				console.log(error);
			}
		});
  	}
  	$.fn.get_all_subscribers();
  	
  	$("#ml-page").on('click', '.btn-delete-field', function() {
  		var field_id = $(this).closest('tr').find('.field_id_table').val();
  		var field_name = $(this).closest('tr').find('.field_title_table').html();
		var confirmMsg = confirm("Do you really want to delete "+field_name+" field.");
		var that = this;
  		var action = "deleteField";
		if (confirmMsg) {
	  		$.ajax({
	  			url: "AppController.php",
				type: "POST",
				data: {
					action: action,
					field_id: field_id
				},
				datatype: 'json',
				success: function(output) {
					console.log(output);
					output = JSON.parse(output);
					//console.log(output['status']);
					if (output['status'] == 'error') {
						$(that).closest('tr').find('.field-del-msg').html('Error: '+output['result']);
					} else {
						$.fn.get_all_fields();
					}
				}
			});
	  	}
  	});
	
  	$("#ml-page").on('click', '#btn-add-subscriber', function() {
  		var modal = document.getElementById('add-subscriber-modal');
		modal.style.display = "block";
		
		var action="getAllFields";
		$.ajax({
			url: "AppController.php",
			type: "GET",
			data: {
				action: action
			},
			success: function(output) {
				console.log(output);
				output = JSON.parse(output);
				if (output['status'] == 'success') {
					var all_fields = output['result'];
					var html_content = "<span class='close'>&times;</span>"
						+"<p class='form-title' align='left'>Subscriber Details</p>"
						+"<div class='add-subscriber-form form-row-fields'>"
							+"<div class='label-form'>"
								+"* Email"
							+"</div>"
							+"<div class='form-field'>"
								+"<input type='text' class='form-control email_id' />"
							+"</div>"
							+"<div class='label-form'>"
								+"Name"
							+"</div>"
							+"<div class='form-field'>"
								+"<input type='text' class='form-control subscriber_name' />"
							+"</div>";

					for (var field in all_fields) {
						var field_id = all_fields[field]['field_id'];
						var field_title = all_fields[field]['title'];
						var field_type = all_fields[field]['type'];

						html_content = html_content
							+"<div class='label-form'>"
								+field_title
							+"</div>";
						if (field_type == "Date") {
							html_content = html_content	
								+"<div class='form-field' style='display: inline-block;'>"	
									+"<input type='text' class='form-control year' placeholder='YYYY'/>&nbsp;"
									+"<input type='text' class='form-control month' placeholder='MM'/>&nbsp;"
									+"<input type='text' class='form-control day'  placeholder='DD'/>"
									+"<input type='hidden' class='subscriber-field date'  field-title='"+field_title+"' field-id='"+field_id+"' field-type='"+field_type+"' placeholder='DD'/>"								
								+"</div>";
						} else {
							html_content = html_content	
								+"<div class='form-field'>"
									+"<input type='text' class='form-control subscriber-field' field-title='"+field_title+"' field-id='"+field_id+" field-type='"+field_type+"'/>"
								+"</div>";
						}
					}

					html_content = html_content	+ "<div class='label-form'>"
							+"<button id='btn-crt-subscriber' class='btn btn-action-main' >Create</button>"
						+"</div>";
					html_content = html_content	+ "</div>";
					$('.add-subscriber-modal-content').html(html_content);
					
					//$('#fields-table tbody').html(html_content);         
				} 

			}, 
			error: function(error) {
				console.log(error);
			}
		});
			
  	});
  	
  	$("#ml-page").on('click', '#btn-crt-subscriber', function() {
  		var email_id = $(this).closest('.add-subscriber-form').find('.email_id').val();
		if (!validateEmail(email_id)) {
			alert("Invalid Email Address. Please try again!");
			return true;
		}

		var name = $(this).closest('.add-subscriber-form').find('.subscriber_name').val();
		
		var fields_array = {};
		$('.add-subscriber-form .subscriber-field').each(function() {
			var field_title = '';
			var field_id = '';
			var field_value = '';
			var field_type = '';
			
			if ($(this).hasClass('date')) {
				field_title = $(this).attr('field-title');
				field_id = $(this).attr('field-id');
				field_type = $(this).attr('field-type');

				var year = $(this).closest('.form-field').find('.year').val();
				var month = $(this).closest('.form-field').find('.month').val();
				var day = $(this).closest('.form-field').find('.day').val();
				
				field_value = year+"-"+month+"-"+day;
			} else {
				field_title = $(this).attr('field-title');
				field_id = $(this).attr('field-id');
				field_value = $(this).val();
			}
			fields_array[field_title] = field_value;
			/*
			subscriber.fields.push({ 
		        "field_title" : field_value
		    });
		    */
			
		});
		var subscriber = {
			email_address: email_id,
			name: name,
			state: 'active',
			source: 'manual',
		    fields: fields_array
		};

		console.log(JSON.stringify(subscriber));
		var action = "createNewSubscriber";
		$.ajax({
			url: "AppController.php",
			type: "POST",
			data: {
				action: action,
				subscriber: JSON.stringify(subscriber)
			},
			datatype: 'json',
			success: function(output) {
				console.log(output);
				var add_subscriber_modal = document.getElementById('add-subscriber-modal');
				add_subscriber_modal.style.display = "none";
				
				$.fn.get_all_subscribers();
				//output = JSON.parse(output);
			}
		});
  	});

  	$("#ml-page").on('click', '.btn-delete-subscriber', function() {
  		var subscriber_id = $(this).closest('tr').find('.subscriber_id_table').val();
  		var email_id = $(this).closest('tr').find('.subscriber_email_table').attr('data-val');
		var confirmMsg = confirm("Do you really want to delete the subscriber with email address "+email_id+" ?");
		var that = this;
  		var action = "deleteSubscriber";
		if (confirmMsg) {
	  		$.ajax({
	  			url: "AppController.php",
				type: "POST",
				data: {
					action: action,
					subscriber_id: subscriber_id
				},
				datatype: 'json',
				success: function(output) {
					console.log(output);
					output = JSON.parse(output);
					//console.log(output['status']);
					if (output['status'] == 'error') {
						$(that).closest('tr').find('.subscriber-del-msg').html('Error: '+output['result']);
					} else {
						$.fn.get_all_subscribers();
					}
				}
			});
	  	}
  	});
	
	$("#ml-page").on('click', '.btn-edit-subscriber', function() {
		var subscriber_id = $(this).closest('tr').find('.subscriber_id_table').val();
  		var modal = document.getElementById('edit-subscriber-modal');
		modal.style.display = "block";
		
		var action="getSubscriberDetails";
		$.ajax({
			url: "AppController.php",
			type: "GET",
			data: {
				subscriber_id: subscriber_id,
				action: action
			},
			success: function(output) {
				console.log(output);
				output = JSON.parse(output);
				if (output['status'] == 'success') {
					var subscriber = output['subscriber_details'];
					
					var subscriber_id = subscriber['subscriber_id'];
					var email_address = subscriber['email_address'];
					var name = subscriber['name'];

					var html_content = "<span class='close'>&times;</span>"
						+"<p class='form-title' align='left'>Edit Subscriber Details</p>"
						+"<div class='edit-subscriber-form form-row-fields'>"
							+"<input type='hidden' class='subscriber_id' value='"+subscriber_id+"'>"
							+"<div class='label-form'>"
								+"* Email"
							+"</div>"
							+"<div class='form-field'>"
								+"<input type='text' class='form-control email_id' value='"+email_address+"' disabled/>"
							+"</div>"
							+"<div class='label-form'>"
								+"Name"
							+"</div>"
							+"<div class='form-field'>"
								+"<input type='text' class='form-control subscriber_name' value='"+name+"'/>"
							+"</div>";

					var subscriber_fields = subscriber['fields'];
					var subscriber_fields_list = subscriber_fields.split(',');
					
					var all_fields = output['all_fields'];
					for (var field in all_fields) {
						var field_id = all_fields[field]['field_id'];
						var field_title = all_fields[field]['title'];
						var field_type = all_fields[field]['type'];
						var field_value = ''; 
						var s_field = [];

						var year = '';
						var month = '';
						var day = '';
						if (subscriber_fields_list.length > 0) {
							subscriber_fields_list.map((key,value) => {
								s_field = key.split(':');
								if (s_field[0] == field_title) {
									field_value = s_field[1];
									if (field_type == "Date") {
										var date_field = field_value.split('-');
										year = date_field[0];
										month = date_field[1];
										day = date_field[2];
									}
								}
							});
							
						}
						html_content = html_content
							+"<div class='label-form'>"
								+field_title
							+"</div>";
						if (field_type == "Date") {
							html_content = html_content	
								+"<div class='form-field' style='display: inline-block;'>"	
									+"<input type='text' class='form-control year' placeholder='YYYY' value='"+year+"'/>&nbsp;"
									+"<input type='text' class='form-control month' placeholder='MM' value='"+month+"'/>&nbsp;"
									+"<input type='text' class='form-control day'  placeholder='DD' value='"+day+"'/>"
									+"<input type='hidden' class='subscriber-field date'  field-title='"+field_title+"' field-id='"+field_id+"' field-type='"+field_type+"' placeholder='DD'/>"								
								+"</div>";
						} else {
							html_content = html_content	
								+"<div class='form-field'>"
									+"<input type='text' class='form-control subscriber-field' field-title='"+field_title+"' field-id='"+field_id+" field-type='"+field_type+"' value='"+field_value+"'/>"
								+"</div>";
						}
					}

					html_content = html_content	+ "<div class='label-form'>"
							+"<button id='btn-update-subscriber' class='btn btn-action-main' >Save</button>"
						+"</div>";
					html_content = html_content	+ "</div>";
					$('.edit-subscriber-modal-content').html(html_content);
					
					//$('#fields-table tbody').html(html_content);         
				} 

			}, 
			error: function(error) {
				console.log(error);
			}
		});
			
  	});
	
	$("#ml-page").on('click', '#btn-update-subscriber', function() {
  		var subscriber_id = $(this).closest('.edit-subscriber-form').find('.subscriber_id').val();
		var email_id = $(this).closest('.edit-subscriber-form').find('.email_id').val();
		var name = $(this).closest('.edit-subscriber-form').find('.subscriber_name').val();
		var fields_array = {};
		$('.edit-subscriber-form .subscriber-field').each(function() {
			var field_title = '';
			var field_id = '';
			var field_value = '';
			var field_type = '';
			
			if ($(this).hasClass('date')) {
				field_title = $(this).attr('field-title');
				field_id = $(this).attr('field-id');
				field_type = $(this).attr('field-type');

				var year = $(this).closest('.form-field').find('.year').val();
				var month = $(this).closest('.form-field').find('.month').val();
				var day = $(this).closest('.form-field').find('.day').val();
				
				field_value = year+"-"+month+"-"+day;
			} else {
				field_title = $(this).attr('field-title');
				field_id = $(this).attr('field-id');
				field_value = $(this).val();
			}
			fields_array[field_title] = field_value;
			/*
			subscriber.fields.push({ 
		        "field_title" : field_value
		    });
		    */
			
		});
		var subscriber = {
			subscriber_id: subscriber_id,
			email_address: email_id,
			name: name,
			state: 'active',
			source: 'manual',
		    fields: fields_array
		};

		console.log(JSON.stringify(subscriber));
		var action = "updateSubscriber";
		$.ajax({
			url: "AppController.php",
			type: "POST",
			data: {
				action: action,
				subscriber: JSON.stringify(subscriber)
			},
			datatype: 'json',
			success: function(output) {
				console.log("testttt:::"+output);
				var edit_subscriber_modal = document.getElementById('edit-subscriber-modal');
				edit_subscriber_modal.style.display = "none";
				
				$.fn.get_all_subscribers();
				//output = JSON.parse(output);
			}
		});
  	});
  	
	function validateEmail(email) {
	  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	  return re.test(email);
	}

  	$( document ).on( "click", ".close", function() {
		var modal = document.getElementById('add-subscriber-modal');
		var modal2 = document.getElementById('edit-subscriber-modal');
		modal.style.display = "none";
		modal2.style.display = "none";
	});

  	window.onclick = function(event) {
		var add_subscriber_modal = document.getElementById('add-subscriber-modal');
		if (event.target == add_subscriber_modal) {
			add_subscriber_modal.style.display = "none";
		}

		var edit_subscriber_modal = document.getElementById('edit-subscriber-modal');
		if (event.target == edit_subscriber_modal) {
			edit_subscriber_modal.style.display = "none";
		}
	}
});