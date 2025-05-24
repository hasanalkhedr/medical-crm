@if($country)
<div class="flex items-center gap-2">
    <span class="fi fi-{{ $country['flag'] }}"></span>
    <span>+{{ $country['code'] }}</span>
</div>
@endif
