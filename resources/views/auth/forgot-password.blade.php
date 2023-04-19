@extends('layouts.main')

@section('content')
    <x-guest-layout>
        <p class="font-share-tech text-center text-3xl font-medium mb-4">Mot de passe oublié</p>

        <div class="mb-4 text-sm lg:text-base">
            {{ __('Vous avez oublié votre mot de passe ? Pas de problème. Indiquez-nous votre adresse électronique et nous vous enverrons un lien de réinitialisation du mot de passe qui vous permettra d\'en choisir un nouveau.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 text-sm lg:text-base text-custom-blue" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-8">
                <x-primary-button>
                    {{ __('Lien de réinitialisation d\'email') }}
                </x-primary-button>
            </div>
        </form>
    </x-guest-layout>   
@endsection

