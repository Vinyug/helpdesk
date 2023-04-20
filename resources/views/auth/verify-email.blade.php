@extends('layouts.main')

@section('content')
    <x-guest-layout>
        <p class="font-share-tech text-center text-3xl font-medium mb-4">Vérification de compte</p>

        <div class="mb-4 text-sm lg:text-base">
            {{ __('Merci de vous être inscrit ! Avant de commencer, pourriez-vous vérifier votre adresse électronique en cliquant sur le lien que nous venons de vous envoyer ? Si vous n\'avez pas reçu l\'email, nous vous en enverrons un autre avec plaisir.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 text-sm lg:text-base text-custom-blue">
                {{ __('Un nouveau lien de vérification a été envoyé à l\'adresse électronique que vous avez fournie lors de votre inscription.') }}
            </div>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="mt-2 text-sm text-custom-grey hover:text-custom-blue hover:underline transition duration-300 ease-in-out">
                {{ __('Déconnexion') }}
            </button>
        </form>

        <form class="mt-8" method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Renvoyer l\'email de vérification') }}
                </x-primary-button>
            </div>
        </form>
        
    </x-guest-layout>
@endsection