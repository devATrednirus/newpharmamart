

<div class="container">

			<div class="row"> 
		        <div class="col-md-12 company-bread"> 
		            <nav aria-label="breadcrumb" role="navigation" class="pull-left"> 
		                <ol class="breadcrumb"> 
		                    <li class="breadcrumb-item"><a href="{{ lurl($company_url) }}">Home</a></li> 
		                    <li class="breadcrumb-item">About Us</li> 
		                </ol>
		            </nav>
		        </div>
		    </div>

			<div class="row">
				<div class="col-md-8 company-about">

					<h2>{{$sUser->name}}</h2>
					{!! transformDescription($sUser->about_us) !!}

				</div>
				
				<div class="col-md-4 company-infomation">
						 	<h2>Basic Information</h2>
							<table class="table table-striped">

							    <tbody>
							    	@if($sUser->businessType)
								    <tr>
								        <td>Nature of Business</td>
								        <td>{{$sUser->businessType->name}}</td>
								    </tr> 
								    @endif
								    @if($sUser->ownershipType)
								    <tr>
								        <td>Ownership Type</td>
								        <td>{{$sUser->ownershipType->name}}</td>
								    </tr> 
								    @endif
								    @if($sUser->ceo_first_name)
								    <tr>
								        <td>Company CEO</td>
								        <td>{{$sUser->ceo_first_name}} {{$sUser->ceo_last_name}}</td>
								    </tr> 
								    @endif
								    @if($sUser->city)
								    <tr>
								        <td>Registered Address</td>
								        <td>{{$sUser->address1}}, {{$sUser->address2}}, {{$sUser->city->name}} {{($sUser->city->subAdmin1 && $sUser->city->name!=$sUser->city->subAdmin1->name)?$sUser->city->subAdmin1->name:''}}  {{$sUser->pincode}}</td>
								    </tr> 
								    @endif
								   
								    @if($sUser->no_employees)
								    <tr>
								        <td>Total Number of Employees</td>
								        <td>{{$sUser->no_employees}}</td>
								    </tr> 
								    @endif
								    @if($sUser->establishment_year)
								    <tr>
								        <td>Year of Establishment</td>
								        <td>{{$sUser->establishment_year}}</td>
								    </tr> 
								    @endif
								    {{-- @if($sUser->business_type)
								    <tr>
								        <td>Legal Status of Firm</td>
								        <td>{{$sUser->business_type}}</td>
								    </tr> 
								    @endif --}}

								    @if($sUser->business_type)
								    <tr>
								        <td>Nature of Business</td>
								        <td>{{$sUser->businessType->name}}</td>
								    </tr> 
								    @endif

								   

								    @if($sUser->annual_turnover)
								    <tr>
								        <td>Annual Turnover</td>
								        <td>{{$sUser->annual_turnover}}</td>
								    </tr> 
								    @endif

							    </tbody>
						  </table>
						  @if($sUser->bank_name)
						  <h2>Statutory Profile</h2>
							<table class="table table-striped">

							    <tbody>
							    	@if($sUser->bank_name)
								    <tr>
								        <td>Bank Name</td>
								        <td>{{$sUser->bank_name}}</td>
								    </tr> 
								    @endif
								    @if($sUser->gstin)
								    <tr>
								        <td>GSTIN</td>
								        <td>{{$sUser->gstin}}</td>
								    </tr> 
								    @endif

								    
								    @if($sUser->tan_no)
								    <tr>
								        <td>TAN No.</td>
								        <td>{{$sUser->tan_no}}</td>
								    </tr> 
								    @endif
								    @if($sUser->pan_no)
								    <tr>
								        <td>PAN No.</td>
								        <td>{{$sUser->pan_no}}</td>
								    </tr> 
								    @endif
								    @if($sUser->cin_no)
								    <tr>
								        <td>CIN No.</td>
								        <td>{{$sUser->cin_no}}</td>
								    </tr> 
								    @endif
								    @if($sUser->dgft_no)
								    <tr>
								        <td>DGFT/IE Code</td>
								        <td>{{$sUser->dgft_no}}</td>
								    </tr> 
								    @endif 
							    </tbody>
						  </table>
						  @endif
					</div>
				
				
			</div>
			
				<div class="row">
					
				</div>

				

			 

			@if($sUser->why_us!="")
			<div class="row">
				<div class="col-md-12 why-us">
					<h2>Why Us?</h2>

					{!! transformDescription($sUser->why_us) !!}

				</div>
			</div>
			@endif

			@if($sUser->our_product!="")
			<div class="row">
				<div class="col-md-12 why-us">
					<h2>Mission / Vision</h2>

					{!! transformDescription($sUser->our_product) !!}

				</div>
			</div>
			@endif

			


		
</div>
 