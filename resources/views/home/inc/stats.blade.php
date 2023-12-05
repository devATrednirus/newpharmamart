<?php $sty = '';
if(!empty($_GET['debu'])) {
  if($_GET['debu'] == 1)  {
    echo "home.inc.stats";
    $sty = ' style="border: 1px solid;" ';
  }
} ?>
{{--

@if (isset($countPosts) and isset($countUsers) and isset($countCities))



<div class="help-block" style="display:none;">
    <div class="container-fluid">
	    <div class="row">
		    <div class="col-lg-6 col-md-6 col-sm-6 listing-counter">
			    <h2>Get Quotes From Verified Suppliers</h2>
					<div class="container">
					    <div class="row">
						<div class="page-info page-info-lite rounded">
							<div class="text-center section-promo">
								<div class="row">

									@if (isset($countPosts))
									<div class="col-sm-4 col-xs-6 col-xxs-12">
										<div class="iconbox-wrap">
											<div class="iconbox">
												<div class="iconbox-wrap-icon">
													<i class="icon icon-docs"></i>
												</div>
												<div class="iconbox-wrap-content">
													<h5><span>{{ $countPosts }}</span></h5>
													<div class="iconbox-wrap-text">{{ t('Free ads') }}</div>
												</div>
											</div>
										</div>
									</div>
									@endif

									@if (isset($countUsers))
									<div class="col-sm-4 col-xs-6 col-xxs-12">
										<div class="iconbox-wrap">
											<div class="iconbox">
												<div class="iconbox-wrap-icon">
													<i class="icon icon-group"></i>
												</div>
												<div class="iconbox-wrap-content">
													<h5><span>{{ $countUsers }}</span></h5>
													<div class="iconbox-wrap-text">{{ t('Trusted Sellers') }}</div>
												</div>
											</div>
										</div>
									</div>
									@endif

									@if (isset($countCities))
									<div class="col-sm-4 col-xs-6 col-xxs-12">
										<div class="iconbox-wrap">
											<div class="iconbox">
												<div class="iconbox-wrap-icon">
													<i class="icon icon-location"></i>
												</div>
												<div class="iconbox-wrap-content">
													<h5><span>{{ $countCities . '+' }}</span></h5>
													<div class="iconbox-wrap-text">{{ t('Locations') }}</div>
												</div>
											</div>
										</div>
									</div>
									@endif

								</div>
							</div>
						</div>
						</div>
					</div>
					@endif

			</div>
			<?php


							if(auth()->check()){
								if(auth()->user()->user_type_id!="2"){

									$name = old('quick_query_name', auth()->user()->name);
								}
								else{

									$name = old('quick_query_name', auth()->user()->first_name);
								}

							}
							else{

									$name = old('quick_query_name');;


							}

						?>

			<div class="col-lg-6 col-md-6 col-sm-6 help-form">
			    <div class="help-form-inner">
				    <h3 class="bounce">Let us Help You</h3>
                   <form name="quick_query_listing" class="quick_query_form" onSubmit="return submitQuery(this)">
					    <div class="field-box">
						    <div class="input-group">
							    <i class="icon-help"></i>
						        <input type="text" placeholder="Tell Us Your Requirement" name="quick_query">
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 field-box" style="padding-right:4px;">
							    <div class="input-group">
							    	 <i class="icon-user fa hidden-sm"></i>
								    <input type="text"  name="quick_query_name" placeholder="Name" value="{{$name}}">
								</div>
							</div>
							<div class="col-md-6 field-box" style="padding-left:4px;">
							    <div class="input-group">

								    <i class="icon-phone-1"></i>
								    <input type="text" name="quick_query_phone" placeholder="Mobile No." value="{{(auth()->check()) ? auth()->user()->phone : ''}}">

								</div>
							</div>
						</div>
						<div class="field-box">
						    <div class="input-group">
						        <input type="submit" value="Submit">
							</div>
						</div>
					</form>
                </div>
			</div>
		</div>
	</div>
</div>

 <section class="section-block banner-top-wrapper">
	    <div class="container-fluid">
		    <div class="section-bg">
			<div class="container-fluid">
		    <div class="row">
			    <div class="col-md-3 banner-text">
				    <h2>We connect<br><strong>Buyers & Sellers</strong></h2>
					<p>Rednirus Mart is India's largest online B2B marketplace, connecting buyers with suppliers.</p>
					<ul class="banner-text-list">
                        <li class="">
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <p>Trusted Platform</p>
                        </li>
                        <li class="">
                            <i class="fa fa-shield-alt" aria-hidden="true"></i>
                            <p>Safe &amp; Secure</p>
                        </li>
                        <li class="">
                            <i class="fa fa-comment" aria-hidden="true"></i>
                            <p>Quick Assistance</p>
                        </li>
                    </ul>

				</div>
				<div class="col-md-9 slider-block">
				    <div class="slider-inner">

<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="d-block w-100" src="images/banner-slide.jpg" alt="First slide" loading="lazy">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="images/banner-slide.jpg" alt="Second slide" loading="lazy">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="images/banner-slide.jpg" alt="Third slide" loading="lazy">
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>


				</div>



				</div>
			</div>
			</div>
			</div>
		</div>
	</section>


<section class="section-block requirement-form-wrap">


	    <div class="container-fluid">
		    <div class="section-bg">
			<div class="container-fluid">
		    <div class="row">
			    <div class="col-md-6 banner-text">
				    <h2 style="font-family: 'poppins';">We connect<br><strong>Buyers & Sellers</strong></h2>home\inc\stats.blade.php
					<p>Rednirus Mart is India's largest online B2B marketplace, connecting buyers with suppliers.</p>
                                        <p style="font-family: 'poppins';">Pharmafranchisemart is designed to simplify the supply chain by connecting buyers with sellers, providing access to a vast network of trusted suppliers, and allowing for easy comparison of products and prices.</p>


                           <div class="row">
                               <div class="col-lg-12 col-md-4">
                                   <div class="trusted">
                                       <i class="fa fa-star" aria-hidden="true"></i>
                                     <p>Trusted Platform</p>
                                   </div>
                               </div>
                               <div class="col-lg-12 col-md-4">
                                   <div class="trusted">
                            <i class="fa fa-shield-alt" aria-hidden="true"></i>
                            <p>Safe &amp; Secure</p>

                                   </div>
                               </div>

                               <div class="col-lg-12 col-md-4">
                                   <div class="trusted">
                            <i class="fa fa-comment" aria-hidden="true"></i>
                            <p>Quick Assistance</p>

                                   </div>
                               </div>

                           </div>


				</div>
				<div class="col-md-6 requirement-form-pk">
					<div class="requirement-form-inner">
					<h2>Post Your requirement Here... home\inc\stats.blade.php</h2>




                                         <form name="quick_query_listing" class="quick_query_form" onSubmit="return submitQuery(this)">
  					     <div class="form-group">
   						 <label for="exampleInputEmail1">Enter Product / Service name</label>
   						 <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="quick_query">
    						  					   </div>
 					 <div class="form-group">
   						 <label for="exampleInputPassword1">Enter your name</label>
   						 <input type="text" class="form-control" name="quick_query_name" id="exampleInputPassword1" value="{{$name}}" >
  					</div>
 					 <div class="form-group">
   						 <label for="exampleInputPassword1">Enter your mobile</label>
   						 <input type="text" class="form-control" name="quick_query_phone" id="exampleInputPassword1" value="{{(auth()->check()) ? auth()->user()->phone : ''}}">
  					</div>


                                           <input style="font-family: 'poppins';cursor: pointer;background: #00b5b7;border: none;color: #fff;padding: 10px;border-radius: 4px;" class="" type="submit" value="Submit Requirement">

					</form>

					</div>
				</div>
			</div>
			</div>
			</div>
		</div>
	</section>



@section('after_scripts')
	@parent
<script>

function submitQuery(form){



 			var form =$(form);

 			$('form').removeClass('active_query');

 			form.addClass('active_query');
 			$(".quick_query_form [type='submit']").attr('disabled','disabled');
		    $.ajax({
					method: 'POST',
					url: '{{ lurl('quick_query') }}',
					data: {
						'quick_query': form.find('[name="quick_query"]').val(),
						'quick_query_name': form.find('[name="quick_query_name"]').val(),
						'quick_query_phone': form.find('[name="quick_query_phone"]').val(),
						'_token': $('input[name=_token]').val()
					}
				}).done(function(data) {
					$(".quick_query_form [type='submit']").removeAttr('disabled');
					$('#query_type').val(data.type);
					$('#query_id').val(data.id);
					$('#slider_from_email').val(data.email);

					$('[name="quick_query_phone"]').val(form.find('[name="quick_query_phone"]').val());
					$('[name="quick_query_name"]').val(form.find('[name="quick_query_name"]').val());



					window.dataLayer =window.dataLayer || [];

					window.dataLayer.push({
						'event':'quickQuery','conversionValue':1
					});



					$("#sliderForm #msform fieldset").removeAttr('style').hide();
					$("#sliderForm #msform fieldset:eq(0)").show();
					$("#sliderForm").modal({backdrop: 'static', keyboard: false});







				}).error(function(response) {

					$(".quick_query_form [type='submit']").removeAttr('disabled');

					var responseJSON = response.responseJSON;

					if(responseJSON.code==100){
						$('#signin_phone').val(form.find('[name="quick_query_phone"]').val());
						$('#signin_name').val(form.find('[name="quick_query_name"]').val());
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

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap');
.requirement-form-inner {
    background: #e3f9f9;
    padding: 29px;
     border-radius: 17px;
   font-family:'poppins';
}
.requirement-form-inner h2 {
    font-size: 22px;
    font-weight: bold;
    font-family: 'poppins';
    margin-bottom: 24px;
}
.trusted i {
    font-size: 30px;
    margin-top: ba;
    padding: 10px;
    border-radius: 40px;
    margin-top: -39px;
    background: #00b5b7;
}
.trusted p {
    font-size: 17px;
    font-family: 'poppins';
    font-weight: bold;
}
.trusted {
    margin-top: 33px;
    background: #00b5b7;
    color: #fff;
    padding: 12px;
    border-radius: 6px;
}
</style>


@endsection

--}}
