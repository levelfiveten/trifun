@extends('layouts.shopify_admin')
@section('head')
<script type="text/javascript">
    ShopifyApp.init({
    apiKey: '{{ env('SHOPIFY_KEY') }}',
    shopOrigin: 'https://{{ $store->domain }}',
    debug: false
    });
</script>
<link rel="stylesheet" href="https://sdks.shopifycdn.com/polaris/latest/polaris.css" />
<style>
.span-i {
    font-style:italic;
}
 span > .k-i-clock {
  margin-top:4px;
}

.k-i-arrow-60-down {
    margin-top: 9px;
} 

.k-i-warning {
    margin-top: 4px;
}

.save-btn, .activate-btn, .deactivate-btn, .load-btn {
  margin-top:15px;
}
</style>

@endsection

@section('content')
<body>
  <div class="Polaris-Page">
    <div class="Polaris-Page__Content">
      <div class="Polaris-Layout">
        <div class="Polaris-Layout__AnnotatedSection">
          <div class="Polaris-Layout__AnnotationWrapper">
            <div class="Polaris-Layout__Annotation">
              <div class="Polaris-TextContainer">
                <h2 class="Polaris-Heading">Regions</h2> <a id="viewRegions" href="#">(view)</a>
                <p>Configure Regions</p>
              </div>
            </div>
            @include('store.region')
          </div>
        </div>

        <div class="Polaris-Layout__AnnotatedSection">
          <div class="Polaris-Layout__AnnotationWrapper">
            <div class="Polaris-Layout__Annotation">
              <div class="Polaris-TextContainer">
                <h2 class="Polaris-Heading">Passes</h2> <a id="viewRegionPassTypes" href="#">(view)</a>
                <p>Configure available passes for a Region</p>
              </div>
            </div>
            @include('store.pass_type')
          </div>
        </div>

        <div class="Polaris-Layout__AnnotatedSection">
          <div class="Polaris-Layout__AnnotationWrapper">
            <div class="Polaris-Layout__Annotation">
              <div class="Polaris-TextContainer">
                <h2 class="Polaris-Heading">Venues</h2> <a id="viewVendors" href="#">(view)</a>
                <p>Configure Venues</p>
              </div>
            </div>
            @include('store.vendors.vendor')
          </div>
        </div>

        <div class="Polaris-Layout__AnnotatedSection">
          <div class="Polaris-Layout__AnnotationWrapper">
            <div class="Polaris-Layout__Annotation">
              <div class="Polaris-TextContainer">
                <h2 class="Polaris-Heading">Demo Accounts</h2> 
                <!-- <a id="viewVendors" href="#">(view)</a> -->
                <p>Configure Demo Accounts</p>
              </div>
            </div>
            @include('store.demo')
          </div>
        </div>

        <div class="Polaris-Layout__AnnotatedSection">
          <div class="Polaris-Layout__AnnotationWrapper">
            <div class="Polaris-Layout__Annotation">
              <div class="Polaris-TextContainer">
                <h2 class="Polaris-Heading">Customer History</h2> 
                <!-- <a id="viewVendors" href="#">(view)</a> -->
                <p>View customer redemption history</p>
              </div>
            </div>
            @include('store.customer_history')
          </div>
        </div>

        <div class="Polaris-Layout__AnnotatedSection">
          <div class="Polaris-Layout__AnnotationWrapper">
            <div class="Polaris-Layout__Annotation">
              <div class="Polaris-TextContainer">
                <h2 class="Polaris-Heading">Venue History</h2> 
                <!-- <a id="viewVendors" href="#">(view)</a> -->
                <p>View pass redemption history by Venue</p>
              </div>
            </div>
            @include('store.vendors.vendor_history')
          </div>
        </div>

        <div class="Polaris-Layout__Section">
          <div class="Polaris-FooterHelp">
            <div class="Polaris-FooterHelp__Content">
              <div class="Polaris-FooterHelp__Icon">

              </div>
              <div class="Polaris-FooterHelp__Text">
                <!-- Footer text here -->
                &copy; {{ date('Y') }} Level 510. All Rights Reserved.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

<script type="text/javascript">
ShopifyApp.ready(function(){
    ShopifyApp.Bar.initialize({
        icon: '{{asset('images/icon_l510.png')}}',
        title: 'Settings',
    });
});

$('#changeUrlBtn').click(function()
{

    ShopifyApp.Modal.confirm({
      title: "",
      message: "",
      okButton: "",
      style: ""
    }, 
    function(result){
        // if(result)
            //confirm code here
    });
});

$('#viewRegions').click(function()
{
    ShopifyApp.Modal.open({
        src: "{{ route('regions.view') }}",
        title: 'Regions',
        width: 'small',
        height: 300,
        buttons: {
            primary: [{ label: "Close", callback: function (label) { ShopifyApp.Modal.close(); } }]
        }
    }, 
    function(result, data){
        // if(result)
            //confirm code here
    });
});
$('#viewRegionPassTypes').click(function()
{
    ShopifyApp.Modal.open({
        src: "{{ route('region.passTypes.view') }}",
        title: 'Passes',
        width: 'small',
        height: 300,
        buttons: {
            primary: [{ label: "Close", callback: function (label) { ShopifyApp.Modal.close(); } }]
        }
    }, 
    function(result, data){
        // if(result)
            //confirm code here
    });
});

$('#viewVendors').click(function()
{
    ShopifyApp.Modal.open({
        src: "{{ route('vendors.view') }}",
        title: 'Vendors',
        width: 'fullwidth',
        buttons: {
            primary: [{ label: "Close", callback: function (label) { ShopifyApp.Modal.close(); } }]
        },
        loading:true
    }, 
    function(result, data){
        // if(result)
            //confirm code here
    });
});
</script>


<script type="text/javascript">
ShopifyApp.ready(function() {

    $('#customerHistory').submit(function (e) {
        e.preventDefault();
        ShopifyApp.Modal.open({
            src: "{{ env('APP_URL') }}" + "store/pass_history/customer?customerId=" + $('#customer').val(),
            title: 'History',
            width: 'large',
            height: 300,
            buttons: {
                primary: [{ label: "Close", callback: function (label) { ShopifyApp.Modal.close(); } }]
            }
        }, 
        function(result, data){
            // if(result)
                //confirm code here
        });
    });

    $('#vendorHistory').submit(function (e) {
        e.preventDefault();
        ShopifyApp.Modal.open({
            src: "{{ env('APP_URL') }}" + "store/pass_history/vendor?vendorId=" + $('#venuesUsed').val(),
            title: 'History',
            width: 'large',
            height: 300,
            buttons: {
                primary: [{ label: "Close", callback: function (label) { ShopifyApp.Modal.close(); } }]
            }
        }, 
        function(result, data){
            // if(result)
                //confirm code here
        });
    });

    $('#vendorHistoryMonthly').submit(function (e) {
        e.preventDefault();
        ShopifyApp.Modal.open({
            src: "{{ env('APP_URL') }}" + "store/pass_history/vendor_monthly?monthInt=" + $('#monthSelect').val() + "&monthName=" + $( "#monthSelect option:selected" ).text(),
            title: 'History',
            width: 'large',
            height: 300,
            buttons: {
                primary: [{ label: "Close", callback: function (label) { ShopifyApp.Modal.close(); } }]
            }
        }, 
        function(result, data){
            // if(result)
                //confirm code here
        });
    });

    $('#demoAccount').submit(function (e) {
        e.preventDefault();
        var isReset = ($('#demo_reset').val() == 'true') ? true : false;
        var xUrl = "{{route('demo.account')}}";
        var xData = {
            "_token": "{{ csrf_token() }}",
            "name": $('#demo_name').val(),
            "email": $('#demo_email').val(),
            "is_reset": isReset
        };
        // console.log(xData);
        ShopifyApp.Modal.confirm({
            title: (isReset) ? "Reset passes for this demo account?" : "Register demo account?",
            message: (isReset) ? "Are you sure you want to reset the passes for this demo account?" : "Are you sure you want to register a new demo account?",
            okButton: "Yes",
            cancelButton: "No"
            }, function(result) {
                if (result) {
                    if (isReset)
                        doAjaxCall('demoReset', 'POST', xUrl, xData);
                    else
                        doAjaxCall('demoRegister', 'POST', xUrl, xData);
                }
        });
    });

    $("#createRegion").submit(function(e) {
        e.preventDefault();        
        var xUrl = "{{route('region.create')}}";
        var xData = {
            "_token": "{{ csrf_token() }}",
            "name": $('#region_name').val(),
            "code": $('#region_code').val()
        };
        doAjaxCall('region', 'POST', xUrl, xData);
    });

    $("#createRegionPassType").submit(function(e) {
        var validator = $("#createRegionPassType").kendoValidator().data("kendoValidator");
        e.preventDefault();
        if (validator.validate()) {
            var xUrl = "{{ route('region.passType.create') }}";
            var xData = ($('#type_name').val() == 'experience' || $('#type_name').val() == 'bonus' || $('#type_name').val() == 'mini') ? {
                "_token": "{{ csrf_token() }}",
                "name": $('#type_name option:selected').text(),
                "region_id": $('#type_region_id').val(),
                "type": $('#type_name').val(),
                "use_per_vendor": $('#use_per_vendor').val(),    
                "days_valid": $('#days_valid').val(),
            } : {
                "_token": "{{ csrf_token() }}",
                "name": $('#type_name option:selected').text(),
                "region_id": $('#type_region_id').val(),
                "type": $('#type_name').val(),
                "usage_limit": $('#usage_limit').val(),   
                "days_valid": $('#days_valid').val(),
            };
            doAjaxCall('passType', 'POST', xUrl, xData);
        }
    });

    $("#vendorForm").submit(function(e) {
        var validator = $("#vendorForm").kendoValidator().data("kendoValidator");
        e.preventDefault();        
        if (validator.validate()) {
            if ($('#vendorActionType').val() == 'add') {
                var xUrl = "{{route('vendor.create')}}";
                var xData = {
                    "_token": "{{ csrf_token() }}",
                    "region_id": $('#vendor_region_id').val(),
                    "pass_type": $('#vendor_pass_type').val(),
                    "name": $('#vendor_name').val(),
                    "email": $('#vendor_email').val(),
                    'redeem_txt': $('#redeem_txt').val(),
                    'offer_desc': $('#offer_desc').val(),
                    'pass_code': $('#pass_code').val(),
                    'location_name': $('#location_name').val(),
                    'address1': $('#address1').val(), 
                    'address2': $('#address2').val(),
                    'city': $('#city').val(),
                    'state': $('#state').val(),
                    'zipcode': $('#zipcode').val(),
                    'max_pass_use': $('#max_pass_use').val()
                };
                doAjaxCall('vendor', 'POST', xUrl, xData);
            }
            else if ($('#vendorActionType').val() == 'edit') {
                var xUrl = "{{route('vendor.update')}}";
                var xData = {
                    "_token": "{{ csrf_token() }}",
                    "vendor_id": $('#venue_select').val(),
                    "name": $('#vendor_name').val(),
                    "email": $('#vendor_email').val(),
                    'redeem_txt': $('#redeem_txt').val(),
                    'offer_desc': $('#offer_desc').val(),
                    'pass_code': $('#pass_code').val(),
                    'max_pass_use': $('#max_pass_use').val()
                };
                doAjaxCall('vendor', 'PATCH', xUrl, xData);
            }
        }
    });
    $("#vendorLocationSaveBtn").click(function(e) {
        var xUrl = "{{route('vendor.location.create')}}";
        var xData = {
            "_token": "{{ csrf_token() }}",
            "vendor_id": $('#venue_select').val(),
            'location_name': $('#x_location_name').val(),
            'address1': $('#x_address1').val(), 
            'address2': $('#x_address2').val(),
            'city': $('#x_city').val(),
            'state': $('#x_state').val(),
            'zipcode': $('#x_zipcode').val()
        };
        doAjaxCall('vendorLocation', 'POST', xUrl, xData);
    });
    $("#vendorLocationUpdateBtn").click(function(e) {
        e.preventDefault();
        ShopifyApp.Modal.confirm({
            title: "Update Venue Location?",
            message: "Are you sure you want to update this venue location?",
            okButton: "Yes",
            cancelButton: "No",
            style: "info"
            }, function(result) {
                if (result) {
                    var xUrl = "{{route('vendor.location.update')}}";
                    var xData = {
                        "_token": "{{ csrf_token() }}",
                        "vendor_location_id": $('#vendor_location_select').val(),
                        'location_name': $('#x_location_name').val(),
                        'address1': $('#x_address1').val(), 
                        'address2': $('#x_address2').val(),
                        'city': $('#x_city').val(),
                        'state': $('#x_state').val(),
                        'zipcode': $('#x_zipcode').val()
                    };
                    doAjaxCall('vendorLocation', 'PATCH', xUrl, xData);
                }
        });
    });
    $("#vendorLocationDeleteBtn").click(function(e) {
        e.preventDefault();
        ShopifyApp.Modal.confirm({
            title: "Delete Venue Location?",
            message: "Are you sure you want to delete this venue location?",
            okButton: "Yes",
            cancelButton: "No",
            style: "info"
            }, function(result) {
                if (result) {
                    var xUrl = "{{route('vendor.location.delete')}}";
                    var xData = {
                        "_token": "{{ csrf_token() }}",
                        "vendor_location_id": $('#vendor_location_select').val()
                    };
                    doAjaxCall('vendorLocation', 'DELETE', xUrl, xData);
                }
        });
    });
    $("#vendorWithdrawUpdateBtn").click(function(e) {
        e.preventDefault();
        ShopifyApp.Modal.confirm({
            title: "Withdraw Venue?",
            message: "Are you sure you want to withdraw this venue?",
            okButton: "Yes",
            cancelButton: "No",
            style: "danger"
            }, function(result) {
                if (result) {
                    var xUrl = "{{route('vendor.withdraw')}}";
                    var xData = {
                        "_token": "{{ csrf_token() }}",
                        "vendor_id": $('#venue_select').val()
                    };
                    doAjaxCall('vendorWithdraw', 'PATCH', xUrl, xData);
                }
        });
    });
    $("#vendorEnrollUpdateBtn").click(function(e) {
        e.preventDefault();
        ShopifyApp.Modal.confirm({
            title: "Re-enroll Venue?",
            message: "Are you sure you want to re-enroll this venue?",
            okButton: "Yes",
            cancelButton: "No",
            style: "info"
            }, function(result) {
                if (result) {
                    var xUrl = "{{route('vendor.enroll')}}";
                    var xData = {
                        "_token": "{{ csrf_token() }}",
                        "vendor_id": $('#venue_select').val()
                    };
                    doAjaxCall('vendorEnroll', 'PATCH', xUrl, xData);
                }
        });
    });
});
function doAjaxCall(elemPrefix, xType, xUrl, xData) {
    var saveBtnElement = (xType == 'PATCH') ? elemPrefix+'UpdateBtn' : elemPrefix+'SaveBtn';
    var loadBtnElement = (xType == 'PATCH') ? elemPrefix+'UpdateLoadBtn' : elemPrefix+'LoadBtn';
    saveBtnElement = (xType == 'DELETE') ? elemPrefix+'DeleteBtn' : saveBtnElement;
    loadBtnElement = (xType == 'DELETE') ? elemPrefix+'DeleteLoadBtn' : loadBtnElement;
    swapVisibility(saveBtnElement, loadBtnElement);
    $.ajax({
        type: xType,
        url: xUrl,
        data: xData,
        success:function(rData) {
            // console.log(rData.regions);
            if (elemPrefix == 'region') {
                $("#type_region_id").kendoDropDownList({
                    change: onPassTypeRegionChange,
                    dataSource: rData.regions,
                    dataTextField: "regionName",
                    dataValueField: "regionId",
                    value: ""
                });
                $("#vendor_region_id").kendoDropDownList({
                    change: onPassTypeRegionChange,
                    dataSource: rData.regions,
                    dataTextField: "regionName",
                    dataValueField: "regionId",
                    value: ""
                });
            }
            else if (elemPrefix == 'vendorLocation' && xType == 'POST') {
                $("#vendor_location_select").kendoDropDownList({
                    change: onVendorLocationSelectChange,
                    dataSource: rData.selectLocations,
                    dataTextField: "name",
                    dataValueField: "id",
                    value: ""
                });
            }
            resetForm(elemPrefix, xType, xUrl);
            ShopifyApp.flashNotice(rData.result);
        },
        error:function(data) {
            // console.log(data);
            if (data.responseText != undefined) {
                var errorJSON = JSON.parse(data.responseText)
                var errorMsg = "";
                for (i = 0; i < errorJSON['errors'].length; i++)
                    errorMsg += errorJSON['errors'][i] + ' ';

                ShopifyApp.flashError(errorMsg);
            }
            else 
                ShopifyApp.flashError('Error encountered performing the requested operation. Please reload your browser and try again.');
        },
        complete:function(data){
            swapVisibility(loadBtnElement, saveBtnElement);
        }
    });
}

function resetForm(elemPrefix, type, url) {
    if (elemPrefix == 'vendor' && type == 'POST')
        document.getElementById("vendorForm").reset();
    // else if (elemPrefix == 'vendor' && type == 'PATCH') {
    //     document.getElementById("vendorForm").reset();
    // }
    else if (url == "{{route('vendor.withdraw')}}" || url == "{{route('vendor.enroll')}}") {
        if (url == "{{route('vendor.withdraw')}}") {
            $('#vendorWithdrawnDiv').show();
            $('#vendorWithdrawnBtnGroup').hide();
        }
        else if (url == "{{route('vendor.enroll')}}") {
            $('#vendorWithdrawnDiv').hide();
            $('#vendorWithdrawnBtnGroup').show();
        }
    }
    else if (url == "{{route('vendor.location.delete')}}") {
        $("#vendorLocationSelect").hide();
        populateVendorSelect();
    }
    else if (elemPrefix == 'region' && type == 'POST')
        document.getElementById("createRegion").reset();
    else if (elemPrefix == 'passType' && type == 'POST') {
        $('#regionPassTypeBox').hide();
        $('#regionPassTypeFields').hide();
        document.getElementById("createRegionPassType").reset();
        document.getElementById("vendorForm").reset();
    }
}
</script>

@if(session('status-error'))
<script>
  ShopifyApp.ready(function() {
      ShopifyApp.flashError("{{ session('status-error') }}");
  });
</script>
@endif
@if(session('status-success'))
<script>
  ShopifyApp.ready(function() {
      ShopifyApp.flashNotice("{{ session('status-success') }}");
  });
</script>
@endif

<script>
function swapVisibility(hideElem, showElem) {
  var hideElement = document.getElementById(hideElem);
  hideElement.style.cssText = "display: none";
  var showElement = document.getElementById(showElem);
  showElement.style.cssText = "";
}
</script>
@endsection