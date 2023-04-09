<section>
    <header>
        <h2 class="text-base sm:text-lg font-bold">
            {{ __('Informations sur votre entreprise') }}
        </h2>

        @if (auth()->user()->can('company-edit'))
        <p class="text-sm sm:text-base mt-1">
            {{ __("Mettez à jour les informations de votre entreprise.") }}
        </p>
        @else
        <p class="text-sm sm:text-base mt-1">
            {{ __("Consultez les informations de votre entreprise.") }}
        </p>
        @endif
    </header>

    @include('includes.company.company-information')

</section>
