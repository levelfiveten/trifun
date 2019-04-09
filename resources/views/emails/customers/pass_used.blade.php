@component('mail::message')
@if (!is_null($regionLogo))
![alt text][logo]
[logo]: {{asset('/images/'.$regionLogo)}} "logo"
@endif

# Hi {{ $customerName }}, thanks for using your Tri-Fun pass!

@component('mail::panel')
<strong>{{ $passPurchase->passType->name }} Pass</strong><br>
Customer: {{ $customerName }} ({{ $customerEmail }}) <br>
Confirmation #{{ $confCode }}<br>
Quantity: {{ $qty }}<br>
Offer: {{ $vendor->offer_desc }}<br>
Venue: {{ $vendor->name }}<br>
Location: {{ $location->name }} <small>{{$location->address1}} {{$location->address2}} {{$location->city}}, {{$location->state}} {{$location->zipcode}}</small>
@endcomponent

@component('mail::button', ['url' => env('PASS_PURCHASE_URL')])
Purchase Passes
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
