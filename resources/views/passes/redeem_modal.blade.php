<div class="modal fade" id="redeemModal" tabindex="-1" role="dialog" aria-labelledby="redeemModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="vendorRedeemTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {!! Form::open(['url' => $redeemRoute, 'method' => 'post', 'id' => 'redeemForm']) !!}
      <div class="modal-body">
        <p id="redeemVendorTxt"></p>
        <p>*<small id="vendorOfferTxt"></small></p>
        <div class="form-group row">
            <label for="redeemVendorLocationId" class="col-sm-2 col-form-label">Location</label>
            <div class="col-sm-10">
            {!! Form::select('redeemVendorLocationId', ['' => 'Select location'], null, ['class' => 'form-control', 'id' => 'redeemVendorLocationId']) !!}
            </div>
        </div>
        <div class="form-group row">
            <label for="redeemVendorLocationId" class="col-sm-2 col-form-label">Quantity</label>
            <div class="col-sm-10">
            <input class="form-control" name="redeemVendorQuantity" id="redeemVendorQuantity" type="number" pattern="[0-9]*" />
            </div>
        </div>

        <div class="form-group row">
            <label for="redeemCode" class="col-sm-2 col-form-label">Code</label>
            <div class="col-sm-10">
                <input class="form-control" type="text" name="redeemCode" id="redeemCode" placeholder="Enter redemption code" required>
            </div>
            <input class="form-control" type="hidden" name="redeemVendorId" id="redeemVendorId">
        </div>
      </div>
        <div id="errorMsg" style="display:none"> 
            <div id="errorTxt" class="alert alert-danger" style="text-align:center;margin-left:10px;margin-right:10px;"></div>        
        </div>
        <div id="resultMsg" style="display:none"> 
            <div id="resultTxt" class="alert alert-success" style="text-align:center;margin-left:10px;margin-right:10px;"></div>            
        </div>
      <div class="modal-footer justify-content-center">
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
        <button id="redeemSubmit" type="submit" class="btn btn-lg btn-warning" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Processing...">Redeem Now!</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>