<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">{{ $packageUsersChart->title }}</h3>
		
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body chart-responsive">
		<div class="chart" id="barChartPosts" style="height: 300px;"></div>
	</div>
</div>

@push('dashboard_styles')
@endpush

@push('dashboard_scripts')
    <script>
        $(function () {
            "use strict";
        
            // ADS STATS
            var area = new Morris.Bar({
                element: 'barChartPosts',
                resize: true,
                data: {!! $packageUsersChart->data !!},
                xkey: 'y',
                ykeys: ['users_count'],
                labels: ['Users'],
                lineColors: ['#3c8dbc', '#a0d0e0'],
                hideHover: 'auto',
                parseTime: false
            });
        });
    </script>
@endpush
