

<div class="container">

			<div class="row"> 
		        <div class="col-md-12"> 
		            <nav aria-label="breadcrumb" role="navigation" class="pull-left"> 
		                <ol class="breadcrumb"> 
		                    <li class="breadcrumb-item"><a href="{{ lurl($company_url) }}">Home</a></li> 
		                    <li class="breadcrumb-item">Contact Us</li> 
		                </ol>
		            </nav>
		        </div>
		    </div>
		    <div class="row">
				<div class="col-md-12 contact-topinfo">

					<h2 >{{$sUser->name}}</h2>

					 
 
				</div>
			</div>
		    @if($sUser->city)
			<div class="row">
				<div class="col-md-12 contact-add">
					{{$sUser->address1}}, {{$sUser->address2}}, {{$sUser->city->name}} {{($sUser->city->subAdmin1 && $sUser->city->name!=$sUser->city->subAdmin1->name)?$sUser->city->subAdmin1->name:''}}  {{$sUser->pincode}}
				</div>
			</div>
			@endif

			<div class="row">
				<div class="col-md-12 big-btn-c">
 
					<a class="btn btn-default send_company send_message" data-id="{{$sUser->id}}" data-toggle="modal" href="{{ $contactSellerURL }}"><i class="icon-mail-2"></i> Submit Query </a>

				</div>
			</div>
			


		
</div>
 