<form method="post" action="{{ route('roles.destroy', $role->id) }}" class="p-6">
    @csrf
    @method('DELETE')

    <h2 class="text-lg font-medium">Êtes-vous sûr de vouloir supprimer ce rôle ?</h2>

    <p class="mt-1 text-sm text-custom-grey">Une fois ce rôle supprimé, toutes ses ressources et données seront définitivement effacées.</p>

    @if ($role->users()->exists())
        <p class="mt-4 text-sm font-medium text-red-600">Vous ne pouvez pas supprimer le rôle. Au moins un utilisateur dispose de ce rôle, il faut lui en réattribuer un autre.</p>
    @endif

    <div class="mt-2 flex flex-wrap justify-end">
        <x-secondary-button class="mt-4 ml-0 w-full sm:w-auto" x-on:click="$dispatch('close')">
            {{ __('Annuler') }}
        </x-secondary-button>

        @if (!$role->users()->exists())
            <x-danger-button class="ml-0 mt-4 sm:ml-3 w-full sm:w-auto">
                {{ __('Confirmer suppression') }}
            </x-danger-button>
        @else
            <a href="{{ route('users.index')}}" class="btn-solid-orange ml-0 mt-4 sm:ml-3 w-full sm:w-auto cursor-pointer">Liste des utilisateurs</a>
        @endif
    </div>
</form>


