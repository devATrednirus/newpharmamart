<style>
 .title_text {
    font-size: 41px!important;
}
.title_text{
	  font-size: 31px!important;
  }

.com-breadcrumbs{margin-top:100px!important;}
.para{font-size: 14px;
    color: #fff;}

  </style>

<div class="com-breadcrumbs">
		        <div class="container-fluid">
		            <nav aria-label="breadcrumb" role="navigation">
		                <ol class="breadcrumb">
		                    <li class="breadcrumb-item"><a href="{{ lurl($company_url) }}">Home</a></li>
		                    <li class="breadcrumb-item">About</li>
		                </ol>
		            </nav>
		        </div>
		    </div>

<section class="feature_section sec_ptb_100 clearfix">
				<div class="container maxw_1480">

					<div class="ss_section_title text-center mb_30">
						<h3 class="title_text">Welcome To <span style="color:#bf2626">{{$sUser->name}}</span></h3>
					</div>
	<div class="row  mt-5 mb-5 text-center" style="background-color: #000000ed;">
		<div class="col-md-3 col-sm-3">
			<i class="fa fa-medkit  text-center circle-box"></i>
			<h5 style="color: #c31919;">Nature of Business</h5>
			<p class="para">@if(!empty($sUser->businessType))   {{$sUser->businessType->name}} @endif</p>
		</div>
		<div class="col-md-3 col-sm-3">
			<i class="fa fa-users text-center circle-box"></i>
			<h5 style="color: #c31919;">Ownership Type</h5>
			<p class="para">@if(!empty($sUser->ownershipType)) {{$sUser->ownershipType->name}} @endif</p>
		</div>
		<div class="col-md-3 col-sm-3">
			<i class="fa fa-user text-center circle-box"></i>
			<h5 style="color: #c31919;">Company CEO</h5>
			<p class="para">{{$sUser->ceo_first_name}} {{$sUser->ceo_last_name}}</p>
		</div>
		<div class="col-md-3 col-sm-3">
			<i class="fa fa-map text-center circle-box"></i>
			<h5 style="color: #c31919;">Registered Address</h5>
			 <p class="para">{{@$sUser->address1}}, {{@$sUser->address2}}, {{@$sUser->city->name}} {{(@$sUser->city->subAdmin1 && @$sUser->city->name!=@$sUser->city->subAdmin1->name)?@$sUser->city->subAdmin1->name:''}}  {{@$sUser->pincode}}</p>
			</div>

				<div class="col-md-3 col-sm-3">
			<i class="fa fa-users text-center circle-box"></i>
			<h5 style="color: #c31919;">Total Number of Employees	</h5>
			 <p class="para">{{@$sUser->no_employees}}</p>
			</div>

		<div class="col-md-3 col-sm-3">
			<i class="fa fa-inr text-center circle-box"></i>
			<h5 style="color: #c31919;">Annual Turnover</h5>
			 <p class="para">{{@$sUser->annual_turnover}}</p>
			</div>
		</div>

		<div class="row p-3 seller-about-content">

		<?php echo nl2br($sUser->about_us);
 		?>
			<h3 style="width:100%">Why Us</h3>
			<div style="width:100%">
			<?php echo nl2br($sUser->why_us); ?>

			</div>
			<div style="clear:both"></div>
			@if($sUser->our_product!='')
			<h3 style="width:100%">Vision and Mission</h3>
			<div><?php echo nl2br($sUser->our_product);?></div>
			@endif
			 </div>

	</div>
</section>
