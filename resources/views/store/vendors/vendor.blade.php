<style>
    .demo-section {
        text-align: center;
    }
</style>
<div class="Polaris-Layout__AnnotationContent">
    <div class="Polaris-Card">
        <div class="Polaris-Card__Section">
            <div class="demo-section k-content">
                <div id="select-action">
                    <span id="newVendorBtn">
                        New
                    </span>
                    <span id="editVendorBtn">
                        Edit
                    </span>
                </div>
            </div>
            <!-- <div class="Polaris-ButtonGroup Polaris-ButtonGroup--segmented float-left">
                <div class="Polaris-ButtonGroup__Item"><button type="button" id="newVendorBtn" class="Polaris-Button"><span class="Polaris-Button__Content"><span>New</span></span></button></div>
                <div class="Polaris-ButtonGroup__Item"><button type="button" id="editVendorBtn" class="Polaris-Button"><span class="Polaris-Button__Content"><span>Edit</span></span></button></div>                                
            </div> -->
            <hr>
            @include('store.vendors.form')
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $("#select-action").kendoButtonGroup({
        index: 0
    });
    $("#vendor-action").kendoButtonGroup({
        index: 0
    });

    $("#vendorUpdateBtn").click(function(e) {
        e.preventDefault();
        ShopifyApp.Modal.confirm({
            title: "Update Venue?",
            message: "Are you sure you want to update this venue?",
            okButton: "Yes",
            cancelButton: "No",
            style: "danger"
            }, function(result) {
                if (result)
                    $('#vendorForm').submit();
        });
    });

    $("#vendor_region_id").kendoDropDownList({
        change: onVendorRegionChange
    });
    $("#vendor_pass_type").kendoDropDownList({
        change: onVendorPassTypeChange
    });
    $("#vendor_select").kendoDropDownList({
        change: onVendorSelectChange
    });
    $("#vendor_location_select").kendoDropDownList({
        change: onVendorLocationSelectChange
    });

    $("#max_pass_use").kendoNumericTextBox({
        max: 10,
        step: 1,
        decimals: 0,
        format: "{0:n0}"
    });
    
    $("#createVendor").kendoValidator();

    $('#newVendorBtn').click(function() {
        $('#vendorPropertiesBtn').click();
        document.getElementById("vendorForm").reset();
        $('#vendorActionType').val('add');   
        $('#editVenueBadge').hide();     
        $('#newVenueBadge').show();
        $('#vendorPropertiesDiv').show();
        $('#vendorSelDiv').hide();
        $('#vendorBtnDiv').show();
        $('#addVendorBtnGrp').show();
        $('#updateVendorBtnGrp').hide();
        $('#otherVendorBtnGrp').hide();
        $('#vendorLocationDiv').show();
    });
    $('#editVendorBtn').click(function() {    
        document.getElementById("vendorForm").reset();
        $('#vendorActionType').val('edit');
        $('#newVenueBadge').hide();
        $('#editVenueBadge').show();
        $('#vendorPropertiesDiv').hide();
        $('#vendorSelDiv').hide();
        $('#vendorBtnDiv').hide();
        $('#addVendorBtnGrp').hide();
        $('#updateVendorBtnGrp').show();
    });

    $('#vendorPropertiesBtn').click(function() {
        $('#vendorLocationDiv').hide();
        $('#vendorLocationEditDiv').hide();
        $('#vendorLocationSelect').hide();
        $('#vendorPropertiesDiv').show();     
        $('#vendorProperties').show();   
        $('#updateVendorBtnGrp').show();
        $('#addVendorLocationBtnGrp').hide();
        $('#editVendorLocationBtnGrp').hide();
        $('#deleteVendorLocationBtnGrp').hide();
    });
    $('#vendorLocationNewBtn').click(function() {
        resetVendorLocationEditFields();
        $('#vendorPropertiesDiv').show();
        $('#vendorLocationDiv').hide();
        $('#vendorLocationEditDiv').show();
        $('#addVendorLocationBtnGrp').show();
        $('#vendorLocationSelect').hide();
        $('#vendorProperties').hide();
        $('#updateVendorBtnGrp').hide();
        $('#editVendorLocationBtnGrp').hide();
        $('#deleteVendorLocationBtnGrp').hide();
    });
    $('#vendorLocationEditBtn').click(function() {
        resetVendorLocationEditFields();
        $('#vendorLocationSelect').show();
        $('#vendorLocationEditDiv').hide();        
        $('#vendorPropertiesDiv').show();   
        $('#editVendorLocationBtnGrp').show();
        $('#deleteVendorLocationBtnGrp').show();
        $('#vendorLocationDiv').hide();
        $('#vendorProperties').hide();      
        $('#updateVendorBtnGrp').hide();
        $('#addVendorLocationBtnGrp').hide();
    });
});

function onVendorRegionChange() {
    populateVendorSelect();
}
function onVendorPassTypeChange() {
    populateVendorSelect();
}
function onVendorSelectChange() {
    populateVendorProperties();
}
function onVendorLocationSelectChange() {
    populateVendorLocationEditProperties();
}

function populateVendorSelect() {
    if ($('#vendorActionType').val() == 'edit' && $("#vendor_region_id").val() != '' && $("#vendor_pass_type").val() != '') {
        $.ajax({
            type: 'GET',
            url: "{{ route('vendors.get') }}",
            data: {
                "region_id": $("#vendor_region_id").val(),
                "pass_type": $("#vendor_pass_type").val()
            },
            success:function(data) {
                if (data.vendors.length === 1) {
                    $("#vendorSelDiv").hide();
                    ShopifyApp.flashError('No venues found. Please try another selection.');
                }                
                else {
                    $("#venue_select").kendoDropDownList({
                        change: onVendorSelectChange,
                        dataSource: data.vendors,
                        dataTextField: "vendorName",
                        dataValueField: "vendorId",
                        value: ""
                    });
                    $("#vendorSelDiv").show();
                }
            },
            error:function(data) {
                $("#vendorSelDiv").hide();
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
            complete:function(data) {
                $('#vendorPropertiesDiv').hide();
                $('#vendorBtnDiv').hide();
                $('#otherVendorBtnGrp').hide();          
            }
        });
    }
    else if ($('#vendorActionType').val() == 'edit') {
        $("#vendorSelDiv").hide();
        $("#vendorPropertiesDiv").hide();
        $('#vendorBtnDiv').hide();
    }
    else if ($("#vendor_region_id").val() != '' && $("#vendor_pass_type").val() != '') {
        //get total uses of a pass and set that as max limit for the vendor's max pass use
        $.ajax({
            type: 'GET',
            url: "{{ route('region.passTypes.totalPassUses') }}",
            data: {
                "region_id": $("#vendor_region_id").val(),
                "pass_type": $("#vendor_pass_type").val()
            },
            success:function(data) {
                var maxPassUseTextBox = $("#max_pass_use").data("kendoNumericTextBox");
                maxPassUseTextBox.options.max = data.totalPassUses;
                maxPassUseTextBox.value('');
            },
            error:function(data) {
                if (data.responseText != undefined) {
                    var errorJSON = JSON.parse(data.responseText)
                    var errorMsg = "";
                    for (i = 0; i < errorJSON['errors'].length; i++)
                        errorMsg += errorJSON['errors'][i] + ' ';

                    ShopifyApp.flashError(errorMsg);
                }
                else 
                    ShopifyApp.flashError('Error encountered performing the requested operation. Please reload your browser and try again.');
            }
        });
    }
}

function populateVendorProperties() {
    if ($('#venue_select').val() != '') {        
        $.ajax({
            type: 'GET',
            url: "{{ route('vendor.properties') }}",
            data: {
                "vendor_id": $("#venue_select").val()
            },
            success:function(data) {
                populateVendorFields(data);

                $('#vendorPropertiesBtn').click();
                var buttonGroup = $("#vendor-action").data("kendoButtonGroup");
                buttonGroup.select(0);

                $("#vendorPropertiesDiv").show();
                $('#vendorBtnDiv').show();
                $('#otherVendorBtnGrp').show();
            },
            error:function(data) {
                $("#vendorPropertiesDiv").hide();
                $('#otherVendorBtnGrp').hide();
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
                $('#vendorLocationDiv').hide();         
            }
        });
        
    }
    else {
        $("#vendorPropertiesDiv").hide();
        $('#vendorBtnDiv').hide();
        $('#vendorLocationDiv').hide();
        $('#otherVendorBtnGrp').hide();
        $("#vendorLocationSelect").hide();
    }
}

function populateVendorFields(data) {
    // console.log(data);
    $('#vendor_name').val(data.vendor.name);
    $('#vendor_email').val(data.vendor.email);
    $('#pass_code').val(data.vendor.pass_code);
    $('#offer_desc').val(data.vendor.offer_desc);
    $('#redeem_txt').val(data.vendor.redeem_txt);
    var maxPassUseTextBox = $("#max_pass_use").data("kendoNumericTextBox");
    maxPassUseTextBox.options.max = data.vendor.total_pass_use;
    maxPassUseTextBox.value(data.vendor.max_pass_use);

    if (data.vendor.is_withdrawn) {
        $('#vendorWithdrawnDiv').show();
        $('#vendorWithdrawnBtnGroup').hide();
    }
    else {
        $('#vendorWithdrawnDiv').hide();
        $('#vendorWithdrawnBtnGroup').show();
    }

    //hack to get around client required validation for location (since we are reusing the create vendor form, which requires an initial location)
    //to separate concerns, provide CRUD on vendor locations in a separate area    
    $('#location_name').val(data.locations[0].name);
    $('#address1').val(data.locations[0].address1);
    $('#address2').val(data.locations[0].address2);
    $('#city').val(data.locations[0].city);
    $('#state').val(data.locations[0].state);
    $('#zipcode').val(data.locations[0].zipcode);

    $('#x_location_name').val('');
    $('#x_address1').val('');
    $('#x_address2').val('');
    $('#x_city').val('');
    $('#x_state').val('');
    $('#x_zipcode').val('');

    $("#vendor_location_select").kendoDropDownList({
        change: onVendorLocationSelectChange,
        dataSource: data.selectLocations,
        dataTextField: "name",
        dataValueField: "id",
        value: ""
    });
}

function populateVendorLocationEditProperties(location) {
    if ($('#vendor_location_select').val() != '') {        
        $.ajax({
            type: 'GET',
            url: "{{ route('vendor.location.properties') }}",
            data: {
                "location_id": $("#vendor_location_select").val()
            },
            success:function(data) {
                // console.log(data.vendor);
                populateVendorLocationEditFields(data.location)
                $('#vendorLocationEditDiv').show();
            },
            error:function(data) {
                $("#vendorLocationEditDiv").hide();
                console.log(data);
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
                // swapVisibility(elemPrefix+'LoadBtn', elemPrefix+'SaveBtn');            
            }
        });
        
    }
    else {
        $("#vendorLocationEditDiv").hide();
    }
}

function populateVendorLocationEditFields(location) {
    $('#x_location_id').val(location.id);
    $('#x_location_name').val(location.name);
    $('#x_address1').val(location.address1);
    $('#x_address2').val(location.address2);
    $('#x_city').val(location.city);
    $('#x_state').val(location.state);
    $('#x_zipcode').val(location.zipcode);
}

function addVendorLocation()
{
    // $.ajax({
    //     type: 'GET',
    //     url: "",
    //     data: {
    //         "vendor_id": $("#venue_select").val()
    //     },
    //     success:function(data) {
    //         // console.log(data.vendor);
    //         populateVendorFields(data);
    //         $("#vendorPropertiesDiv").show();
    //         $('#vendorBtnDiv').show();
    //         $('#otherVendorBtnGrp').show();
    //     },
    //     error:function(data) {
    //         $("#vendorPropertiesDiv").hide();
    //         $('#otherVendorBtnGrp').hide();
    //         console.log(data);
    //         if (data.responseText != undefined) {
    //             var errorJSON = JSON.parse(data.responseText)
    //             var errorMsg = "";
    //             for (i = 0; i < errorJSON['errors'].length; i++)
    //                 errorMsg += errorJSON['errors'][i] + ' ';

    //             ShopifyApp.flashError(errorMsg);
    //         }
    //         else 
    //             ShopifyApp.flashError('Error encountered performing the requested operation. Please reload your browser and try again.');
    //     },
    //     complete:function(data){
    //         $('#vendorLocationDiv').hide();
    //         // swapVisibility(elemPrefix+'LoadBtn', elemPrefix+'SaveBtn');            
    //     }
    // });
}
function editVendorLocation(vendorId)
{

}

function resetVendorLocationEditFields()
{
    var vendorLocationSel = $("#vendor_location_select").data("kendoDropDownList");
    vendorLocationSel.value('');
    $('#x_location_id').val('');
    $('#x_location_name').val('');
    $('#x_address1').val('');
    $('#x_address2').val('');
    $('#x_city').val('');
    $('#x_state').val('');
    $('#x_zipcode').val('');
}
</script>