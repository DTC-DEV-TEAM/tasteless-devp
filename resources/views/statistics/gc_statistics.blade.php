<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

<style>
  .panel_body_stat_section{
    /* height: 100%; */
    position: relative;
    padding: 20px;
  }
  .section-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(23rem, 1fr)); /* Responsive grid */
    grid-template-rows: 150px;
    grid-gap: 15px;
}
.panel-section-chart{
	grid-column: 1/-1;
	border-radius: 5px;
  box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
}
.section-content{

}
.section-content,
.section-title,
.panel-section{
	width: 100%;
}

.section-title{
    color: rgb(255, 255, 255);
    padding: 10px;
		text-align: center;
		font-weight: 600;
		height: 55px;

}
.section-content-date{
	text-indent: 20px;
	padding: 10px 0px;
}
.section-display-text{
	font-size: 28px;
	text-align: center;
	padding-bottom: 10px;
	font-weight: 700;
}
.title1{
	background-color:#A4303F ;
}
.content1{
	border-bottom: 6px solid #A4303F;
	border-radius: 0px 0px 5px 5px;
  box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
}
.text1{
	color: #A4303F;
}
.title2{
	background-color:#3060a4 ;
}
.content2{
	border-bottom: 6px solid #3060a4;
	border-radius: 0px 0px 5px 5px;
  box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
}
.text2{
	color:#3060a4;
}
.title3{
	background-color:#3F826D ;
}
.content3{
	border-bottom: 6px solid #3F826D;
	border-radius: 0px 0px 5px 5px;
  box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
}
.text3{
	color: #3F826D;
}
.title4{
	background-color:#07020D ;
}
.content4{
	border-bottom: 6px solid #07020D;
	border-radius: 0px 0px 5px 5px;
  box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
}
.text4{
	color: #07020D;
}
.title5{
	background-color:#A4303F ;
}
.content5{
	border-bottom: 6px solid #A4303F;
	border-radius: 0px 0px 5px 5px;
  box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
}
.text5{
	color: #A4303F;
}
.title6{
	background-color:#3060a4 ;
}
.content6{
	border-bottom: 6px solid #3060a4;
	border-radius: 0px 0px 5px 5px;
  box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
}
.text6{
	color: #3060a4;
}
.title7{
	background-color:#3F826D ;
}
.content7{
	border-bottom: 6px solid #3F826D;
	border-radius: 0px 0px 5px 5px;
  box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
}
.text7{
	color: #3F826D;
}
.title8{
	background-color:#605CA8 ;
}
.content8{
	border-bottom: 6px solid #605CA8;
	border-radius: 0px 0px 5px 5px;
  box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
}
.text8{
	color: #605CA8;
}

</style>
@section('content')
    <!-- Your html goes here -->
    {{-- <p><a title='Return' href='{{ CRUDBooster::mainpath() }}'><i class='fa fa-chevron-circle-left '></i>&nbsp; Back To Redeem QR Home</a></p> --}}
    <div class='panel panel-default'>
        <div class='panel_body_stat_section section-grid'>
          <div class="panel-section">
              <div class="section-title title1">
                  <span>Highest Store Sales-Claim</span>
              </div>
              <div class="section-content content1">
                <div class="section-content-date"><span><strong>Date:</strong> 2024-01-30</span></div>
                <div class="section-display-text text1"><span>546,323</span></div>
              </div>
          </div>
          <div class="panel-section">
            <div class="section-title title2">
                <span>Per Store Redemption</span>
            </div>
            <div class="section-content content2">
              <div class="section-content-date"><span><strong>Date:</strong> 2024-01-30</span></div>
							<div class="section-display-text text2"><span>546,323</span></div>
            </div>
          </div>
          <div class="panel-section">
            <div class="section-title title3">
                <span>Per Store Claiming</span>
            </div>
            <div class="section-content content3">
              <div class="section-content-date"><span><strong>Date:</strong> 2024-01-30</span></div>
							<div class="section-display-text text3"><span>546,323</span></div>
            </div>
          </div>
          <div class="panel-section">
            <div class="section-title title4 ">
                <span>Total GCs Sold Per Concept Value</span>
            </div>
            <div class="section-content content4">
              <div class="section-content-date"><span><strong>Date:</strong> 2024-01-30</span></div>
							<div class="section-display-text text4"><span>546,323</span></div>
            </div>
          </div>
					<div class="panel-section">
            <div class="section-title title5 ">
                <span>Status Tabulation</span>
            </div>
            <div class="section-content content5">
              <div class="section-content-date"><span><strong>Date:</strong> 2024-01-30</span></div>
							<div class="section-display-text text5"><span>546,323</span></div>
            </div>
          </div>
          <div class="panel-section">
            <div class="section-title title6">
                <span>Total GCs Redeemed Per Concept Value</span>
            </div>
            <div class="section-content content6">
              <div class="section-content-date"><span><strong>Date:</strong> 2024-01-30</span></div>
							<div class="section-display-text text6"><span>546,323</span></div>
            </div>
          </div>
          <div class="panel-section">
            <div class="section-title title7">
                <span>Average Duration From Sold Date To Redeemed Date</span>
            </div>
            <div class="section-content content7">
              <div class="section-content-date "><span><strong>Date:</strong> 2024-01-30</span></div>
              <div class="section-display-text text7"><span>546,323</span></div>
            </div>
          </div>
					<div class="panel-section-chart">
            <div class="section-title title8">
                <span>Chart Statistics - Sales And Claim</span>
            </div>
            <div class="section-content content8">
              <div class="section-content-date text8"><span><strong>Date:</strong> 2024-01-30</span></div>
							<div>
								<canvas id="myChart"></canvas>
							</div>
            </div>
          </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
					
					const ctx = $('#myChart');

					const generateMonthLabels = () => {
            const labels = [];
            const currentDate = new Date();
            for (let i = 6; i >= 0; i--) {
                const month = currentDate.getMonth() - i;
                const year = currentDate.getFullYear();
                labels.push(`${year}-${(month + 1).toString().padStart(2, '0')}`);
            }
            return labels;
        };
        const labels = generateMonthLabels();
        const data = {
            labels: labels,
            datasets: [{
                label: 'My First Dataset',
                data: [65, 59, 80, 81, 56, 55, 40],
                fill: false,
								borderColor: '#555191',
								backgroundColor: '#7772cb',
                tension: 0.1
            }]
        };
        const config = {
            type: 'line',
            data: data,
        };

        new Chart(ctx, config);

        })

    </script>
    
@endsection