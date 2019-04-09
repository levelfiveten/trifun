<div class="Polaris-Layout__AnnotationContent">
    <div class="Polaris-Card">
        <div class="Polaris-Card__Section">
            <form id="createRegion">
                <div class="Polaris-FormLayout">
                    <div role="group" class="">
                        <div class="Polaris-FormLayout__Items">
                            <div class="Polaris-FormLayout__Item">
                                <div class="">
                                    <div class="Polaris-Labelled__LabelWrapper">
                                        <div class="Polaris-Label">
                                            <label id="region_name_label" for="region_name" class="Polaris-Label__Text">Name</label>
                                        </div>
                                    </div>
                                    <div class="Polaris-TextField Polaris-TextField--hasValue">
                                        {{ Form::text('region_name', null, ['class' => 'Polaris-TextField__Input', 'id' => 'region_name', 'required']) }}
                                        <div class="Polaris-TextField__Backdrop"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="Polaris-FormLayout__Item">
                                <div class="">
                                    <div class="Polaris-Labelled__LabelWrapper">
                                        <div class="Polaris-Label">
                                            <label id="region_name_label" for="region_code" class="Polaris-Label__Text">Code (use on product SKU)</label>
                                        </div>
                                    </div>
                                    <div class="Polaris-TextField Polaris-TextField--hasValue">
                                        {{ Form::text('region_code', null, ['class' => 'Polaris-TextField__Input', 'id' => 'region_code', 'required']) }}
                                        <div class="Polaris-TextField__Backdrop"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="Polaris-Card__Footer">
                    <div class="Polaris-ButtonGroup">
                        <div class="Polaris-ButtonGroup__Item">
                            <button id="regionSaveBtn" type="submit" class="save-btn Polaris-Button Polaris-Button--primary"><span class="Polaris-Button__Content"><span>Add</span></span></button>
                            <button id="regionLoadBtn" type="button" class=" load-btn Polaris-Button Polaris-Button--disabled Polaris-Button--loading" disabled="" role="alert" aria-busy="true" style="display:none"><span class="Polaris-Button__Content"><span class="Polaris-Button__Spinner"><svg viewBox="0 0 20 20" class="Polaris-Spinner Polaris-Spinner--colorInkLightest Polaris-Spinner--sizeSmall" aria-label="Loading" role="status"><path d="M7.229 1.173a9.25 9.25 0 1 0 11.655 11.412 1.25 1.25 0 1 0-2.4-.698 6.75 6.75 0 1 1-8.506-8.329 1.25 1.25 0 1 0-.75-2.385z"></path></svg></span><span>Save</span></span></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>