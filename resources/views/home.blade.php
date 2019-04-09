@extends('layouts.app')
@section('head')
<style>
.region {
    margin-right: 15px;
}
.dining {
    background-image: linear-gradient(to bottom right, #990000, #ff0000);
}
#experience {
    background-image: linear-gradient(to bottom right, #ff8507, #ffc107);
}
</style>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="row">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                @if ($diningPasses->count() == 0 || $expPasses->count() == 0)
                <div class="col-md-12 pass-div">
                    <p><a href="https://tri-fun.com/collections/all" class="dining btn btn-outline-secondary btn-lg btn-block" role="button" aria-pressed="true" style="background-image: linear-gradient(to bottom right, #990000, #ff0000);color:#fff">Buy a Pass</a></p>
                </div>
                @endif
                <div class="col-md-12 pass-div">
                    @if ($diningPasses->count() > 0)
                    <a href="#" id="dining" name="dining" data-toggle="modal" data-target="{{ ($diningRegions->count() > 1) ? '#regionModal' : '#' }}" class="card bg-dark text-white mb-3" style="background-image: linear-gradient(to bottom right, #990000, #ff0000);text-decoration: none;">
                    @else
                    <div id="dining" name="dining" class="card bg-dark text-white mb-3"  style="background-color: #333;text-decoration: none;">
                    @endif
                        <div class="card-body">
                            <h3 class="card-title">Dining Passes <span class="badge badge-light">{{ $diningPasses->count() }}</span></h3>
                            <p class="card-text">
                                <small style="text-decoration:underline">Active Passes</small><br>
                                <?php $i = 0; ?>
                                @foreach($diningPasses as $diningPass)
                                    <?php $i++; ?>
                                        <p>Pass #{{$i}}: ({{ $diningPass->passType->region->name }})<br>
                                            <?php
                                                $remainingUses = $diningPass->getRemainingUses();
                                                $maxPassUsage = $diningPass->passType->usage_limit;
                                                $usedCount = $diningPass->passUsages->count();
                                            ?>
                                            @for($j = 0; $j < $maxPassUsage; $j++)
                                                @if($usedCount > 0)
                                                    <i class="far fa-circle"></i>                                                    
                                                @else
                                                    <i class="fas fa-circle"></i>
                                                @endif
                                                <?php $usedCount--; ?>
                                            @endfor
                                        </p>
                                @endforeach
                            </p>
                        </div>
                    </a>
                    <!-- <button id="showActiveDiningPassBtn" type="button" class="btn btn-outline-secondary btn-lg btn-block" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Show More</button> -->
                </div>
               
                <div class="col-md-12 pass-div">
                    @if ($expPasses->count() > 0)
                    <a href="#" id="experience" name="experience" data-toggle="modal" data-target="{{ ($expRegions->count() > 1) ? '#regionModal' : '#' }}" class="card bg-dark text-white mb-3" style="background-image: linear-gradient(to bottom right, #ff8507, #ffc107);text-decoration: none;">
                    @else
                    <div class="card bg-dark text-white mb-3" style="background-color: #333;text-decoration: none;">
                    @endif
                        <div class="card-body">
                            <h3 class="card-title">Experience Passes <span class="badge badge-light">{{ $expPasses->count() }}</span></h3>
                            <p class="card-text">
                                <small style="text-decoration:underline">Active Passes</small><br>                            
                                <?php $i = 0; ?>
                                @foreach($expPasses as $expPass)
                                    <?php $i++; ?>
                                        <p>Pass #{{$i}}: ({{ $expPass->passType->region->name }})<br>
                                            <?php 
                                                $remainingUses = $expPass->getRemainingUses(); 
                                                $vendorCount = $expPass->getVendorCount();
                                                $withdrawnVendorCount = $expPass->getWithdrawnVendorCount();
                                                $usedCount = $expPass->passUsages->count();
                                            ?>
                                            @for($k = 0; $k < $vendorCount; $k++)
                                                @if($usedCount > 0)
                                                    <i class="far fa-circle"></i>
                                                @else
                                                    <i class="fas fa-circle"></i>
                                                @endif
                                                <?php $usedCount--; ?>
                                            @endfor
                                        </p>
                                @endforeach
                            </p>
                        </div>
                    </a>                    
                </div>

                <div class="col-md-12 pass-div">
                    @if ($miniPasses->count() > 0)
                    <a href="#" id="mini" name="mini" data-toggle="modal" data-target="{{ ($miniPasses->count() > 1) ? '#regionModal' : '#' }}" class="card bg-dark text-white mb-3" style="background-image: linear-gradient(to bottom right, #990000, #ff0000);text-decoration: none;">
                    @else
                    <div class="card bg-dark text-white mb-3" style="background-color: #333;text-decoration: none;">
                    @endif
                        <div class="card-body">
                            <h3 class="card-title">Mini Passes <span class="badge badge-light">{{ $miniPasses->count() }}</span></h3>
                            <p class="card-text">
                                <small style="text-decoration:underline">Active Offers</small><br>                            
                                <?php $i = 0; ?>
                                @foreach($miniPasses as $miniPass)
                                    <?php $i++; ?>
                                        <p>Pass #{{$i}}: ({{ $miniPass->passType->region->name }})<br>
                                            <?php 
                                                $remainingUses = $miniPass->getRemainingUses(); 
                                                $vendorCount = $miniPass->getVendorCount();
                                                $withdrawnVendorCount = $miniPass->getWithdrawnVendorCount();
                                                $usedCount = $miniPass->passUsages->count();
                                            ?>
                                            @for($k = 0; $k < $vendorCount; $k++)
                                                @if($usedCount > 0)
                                                    <i class="far fa-circle"></i>
                                                @else
                                                    <i class="fas fa-circle"></i>
                                                @endif
                                                <?php $usedCount--; ?>
                                            @endfor
                                        </p>
                                @endforeach
                            </p>
                        </div>
                    </a>                    
                </div>

                <div class="col-md-12 pass-div">
                    @if ($bonusPasses->count() > 0)
                    <a href="#" id="bonus" name="bonus" data-toggle="modal" data-target="{{ ($bonusRegions->count() > 1) ? '#regionModal' : '#' }}" class="card bg-dark text-white mb-3" style="background-image: linear-gradient(to bottom right, #990000, #ff0000);text-decoration: none;">
                    @else
                    <div class="card bg-dark text-white mb-3" style="background-color: #333;text-decoration: none;">
                    @endif
                        <div class="card-body">
                            <h3 class="card-title">Bonus Offers <span class="badge badge-light">{{ $bonusPasses->count() }}</span></h3>
                            <p class="card-text">
                                <small style="text-decoration:underline">Active Offers</small><br>                            
                                <?php $i = 0; ?>
                                @foreach($bonusPasses as $bonusPass)
                                    <?php $i++; ?>
                                        <p>Pass #{{$i}}: ({{ $bonusPass->passType->region->name }})<br>
                                            <?php 
                                                $remainingUses = $bonusPass->getRemainingUses(); 
                                                $vendorCount = $bonusPass->getVendorCount();
                                                $withdrawnVendorCount = $bonusPass->getWithdrawnVendorCount();
                                                $usedCount = $bonusPass->passUsages->count();
                                            ?>
                                            @for($k = 0; $k < $vendorCount; $k++)
                                                @if($usedCount > 0)
                                                    <i class="far fa-circle"></i>
                                                @else
                                                    <i class="fas fa-circle"></i>
                                                @endif
                                                <?php $usedCount--; ?>
                                            @endfor
                                        </p>
                                @endforeach
                            </p>
                        </div>
                    </a>                    
                </div>
                
                <!-- <div class="col-md-4">
                    <a href="" class="card bg-dark text-white mb-3" style="background-color: #333;">
                        <div class="card-body">
                            <h3 class="card-title">Special Offers Pass</h3>
                        </div>
                    </a>
                </div> -->
                <div class="col-md-12">
                    <button id="historyModalBtn" type="button" class="btn btn-outline-secondary btn-lg btn-block" data-toggle="modal" data-target="#historyModal">View History</button>
                </div>
            </div>
        </div>            
    </div>
</div>
<div id="diningRegions" style="display:none">
    <div class="row justify-content-center">
    @foreach($diningRegions as $diningRegion)
        <div class="col-xs-3 region" style="margin-right:15px;margin-bottom:15px;">
            <a id="diningRegion{{$diningRegion->id}}" role="button" class="btn btn-lg btn-secondary" href="{{ route('dining', ['regionId' => $diningRegion->id]) }}">{{ $diningRegion->name }}</a>            
        </div>
    @endforeach
    </div>
</div>
<div id="expRegions" style="display:none">
    <div class="row justify-content-center">
    @foreach($expRegions as $expRegion)
        <div class="col-xs-3 region" style="margin-right:15px;margin-bottom:15px;">
            <a id="expRegion{{$expRegion->id}}" role="button" class="btn btn-lg btn-secondary" href="{{ route('experience', ['regionId' => $expRegion->id]) }}">{{ $expRegion->name }}</a>            
        </div>
    @endforeach
    </div>
</div>
<div id="bonusRegions" style="display:none">
    <div class="row justify-content-center">
    @foreach($bonusRegions as $bonusRegion)
        <div class="col-xs-3 region" style="margin-right:15px;margin-bottom:15px;">
            <a id="bonusRegion{{$bonusRegion->id}}" role="button" class="btn btn-lg btn-secondary" href="{{ route('bonus', ['regionId' => $bonusRegion->id]) }}">{{ $bonusRegion->name }}</a>            
        </div>
    @endforeach
    </div>
</div>
@include('history_modal')
@include('region_modal')
@if ($diningRegions->count() == 1)
<script>
    $('#dining').click( function()
    {
        window.location.href = "{{ route('dining', ['regionId' => $diningRegions[0]->id]) }}";
    });
</script>
@else
<script>
    $('#dining').click( function()
    {
        $('#regionModalBody').html($('#diningRegions').html());
    });
</script>
@endif

@if ($expRegions->count() == 1)
<script>
    $('#experience').click( function()
    {
        window.location.href = "{{ route('experience', ['regionId' => $expRegions[0]->id]) }}";
    });
</script>
@else
<script>
    $('#experience').click( function()
    {
        $('#regionModalBody').html($('#expRegions').html());
    });
</script>
@endif

@if ($bonusRegions->count() == 1)
<script>
    $('#bonus').click( function()
    {
        window.location.href = "{{ route('bonus', ['regionId' => $bonusRegions[0]->id]) }}";
    });
</script>
@else
<script>
    $('#bonus').click( function()
    {
        $('#regionModalBody').html($('#bonusRegions').html());
    });
</script>
@endif

@if ($miniRegions->count() == 1)
<script>
    $('#mini').click( function()
    {
        window.location.href = "{{ route('mini', ['regionId' => $miniRegions[0]->id]) }}";
    });
</script>
@else
<script>
    $('#mini').click( function()
    {
        $('#regionModalBody').html($('#miniRegions').html());
    });
</script>
@endif

@endsection
