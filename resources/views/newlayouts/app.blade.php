<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
@include('newlayouts.headtag')
 </head>

<body>

   <div class="ps-page">



	@include('newlayouts.head')



	@include('whatsappbutton')














	@yield('content')





























@include('newlayouts.footer')




    </div>


  </div>

  @include('modalbutton')
  <!-- include 'modal' --->
  @yield('loginotpslide')
	<!-- The Modal -->
	<div id="mainModal" class="modal tmodal">

	  <!-- Modal content -->
	  <div class="modal-content tmodal-content">
	    <span class="closeModal tcloseModal">&times;</span>
	    <p>
														<div class="contact-wrap w-100 p-md-5 p-4">
                               <img src="/assets/images/thanku.png" style="width:205px; ">
                                <div id="form-message-warning" class="mb-4"></div>
                                <div id="form-message-success" class="mb-4" style="font-size: 25px;">
                                    Your message was sent
                                </div>

                            </div>
			</p>
	  </div>

	</div>
  <script src="/assets/js/popper.min.js"></script>
    <!-- <script src="/assets/js/jquery.js"></script>

    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/plugins.js"></script>
    <script src="/assets/js/owl.js"></script>
    <script src="/assets/js/wow.js"></script>
    <script src="/assets/js/validation.js"></script>
    <script src="/assets/js/jquery.fancybox.js"></script>
    <script src="/assets/js/appear.js"></script>
    <script src="/assets/js/scrollbar.js"></script>
    <script src="/assets/js/isotope.js"></script>
    <script src="/assets/js/jquery.nice-select.min.js"></script>
    <script src="/assets/js/jquery-ui.js"></script>
    <script src="/assets/js/parallax-scroll.js"></script>  -->

    <!-- main-js -->
    <!-- <script src="/assets/js/script.js"></script>  -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="/home/plugins/jquery.min.js"></script>
    <script src="/home/plugins/popper.min.js"></script>
    <script src="/home/plugins/bootstrap4/js/bootstrap.min.js"></script>
    <script src="/home/plugins/select2/dist/js/select2.full.min.js"></script>
    <script src="/home/plugins/owl-carousel/owl.carousel.min.js"></script>
    <script src="/home/plugins/jquery-bar-rating/dist/jquery.barrating.min.js"></script>
    <script src="/home/plugins/lightGallery/dist/js/lightgallery-all.min.js"></script>
    <script src="/home/plugins/slick/slick/slick.min.js"></script>
    <script src="/home/plugins/noUiSlider/nouislider.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.js"></script>

    <!-- custom code-->
    <script src="/home/js/main.js"></script>


		<script>

var siteUrl = '{{ url('/') }}';
var languageCode = 'en';
var countryCode = 'IN';

		$("#bigbtn-enqsubmit").on('click',function(e){

		//function tohandlemodal() {
			console.log('ddd');
			if($('#bigfrmenqrform')[0].checkValidity()) {
				console.log('eee');

				$.ajax({

						 type:'POST',
						 url:"{{-- route('enquirysideform') --}}",
						 data:$('#bigfrmenqrform').serialize(),

						 //{
							 //$('#frmenqrform').serialize(),
						 //},
						 success:function(data){
								console.log(data.response);
							//$('body').removeClass("modal-open");
								//$('div').removeClass("modal-backdrop");
								//$('#exampleModal').modal('toggle');
								var modal = document.getElementById("mainModal")
								modal.style.display = "block";
						 }

				});
				// modal show logics

				e.preventDefault();
			} else {
				console.log('fff');
				console.log($('#bigfrmenqrform').serialize());
				e.preventDefault();
			}



		//}
		});

		</script>







		<script>




    $(".mysublink").on('click',function(e){

    //console.log($("button[data-target='exampleModal']")data-target="#exampleModal");
    $("#btenqr").click()
    	//var modal = document.getElementById("exampleModal")
    //	modal.style.display = "block";

    	//$('#exampleModal').modal('toggle');
    	//$('body').addClass("modal-open");
    });






			$.ajaxSetup({
				headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			$("#btn-submit").click(function(e){

				if($('#frmfootform')[0].checkValidity()) {
					$.ajax({

							 type:'POST',
							 url:"{{-- route('enquiryform') --}}",
							 data:{name:$("#frmfootform #name").val() , email:$("#frmfootform #email").val(), phone:$("#frmfootform #phone").val(), city:$("#frmfootform #city").val(), message:$("#frmfootform #message").val()},
					 		 success:function(data){
									console.log(data.response);
							 }

					});
					// modal show logics
					var modal = document.getElementById("mainModal")
					modal.style.display = "block";
					e.preventDefault();
				} else {


				}






		  });






			var modal = document.getElementById("mainModal")
			window.onclick = function(event) {
			  if (event.target == modal) {
			    modal.style.display = "none";
			  }
			}
			var span = document.getElementsByClassName("tcloseModal")[0];
			span.onclick = function() {
			  modal.style.display = "none";
			}

		</script>



		<script>











			$.ajaxSetup({
				headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			$("#btn-submit-barside").click(function(e){

				if($('#frmbarsideform')[0].checkValidity()) {
					$.ajax({

							 type:'POST',
							 url:"{{-- route('enquiryform') --}}",
							 data:{name:$("#frmbarsideform #name").val() , email:$("#frmbarsideform #email").val(), phone:$("#frmbarsideform #phone").val(), city:$("#frmbarsideform #city").val(), message:$("#frmbarsideform #message").val()},
					 		 success:function(data){
									console.log(data.response);
							 }

					});
					// modal show logics

					console.log($("#frmbarsideform").parent());

					var object = $("#frmbarsideform").closest('.modal-body');

            if (object.length) {
							$('body').removeClass("modal-open");
								$('div').removeClass("modal-backdrop");
							$('#myModal').modal('toggle');
						}
					var modal = document.getElementById("mainModal")
					modal.style.display = "block";
					e.preventDefault();
				} else {


				}






		  });







		</script>





<script>
         jQuery(document).ready(function($){

           jQuery("#mini_contact_form_container").click(function(){
           jQuery("#mini_contact_form").slideToggle(1500);
           });
           jQuery("#clik_show").click(function(){
         jQuery("#mini_contact_forms").show()
         });


         jQuery('#closes').click(function(){
         jQuery("#mini_contact_forms").addClass("hide");
         });

         });
      </script>

      <script>
          $('.autoplay').slick({
  slidesToShow: 7,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 2000,
});
      </script>

      <script>
function myFunction() {
  var x = document.getElementById("myLinks");
  if (x.style.display === "block") {
    x.style.display = "none";
  } else {
    x.style.display = "block";
  }
}
</script>
<script>
/* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
var dropdown = document.getElementsByClassName("dropdown-btn");
var i;

for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var dropdownContent = this.nextElementSibling;
    if (dropdownContent.style.display === "block") {
      dropdownContent.style.display = "none";
    } else {
      dropdownContent.style.display = "block";
    }
  });
}
</script>

@yield('after_scripts')

</body>

</html>
