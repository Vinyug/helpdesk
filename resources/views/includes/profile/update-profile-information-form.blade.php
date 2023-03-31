<section>
    <header>
        <h2 class="text-base sm:text-lg font-bold">
            {{ __('Informations sur votre profil') }}
        </h2>

        <p class="text-sm sm:text-base mt-1">
            {{ __("Mettez à jour les informations de votre profil et l'adresse email de votre compte.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid sm:grid-cols-2 gap-4 p-4"> 
            <div class="w-full">
                <x-input-label for="firstname" :value="__('Prénom :')" />
                <x-text-input id="firstname" name="firstname" type="text" class="mt-1 block w-full" :value="old('firstname', $user->firstname)" required autofocus autocomplete="firstname" />
                <x-input-error class="mt-2" :messages="$errors->get('firstname')" />
            </div>

            <div class="w-full">
                <x-input-label for="lastname" :value="__('Nom :')" />
                <x-text-input id="lastname" name="lastname" type="text" class="mt-1 block w-full" :value="old('lastname', $user->lastname)" required autofocus autocomplete="lastname" />
                <x-input-error class="mt-2" :messages="$errors->get('lastname')" />
            </div>

            <div class="col-span-full">
                <x-input-label for="email" :value="__('Email :')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2">
                            {{ __('Votre email n\'est pas vérifié.') }}

                            <button form="send-verification" class="text-sm text-custom-grey hover:text-custom-blue hover:underline transition duration-300 ease-in-out">
                                {{ __('Cliquez-ici pour transmettre l\'email de vérification.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('Un nouveau lien de vérification  a bien été envoyé sur votre adresse email.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="col-span-full flex items-center gap-4">
                <button type="submit" class="btn-comment-orange">{{ __('Modifier') }}</button>

                @if (session('status') === 'profile-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-custom-blue"
                    >{{ __('Sauvegardé !') }}</p>
                @endif
            </div>
        </div>
    </form>
</section>
