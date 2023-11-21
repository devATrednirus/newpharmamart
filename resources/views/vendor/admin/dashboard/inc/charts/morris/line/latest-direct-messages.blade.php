<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">{{ $latestDirectMessageChart->title }}</h3>
		
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body chart-responsive">
		<div class="chart" id="lineChartDirectMessages" style="height: 300px;"></div>
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
                element: 'lineChartDirectMessages',
                resize: true,
                data: {!! $latestDirectMessageChart->data !!},
                xkey: 'y',
                ykeys: ['pedning','submitted', 'shared'],
                labels: ['Pedning','Submitted', 'Shared'],
                lineColors: ['#dd4b39', '#4da74d','#3c8dbc'],
                hideHover: 'auto',
                parseTime: false
            });
        });
    </script>
@endpush
