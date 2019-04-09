<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="historyModalTitle">History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="historyModalBody" class="modal-body">
        @foreach($allPassUses as $passUsage) 
          <?php 
            $passUse = \App\PassUsage::where('conf_code', $passUsage->conf_code)->get();
          ?>
            <p>
              <span class="float-left">
                <span class="badge badge-secondary">{{ $passUsage->pass_type_name }}</span><small>  {{ \App\Helpers\Helper::convertDateTimeToApp($passUsage->pass_used_dt) }}</small>
              </span>
              <span class="float-right"><small>#{{ $passUsage->conf_code }}</small></span>
            </p>
            <p style="clear:both"><small>{{ $passUsage->vendor_name }} ({{ $passUsage->vendor_location_name }})</small><br>
            <small>Quantity Used: {{ $passUse->count() }}</small></p>
            <!-- pass_purchase_id -->
        @endforeach
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>