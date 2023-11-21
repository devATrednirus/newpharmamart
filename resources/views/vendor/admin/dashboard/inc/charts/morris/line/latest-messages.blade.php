<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">{{ $latestMessageChart->title }}</h3>
		
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body chart-responsive">
		<div class="chart" id="lineChartMessages" style="height: 300px;"></div>
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
                element: 'lineChartMessages',
                resize: true,
                data: {!! $latestMessageChart->data !!},
                xkey: 'y',
                ykeys: ['company','post', 'shared'],
                labels: ['Company','Post', 'Shared'],
                lineColors: ['#dd4b39', '#4da74d','#3c8dbc'],
                hideHover: 'auto',
                parseTime: false
            });
        });
    </script>
@endpush
