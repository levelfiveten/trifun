<div class="Polaris-Layout__AnnotationContent">
    <div class="Polaris-Card">
        <div class="Polaris-Card__Section">
            <form id="customerHistory">
                <div class="Polaris-FormLayout">
                    <div role="group" class="">
                        <div class="Polaris-FormLayout__Items">
                            <div class="Polaris-FormLayout__Item">
                                <div class="k-content">
                                    <div class="Polaris-Labelled__LabelWrapper">
                                        <div class="Polaris-Label">
                                            <label id="customer_label" for="customer" class="Polaris-Label__Text">Customer</label>
                                        </div>
                                    </div>
                                    <div class="Polaris-TextField Polaris-TextField--hasValue">
                                        {{ Form::select('customer', $customerPassUsers, null, ['id' => 'customer', 'style' => 'width:100%', 'required']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="Polaris-Card__Footer">
                    <div class="Polaris-ButtonGroup">
                        <div class="Polaris-ButtonGroup__Item">
                            <button id="showCustomerHisoryBtn" type="submit" class="save-btn Polaris-Button Polaris-Button--primary"><span class="Polaris-Button__Content"><span>Customer History</span></span></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $("#customer").kendoComboBox({
        filter: "contains",
        suggest: true,
        index: 1
    });
});
</script>