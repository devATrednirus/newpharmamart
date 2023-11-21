<!-- advertisement_section - start
			================================================== -->
			<section class="advertisement_section clearfix">
				<div style="background: #ccc;">
				<div class="container">
					<div class="row no-gutters">
                         <div class="col-lg-12">
							<div class="main_contact_content" style="padding:30px 30px; background: rgb(194, 0, 0); margin-top: 50px; margin-bottom: 50px;">
								<h2 class="title_text text-white mb_15" style="text-align:center;">What are you looking for?</h2> 
								
								<form role="form" method="POST" style="background-color: rgb(194, 0, 0);"  class="quick_query_form" onSubmit="return submitQueryCompanyForm(this)">
    		{!! csrf_field() !!}
    
								<?php 

			 


			if(auth()->check()){
				if(auth()->user()->user_type_id!="2"){

					$name = old('from_name', auth()->user()->name);
				}
				else{

					$name = old('from_phone', auth()->user()->first_name);
				}

			}
			else{

					$name = old('quick_query_name');;

					 
			}	

		?>
									<div class="row">
												<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
													<div class="form_item">
														<input type="text" name="from_name"  value="{{$name}}" class="required inputData " required="required" placeholder="Name" />
														<input type="hidden" name="from_email" id="from_email" value="gg@hh.com">
													</div>
												</div>

												

												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="form_item">
														<input type="text" name="from_phone"  required="required"   value="{{ old('from_phone', (auth()->check()) ? auth()->user()->phone : '') }}" class="required inputData " placeholder="Phone">
													</div>
												</div>
											</div>

											<div class="form_item">
												<textarea name="message"  class="required inputData "   required="required"   placeholder="Query"></textarea>
											</div>
											<center>
											<button type="submit" class="custom_btn bg_default_black text-uppercase">Submit</button>
										  </center>
										</form>
							</div>  
						</div>

						

					</div>
				</div>
			</div>
			</section>
			<!-- advertisement_section - end
			================================================== -->
@section('after_scripts')
 @parent
	<script>

	 
		function submitQueryCompanyForm(form){

 		 	
		    
 			var form =$(form);
 			
 			$('form').removeClass('active_query');

 			form.addClass('active_query');

 			$(".fixed-form [type='submit']").attr('disabled','disabled');

		    $.ajax({
					method: 'POST',
					url: '{{ lurl('company/'.$sUser->id.'/contact') }}',
					data: {
						'message': form.find('[name="message"]').val(),
						 
						'from_name': form.find('[name="from_name"]').val(),
						'from_phone': form.find('[name="from_phone"]').val(),
						'from_email': form.find('[name="from_email"]').val(),
						'_token': $('input[name=_token]').val()
					}
				}).done(function(data) {
					
					$(".fixed-form [type='submit']").removeAttr('disabled');
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
					
					
					$(".fixed-form [type='submit']").removeAttr('disabled');
					var responseJSON = response.responseJSON;

					if(responseJSON.code==100){
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


		$(document).ready(function () {
			  
			@if ($errors->any())
				@if ($errors->any() and old('messageForm')=='1')
					$('#contactCompany').modal();
				@endif
			@endif
		});
	</script>
@endsection
