@extends('layouts.main')

@section('content')
    <x-guest-layout>
        <p class="font-share-tech text-center text-3xl font-medium mb-4">Connexion</p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" placeholder="Saisir votre email" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" placeholder="Saisir votre mot de passe" required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="border-gray-300 focus:border-custom-blue focus:ring-custom-blue rounded-sm shadow-sm transition duration-300 ease-in-out" name="remember">
                    <span class="ml-2 text-sm">{{ __('Se souvenir de moi') }}</span>
                </label>
            </div>
            
            <!-- Forgot password -->
            <div class="flex">
                <div class="block mt-2">
                    @if (Route::has('password.request'))
                    <a class="text-sm text-custom-grey hover:text-custom-blue hover:underline transition duration-300 ease-in-out" href="{{ route('password.request') }}">
                        {{ __('Mot de passe oublié ?') }}
                    </a>
                    @endif
                </div>
                
                <!-- Not register -->
                <div class="block ml-auto mt-2">
                    <a class="text-sm text-custom-grey hover:text-custom-blue hover:underline transition duration-300 ease-in-out" href="{{ route('register') }}">
                        {{ __('Pas encore inscrit ?') }}
                    </a>
                </div>
            </div>

            <div class="flex items-center justify-end mt-8">
                <x-primary-button class="ml-3">
                    {{ __('Se connecter') }}
                </x-primary-button>
            </div>
        </form>
    </x-guest-layout>
@endsection
