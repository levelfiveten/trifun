<div class="Polaris-FormLayout__Items">
    <div class="Polaris-FormLayout__Item">
        <div class="">
            <div class="Polaris-Labelled__LabelWrapper">
                <div class="Polaris-Label">
                    <label id="location_name_label" for="location_name" class="Polaris-Label__Text">Location Name</label>
                </div>
            </div>
            <div class="Polaris-TextField Polaris-TextField--hasValue">
                {{ Form::text('location_name', null, ['class' => 'Polaris-TextField__Input', 'id' => 'location_name', 'required']) }}                                        
                <div class="Polaris-TextField__Backdrop"></div>                                        
            </div>
            <span class="k-invalid-msg" data-for="location_name"></span>
        </div>
    </div>
    <div class="Polaris-FormLayout__Item">
        <div class="">
            <div class="Polaris-Labelled__LabelWrapper">
                <div class="Polaris-Label">
                    <label id="address1_label" for="address1" class="Polaris-Label__Text">Address Line 1</label>
                </div>
            </div>
            <div class="Polaris-TextField Polaris-TextField--hasValue">
                {{ Form::text('address1', null, ['class' => 'Polaris-TextField__Input', 'id' => 'address1', 'required']) }}                                        
                <div class="Polaris-TextField__Backdrop"></div>                                        
            </div>
            <span class="k-invalid-msg" data-for="address1"></span>
        </div>
    </div>
    <div class="Polaris-FormLayout__Item">
        <div class="">
            <div class="Polaris-Labelled__LabelWrapper">
                <div class="Polaris-Label">
                    <label id="address2_label" for="address2" class="Polaris-Label__Text">Address Line 2</label>
                </div>
            </div>
            <div class="Polaris-TextField Polaris-TextField--hasValue">
                {{ Form::text('address2', null, ['class' => 'Polaris-TextField__Input', 'id' => 'address2']) }}                                        
                <div class="Polaris-TextField__Backdrop"></div>                                        
            </div>
            <span class="k-invalid-msg" data-for="address2"></span>
        </div>
    </div>
    <div class="Polaris-FormLayout__Item">
        <div class="">
            <div class="Polaris-Labelled__LabelWrapper">
                <div class="Polaris-Label">
                    <label id="city_label" for="city" class="Polaris-Label__Text">City</label>
                </div>
            </div>
            <div class="Polaris-TextField Polaris-TextField--hasValue">
                {{ Form::text('city', null, ['class' => 'Polaris-TextField__Input', 'id' => 'city', 'required']) }}
                <div class="Polaris-TextField__Backdrop"></div>
            </div>
            <span class="k-invalid-msg" data-for="city"></span>
        </div>
    </div>
    <div class="Polaris-FormLayout__Item">
        <div class="">
            <div class="Polaris-Labelled__LabelWrapper">
                <div class="Polaris-Label">
                    <label id="state_label" for="state" class="Polaris-Label__Text">State</label>
                </div>
            </div>
            <div class="Polaris-TextField Polaris-TextField--hasValue">
                {{ Form::text('state', null, ['class' => 'Polaris-TextField__Input', 'id' => 'state', 'required']) }}                                        
                <div class="Polaris-TextField__Backdrop"></div>                                        
            </div>
            <span class="k-invalid-msg" data-for="state"></span>
        </div>
    </div>
    <div class="Polaris-FormLayout__Item">
        <div class="">
            <div class="Polaris-Labelled__LabelWrapper">
                <div class="Polaris-Label">
                    <label id="zipcode_label" for="zipcode" class="Polaris-Label__Text">Zipcode</label>
                </div>
            </div>
            <div class="Polaris-TextField Polaris-TextField--hasValue">
                {{ Form::text('zipcode', null, ['class' => 'Polaris-TextField__Input', 'id' => 'zipcode', 'required']) }}                                        
                <div class="Polaris-TextField__Backdrop"></div>                                        
            </div>
            <span class="k-invalid-msg" data-for="zipcode"></span>
        </div>
    </div>
</div>