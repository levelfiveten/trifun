<div class="Polaris-Layout__AnnotationContent">
    <div class="Polaris-Card">
        <div class="Polaris-Card__Section">
            <form id="demoAccount">
                <div class="Polaris-FormLayout">
                    <div role="group" class="">
                        <div class="Polaris-FormLayout__Items">
                            <div class="Polaris-FormLayout__Item">
                                <div class="">
                                    <div class="Polaris-Labelled__LabelWrapper">
                                        <div class="Polaris-Label">
                                            <label id="demo_name_label" for="demo_name" class="Polaris-Label__Text">Name</label>
                                        </div>
                                    </div>
                                    <div class="Polaris-TextField Polaris-TextField--hasValue">
                                        {{ Form::hidden('demo_reset', false, ['id' => 'demo_reset']) }}
                                        {{ Form::text('demo_name', null, ['class' => 'Polaris-TextField__Input', 'id' => 'demo_name', 'required']) }}
                                        <div class="Polaris-TextField__Backdrop"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="Polaris-FormLayout__Item">
                                <div class="">
                                    <div class="Polaris-Labelled__LabelWrapper">
                                        <div class="Polaris-Label">
                                            <label id="demo_email_label" for="demo_email" class="Polaris-Label__Text">Email</label>
                                        </div>
                                    </div>
                                    <div class="Polaris-TextField Polaris-TextField--hasValue">                                        
                                        {{ Form::text('demo_email', null, ['class' => 'Polaris-TextField__Input', 'id' => 'demo_email', 'required', 'aria-labelledby' => 'demo_email_label demo_email_suffix']) }}
                                        <div class="Polaris-TextField__Suffix" id="demo_email_suffix">@tri-fun.com</div>
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
                            <button id="demoResetSaveBtn" type="submit" class="save-btn Polaris-Button Polaris-Button--destructive"><span class="Polaris-Button__Content"><span>Reset Passes</span></span></button>
                            <button id="demoResetLoadBtn" type="button" class=" load-btn Polaris-Button Polaris-Button--disabled Polaris-Button--loading" disabled="" role="alert" aria-busy="true" style="display:none"><span class="Polaris-Button__Content"><span class="Polaris-Button__Spinner"><svg viewBox="0 0 20 20" class="Polaris-Spinner Polaris-Spinner--colorInkLightest Polaris-Spinner--sizeSmall" aria-label="Loading" role="status"><path d="M7.229 1.173a9.25 9.25 0 1 0 11.655 11.412 1.25 1.25 0 1 0-2.4-.698 6.75 6.75 0 1 1-8.506-8.329 1.25 1.25 0 1 0-.75-2.385z"></path></svg></span><span>Save</span></span></button>
                        </div>
                        <div class="Polaris-ButtonGroup__Item">
                            <button id="demoRegisterSaveBtn" type="submit" class="save-btn Polaris-Button Polaris-Button--primary"><span class="Polaris-Button__Content"><span>Register</span></span></button>
                            <button id="demoRegisterLoadBtn" type="button" class=" load-btn Polaris-Button Polaris-Button--disabled Polaris-Button--loading" disabled="" role="alert" aria-busy="true" style="display:none"><span class="Polaris-Button__Content"><span class="Polaris-Button__Spinner"><svg viewBox="0 0 20 20" class="Polaris-Spinner Polaris-Spinner--colorInkLightest Polaris-Spinner--sizeSmall" aria-label="Loading" role="status"><path d="M7.229 1.173a9.25 9.25 0 1 0 11.655 11.412 1.25 1.25 0 1 0-2.4-.698 6.75 6.75 0 1 1-8.506-8.329 1.25 1.25 0 1 0-.75-2.385z"></path></svg></span><span>Save</span></span></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$('#demoResetSaveBtn').click(function () {
    $('#demo_reset').val(true);
});
$('#demoRegisterSaveBtn').click(function () {
    $('#demo_reset').val(false);
});
</script>