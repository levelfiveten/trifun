<div class="Polaris-Layout__AnnotationContent">
    <div class="Polaris-Card">
        <div class="Polaris-Card__Section">
            <form id="createRegionPassType">
                <div class="Polaris-FormLayout">
                    <div role="group" class="">
                        <div class="Polaris-FormLayout__Items">
                            <div class="Polaris-FormLayout__Item">
                                <div class="k-content">
                                    <div class="Polaris-Labelled__LabelWrapper">
                                        <div class="Polaris-Label">
                                            <label id="type_label" for="type_region_id" class="Polaris-Label__Text">Region</label>
                                        </div>
                                    </div>
                                    {{ Form::select('type_region_id', $regions, null, ['placeholder' => 'Select Region', 'id' => 'type_region_id', 'style' => 'width:100%', 'required']) }}
                                    <span class="k-invalid-msg" data-for="type_region_id"></span>
                                </div>
                            </div>
                            <div class="Polaris-FormLayout__Item">
                                <div class="k-content" id="regionPassTypeBox" style="display:none">
                                    <div class="Polaris-Labelled__LabelWrapper">
                                        <div class="Polaris-Label">
                                            <label id="type_label" for="type_name" class="Polaris-Label__Text">Type</label>
                                        </div>
                                    </div>
                                    {{ Form::select('type_name', $passTypes, null, ['placeholder' => 'Select Type', 'id' => 'type_name', 'style' => 'width:100%', 'required']) }}
                                    <span class="k-invalid-msg" data-for="type_name"></span>
                                </div>
                            </div>
                        </div>
                        <div class="Polaris-FormLayout__Items" id="regionPassTypeFields" style="display:none">
                            <div class="Polaris-FormLayout__Item">
                                <div class="">
                                    <div class="Polaris-Labelled__LabelWrapper">
                                        <div class="Polaris-Label">
                                            <label id="days_valid_label" for="days_valid" class="Polaris-Label__Text">Days valid</label>
                                        </div>
                                    </div>
                                    {{ Form::number('days_valid', null, ['style' => 'width:100%', 'id' => 'days_valid', 'required']) }}
                                    <span class="k-invalid-msg" data-for="days_valid"></span>
                                </div>
                            </div>
                            <div class="Polaris-FormLayout__Item" id="maxVendorUsesItem">
                                <div class="">
                                    <div class="Polaris-Labelled__LabelWrapper">
                                        <div class="Polaris-Label">
                                            <label id="use_per_vendor_label" for="use_per_vendor" class="Polaris-Label__Text">Max uses at vendor</label>
                                        </div>
                                    </div>
                                    {{ Form::number('use_per_vendor', null, ['style' => 'width:100%', 'id' => 'use_per_vendor']) }}
                                </div>
                            </div>
                            <div class="Polaris-FormLayout__Item" id="totalUsesItem">
                                <div class="">
                                    <div class="Polaris-Labelled__LabelWrapper">
                                        <div class="Polaris-Label">
                                            <label id="usage_limit_label" for="usage_limit" class="Polaris-Label__Text">Total uses per pass</label>
                                        </div>
                                    </div>
                                    {{ Form::number('usage_limit', null, ['style' => 'width:100%', 'id' => 'usage_limit']) }}
                                    <!-- <span class="k-invalid-msg" data-for="usage_limit"></span> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="Polaris-Card__Footer">
                    <div class="Polaris-ButtonGroup">
                        <div class="Polaris-ButtonGroup__Item">
                            <button id="passTypeSaveBtn" type="submit" class="save-btn Polaris-Button Polaris-Button--primary"><span class="Polaris-Button__Content"><span>Add</span></span></button>
                            <button id="passTypeLoadBtn" type="button" class=" load-btn Polaris-Button Polaris-Button--disabled Polaris-Button--loading" disabled="" role="alert" aria-busy="true" style="display:none"><span class="Polaris-Button__Content"><span class="Polaris-Button__Spinner"><svg viewBox="0 0 20 20" class="Polaris-Spinner Polaris-Spinner--colorInkLightest Polaris-Spinner--sizeSmall" aria-label="Loading" role="status"><path d="M7.229 1.173a9.25 9.25 0 1 0 11.655 11.412 1.25 1.25 0 1 0-2.4-.698 6.75 6.75 0 1 1-8.506-8.329 1.25 1.25 0 1 0-.75-2.385z"></path></svg></span><span>Save</span></span></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#type_region_id").kendoDropDownList({
            change: onPassTypeRegionChange
        });
        $("#type_name").kendoDropDownList({
            change: onPassTypeTypeChange
        });
    });
    function onPassTypeRegionChange()
    {
        if ($('#type_region_id').val() != '') {
            $('#regionPassTypeBox').show();
            $('#regionPassTypeFields').hide();
            var typeName = $("#type_name").data("kendoDropDownList");
            typeName.value('');
        }
        else {
            $('#regionPassTypeBox').hide();
            $('#regionPassTypeFields').hide();
        }
    }
    function onPassTypeTypeChange()
    {
        var passTypeSelected = $("#type_name").val();
        if (passTypeSelected != '')
            $('#regionPassTypeFields').show();
        else
            $('#regionPassTypeFields').hide();
        switch(passTypeSelected) {
            case "experience":
            case "bonus":
            case "mini":
                $('#maxVendorUsesItem').show();
                $('#totalUsesItem').hide();
                var usePerVendor = $("#use_per_vendor").data("kendoNumericTextBox");
                usePerVendor.value(1);
                var daysValid = $("#days_valid").data("kendoNumericTextBox");
                daysValid.value(365);
                break;
            case "dining":
                $('#totalUsesItem').show();
                $('#maxVendorUsesItem').hide();
                var usageLimit = $("#usage_limit").data("kendoNumericTextBox");
                usageLimit.value(10);
                var daysValid = $("#days_valid").data("kendoNumericTextBox");
                daysValid.value(365);
                break;
            case "special":
                $('#totalUsesItem').show();
                $('#maxVendorUsesItem').hide();
                var daysValid = $("#days_valid").data("kendoNumericTextBox");
                daysValid.value(365);
                break;
        }
    }

    $("#use_per_vendor").kendoNumericTextBox({
        min:0,
        max: 10,
        step: 1,
        decimals: 0,
        format: "{0:n0}"
    });

    $("#usage_limit").kendoNumericTextBox({
        min: 1,
        max: 10,
        step: 1,
        decimals: 0,
        format: "{0:n0}"
    });

    $("#days_valid").kendoNumericTextBox({
        min: 1,
        max: 365,
        step: 1,
        decimals: 0,
        format: "{0:n0}",
        value: 365
    });

    $("#createRegionPassType").kendoValidator();
</script>