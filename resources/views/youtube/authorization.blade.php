<x-app-layout>
    @include('layouts.slot_header')
    <h3>Authorization Required</h3>
    <p>You need to <a href="{{ $authUrl }}">authorize access</a> before proceeding.<p>
</x-app-layout>