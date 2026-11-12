<x-app-layout>
    <x-slot name="header"><strong>Account settings</strong></x-slot>
    <div class="row g-4 justify-content-center">
        <div class="col-lg-8"><section class="account-panel">@include('profile.partials.update-profile-information-form')</section></div>
        <div class="col-lg-8"><section class="account-panel">@include('profile.partials.update-password-form')</section></div>
        <div class="col-lg-8"><section class="account-panel account-danger">@include('profile.partials.delete-user-form')</section></div>
    </div>
</x-app-layout>
