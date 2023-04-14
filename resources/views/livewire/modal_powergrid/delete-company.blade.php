<form method="post" action="{{ route('companies.destroy', $company->uuid) }}" class="p-6">
    @csrf
    @method('DELETE')

    <h2 class="text-lg font-medium">Êtes-vous sûr de vouloir supprimer cette entreprise ?</h2>

    <p class="mt-1 text-sm text-custom-grey">Une fois cette entreprise supprimée, toutes ses ressources et données seront définitivement effacées.</p>
    
    @if ($company->users()->exists())
        <p class="mt-4 text-sm font-medium text-red-600">Vous ne pouvez pas supprimer l'entreprise. Tous les employés doivent être réattribué à une nouvelle entreprise.</p>
    @endif

    <div class="mt-2 flex flex-wrap justify-end">
        <x-secondary-button class="mt-4 ml-0 w-full sm:w-auto" x-on:click="$dispatch('close')">
            {{ __('Annuler') }}
        </x-secondary-button>

        @if (!$company->users()->exists())
            <x-danger-button class="ml-0 mt-4 sm:ml-3 w-full sm:w-auto">
                {{ __('Confirmer suppression') }}
            </x-danger-button>
        @else
            <a href="{{ route('users.index')}}" class="btn-comment-orange ml-0 mt-4 sm:ml-3 w-full sm:w-auto cursor-pointer">Liste des utilisateurs</a>
        @endif
    </div>
</form>


