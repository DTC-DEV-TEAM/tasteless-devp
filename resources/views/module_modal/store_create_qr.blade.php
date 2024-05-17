<style>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>

@if(session('success'))
<div class="modal-store">
    <div class="modal-box">
        <div class="modal-header">Status</div>
        <div class='modal-content'>
            <span style="font-weight: 500;">QR Link: </span><a id="url" href="" target="_blank"></a>
        </div>
        <div class="modal-box-footer">
            <span style="font-size: 14px; color: green; font-weight: 600; margin: 5px 5px 5px 0">Your request has been succesfully submitted.</span>
            <button class="btn" id="modal-close" type="submit">Close</button>
        </div>
    </div>
</div>
@endif

<div class="store-modal-bg-dark">
    <div class="store-modal">
        <form method="POST" action="{{ route('create_egc') }}"  id="gc-form">
            {{ csrf_field() }}
            <div class="store-modal-header">Create Gift Card</div>
            <br>
            <div class="store-modal-content">
                <label for="">EGC Value</label>
                <div>
                    <select class="form-control" name="egc_value" id="egc-value" required></select>
                </div>
                <br>
                <label for="">Invoice Number</label>
                <div>
                    <input class="form-control" name="invoice_number" type="number" min="0" required>
                </div>
                <div class="btn-content">
                    <button class="btn btn-default pull-left" id="btn-cancel" type="button">Cancel</button>
                    <button class="btn btn-primary pull-right" id="btn-submit" type="submit">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>

    egcValue();
    
    async function egcValue(){
        const response = await fetch("{{ route('egc_value') }}");
        const data = await response.json();

        $('#egc-value').append(`<option value="" selected disabled>Please select EGC Value</option>`)

        data.egc_value.forEach((value, index) => {
            $('#egc-value').append(`<option value="${value.id}">${value.name}</option>`)
        });
    }

    
    $('#btn-cancel').on('click', function(){
        $('.store-modal-bg-dark').hide();
        $('.store-modal').hide();
        $('body').css('overflow', 'auto');
        $('input,select').val('');
    })

    $('#create-gc').on('click', function(event){
        event.preventDefault();

        $('body').css('overflow', 'hidden');
        $('.store-modal-bg-dark').show();
        $('.store-modal').show();

    })

    if ("{{ session('success') }}"){
        $('.modal-store').css('visibility', 'visible');
        $('body').css('overflow', 'hidden');
        
        // Set the initial countdown value
        var countdownSeconds = 1;

        // $('#url').text('Redirecting in ' + countdownSeconds + ' seconds');
        $('#url').text('{{ session('success') }}');
        $('#url').attr('href', '{{ session("success") }}')

        var countdownInterval = setInterval(function () {
            countdownSeconds--;

            // $('#url').text('Redirecting in ' + countdownSeconds + ' seconds');

            if (countdownSeconds === 0) {
                clearInterval(countdownInterval);
                window.open("{{ session('success') }}");
            }
        }, 1000);


        $('#modal-close').on('click', function(){
            $('.modal-store').css('display', 'none');
            $('body').css('overflow', 'auto');
        })

    }

    $('#gc-form').submit(function(event){
        event.preventDefault();
        const btnSubmit = $('#btn-submit').text();

        const form = this;

        swal({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: `Yes, ${btnSubmit} it!`,
            reverseButtons: true
            }, function(){
                $('#btn-submit').attr('disabled', true);
                form.submit();
            })
    });
</script>