<div class="col-md-3 profile-form fixed-form ">
    <h4>Send Enquiry</h4>
    <form role="form" method="POST" class="quick_query_form" onSubmit="return submitQueryCompanyForm(this)">
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
        <input type="text" name="from_name"  value="{{$name}}" class="required inputData " required="required" placeholder="Name" />
       
        <input type="text" name="from_phone"  required="required"   value="{{ old('from_phone', (auth()->check()) ? auth()->user()->phone : '') }}" class="required inputData " placeholder="Phone" />
        
        <textarea name="message"  class="required inputData "   required="required"   placeholder="Query"></textarea>
        <input type="submit" name="submit" value="Submit" class="submit" style="cursor:pointer">
    </form>
</div>
 

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












