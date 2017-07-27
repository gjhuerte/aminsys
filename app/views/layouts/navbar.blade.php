@if(Auth::user()->accesslevel == 0)
@include('layouts.navbar.amo.default')
@else
@include('layouts.navbar.accounting.default')
@endif