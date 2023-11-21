<?php
	
	if(auth()->user()){
		$authuser = \App\Models\User::with('city')->find(Auth::id());

		if(auth()->user()->user_type_id!="2"){

			$name = old('from_name', auth()->user()->name);
		}
		else{

			$name = old('from_name', auth()->user()->first_name);
		}
	}
	else{
		$authuser = null;	
	}
	

	 
?>

<div class="modal fade" id="contactCompany" tabindex="-1" role="dialog">
	<div class="modal-dialog" style="background-color:#fff; padding:40px;">
        <div class="modal-content" style="background: none; border:0px;">
            <div class="row">

                <div class="col-md-12">
                    <form id="msform" role="form" method="POST"  enctype="multipart/form-data" onSubmit="return submitContactQueryForm(this)">

                    	{!! csrf_field() !!}
                    	 

                        <fieldset>
                           <!--  <h2 class="fs-title">Comunication Details </h2> -->


                            <div class="col-md-12">
								<div class="form-group required">
									 
									<div class="input-group">
								 		<label style="font-weight: bold; color: #3c3a3a; padding-right: 10px;">Name </label>
										<input  name="from_name" type="text" placeholder="Name" required="required" 
											   class="form-control" value="{{($authuser?$name:'')}}">
											   <input type="hidden" name="from_email" value="ff@hh.com">
											   
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group required">
									 
									<div class="input-group">
								 		<label style="font-weight: bold; color: #3c3a3a; padding-right: 10px;">Phone</label>
										<input  name="from_phone" type="text" placeholder="Phone" required="required" 
											   class="form-control" value="{{($authuser?$authuser->phone:'')}}">
									</div>
								</div>
							</div>


                             
							<div class="col-md-12">
								<div class="form-group required">
									 
									<div class="input-group">
										<label style="font-weight: bold; color: #3c3a3a; padding-right: 10px;">Query</label>
										<textarea 
											   name="message"
											   class="form-control"
											   placeholder="Query"></textarea>
										 
									</div>
								</div>
							</div>
 
                            <center>
                             <input type="submit" name="submit" style="border:0px;" class="submit action-button btnsave" value="Submit" />
                             </center>
					   </fieldset>

                         
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@section('after_scripts')
 @parent
 

<script>
		
		var company_url = "";
		$(document).on('click', '.send_company', function(){
			company_url = "{{ lurl('company') }}/"+$(this).data('id')+"/contact";		 
		});
		 function submitContactQueryForm(form){
 		 	
		    
 			var form =$(form);
 			
 			$('form').removeClass('active_query');
 			form.addClass('active_query');
 			$("#contactCompany [type='submit']").attr('disabled','disabled');
		    $.ajax({
					method: 'POST',
					url: company_url,
					data: {
						'from_name': form.find('[name="from_name"]').val(),
						'from_phone': form.find('[name="from_phone"]').val(),
						'message': form.find('[name="message"]').val(),
						'from_email': form.find('[name="from_email"]').val(),
						'_token': $('input[name=_token]').val()
					}
				}).done(function(data) {
					 
					
					$('#contactCompany').modal('hide');
					$("#contactCompany  [type='submit']").removeAttr('disabled');
					$('#query_type').val(data.type);
					$('#query_id').val(data.id);
					$('#slider_from_email').val(data.email);
					 
					
					window.dataLayer =window.dataLayer || [];
					
					window.dataLayer.push({
						'event':'directCompanyQuery','conversionValue':1
					});
				 
					$("#sliderForm #msform fieldset").removeAttr('style').hide(); 
					$("#sliderForm #msform fieldset:eq(0)").show();
					$("#sliderForm").modal({backdrop: 'static', keyboard: false}); 					
				 
				 
					 
					
					 
				}).error(function(response) {
					
					 
					
					$("#contactCompany  [type='submit']").removeAttr('disabled');
					var responseJSON = response.responseJSON;
					if(responseJSON.code==100){
						$('#contactCompany').modal('hide');
						$('#signin_phone').val(form.find('[name="from_phone"]').val());
						$('#signin_name').val(form.find('[name="from_name"]').val());
						$('#userLogin form').submit();
					}
					else if(responseJSON.code==422){
 							
 							alert(responseJSON.message);
					}
					else{
						var data = responseJSON.data;
						var msg=[];
						$.each(data, function (index, value) {
							
							if(msg.length==0){
								form.find('[name="'+index+'"]').focus()
							}
							msg.push(value);
						});
						alert(msg.join("\n"));
					}
					
 
					 
				});
				return false;
 }
	</script>
@endsection
