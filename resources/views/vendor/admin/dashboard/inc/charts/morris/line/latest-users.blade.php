<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">{{ $latestUsersChart->title }}</h3>
		
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body chart-responsive">
		<div class="chart" id="lineChartUsers" style="height: 300px;"></div>
	</div>
</div>

@push('dashboard_styles')
@endpush

@push('dashboard_scripts')
    <script>
        $(function () {
            "use strict";
			
            // USERS STATS
            var line = new Morris.Line({
                element: 'lineChartUsers',
                resize: true,
                data: {!! $latestUsersChart->data !!},
                xkey: 'y',
                ykeys: ['activated', 'unactivated','activated_sellers', 'unactivated_sellers'],
                labels: ['Active Buyers', 'Unactivated Buyers','Active Sellers', 'Unactivated Sellers'],
                lineColors: ['#4da74d', '#f39c12','#3c8dbc','#dd4b39'],
                hideHover: 'auto',
                parseTime: false
            });
        });
    </script>
@endpush
