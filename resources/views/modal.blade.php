<!-- Modal -->
<?php
	if(auth()->user()){
      $fromuserid = auth()->user()->id;
      // to pick from the site itself
      //post_id
      //to_user_id
      //subject
  }

?>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <p>Send Enquiry<br>(Do Not Post Job Enquiry)</p> <span  id="errmsg" style="visibility:hidden;color:red;"><small>Please fill complete form precisely.</small></span>
      <div class="modal-header">
       <form action="/enquiry/store/from/popup"  id="frmenqrform" method="POST"  >
                        @csrf
                        <input name="prsntstep" type="hidden" id="prsntstep" value="1">
                        <div class="row">

                          <div id="stp1">

                            <div class="col-md-6 col-sm-12">
                              <div class="contact-form-section best">
                                  <p class="kVZdeE"><b>Are You Looking for*</b></p>
                                  <input type="text" list="tttt" class="form-control" name="looking" id="looking" required="" >
                                  <datalist id="tttt">
                                   <option>Third Party Manufacturing</option>
                                    <option>PCD Pharma Franchise</option>
                                  </datalist>
                              </div>
                           </div>

                            <div class="col-md-6 col-sm-12">
                              <div class="contact-form-section best">
                                  <p class="kVZdeE"><b>Requirement Time*</b></p>
                                  <input type="text" list="hhhhhhh" class="form-control" name="requirement" id="requirement" required="">
                                    <datalist id="hhhhhhh">
                                     <option>Immediate</option>
                                     <option>Within 15 Days</option>
                                      <option>Within A Month</option>
                                      <option>In Future</option>
                                     </datalist>
                              </div>
                           </div>


                           <div class="col-md-6 col-sm-12">
                             <div class="contact-form-section best">
                                 <p class="kVZdeE"><b>When we contact you*</b></p>
                                 <input type="text" list="hhhiii" class="form-control" name="contacttime" id="contacttime" required="">
                                   <datalist id="hhhiii">
                                    <option>Morning</option>
                                    <option>Afternoon</option>
                                     <option>Evening</option>
                                     <option>Anytime</option>
                                    </datalist>
                             </div>
                          </div>


                          <div class="col-md-3 col-sm-12">
                              <div class="contact-form-section best">
                                  <p class="kVZdeE"><b>Profession*</b></p>
                                  <input type="text" list="dddddd" class="form-control" name="profesional" id="profesional" required="">
                                    <datalist id="dddddd">
                                     <option>Doctor</option>
                                      <option>Medical Representative</option>
                                      <option>Stockist</option>
                                       <option>Chesmist</option>
                                        <option>Distributor</option>
                                     </datalist>
                              </div>
                           </div>

                           <div class="col-md-3 col-sm-12">
                             <div class="contact-form-section best">
                                 <p class="kVZdeE"><b>Experience</b></p>
                                 <input type="text" list="mmmmm" class="form-control" name="experience" id="experience" >
                                 <datalist id="mmmmm">
                                  <option>Below 3 years</option>
                                   <option>5 year</option>
                                  </datalist>
                             </div>
                          </div>


                           <div class="col-md-6 col-sm-12">
                              <div class="contact-form-section best">
                                  <p class="kVZdeE"><b>Phone*</b></p>
                                 <input type="text" class="form-control" placeholder="Phone" id="phone" name="phone" required="">
                              </div>
                           </div>

                           <div class="col-md-6 col-sm-12">
                              <div class="contact-form-section best">
                                  <p class="kVZdeE"><b>Email*</b></p>
                                 <input type="text" class="form-control" placeholder="Email" id="email" name="email" required="">
                              </div>
                           </div>

                           <div class="col-md-6 col-sm-12">
                              <div class="contact-form-section best">
                                  <p class="kVZdeE"><b>Name*</b></p>
                                 <input type="text" class="form-control" placeholder="Name" id="name" name="name" required="">
                              </div>
                           </div>
                           <div class="col-md-6 col-sm-12">
                              <div class="contact-form-section best">
                                  <p class="kVZdeE"><b>City*</b></p>
                                 <input type="text" class="form-control" placeholder="City" id="city" name="city" required="">
                              </div>
                           </div>

                         </div>



                        <div id="stp2" style="">

                          <div class="col-md-6 col-sm-12">
                             <div class="contact-form-section best">
                                 <p class="kVZdeE"><b>OTP*</b></p>
                                <input type="text" class="form-control" type="text" name="otp" value="" placeholder="otp" required="" >
                             </div>
                          </div>


                           <!--newform-->
                           <div class="col-md-6 col-sm-12">
                              <div class="contact-form-section best">
                                  <p class="kVZdeE"><b>Pincode*</b></p>
                                 <input type="text" class="form-control" type="text" name="pincode" value="" placeholder="Pincode" required="" >
                              </div>
                           </div>



                           <div class="col-md-6 col-sm-12">
                              <div class="contact-form-section best">
                                  <p class="kVZdeE"><b>State</b></p>
                                 <input  type="text" class="form-control" type="text" name="state" value="" placeholder="State" >
                              </div>
                           </div>


                            <!--newform-->
                           <div class="col-md-6 col-sm-12">
                              <div class="contact-form-section best">
                                  <p class="kVZdeE"><b>Drug Licence</b></p>
                                  <label>Yes</label>
                                  <input type="radio" name="drug" value="yes">
                                  <label>No</label>
                                  <input type="radio" name="drug" value="no">
                                  <label>Applied</label>
                                  <input type="radio" name="drug" value="applied">
                              </div>
                           </div>

                           <div class="col-md-6 col-sm-12">
                              <div class="contact-form-section best">
                                  <p class="kVZdeE"><b>GST</b></p>
                                  <label>Yes</label>
                                  <input type="radio" name="gst" value="yes">
                                  <label>No</label>
                                  <input type="radio" name="gst" value="no">
                                  <label>Applied</label>
                                  <input type="radio" name="gst" value="applied">
                              </div>
                           </div>


                           <div class="col-md-12 col-sm-12">
                              <div class="contact-form-section best" style="padding-top:10px;">
                                  <p class="kVZdeE"><b>Message*</b></p>
                                 <textarea class="form-control" id="message" name="message" rows="4"
                                    placeholder="Message" style="height: 50px;" required=""></textarea>
                              </div>
                           </div>

                        </div>


                           <div class="col-md-12 col-sm-12 pb-4">
                              <button id="btn-prev"  class="btn btn-success" >Previous</button>
                              <button type="submit" id="btn-enqsubmit" class="btn btn-success" >
                                 Next
                              </button>

                           </div>



                        </div>
                     </form>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-footer">
        <button  type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>




<script>

jQuery(document).ready(function($){
  //console.log('eeefff');
  frmreset();
});

$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

$( "#exampleModal" ).on('shown.bs.modal', function(){
    frmreset();
});

$( "#exampleModal" ).on('hidden.bs.modal', function(){
    console.log('hidden event');
});

function frmreset() {
  $('#prsntstep').val('1');
  $('#btn-prev').hide();
  $('#btn-enqsubmit').html('Next');
  $('#stp1').show();
  $('#stp2').hide();
  $('#stp1').css("display","block");
  $('#stp2').css("display","none");
  $('#frmenqrform')[0].reset();
  $('#errmsg').css("visibility","hidden");
}

$("#btn-prev").on('click',function(e){
  $('#prsntstep').val('1');
  $('#btn-prev').hide();
  $('#btn-enqsubmit').html('Next');
  $('#stp1').show();
  $('#stp2').hide();
  $('#stp1').css("display","block");
  $('#stp2').css("display","none");
  //$('#stp2').css("visibility","hidden");
  //$('#stp2').css("visibility","visible");

});


$("#btn-enqsubmit").on('click',function(e){

  if($('#prsntstep').val() == '1' ) {

    $('#prsntstep').val('2');
    $('#stp1').hide();
    $('#stp2').css("display","block");
    $('#stp2').show();
    $('#btn-enqsubmit').html('Save');
    $('#btn-prev').show();
    $('#stp1').css("display","none");

    e.preventDefault();
  } else {
    $('#btn-prev').show();
  }

//function tohandlemodal() {

	if($('#frmenqrform')[0].checkValidity()) {
    $('#errmsg').css("visibility","hidden");

		$.ajax({

				 type:'POST',
				 url:"{{-- route('enquirysideform') --}}",
				 data:$('#frmenqrform').serialize(),

				 //{
					 //$('#frmenqrform').serialize(),
				 //},
				 success:function(data){
						console.log(data.response);
					$('body').removeClass("modal-open");
						$('div').removeClass("modal-backdrop");
						$('#exampleModal').modal('toggle');
						var modal = document.getElementById("mainModal")
						modal.style.display = "block";
				 }

		});
		// modal show logics

		e.preventDefault();
	} else {
		console.log('fff');
		console.log($('#frmenqrform').serialize());

    $('#errmsg').css("visibility","visible");

		e.preventDefault();
	}



//}
});

</script>
