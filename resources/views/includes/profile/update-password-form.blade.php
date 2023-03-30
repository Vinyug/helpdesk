<section>
    <header>
        <h2 class="text-base sm:text-lg font-bold">
            {{ __('Mettre à jour votre mot de passe') }}
        </h2>

        <p class="text-sm sm:text-base mt-1">
            {{ __("Veillez à ce que votre compte utilise un mot de passe long et aléatoire pour rester sécurisé.") }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="grid sm:grid-cols-2 gap-4 p-4"> 
            <div class="col-span-full">
                <x-input-label for="current_password" :value="__('Mot de passe actuel :')" />
                <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <div class="w-full">
                <x-input-label for="password" :value="__('Nouveau mot de passe :')" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div class="w-full">
                <x-input-label for="password_confirmation" :value="__('Confimer mot de passe :')" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="col-span-full flex items-center gap-4">
                <button type="submit" class="btn-comment-orange">{{ __('Modifier') }}</button>

                @if (session('status') === 'password-updated')
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
