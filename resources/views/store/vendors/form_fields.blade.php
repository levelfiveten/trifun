<div class="Polaris-FormLayout">
    <div role="group" class="">
        <div class="Polaris-FormLayout__Items">
            <div class="Polaris-FormLayout__Item">
                <div class="k-content">
                    <div class="Polaris-Labelled__LabelWrapper">
                        <div class="Polaris-Label">
                            <label id="type_label" for="vendor_region_id" class="Polaris-Label__Text">Region</label>
                        </div>
                    </div>
                    {{ Form::hidden('vendorActionType', 'add', ['id' => 'vendorActionType']) }}
                    {{ Form::select('vendor_region_id', $regions, null, ['placeholder' => 'Select Region', 'id' => 'vendor_region_id', 'style' => 'width:100%', 'required']) }}                                    
                </div>
                <span class="k-invalid-msg" data-for="vendor_region_id"></span>
            </div>
            <div class="Polaris-FormLayout__Item">
                <div class="k-content">
                    <div class="Polaris-Labelled__LabelWrapper">
                        <div class="Polaris-Label">
                            <label id="type_label" for="vendor_pass_type" class="Polaris-Label__Text">Type</label>
                        </div>
                    </div>
                    {{ Form::select('vendor_pass_type', $passTypes, null, ['placeholder' => 'Select Type', 'id' => 'vendor_pass_type', 'style' => 'width:100%', 'required']) }}                                    
                </div>
                <span class="k-invalid-msg" data-for="vendor_pass_type"></span>
            </div>
        </div>
        <div id="vendorSelDiv" style="display:none">
            <div class="Polaris-FormLayout__Items">
                <div class="Polaris-FormLayout__Item">
                    <div class="k-content">
                        <div class="Polaris-Labelled__LabelWrapper">
                            <div class="Polaris-Label">
                                <label id="venue_select_label" for="venue_select" class="Polaris-Label__Text">Venue</label>
                            </div>
                        </div>
                        {{ Form::select('venue_select', [], null, ['placeholder' => 'Select Venue', 'id' => 'venue_select', 'style' => 'width:100%']) }}                                    
                    </div>
                </div>
            </div>
            <div class="Polaris-FormLayout__Items" id="otherVendorBtnGrp" style="display:none">
                <div class="Polaris-FormLayout__Item">
                    <div class="demo-section k-content" style="margin: 0 auto;">
                        <div id="vendor-action">
                            <span id="vendorPropertiesBtn">
                                Properties
                            </span>
                            <span id="vendorLocationNewBtn">
                                New Location
                            </span>
                            <span id="vendorLocationEditBtn">
                                Edit Location
                            </span>
                        </div>
                    </div>
                    <hr>
                </div>
                <!-- <div class="Polaris-FormLayout__Item">
                    <div class="Polaris-ButtonGroup Polaris-ButtonGroup--segmented Polaris-ButtonGroup--fullWidth Polaris-ButtonGroup--connectedTop">
                        <div class="Polaris-ButtonGroup__Item">                            
                            <button id="vendorLocationAddBtn" type="button" class="Polaris-Button Polaris-Button--sizeSlim"><span class="Polaris-Button__Content"><span>Add Location</span></span></button>
                        </div>
                        <div class="Polaris-ButtonGroup__Item">
                            <button id="vendorLocationEditBtn" type="button" class="Polaris-Button Polaris-Button--sizeSlim"><span class="Polaris-Button__Content"><span>Edit Location</span></span></button>
                        </div>
                        <div class="Polaris-ButtonGroup__Item">
                            
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
        <div class="Polaris-FormLayout__Items" id="vendorLocationSelect" style="display:none">
            <div class="Polaris-FormLayout__Item">
                    <div class="k-content">
                        <div class="Polaris-Labelled__LabelWrapper">
                            <div class="Polaris-Label">
                                <label id="vendor_location_select_label" for="vendor_location_select" class="Polaris-Label__Text">Location</label>
                            </div>
                        </div>
                        {{ Form::select('vendor_location_select', [], null, ['placeholder' => 'Select Location', 'id' => 'vendor_location_select', 'style' => 'width:100%']) }}                                   
                    </div>
            </div>
        </div>
        <div id="vendorPropertiesDiv">
            <div id="vendorProperties">
                <div class="Polaris-FormLayout__Items" id="vendorWithdrawnDiv" style="display:none">
                    <div class="Polaris-FormLayout__Item">
                        <div class="">
                            @include('store.vendors.withdrawn')
                        </div>
                    </div>
                </div>
                <div class="Polaris-FormLayout__Items">
                    <div class="Polaris-FormLayout__Item">
                        <div class="">
                            <div class="Polaris-Labelled__LabelWrapper">
                                <div class="Polaris-Label">
                                    <label id="vendor_name_label" for="vendor_name" class="Polaris-Label__Text">Name</label>
                                </div>
                            </div>
                            <div class="Polaris-TextField Polaris-TextField--hasValue">
                                {{ Form::text('vendor_name', null, ['class' => 'Polaris-TextField__Input', 'id' => 'vendor_name', 'required']) }}                                        
                                <div class="Polaris-TextField__Backdrop"></div>                                        
                            </div>
                            <span class="k-invalid-msg" data-for="vendor_name"></span>
                        </div>
                    </div>
                    <div class="Polaris-FormLayout__Item">
                        <div class="">
                            <div class="Polaris-Labelled__LabelWrapper">
                                <div class="Polaris-Label">
                                    <label id="rvendor_email_label" for="vendor_email" class="Polaris-Label__Text">Email</label>
                                </div>
                            </div>
                            <div class="Polaris-TextField Polaris-TextField--hasValue">
                                {{ Form::text('vendor_email', null, ['class' => 'Polaris-TextField__Input', 'id' => 'vendor_email', 'required']) }}                                        
                                <div class="Polaris-TextField__Backdrop"></div>                                        
                            </div>
                            <span class="k-invalid-msg" data-for="vendor_email"></span>
                        </div>
                    </div>
                </div>
                <div class="Polaris-FormLayout__Items">
                    <div class="Polaris-FormLayout__Item">
                        <div class="">
                            <div class="Polaris-Labelled__LabelWrapper">
                                <div class="Polaris-Label">
                                    <label id="pass_code_label" for="pass_code" class="Polaris-Label__Text">Redeem Code</label>
                                </div>
                            </div>
                            <div class="Polaris-TextField Polaris-TextField--hasValue">
                                {{ Form::text('pass_code', null, ['class' => 'Polaris-TextField__Input', 'id' => 'pass_code', 'required']) }}
                                <div class="Polaris-TextField__Backdrop"></div>
                            </div>
                            <span class="k-invalid-msg" data-for="pass_code"></span>
                        </div>
                    </div>
                    <div class="Polaris-FormLayout__Item">
                        <div class="">
                            <div class="Polaris-Labelled__LabelWrapper">
                                <div class="Polaris-Label">
                                    <label id="offer_desc_label" for="offer_desc" class="Polaris-Label__Text">Offer Description</label>
                                </div>
                            </div>
                            <div class="Polaris-TextField Polaris-TextField--hasValue">
                                {{ Form::text('offer_desc', null, ['class' => 'Polaris-TextField__Input', 'id' => 'offer_desc', 'required']) }}                                        
                                <div class="Polaris-TextField__Backdrop"></div>                                        
                            </div>
                            <span class="k-invalid-msg" data-for="offer_desc"></span>
                        </div>
                    </div>
                </div>
                <div class="Polaris-FormLayout__Items">
                    <div class="Polaris-FormLayout__Item">
                        <div class="k-content">
                            <div class="Polaris-Labelled__LabelWrapper">
                                <div class="Polaris-Label">
                                    <label id="redeem_txt_label" for="redeem_txt" class="Polaris-Label__Text">Redeem Instructions</label>
                                </div>
                            </div>
                            <div class="Polaris-TextField Polaris-TextField--hasValue">
                                {{ Form::text('redeem_txt', null, ['class' => 'Polaris-TextField__Input', 'id' => 'redeem_txt', 'required']) }}                                        
                                <div class="Polaris-TextField__Backdrop"></div>                                        
                            </div>
                            <span class="k-invalid-msg" data-for="redeem_txt"></span>
                        </div>
                    </div>
                    <div class="Polaris-FormLayout__Item">
                        <div class="">
                            <div class="Polaris-Labelled__LabelWrapper">
                                <div class="Polaris-Label">
                                    <label id="max_pass_use_label" for="max_pass_use" class="Polaris-Label__Text">Max pass uses at this venue</label>
                                </div>
                            </div>
                            {{ Form::number('max_pass_use', null, ['style' => 'width:100%', 'id' => 'max_pass_use']) }}
                            <span class="k-invalid-msg" data-for="max_pass_use"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="vendorLocationDiv">
                @include('store.vendors.vendor_location')
            </div>
            <div id="vendorLocationEditDiv" style="display:none">
                @include('store.vendors.vendor_location_edit')
            </div>
        </div>
    </div>
</div>