@extends('layouts.main')

@section('content')
    <x-guest-layout>

        <p class="font-share-tech text-center text-3xl font-medium mb-4">Inscription</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Firstname -->
            <div>
                <x-input-label for="firstname" :value="__('Prénom')" />
                <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" :value="old('firstname')" placeholder="Saisir votre Prénom" required autofocus autocomplete="firstname" />
                <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
            </div>

            <!-- Lastname -->
            <div class="mt-4">
                <x-input-label for="lastname" :value="__('Nom')" />
                <x-text-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname')" placeholder="Saisir votre Nom" required autofocus autocomplete="lastname" />
                <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" placeholder="Saisir votre email" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Mot de passe')" />

                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" placeholder="Saisir votre mot de passe" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirmation du mot de passe')" />

                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" placeholder="Saisir votre confirmation de mot de passe" name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="block mt-4">
                <a class="text-sm text-custom-grey hover:text-custom-blue hover:underline transition duration-300 ease-in-out" href="{{ route('login') }}">
                    {{ __('Déjà inscrit ?') }}
                </a>
            </div>

            <div class="flex items-center justify-end mt-8">
                <x-primary-button class="ml-4">
                    {{ __('S\'inscrire') }}
                </x-primary-button>
            </div>
        </form>
    </x-guest-layout>
@endsection

