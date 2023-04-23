<div class="mt-8 w-full lg:w-2/3 xl:w-1/2 2xl:w-2/5">
    <form>
        <div class="grid sm:grid-cols-2 gap-4 p-4">    
            <div class="w-full">
                <input type="text" wire:model="fullname" name="fullname" id="fullname" class="custom-input-contact" placeholder="Prénom / NOM *" required>
                @error('fullname')
                <div class="custom-error text-custom-blue">{{ $message }}</div>
                @enderror
            </div>

            <div class="w-full">
                <input type="email" wire:model="email" name="email" id="email" class="custom-input-contact" placeholder="Email *" required>
                @error('email')
                <div class="custom-error text-custom-blue">{{ $message }}</div>
                @enderror
            </div>

            <div class="w-full">
                <input type="text" wire:model="address" name="address" id="address" class="custom-input-contact" placeholder="Adresse">
                @error('address')
                <div class="custom-error text-custom-blue">{{ $message }}</div>
                @enderror
            </div>

            <div class="w-full">
                <input type="text" wire:model="company" name="company" id="company" class="custom-input-contact" placeholder="Entreprise">
                @error('company')
                <div class="custom-error text-custom-blue">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-span-full">
                <textarea wire:model="content" class="custom-input-contact h-40" name="content" id="content" placeholder="Message *"></textarea>
                @error('content')
                <div class="custom-error text-custom-blue">{{ $message }}</div>
                @enderror
            </div>

            <div class="block col-span-full text-custom-blue mb-5 ml-4">* les champs obligatoires</div>

            <div class="col-span-full">
                <button wire:click.prevent="submit" class="btn-orange sm:w-auto px-8 sm:ml-auto sm:mr-0 uppercase tracking-wide">Envoyer</button>
            </div>

            @if ($message = Session::get('success'))
                <div class="col-span-full pt-4 text-center text-custom-blue font-bold">
                    <p>{{ $message }}</p>
                </div>
            @endif
        </div>
    </form>
</div>