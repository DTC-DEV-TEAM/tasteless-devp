<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

<style>
    .panel_body_stat_section {
        /* height: 100%; */
        position: relative;
        padding: 20px;
    }

    .section-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(30rem, 1fr));
        /* Responsive grid */
        grid-gap: 15px;
    }

    .panel-section-chart {
        grid-column: 1/-1;
        border-radius: 5px;
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    }

    .section-content {
        height: 100px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .section-content,
    .section-title,
    .panel-section {
        width: 100%;
    }

	.panel-section{
		box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
		border-bottom-left-radius: 5px;
		border-bottom-right-radius: 5px;
	}

    .section-title-content {
        position: absolute;
        left: 1rem;
        top: 1rem;
        color: #6a6a6a;
        font-weight: bold;
    }

	.section-img{
		position: absolute;
		left: 2rem;
	}

	.section-img img{
		object-fit: contain;
		width: 50px;
	}

    .section-title {
        color: rgb(255, 255, 255);
        padding: 10px;
        font-weight: 600;
        height: 55px;
        display: grid;
        place-items: center;

    }

    .section-content-date {
        text-indent: 20px;
        padding: 10px 0px;
    }

    .section-display-text {
        font-size: 28px;
        text-align: center;
        font-weight: 700;
    }

    .title1 {
        background-color: #605CA8;
    }

    .content1 {
        border-bottom: 6px solid #605CA8;
        border-radius: 0px 0px 5px 5px;
    }

    .title2 {
        background-color: #347dcb;
    }

    .content2 {
        border-bottom: 6px solid #347dcb;
        border-radius: 0px 0px 5px 5px;
    }

    .title3 {
        background-color: #222D32;
    }

    .content3 {
        border-bottom: 6px solid #222D32;
        border-radius: 0px 0px 5px 5px;
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    }

    .title4 {
        background-color: #c0392b;
    }

    .content4 {
        border-bottom: 6px solid #c0392b;
        border-radius: 0px 0px 5px 5px;
    }

    .title5 {
        background-color: #A4303F;
    }

    .content5 {
        border-bottom: 6px solid #A4303F;
        border-radius: 0px 0px 5px 5px;
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    }

    .title6 {
        background-color: #3060a4;
    }

    .content6 {
        border-bottom: 6px solid #3060a4;
        border-radius: 0px 0px 5px 5px;
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    }

    .title7 {
        background-color: #3F826D;
    }

    .content7 {
        border-bottom: 6px solid #3F826D;
        border-radius: 0px 0px 5px 5px;
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    }

    .title8 {
        background-color: #605CA8;
    }

    .content8 {
        border-bottom: 6px solid #605CA8;
        border-radius: 0px 0px 5px 5px;
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    }
	
    .chart-box {
        margin: 20px;
        border-radius: 5px;
        padding: 20px;
        border: 1px solid #ddd;
        box-shadow: rgba(9, 30, 66, 0.25) 0px 4px 8px -2px, rgba(9, 30, 66, 0.08) 0px 0px 0px 1px;
		position: relative; 
		height: 250px; 
		width: 100%; 
		min-width: 200px;
    }

    .chart-box-1 {
        display: flex;
    }

	@media (max-width: 650px) {
		.chart-box-1 {
			flex-wrap: wrap;
		}
	}

</style>
@section('content')
    <!-- Your html goes here -->
    {{-- <p><a title='Return' href='{{ CRUDBooster::mainpath() }}'><i class='fa fa-chevron-circle-left '></i>&nbsp; Back To Redeem QR Home</a></p> --}}
    <div class='panel panel-default'>
        <div class='panel_body_stat_section section-grid'>
            <div class="panel-section">
                <div class="section-title title1">
                    <span>Total EGC Sent</span>
                </div>
                <div class="section-content content1">
					<div class="section-img"><img src="{{ asset('utilities_img/invoice.png') }}" alt=""></div>
                    <div class="section-display-text text3"><span>{{ $total }}</span></div>
                </div>
            </div>
            <div class="panel-section">
                <div class="section-title title2">
                    <span>Claimed</span>
                </div>
                <div class="section-content content2">
					<div class="section-img"><img src="{{ asset('utilities_img/task.png') }}" alt=""></div>
                    <div class="section-display-text text2"><span>{{ $claimed }}</span></div>
                </div>
            </div>
            <div class="panel-section">
                <div class="section-title title3">
                    <span>UnClaimed</span>
                </div>
                <div class="section-content content3">
					<div class="section-img"><img src="{{ asset('utilities_img/last-will.png') }}" alt=""></div>
                    <div class="section-display-text text3"><span>{{ $unclaimed }}</span></div>
                </div>
            </div>
			<div class="panel-section">
                <div class="section-title title4">
                    <span>Cancelled</span>
                </div>
                <div class="section-content content4">
					<div class="section-img"><img src="{{ asset('utilities_img/bucket-list.png') }}" alt=""></div>
                    <div class="section-display-text text4"><span>{{ $cancelled }}</span></div>
                </div>
            </div>
            <div class="panel-section">
                <div class="section-title title1">
                    <span>Highest Store Sales</span>
                </div>
                <div class="section-content content1">
                    <div class="section-title-content"><span>Store: {{ $highest_store_sales['store_name'] }}</div>
                    <div class="section-display-text text1"><span>{{ '₱' . $highest_store_sales['value'] }}</span></div>
                </div>
            </div>
			<div class="panel-section">
                <div class="section-title title2">
                    <span>Total Sold EGC</span>
                </div>
                <div class="section-content content2">
                    <div class="section-display-text text2"><span>{{ '₱' . $total_sold_egc }}</span></div>
                </div>
            </div>
        </div>
        <div class="chart-box-1">
			<div class="chart-box">
				<canvas id="redeemed-per-concept"></canvas>
			</div>
            <div class="chart-box">
                <canvas id="sold-per-concept"></canvas>
            </div>
        </div>
		<div class="chart-box-1">
			<div class="chart-box">
				<canvas id="aging-chart"></canvas>
			</div>
		</div>
    </div>

    <script>
        $(document).ready(function() {

            const spc = {!! json_encode($sold_per_concepts) !!};
            const agingData = {!! json_encode($aging_chart) !!};
            
            soldPerConceptChart();
            redeemedPerConceptChart();
			agingChart();

            function redeemedPerConceptChart() {

                const ctx = $('#redeemed-per-concept');

                const data = {
                    labels: spc.map(e => e.concept),
                    datasets: [{
                        label: 'Total GCs redeemed per concept',
                        data: spc.map(e => e.redeemed),
                        fill: false,
                        borderColor: '#3060A4',
                        backgroundColor: '#3060A4',
                        tension: 0.1
                    }]
                };
                const config = {
                    type: 'line',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        context: '2d' // Disable anti-aliasing
                    }
                };

                new Chart(ctx, config);

            }

            function soldPerConceptChart() {

                const ctx = $('#sold-per-concept');

                const data = {
                    labels: spc.map(e => e.concept),
                    datasets: [{
                        label: 'Total GCs sold per concept',
                        data: spc.map(e => e.value),
                        fill: false,
                        borderColor: '#555191',
                        backgroundColor: '#555191',
                        tension: 0.1
                    }]
                };
                const config = {
                    type: 'line',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        context: '2d' // Disable anti-aliasing
                    }
                };

                new Chart(ctx, config);

            }

			function agingChart() {

				const ctx = $('#aging-chart');

				const data = {
					labels: agingData.map(e => e.concept),
					datasets: [{
						label: 'Average duration from sold date to redeemed date',
						data: agingData.map(e => e.average_duration),
						fill: false,
						borderColor: '#555191',
						backgroundColor: '#555191',
						tension: 0.1
					}]
				};
				const config = {
					type: 'line',
					data: data,
					options: {
						responsive: true,
						maintainAspectRatio: false,
						context: '2d' // Disable anti-aliasing
					}
				};

				new Chart(ctx, config);

			}

        });
    </script>
@endsection
