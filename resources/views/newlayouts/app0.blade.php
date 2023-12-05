<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
@include('newlayouts.headtag')
 </head>

<body>

   <div class="ps-page">



	@include('newlayouts.head')

<div class="pen-information"></div>
<div id="wa-chat-widget">
    <div class='wa-chat-widget-header'>
      <img src="images/why-choose/profile (1).png">
      <div class="wa-chat-widget-profile">
        <div>Pharma Franchise Mart</div>
        <small>Typically replies within an hour</small>
      </div>
      <a class='close' href='#'>×</a>
    </div>
    <div class="wa-chat-widget-body">
      <div class="message">
        <small class="profile-name">Pharma Franchise Mart</small>
        <div class="wcw-message">Hi there 👋<br /> How We can Help You?</div>
      </div>
    </div>
    <div class="wa-chat-widget-send">
        <a href="https://api.whatsapp.com/send?phone=91-9888885364&text=Pharma Franchise%20Mart">
        <button type="button" class="btn btn-success">Chat Now</button></a>
      <!--<form target="_blank" method="get" action="https://wa.me/918826588115">
        <input type="text" name="text" placeholder="Type a message" />
        <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
            <path
              d="M24 0l-6 22-8.129-7.239 7.802-8.234-10.458 7.227-7.215-1.754 24-12zm-15 16.668v7.332l3.258-4.431-3.258-2.901z" />
          </svg></button>
      </form>-->
    </div>
  </div>





















    <div class="best emergency call">
          <a href="tel:91-9888885364">
          <img class="ener"src="/images/why-choose/emergency-call.png"></a>
      </div>


  <a href="#wa-chat-widget" class="wa-icon"><svg xmlns="http://www.w3.org/2000/svg"
      xmlns:xlink="http://www.w3.org/1999/xlink" width="60" height="60" viewBox="0 0 1219.547 1225.016">
      <path fill="#E0E0E0"
        d="M1041.858 178.02C927.206 63.289 774.753.07 612.325 0 277.617 0 5.232 272.298 5.098 606.991c-.039 106.986 27.915 211.42 81.048 303.476L0 1225.016l321.898-84.406c88.689 48.368 188.547 73.855 290.166 73.896h.258.003c334.654 0 607.08-272.346 607.222-607.023.056-162.208-63.052-314.724-177.689-429.463zm-429.533 933.963h-.197c-90.578-.048-179.402-24.366-256.878-70.339l-18.438-10.93-191.021 50.083 51-186.176-12.013-19.087c-50.525-80.336-77.198-173.175-77.16-268.504.111-278.186 226.507-504.503 504.898-504.503 134.812.056 261.519 52.604 356.814 147.965 95.289 95.36 147.728 222.128 147.688 356.948-.118 278.195-226.522 504.543-504.693 504.543z" />
      <linearGradient id="a" gradientUnits="userSpaceOnUse" x1="609.77" y1="1190.114" x2="609.77" y2="21.084">
        <stop offset="0" stop-color="#20b038" />
        <stop offset="1" stop-color="#60d66a" />
      </linearGradient>
      <path fill="url(#a)"
        d="M27.875 1190.114l82.211-300.18c-50.719-87.852-77.391-187.523-77.359-289.602.133-319.398 260.078-579.25 579.469-579.25 155.016.07 300.508 60.398 409.898 169.891 109.414 109.492 169.633 255.031 169.57 409.812-.133 319.406-260.094 579.281-579.445 579.281-.023 0 .016 0 0 0h-.258c-96.977-.031-192.266-24.375-276.898-70.5l-307.188 80.548z" />
      <!--<image overflow="visible" opacity=".08" width="682" height="639" xlink:href="FCC0802E2AF8A915.png"
        transform="translate(270.984 291.372)" />--->
      <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFF"
        d="M462.273 349.294c-11.234-24.977-23.062-25.477-33.75-25.914-8.742-.375-18.75-.352-28.742-.352-10 0-26.25 3.758-39.992 18.766-13.75 15.008-52.5 51.289-52.5 125.078 0 73.797 53.75 145.102 61.242 155.117 7.5 10 103.758 166.266 256.203 226.383 126.695 49.961 152.477 40.023 179.977 37.523s88.734-36.273 101.234-71.297c12.5-35.016 12.5-65.031 8.75-71.305-3.75-6.25-13.75-10-28.75-17.5s-88.734-43.789-102.484-48.789-23.75-7.5-33.75 7.516c-10 15-38.727 48.773-47.477 58.773-8.75 10.023-17.5 11.273-32.5 3.773-15-7.523-63.305-23.344-120.609-74.438-44.586-39.75-74.688-88.844-83.438-103.859-8.75-15-.938-23.125 6.586-30.602 6.734-6.719 15-17.508 22.5-26.266 7.484-8.758 9.984-15.008 14.984-25.008 5-10.016 2.5-18.773-1.25-26.273s-32.898-81.67-46.234-111.326z" />
      <path fill="#FFF"
        d="M1036.898 176.091C923.562 62.677 772.859.185 612.297.114 281.43.114 12.172 269.286 12.039 600.137 12 705.896 39.633 809.13 92.156 900.13L7 1211.067l318.203-83.438c87.672 47.812 186.383 73.008 286.836 73.047h.255.003c330.812 0 600.109-269.219 600.25-600.055.055-160.343-62.328-311.108-175.649-424.53zm-424.601 923.242h-.195c-89.539-.047-177.344-24.086-253.93-69.531l-18.227-10.805-188.828 49.508 50.414-184.039-11.875-18.867c-49.945-79.414-76.312-171.188-76.273-265.422.109-274.992 223.906-498.711 499.102-498.711 133.266.055 258.516 52 352.719 146.266 94.195 94.266 146.031 219.578 145.992 352.852-.118 274.999-223.923 498.749-498.899 498.749z" />
    </svg></a>


















	@yield('content')



	<!-- Button trigger modal -->
	<div class="relax lex">
<button type="button" id="btenqr" class="" data-toggle="modal" data-target="#exampleModal">
  Enquire Now
</button>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <p>Send Enquiry<br>
(Do Not Post Job Enquiry)</p>
      <div class="modal-header">
          <!--<form action="https://dealkaregaindia.com/user-lead/submit" method="post">
                    <input type="hidden" name="_token" value="2EVHowQaL6cdn84SRYihMVzvIjcakq8B3hONjMex">                   <div class="row">
                      <div class="col-lg-4 col-md-12">
                          <p class="kVZdeE"> Name</p>
                           <input class="kMhfli" type="text" name="user_name" value="" placeholder="Name" required>
                      </div>
                      <div class="col-lg-4 col-md-12">
                          <p class="kVZdeE">Mobile Number</p>
                           <input class="kMhfli" type="text" name="Phone" value="" placeholder="phone" required>
                      </div>
                      <div class="col-lg-4 col-md-12">
                        <p class="kVZdeE"> Email</p>
                         <input class="kMhfli" type="text" name="user_email" value="" placeholder="Email" >
                      </div>

                    </div>

                    <div class="row">
                         <div class="col-lg-4 col-md-12">
                             <p class="kVZdeE">Service Type</p>
                            <input list="browsers" class="kMhfli" name="service_type" id="browser" >
                            <datalist id="browsers">
                             <option>Pcd pharma franchise</option>
                              <option>Third party manufacturing</option>
                             </datalist>
                         </div>

                         <div class="col-lg-4 col-md-12">
                            <p class="kVZdeE">Service For</p>

                            <input list="browsersss" class="kMhfli" name="service_for" id="browser" >
                            <datalist id="browsersss">
                             <option>Cardio</option>
                              <option>Derma</option>
                              <option>Dental</option>
                              <option>General</option>
                              <option>Gynae</option>
                              <option>Ortho</option>
                              <option>Pediatric</option>
                              <option>Psychiatry</option>
                              <option>Veterinary</option>
                              <option>Neuro</option>
                              <option>Generic</option>
                              <option>Opthalmic</option>
                              <option>Oncology</option>
                              <option>Ayurvedic</option>
                              <option>Other</option>
                             </datalist>
                         </div>
                         <div class="col-lg-4 col-md-12">
                            <p class="kVZdeE">Requirement Emergency</p>
                               <input list="browserssss" class="kMhfli" name="emer" id="browser" >
                            <datalist id="browserssss">
                             <option>Immediate</option>
                              <option>within 15 days</option>
                              <option>within month</option>
                             </datalist>



                         </div>
                    </div>
                   <div class="flex-li">
                      <ul>
                          <li><p class="kVZdeE">City</p>
                   <input class="kMhfli" type="text" name="city" value="" placeholder="City" ></li>
                   <li><p class="kVZdeE">Pincode</p>
                   <input class="kMhfli" type="text" name="pincode" value="" placeholder="Pincode" ></li>
                         <li><p class="kVZdeE">State</p>
                   <input class="kMhfli" type="text" name="state" value="" placeholder="State" ></li>

                      </ul>

                    <div class="row">

                      <div class="col-lg-6 col-md-12">
                         <p class="kVZdeE">GST</p>

                        <input list="browsersssss" class="kMhfli" name="gst" id="browser" >
                            <datalist id="browsersssss">
                             <option>Yes</option>
                              <option>No</option>
                              <option>Applied</option>
                             </datalist>

                      </div>
                      <div class="col-lg-6 col-md-12">
                         <p class="kVZdeE">Drug Licence</p>
                   <input list="browsersssss" class="kMhfli" name="license" id="browser" >
                            <datalist id="browsersssss">
                             <option>Yes</option>
                              <option>No</option>
                              <option>Applied</option>
                              <option>Arrange It</option>
                             </datalist>
                      </div>

                   </div>
                      <p class="kVZdeE">Message</p>
                   <input class="kMhfli" type="text" name="message" value="" placeholder="Message" >


                   </div>





                     <button type="submit" class="btn btn-primary">Submit</button>


                  </form>-->
       <form action="/enquiry/store/from/popup"  id="frmenqrform" method="POST"  >
                        @csrf
                        <div class="row">

                            <div class="col-md-6 col-sm-12">
                              <div class="contact-form-section best">
                                  <p class="kVZdeE"><b>Are You Looking for*</b></p>
                                  <input type="text" list="tttt" class="form-control" name="looking" id="looking" required="" >
                            <datalist id="tttt">
                             <option>Third Party Manufacturing</option>
                              <option>PCD Franchise</option>


                             </datalist>
                              </div>
                           </div>


                            <div class="col-md-6 col-sm-12">
                              <div class="contact-form-section best">
                                  <p class="kVZdeE"><b>Requirement*</b></p>
                                  <input type="text" list="hhhhhhh" class="form-control" name="requirement" id="requirement" required="">
                            <datalist id="hhhhhhh">
                             <option>Immediate</option>
                              <option>With A Month</option>
                              <option>Future</option>

                             </datalist>
                              </div>
                           </div>

                            <div class="col-md-6 col-sm-12">
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


                            <div class="col-md-6 col-sm-12">
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
                                  <p class="kVZdeE"><b>Name*</b></p>
                                 <input type="text" class="form-control" placeholder="Name" id="name" name="name" required="">
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
                                  <p class="kVZdeE"><b>Phone*</b></p>
                                 <input type="text" class="form-control" placeholder="Phone" id="phone" name="phone" required="">
                              </div>
                           </div>
                           <div class="col-md-6 col-sm-12">
                              <div class="contact-form-section best">
                                  <p class="kVZdeE"><b>City*</b></p>
                                 <input type="text" class="form-control" placeholder="City" id="city" name="city" required="">
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
                              <div class="contact-form-section best">
                                  <p class="kVZdeE"><b>Message*</b></p>
                                 <textarea class="form-control" id="message" name="message" rows="4"
                                    placeholder="Message" style="height: 100px;" required=""></textarea>
                              </div>
                           </div>
                           <div class="col-md-12 col-sm-12 pb-4">

                              <button type="submit" id="btn-enqsubmit" class="btn btn-success" >
                                 Send
                              </button>

                           </div>
                        </div>
                     </form>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
      </div>
    </div>
  </div>
</div>

























     <!-- main-footer -->
        <footer class="ps-footer ps-footer--2 ps-footer--4">
            <div class="ps-footer--top">
              </div>
            <div class="container">
                <div class="ps-footer__middle">

                    <div id="footer" class="container">

               <!--<div style="color:#5b6c8f;">
                     <h4 class="ps-block__title " ><a href="https://www.pharmafranchisekart.com/top-pcd-pharma-franchise-companies.html" style="color:#20344c; font-weight:900;">PCD Pharma Franchise Companies :-</h4><a href="https://www.pharmafranchisekart.com/pharma-pcd-franchise-companies-tablets.html">PCD Pharma Franchise Companies For Tablets</a> | <a href="https://www.pharmafranchisekart.com/pharma-pcd-franchise-companies-capsules.html">PCD Pharma Franchise Companies For Capsules</a> | <a href="https://www.pharmafranchisekart.com/top-pcd-pharma-franchise-companies-injections.html">Top PCD Pharma Franchise Companies For Injections</a> | <a href="https://www.pharmafranchisekart.com/pcd-pharma-franchise-companies-syrups.html">PCD Pharma Franchise Companies For Syrups</a>  | <a href="https://www.pharmafranchisekart.com/pcd-pharma-franchise-companies-protein-powder.html">PCD Pharma Franchise Companies For Protein Powder</a> | <a href="https://www.pharmafranchisekart.com/pcd-pharma-franchise-companies-nasal-drops.html">Best PCD Pharma Franchise Companies For Nasal Drops</a> |<a href="https://www.pharmafranchisekart.com/orthopedic-pharma-pcd-companies.html">Top Orthopedic Medicine PCD Franchise Companies </a> | <a href="https://www.pharmafranchisekart.com/ayurvedic-medicine-pcd-franchise-companies.html">best Ayurvedic Medicine PCD Franchise Companies </a> | <a href="https://www.pharmafranchisekart.com/pharma-pcd-franchise-companies-for-derma-medicine.html">
                    Pharma PCD Franchise Companies For Derma Medicine </a> | <a href="https://www.pharmafranchisekart.com/pharma-pcd-franchise-companies-critical-care.html"> Pharma PCD franchise Companies For Critical Care </a> | <a href="https://www.pharmafranchisekart.com/herbal-cosmetics-pcd-franchise-companies.html">Herbal Cosmetics PCD Franchise Companies</a> | <a href="https://www.pharmafranchisekart.com/nutraceuticals-best-pcd-pharma-franchise-companies.html">Nutraceutical Pharma Franchise Companies</a> |
                    <br>
                    <br>
                     <h4 class="ps-block__title " ><a href="https://www.pharmafranchisekart.com/pharma-manufacturing-companies.html" style="color:#20344c; font-weight:900;">Pharma PCD Companies In India:-</h4><a href="https://www.pharmafranchisekart.com/third-party-manufacturers-tablets.html">Third Party Manufacturers For Tablets</a> | <a href="https://www.pharmafranchisekart.com/third-party-manufacturers-capsules.html">Third Party Manufacturers For Capsules</a> | <a href="https://www.pharmafranchisekart.com/top-contractract-manufacturers-injections.html">Top Contractract Manufacturers For Injections</a> | <a href="https://www.pharmafranchisekart.com/top-allopathic-medicine-manufacturers-companies.html">Top Allopathic Medicine Manufacturers Companies </a> | <a href="https://www.pharmafranchisekart.com/ayurvedic-medicine-manufacturers.html">Ayurvedic Medicine Manufacturers  </a> | <a href="">Third Party Manufacturers for Derma Medicine </a> | <a href="https://www.pharmafranchisekart.com/top-manufacturers-critical-care-medicine.html">
                     Top Manufacturers for Critical Care Medicine</a> | <a href="">Herbal Medicine Manufacturers Companies</a> |
                    <br>
                    <br>
                    <h4 class="ps-block__title " ><a href="https://www.pharmafranchisekart.com/pharma-manufacturing-companies.html" style="color:#20344c; font-weight:900;">Pharma Manufacturing Companies :-</h4>
                    <a href="https://www.pharmafranchisemart.com/pharma-pcd-in-andhra-pradesh.html">Pharma pcd companies for Andhra Pradesh</a> |<a href="https://www.pharmafranchisemart.com/pcd-pharma-franchise-companies-in-assam/">PCD pharma franchise company for Assam</a> | <a href="https://www.pharmafranchisemart.com/pharma-pcd-in-bihar.html">Pharma PCD Companies for Bihar</a> | <a href="https://www.pharmafranchisemart.com/pharma-pcd-in-chattisgarh.html">Pharma PCD Companies for Chhattisgarh</a> | <a href="https://www.pharmafranchisemart.com/pharma-pcd-goa.html">Pharma PCD for Goa</a> | <a href="https://www.pharmafranchisemart.com/pharma-pcd-for-gujarat.html">Pharmaceutical PCD Companies for Gujarat</a> |<a href="https://www.pharmafranchisemart.com/pharma-pcd-for-haryana.html">Pharma PCD Companies for Haryana</a> | <a href="https://www.pharmafranchisemart.com/pharma-pcd-for-himachal-pradesh.html">Pharma PCD franchise For Himachal Pradesh</a> | <a href="https://www.pharmafranchisemart.com/pcd-pharma-franchise-company-in-jammu-kashmir/">PCD Companies for Jammu and Kashmir</a> | <a href="https://www.pharmafranchisemart.com/pcd-pharma-franchise-companies-in-jharkhand-2/">PCD Pharma Companies in Jharkhand</a> | <a href="https://www.pharmafranchisemart.com/pharma-pcd-for-karnataka.html">PCD Companies for Karnataka</a> |<a href="https://www.pharmafranchisemart.com/pharma-pcd-kerala.html">Pharma PCD for Kerala</a> | <a href="https://www.pharmafranchisemart.com/pharma-pcd-for-madhya-pradesh.html">PCD Companies for Madhya Pradesh</a> | <a href="https://www.pharmafranchisemart.com/pharma-pcd-for-maharashtra.html">Pharmaceutical Companies for Maharashtra</a> | <a href="https://www.pharmafranchisemart.com/pharma-pcd-for-manipur.html">Pharma PCD for Manipur</a> |<a href="https://www.pharmafranchisemart.com/pharma-pcd-for-meghalaya.html"> Top Pharmaceutical Companies in Meghalaya</a> |<a href="https://www.pharmafranchisemart.com/pcd-for-nagaland.html"> PCD Pharma Company for Nagaland</a> | <a href="https://www.pharmafranchisemart.com/pcd-pharma-franchise-companies-in-odisha/">Best Pharma Company for Odisha</a> |<a href="https://www.pharmafranchisemart.com/top-pharma-franchise-companies-punjab/">Top Pharma Companies in Punjab</a> | <a href="https://www.pharmafranchisemart.com/pharma-pcd-for-rajasthan.html">PCD Pharmaceutical Companies in Rajasthan</a> |<a href="https://www.pharmafranchisemart.com/pharma-pcd-in-tamil-nadu.html"> Top PCD company for Tamil Nadu</a> | <a href="https://www.pharmafranchisemart.com/pharma-pcd-for-tripura.html">Best PCD / Franchise Company for Tripura</a> |<a href="https://www.pharmafranchisemart.com/pharma-pcd-companies-uttar-pradesh.html">Top PCD Companies in Uttar Pradesh</a> | <a href="https://www.pharmafranchisemart.com/pharm-pcd-in-uttarakhand.html">Top PCD Franchise Company for Uttarakhand</a> |<a href="https://www.pharmafranchisemart.com/pharma-pcd-for-west-bengal.html">Best PCD / Franchise Company for West Bengal</a>
                </div>-->
               </div>
                    <div class="row mt-5 pt-5 ml-0 mr-0 ">
                        <div class="col-12 col-md-6 p-0">
                            <div class="row m-0">

															<?php
																$parents = DB::table('categories')->where('active','1')->limit(3)->orderBy('id', 'ASC')->get();   //->orderByRaw("(id <> '".$category->parent_id."')  ASC,name")
															?>
															@foreach($parents as $pa)

                                <div class="col-6 col-md-{{$loop->index + 3 }} p-0">
                                    <div class="ps-footer--block">
                                        <h5 class="ps-block__title">{{$pa->name}}  </h5>
                                        <ul class="ps-block__list">

																					<?php $sub = DB::table('categories')->where('parent_id',$pa->id)->limit(4)->orderBy('id','ASC')->where('active','1')->get(); ?>
																			@foreach($sub as $k => $v)
																				 <li><a href="#">{{$v->name}}</a></li>
																			@endforeach




                                        </ul>
                                    </div>
                                </div>
															@endforeach
                            </div>
                        </div>
                        <div class="col-12 col-md-6 p-0">
                            <div class="row">
                                <div class="col-12 col-md-5">
                                    <div class="ps-footer--contact">
                                        <h5 class="ps-footer__title">Have any Query? </h5>
                                        <div class="ps-footer__fax"><i class="fa fa-phone" aria-hidden="true"></i>
                                        <a href="tel:91-9888885364">
                                        <p class="hes">( +91 9888885364 )</p></a></div>
                                        <p class="ps-footer__work">Monday – Saturday: 9:00 AM - 6:30 PM</p>
                                        <hr>
                                        <div class="footi">
                                          <i class="fa fa-map-marker" aria-hidden="true"></i><p>SCO: 207, Sector 14, Panchkula, Haryana.</p>
                                          </div>
                                          <h3 class="hasi-1">Follow Us</h3>
                                        <ul class="ps-social">
                                            <li><a class="ps-social__link facebook" href="#"><i class="fa fa-facebook"> </i><span class="ps-tooltip">Facebook</span></a></li>
                                            <li><a class="ps-social__link instagram" href="#"><i class="fa fa-instagram"></i><span class="ps-tooltip">Instagram</span></a></li>
                                            <li><a class="ps-social__link pinterest" href="#"><i class="fa fa-pinterest-p"></i><span class="ps-tooltip">Pinterest</span></a></li>
                                            <li><a class="ps-social__link linkedin" href="#"><i class="fa fa-linkedin"></i><span class="ps-tooltip">Linkedin</span></a></li>
                                            <li><a class="ps-social__link linkedin" href="#"><i class="fa-brands fa-twitter"></i><span class="ps-tooltip">Twitter</span></a></li>
                                        </ul>
                                     </div>
                                </div>
                                <div class="col-12 col-md-7">
                                    <div class="ps-footer--address">
                                        <div class="ps-logo"><a href="index-2.html"> <img src="img/sticky-logo.png" alt=""><img class="logo-white" src="img/logo-white.png" alt=""><img class="logo-black" src="img/Logo-black.png" alt=""><img class="logo-white-all" src="img/logo-white1.png" alt=""><img class="logo-green" src="img/logo-green.png" alt=""></a></div>
                                        <div class="ps-footer__title">Contact Us</div>
                                       <div class="form-groups hells">
                  <h4 class="catr">Enquire Now</h4>
                  <div class="choose-contact-box contact-inner">
                      <form action="/enquiry/store" id="frmfootform" method="POST">
                        @csrf
                        <div class="row">
                           <div class="col-md-6 col-sm-12">
                              <div class="contact-form-section best">
                                 <input type="text" class="form-control" placeholder="Name*" id="name" name="name" required="">
                              </div>
                           </div>
                           <div class="col-md-6 col-sm-12">
                              <div class="contact-form-section best">
                                 <input type="text" class="form-control" placeholder="Email*" id="email" name="email" required="">
                              </div>
                           </div>
                           <div class="col-md-6 col-sm-12">
                              <div class="contact-form-section best">
                                 <input type="text" class="form-control" placeholder="Phone*" id="phone" name="phone" required="">
                              </div>
                           </div>
                           <div class="col-md-6 col-sm-12">
                              <div class="contact-form-section best">
                                 <input type="text" class="form-control" placeholder="City*" id="city" name="city" required="">
                              </div>
                           </div>
                           <div class="col-md-12 col-sm-12">
                              <div class="contact-form-section best">
                                 <textarea class="form-control" id="message" name="message" required="" rows="4"
                                    placeholder="Message*" style="height: 100px;"></textarea>
                              </div>
                           </div>
                           <div class="col-md-12 col-sm-12 pb-4">
                               <div class="href-center">
                              <button type="submit" id="btn-submit">
                                 Send
                              </button>
                              </div>
                           </div>
                        </div>
                     </form>
                     <div id="status"></div>
                  </div>
               </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ps-footer--bottom">
                    <div class="row">
                        <div class="col-12 d-flex">
                            <p class="ml-auto mr-auto">© Copyright 2023. All Rights reserved Pharma Franchise Mart - B2B Marketplace</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- main-footer end -->





    </div>


  </div>
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



$("#btn-enqsubmit").on('click',function(e){

//function tohandlemodal() {
	console.log('ddd');
	if($('#frmenqrform')[0].checkValidity()) {
		console.log('eee');

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
		e.preventDefault();
	}



//}
});

</script>

		<script>











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



</body>

</html>
