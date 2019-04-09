<div class="Polaris-Layout__AnnotationContent">
    <div class="Polaris-Card">
        <div class="Polaris-Card__Section">
            <form id="vendorHistory">
                <div class="Polaris-FormLayout">
                    <div role="group" class="">
                        <div class="Polaris-FormLayout__Items">
                            <div class="Polaris-FormLayout__Item">
                                <div class="k-content">
                                    <div class="Polaris-Labelled__LabelWrapper">
                                        <div class="Polaris-Label">
                                            <label id="customer_label" for="customer" class="Polaris-Label__Text">Venue</label>
                                        </div>
                                    </div>
                                    <div class="Polaris-TextField Polaris-TextField--hasValue">
                                        {{ Form::select('venuesUsed', $vendorPassUses, null, ['id' => 'venuesUsed', 'style' => 'width:100%', 'required']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="Polaris-Card__Footer">
                    <div class="Polaris-ButtonGroup">
                        <div class="Polaris-ButtonGroup__Item">
                            <button id="showVendorHisoryBtn" type="submit" class="save-btn Polaris-Button Polaris-Button--primary"><span class="Polaris-Button__Content"><span>Venue History</span></span></button>
                        </div>
                    </div>
                </div>
            </form>
            <hr>
            <br>
            <form id="vendorHistoryMonthly">
                <div class="Polaris-FormLayout">
                    <div role="group" class="">
                        <div class="Polaris-FormLayout__Items">
                            <div class="Polaris-FormLayout__Item">
                                <div class="k-content">
                                    <div class="Polaris-Labelled__LabelWrapper">
                                        <div class="Polaris-Label">
                                            <label id="customer_label" for="customer" class="Polaris-Label__Text">Monthly Reports</label>
                                        </div>
                                    </div>
                                    <div class="Polaris-TextField Polaris-TextField--hasValue">
                                        {{ Form::select('monthSelect', $monthSelect, null, ['id' => 'monthSelect', 'style' => 'width:100%', 'required']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="Polaris-Card__Footer">
                    <div class="Polaris-ButtonGroup">
                        <div class="Polaris-ButtonGroup__Item">
                            <button id="showVendorMonthlyHisoryBtn" type="submit" class="save-btn Polaris-Button Polaris-Button--primary"><span class="Polaris-Button__Content"><span>Monthly History</span></span></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $("#venuesUsed").kendoComboBox({
        filter: "contains",
        suggest: true,
        index: 1
    });
    $("#monthSelect").kendoDropDownList({
    });
});
</script>