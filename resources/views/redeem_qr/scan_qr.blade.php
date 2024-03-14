<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.min.js"></script>
@endpush

@section('content')
<style>
    #redemption-settings:hover{
        opacity: 0.7;
    }

    .modal-box-bg {
        background-image: url('{{ asset('utilities_img/redeem-bg.png') }}');
        background-size: cover;
        /* background-color: red !important; */
    }

</style>

    <!-- Your html goes here -->
    {{-- <p><a title='Return' href='{{ CRUDBooster::mainpath() }}'><i class='fa fa-chevron-circle-left '></i>&nbsp; Back To Redeem QR Home</a></p> --}}
    {{-- <div class='panel panel-default'> --}}
        {{-- <div class='panel-heading'>Redemption Code</div> --}}
        {{-- <div class='panel-body panel_body_section'> --}}
            {{-- <div class="camera">
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
            </div> --}}
        {{-- </div> --}}
    {{-- </div> --}}

    <div class="modal-box modal-box-bg u-bg-white" style="box-shadow: none; display: grid; place-content: center; box-shadow: 0 4px 6px -1px rgba(149, 160, 176, 0.3), 0 2px 4px -2px rgb(0 0 0 / 0.1);">
        <div class="u-t-center">
            <h1>Redemption Code</h1>
        </div>
        {{-- <div class="camera"> --}}
        <div class="camera_card u-box-shadow-default" style="display: none;">
            <video id="video" autoplay></video>
            <canvas id="canvas" style="display: none;"></canvas>
            <div class="u-mt-10" id="output"></div>
        </div>
        {{-- </div> --}}
        <div class="show_scanner_content u-mt-10 u-mb-16" style="display: grid; place-content: center;">
            <div style="display: flex">
                {{-- <button class="show_qr_code_scanner close-scanner u-t-gray u-bg-default u-mr-10" style="display: none" type="button">Close</button> --}}
                {{-- <button class="show_qr_code_scanner open-scanner u-bg-primary" type="button" style="box-shadow: 0 4px 6px -1px rgba(7, 88, 201, 0.3), 0 2px 4px -2px rgb(0 0 0 / 0.1);">Show QR Code Scanner</button> --}}
            </div>
        </div>
        
        <form action="{{ route('get_bdo') }}" method="post">
            @csrf
            <table class="custom_normal_table u-mb-16">
                    <tbody>
                        <tr>
                            <td><input type="text" id="bdo_code" name="bdo_code" class="u-input" style="text-align: center;" placeholder="Enter gift code"></td>
                        </tr>
                    </tbody>
            </table>
        </form>
    </div>

    <br>

    <script>
        // $('body').addClass('sidebar-collapse');
        $(document).ready(function(){

            $('#redemption-settings').click(function() {
                $('#settings-toggle').toggle();
            })

            $('#rct-btn').click(function() {
                if ($('#redemption_id').val() == 2){
                    $('.open-scanner').hide();
                    $('#bdo_code').show();
                }else{
                    $('.open-scanner').show();
                    $('#bdo_code').hide();
                }
            })

            $('.open-scanner').click(function(){
                $('.close-scanner').show();
                $('.camera_card').show(function(){
                    qrScanner();
                });
            });

            $('.close-scanner').show();
            $('.camera_card').show(function(){
                qrScanner();
            });

            $('.close-scanner').click(function() {
                $(this).hide();
                $('.camera_card').hide(function() {
                    // Instead of getUserMedia, use the stop() method on the existing stream
                    if (video.srcObject) {
                    video.srcObject.getTracks().forEach(track => track.stop());
                    }
                });
            });
            
            function qrScanner(){
                
                const video = document.getElementById('video');
                const canvas = document.getElementById('canvas');
                const output = document.getElementById('output');
                // const constraints = { video: true };
                let constraints = { video: true };

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

            function stopMediaStream() {
                if (mediaStream) {
                    const tracks = mediaStream.getTracks();
                    tracks.forEach(track => track.stop());
                    mediaStream = null;
                }
            }
        })

    </script>
    
@endsection