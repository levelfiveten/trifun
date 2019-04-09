<div class="Polaris-FormLayout__Items">
    <div class="Polaris-FormLayout__Item">
        <div class="">
            <div class="Polaris-Labelled__LabelWrapper">
                <div class="Polaris-Label">
                    <label id="x_location_name_label" for="x_location_name" class="Polaris-Label__Text">Location Name</label>
                </div>
            </div>
            <div class="Polaris-TextField Polaris-TextField--hasValue">
                {{ Form::hidden('x_location_id', null, ['id' => 'x_location_id']) }}
                {{ Form::text('x_location_name', null, ['class' => 'Polaris-TextField__Input', 'id' => 'x_location_name']) }}                                        
                <div class="Polaris-TextField__Backdrop"></div>                                        
            </div>
            <span class="k-invalid-msg" data-for="x_location_name"></span>
        </div>
    </div>
    <div class="Polaris-FormLayout__Item">
        <div class="">
            <div class="Polaris-Labelled__LabelWrapper">
                <div class="Polaris-Label">
                    <label id="x_address1_label" for="x_address1" class="Polaris-Label__Text">Address Line 1</label>
                </div>
            </div>
            <div class="Polaris-TextField Polaris-TextField--hasValue">
                {{ Form::text('x_address1', null, ['class' => 'Polaris-TextField__Input', 'id' => 'x_address1']) }}                                        
                <div class="Polaris-TextField__Backdrop"></div>                                        
            </div>
            <span class="k-invalid-msg" data-for="x_address1"></span>
        </div>
    </div>
    <div class="Polaris-FormLayout__Item">
        <div class="">
            <div class="Polaris-Labelled__LabelWrapper">
                <div class="Polaris-Label">
                    <label id="x_address2_label" for="x_address2" class="Polaris-Label__Text">Address Line 2</label>
                </div>
            </div>
            <div class="Polaris-TextField Polaris-TextField--hasValue">
                {{ Form::text('x_address2', null, ['class' => 'Polaris-TextField__Input', 'id' => 'x_address2']) }}                                        
                <div class="Polaris-TextField__Backdrop"></div>                                        
            </div>
            <span class="k-invalid-msg" data-for="x_address2"></span>
        </div>
    </div>
    <div class="Polaris-FormLayout__Item">
        <div class="">
            <div class="Polaris-Labelled__LabelWrapper">
                <div class="Polaris-Label">
                    <label id="x_city_label" for="x_city" class="Polaris-Label__Text">City</label>
                </div>
            </div>
            <div class="Polaris-TextField Polaris-TextField--hasValue">
                {{ Form::text('x_city', null, ['class' => 'Polaris-TextField__Input', 'id' => 'x_city']) }}
                <div class="Polaris-TextField__Backdrop"></div>
            </div>
            <span class="k-invalid-msg" data-for="x_city"></span>
        </div>
    </div>
    <div class="Polaris-FormLayout__Item">
        <div class="">
            <div class="Polaris-Labelled__LabelWrapper">
                <div class="Polaris-Label">
                    <label id="x_state_label" for="x_state" class="Polaris-Label__Text">State</label>
                </div>
            </div>
            <div class="Polaris-TextField Polaris-TextField--hasValue">
                {{ Form::text('x_state', null, ['class' => 'Polaris-TextField__Input', 'id' => 'x_state']) }}                                        
                <div class="Polaris-TextField__Backdrop"></div>                                        
            </div>
            <span class="k-invalid-msg" data-for="x_state"></span>
        </div>
    </div>
    <div class="Polaris-FormLayout__Item">
        <div class="">
            <div class="Polaris-Labelled__LabelWrapper">
                <div class="Polaris-Label">
                    <label id="x_zipcode_label" for="x_zipcode" class="Polaris-Label__Text">Zipcode</label>
                </div>
            </div>
            <div class="Polaris-TextField Polaris-TextField--hasValue">
                {{ Form::text('x_zipcode', null, ['class' => 'Polaris-TextField__Input', 'id' => 'x_zipcode']) }}                                        
                <div class="Polaris-TextField__Backdrop"></div>                                        
            </div>
            <span class="k-invalid-msg" data-for="x_zipcode"></span>
        </div>
    </div>
</div>