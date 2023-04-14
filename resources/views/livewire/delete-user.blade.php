<form method="post" action="{{ route('users.destroy', $user->id) }}" class="p-6">
    @csrf
    @method('DELETE')

    <h2 class="text-lg font-medium">Êtes-vous sûr de vouloir supprimer cet utilisateur ?</h2>

    <p class="mt-1 text-sm text-custom-grey">Une fois cet utilisateur supprimé, toutes ses ressources et données seront définitivement effacées.</p>

    <div class="mt-2 flex flex-wrap justify-end">
        <x-secondary-button class="mt-4 ml-0 w-full sm:w-auto" x-on:click="$dispatch('close')">
            {{ __('Annuler') }}
        </x-secondary-button>

        <x-danger-button class="ml-0 mt-4 sm:ml-3 w-full sm:w-auto">
            {{ __('Confirmer suppression') }}
        </x-danger-button>
    </div>
</form>