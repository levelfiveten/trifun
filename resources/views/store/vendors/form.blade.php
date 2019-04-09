<form id="vendorForm">
    <span id="newVenueBadge" class="Polaris-Badge Polaris-Badge--statusInfo"><span class="Polaris-VisuallyHidden">Info</span>New Venue</span>
    <span id="editVenueBadge" class="Polaris-Badge Polaris-Badge--statusAttention" style="display:none"><span class="Polaris-VisuallyHidden">Attention</span>Edit Venue</span>
    <div id="formFieldDiv">
        @include('store.vendors.form_fields')
        <div id="vendorBtnDiv">
            <div class="Polaris-Card__Footer">
                <div class="Polaris-ButtonGroup">
                    <div class="Polaris-ButtonGroup__Item" id="addVendorBtnGrp">
                        <button id="vendorSaveBtn" type="submit" class="save-btn Polaris-Button Polaris-Button--primary"><span class="Polaris-Button__Content"><span>Add</span></span></button>
                        <button id="vendorLoadBtn" type="button" class=" load-btn Polaris-Button Polaris-Button--disabled Polaris-Button--loading" disabled="" role="alert" aria-busy="true" style="display:none"><span class="Polaris-Button__Content"><span class="Polaris-Button__Spinner"><svg viewBox="0 0 20 20" class="Polaris-Spinner Polaris-Spinner--colorInkLightest Polaris-Spinner--sizeSmall" aria-label="Loading" role="status"><path d="M7.229 1.173a9.25 9.25 0 1 0 11.655 11.412 1.25 1.25 0 1 0-2.4-.698 6.75 6.75 0 1 1-8.506-8.329 1.25 1.25 0 1 0-.75-2.385z"></path></svg></span><span>Save</span></span></button>
                    </div>
                    <div class="Polaris-ButtonGroup__Item" id="updateVendorBtnGrp" style="display:none">
                        <span id="vendorWithdrawnBtnGroup">
                            <button id="vendorWithdrawUpdateBtn" type="button" class="Polaris-Button Polaris-Button--destructive Polaris-Button--sizeSlim"><span class="Polaris-Button__Content"><span>Withdraw</span></span></button>
                            <button id="vendorWithdrawUpdateLoadBtn" type="button" class=" load-btn Polaris-Button Polaris-Button--disabled Polaris-Button--loading" disabled="" role="alert" aria-busy="true" style="display:none"><span class="Polaris-Button__Content"><span class="Polaris-Button__Spinner"><svg viewBox="0 0 20 20" class="Polaris-Spinner Polaris-Spinner--colorInkLightest Polaris-Spinner--sizeSmall" aria-label="Loading" role="status"><path d="M7.229 1.173a9.25 9.25 0 1 0 11.655 11.412 1.25 1.25 0 1 0-2.4-.698 6.75 6.75 0 1 1-8.506-8.329 1.25 1.25 0 1 0-.75-2.385z"></path></svg></span><span>Save</span></span></button>
                        </span>
                        <button id="vendorUpdateBtn" type="submit" class="save-btn Polaris-Button Polaris-Button--primary"><span class="Polaris-Button__Content"><span>Update</span></span></button>
                        <button id="vendorUpdateLoadBtn" type="button" class=" load-btn Polaris-Button Polaris-Button--disabled Polaris-Button--loading" disabled="" role="alert" aria-busy="true" style="display:none"><span class="Polaris-Button__Content"><span class="Polaris-Button__Spinner"><svg viewBox="0 0 20 20" class="Polaris-Spinner Polaris-Spinner--colorInkLightest Polaris-Spinner--sizeSmall" aria-label="Loading" role="status"><path d="M7.229 1.173a9.25 9.25 0 1 0 11.655 11.412 1.25 1.25 0 1 0-2.4-.698 6.75 6.75 0 1 1-8.506-8.329 1.25 1.25 0 1 0-.75-2.385z"></path></svg></span><span>Save</span></span></button>
                    </div>
                    <div class="Polaris-ButtonGroup__Item" id="addVendorLocationBtnGrp" style="display:none">
                        <button id="vendorLocationSaveBtn" type="submit" class="save-btn Polaris-Button Polaris-Button--primary"><span class="Polaris-Button__Content"><span>Add Location</span></span></button>
                        <button id="vendorLocationLoadBtn" type="button" class=" load-btn Polaris-Button Polaris-Button--disabled Polaris-Button--loading" disabled="" role="alert" aria-busy="true" style="display:none"><span class="Polaris-Button__Content"><span class="Polaris-Button__Spinner"><svg viewBox="0 0 20 20" class="Polaris-Spinner Polaris-Spinner--colorInkLightest Polaris-Spinner--sizeSmall" aria-label="Loading" role="status"><path d="M7.229 1.173a9.25 9.25 0 1 0 11.655 11.412 1.25 1.25 0 1 0-2.4-.698 6.75 6.75 0 1 1-8.506-8.329 1.25 1.25 0 1 0-.75-2.385z"></path></svg></span><span>Save</span></span></button>
                    </div>
                    <div class="Polaris-ButtonGroup__Item" id="deleteVendorLocationBtnGrp" style="display:none">
                        <button id="vendorLocationDeleteBtn" type="submit" class="save-btn Polaris-Button Polaris-Button--destructive"><span class="Polaris-Button__Content"><span>Delete Location</span></span></button>
                        <button id="vendorLocationDeleteLoadBtn" type="button" class=" load-btn Polaris-Button Polaris-Button--disabled Polaris-Button--loading" disabled="" role="alert" aria-busy="true" style="display:none"><span class="Polaris-Button__Content"><span class="Polaris-Button__Spinner"><svg viewBox="0 0 20 20" class="Polaris-Spinner Polaris-Spinner--colorInkLightest Polaris-Spinner--sizeSmall" aria-label="Loading" role="status"><path d="M7.229 1.173a9.25 9.25 0 1 0 11.655 11.412 1.25 1.25 0 1 0-2.4-.698 6.75 6.75 0 1 1-8.506-8.329 1.25 1.25 0 1 0-.75-2.385z"></path></svg></span><span>Save</span></span></button>
                    </div>
                    <div class="Polaris-ButtonGroup__Item" id="editVendorLocationBtnGrp" style="display:none">
                        <button id="vendorLocationUpdateBtn" type="submit" class="save-btn Polaris-Button Polaris-Button--primary"><span class="Polaris-Button__Content"><span>Update Location</span></span></button>
                        <button id="vendorLocationUpdateLoadBtn" type="button" class=" load-btn Polaris-Button Polaris-Button--disabled Polaris-Button--loading" disabled="" role="alert" aria-busy="true" style="display:none"><span class="Polaris-Button__Content"><span class="Polaris-Button__Spinner"><svg viewBox="0 0 20 20" class="Polaris-Spinner Polaris-Spinner--colorInkLightest Polaris-Spinner--sizeSmall" aria-label="Loading" role="status"><path d="M7.229 1.173a9.25 9.25 0 1 0 11.655 11.412 1.25 1.25 0 1 0-2.4-.698 6.75 6.75 0 1 1-8.506-8.329 1.25 1.25 0 1 0-.75-2.385z"></path></svg></span><span>Save</span></span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>