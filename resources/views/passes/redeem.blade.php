@extends('layouts.app')
@section('head')
<style>
.modal-body p {
    margin-top:15px;
    margin-bottom:15px;
}
.pass-title {
    font-family: 'Oswald';
    font-weight: 300;
    line-height: 1.2;
    letter-spacing: -.6px;
    font-style: normal;
}
</style>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if(!is_null($passType->logo))
                <div class="pass-logo" style="text-align:center">
                    <img src="{{ asset('/images/'.$passType->logo) }}" width="125" height="143" alt="">
                </div>
            @endif
            <h2 style="text-align:center;margin:40px">Redeem - {{ $title }} ({{ $region->name }})</h2>
            <div class="row"> 
                <div class="col-md-12 pass-div">               
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                        @foreach ($vendors as $vendor)      
                        <li class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h5 class="card-title">
                                        <p class="pass-title">{{ strtoupper($vendor->name) }}</p>
                                    </h5>
                                    <p class="card-text">{{ strtoupper($vendor->redeem_txt) }}</p>   
                                </div>
                                <div class="col-4">
                                    <p> 
                                        <a href="#" class="btn {{ (!$vendor->getAvailablePass($passes)) ? 'btn-default disabled' : 'btn-warning' }}" style="{{ (!$vendor->getAvailablePass($passes)) ? 'background-color:#eee;color:#545B62' : '' }}" data-toggle="modal" data-target="#redeemModal" onclick="populateModalElements({{ $vendor->id }})">
                                            REDEEM
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <input type="hidden" name="vendorName{{ $vendor->id }}" id="vendorName{{ $vendor->id }}" value="{{ $vendor->name }}">
                            <input type="hidden" name="vendorDesc{{ $vendor->id }}" id="vendorDesc{{ $vendor->id }}" value="{{ $vendor->offer_desc }}">
                            <input type="hidden" name="vendorRedeemTxt{{ $vendor->id }}" id="vendorRedeemTxt{{ $vendor->id }}" value="{{ $vendor->redeem_txt }}">
                            <?php 
                                $vendorLocations = [];
                                foreach ($vendor->locations as $location)
                                    $vendorLocations[$location->id] = $location->name;
                            ?>
                            {!! Form::select('vendorLocations'.$vendor->id, $vendorLocations, null, ['class' => 'form-control', 'id' => 'vendorLocations'.$vendor->id, 'style' => 'display:none']) !!}
                            <!-- Form::hidden('vendorPassQuantityMax'.$vendor->id, $vendor->getMaxPassUsesForUser, ['id' => 'vendorPassQuantityMax'.$vendor->id]) -->
                            <!-- show selection for vendor locations -->
                            <!-- <p class="card-text">This is a card with inverse color and background is set as black.</p> -->
                        </li>
                        @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>            
    </div>
</div>

@include('passes.redeem_modal')


<script>
// $('#redeemModal').on('shown.bs.modal', function (e) {
//   console.log(e);
// });
// $('.vendor-card').click( function (event) {
//     $('#vendorRedeemTitle').html($(event.target).text());
//     $('#vendorOfferTxt').html(event.target.children[1].value);
//     //get the locations associated with the vendor and populate select
//     //console.log(event.target.children[1].value);
// });


$(document).ready(function() {
  $('#redeemForm').on('submit', function(e) {
    e.preventDefault();
    $('#redeemSubmit').button('loading');
    $('.close').hide();
  });
});

$(document).ready(function() {
    $('#redeemForm').submit( function(e) {
        e.preventDefault();
        $('#redeemSubmit').button('loading');
        $('.close').hide();
        $.ajax({
            type:'POST',
            url:"{{ $redeemRoute }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "redeemVendorLocationId": $('#redeemVendorLocationId').val(),
                "redeemVendorQuantity": $('#redeemVendorQuantity').val(),
                "redeemCode": $('#redeemCode').val(),
                "redeemVendorId": $('#redeemVendorId').val(),
            },
            success:function(data) {
                $('#redeemSubmit').hide();
                $('#errorMsg').hide();
                $('#resultMsg').show();
                $('#resultTxt').html('Reedemed!');
                setTimeout(function(){ 
                    window.location.href = "{{ route('home') }}";
                }, 3000);                
            },
            error:function(data) {
                console.log(data);
                $('#errorMsg').show();
                if (data.responseJSON == undefined || data.responseJSON['error'] == undefined)
                    $('#errorTxt').html('<i class="fa fa-exclamation-circle" aria-hidden="true"></i> Your session may have expired and we are unable to process the request. Please reload the page and try again.');                    
                else
                    $('#errorTxt').html('<i class="fa fa-exclamation-circle" aria-hidden="true"></i> ' + data.responseJSON['error']);
            },
            complete:function(data) {
                $('#redeemSubmit').button('reset');
                $('.close').show();
            }
        });
    });
});

function populateModalElements(vendorId) {
    document.getElementById("redeemForm").reset();
    $('#redeemSubmit').show(); 
    $('#errorMsg').hide();
    $('#resultMsg').hide();
    $('#errorTxt').html('');
    $('#resultTxt').html('');
    var vendorName = $('#vendorName'+vendorId).val();
    var vendorOffer = $('#vendorDesc'+vendorId).val();
    var vendorRedeemTxt = $('#vendorRedeemTxt'+vendorId).val();
    $('#redeemVendorId').val(vendorId);
    $('#vendorRedeemTitle').html(vendorName);
    $('#vendorOfferTxt').html(vendorOffer);
    $('#redeemVendorTxt').html(vendorRedeemTxt);
    var vendorLocations = $('#vendorLocations'+vendorId).html();
    // var vendorPassQuantity = $('#vendorPassQuantityMax'+vendorId).val();
    $('#redeemVendorLocationId').html(vendorLocations);
    $('#redeemVendorQuantity').val(1);
}
</script>

<script>
// Loading button plugin (removed from BS4)
(function($) {
    $.fn.button = function(action) {
        if (action === 'loading' && this.data('loading-text'))
            this.data('original-text', this.html()).html(this.data('loading-text')).prop('disabled', true);

        if (action === 'reset' && this.data('original-text'))
            this.html('Redeem').prop('disabled', false);
    };
}(jQuery));
</script>
@endsection
