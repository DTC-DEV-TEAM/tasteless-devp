<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.min.js"></script>
@endpush

@section('content')
    <!-- Your html goes here -->
    <p><a title='Return' href='{{ CRUDBooster::mainpath() }}'><i class='fa fa-chevron-circle-left '></i>&nbsp; Back To Redeem QR Home</a></p>
    <div class='panel panel-default'>
        <div class='panel-heading'>Show Results</div>
        <div class='panel-body panel_body_section'>
            <div class="camera">
                <div class="camera_card">
                    <div id="output"></div>
                    <video id="video" autoplay></video>
                    <canvas id="canvas" style="display: none;"></canvas>
                </div>
            </div>
            <div class="qr_code">
                <div class="qr_code_content">
                    <div class="qr_code_box">
                        <p>Once you have scanned the QR code</p>
                        <p>Please wait for the code link to load</p>
                        <p>{{ $scannedData }}</p>
                        <span></span>
                    </div>
                    <div class="show_scanner_content">
                        <button class="show_qr_code_scanner" type="button">Show QR Code Scanner</button>
                    </div>
                </div>
                <div class="qr_code_img">
                    <img class="scan_qr_img" src="{{ asset('img/scan_qr.jpg') }}" alt="">
                </div>
            </div>
        </div>
    </div>

    <script>
        
        $(document).ready(function(){

            $('body').addClass('sidebar-collapse');

            $('.show_qr_code_scanner').click(function(){
                $('.camera').show(function(){
                    qrScanner();
                });
            });
            
            function qrScanner(){
                
                const video = document.getElementById('video');
                const canvas = document.getElementById('canvas');
                const output = document.getElementById('output');
                // const constraints = { video: true };
                let constraints = { video: { facingMode: { exact: 'user' } } };

                if (navigator.userAgent.includes('Mobile')) {
                    constraints = { video: { facingMode: { exact: 'environment' } } };
                }
                
                navigator.mediaDevices.getUserMedia(constraints)
                    .then((stream) => {
                        video.srcObject = stream;
                    })
                    .catch((error) => {
                        console.error('Error accessing camera:', error);
                    });
        
                function scanQRCode() {
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    const context = canvas.getContext('2d');
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height);
                    if (code) {
                        output.innerText = 'Scanned QR code: '+"{{ CRUDBooster::adminPath() }}" + code.data;
                        window.location.href = "{{ CRUDBooster::adminPath() }}"+code.data;
                        return
                    } else {
                        output.innerText = 'No QR code detected.';
                    }
                    requestAnimationFrame(scanQRCode);
                }
        
                video.addEventListener('loadeddata', () => {
                    scanQRCode();
                });
            }
        })

    </script>
      
@endsection