<div class="box-primary">
	<div class="box-header with-border">
        <div class="row">
		<div class="col-lg-9">
                    <a href="/account/conversations?index={{$date_index+1}}" class="btn btn-success">Previous</a> <strong>{{$date_range}}</strong> 

                    @if($date_index>0)
                    <a href="/account/conversations?index={{$date_index-1}}" class="btn btn-success">Next</a>
                    @endif
        </div>
        <div class="col-lg-3">
            <div class="pull-right">


                  
                

                Total {{$latestMessageChart->total}} 
                @if($user->subscription)
                / 
                    @if($user->subscription->monthly_leads > 0)
                        

                        {{$user->subscription->monthly_leads}}
                    @else
                        {{$user->subscription->package->monthly_leads}}
                    @endif
                
                @endif

                 @if(app('impersonate')->isImpersonating() && app('impersonate')->getImpersonatorId()=="1" )
                <a href="/account/conversations?index={{$date_index}}&action=download" class="btn btn-success">Export</a>
                @endif
            </div>
        </div>
		
		 </div>
	</div>
	<div class="box-body chart-responsive">
		<div class="chart" id="lineChartMessages" style="height: 300px;"></div>
	</div>
</div>

@section('after_styles')
    @parent
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/plugins/morris/0.5.1/morris.css">
@endsection


 
@section('after_scripts')
    @parent
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
   <script src="{{ asset('vendor/adminlte') }}/plugins/morris/morris.min.js"></script>
    <script>
        $(function () {
            "use strict";
            var line = new Morris.Line({
                element: 'lineChartMessages',
                resize: true,
                data: {!! $latestMessageChart->data !!},
                xkey: 'y',
                ykeys: ['message','buy'],
                labels: ['Queries','Buy Leads'],
                lineColors: ['#3c8dbc', '#f39c12'],
                hideHover: 'auto',
                parseTime: false
            });
        });
    </script>
@endsection

