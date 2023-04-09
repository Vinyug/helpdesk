@if (auth()->user()->can('company-edit'))
    
<form action="{{ route('companies.update',$company->uuid) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="grid sm:grid-cols-2 gap-x-8 gap-y-4 p-4">    
        @if (auth()->user()->can('all-access'))
        <div class="col-span-full">
            <div class="flex items-center">
                <label for="active" class="custom-label mb-0">Compte actif  :</label>
                <input type="checkbox" name="active" id="active" value="1" class="ml-4 border-gray-300 text-custom-blue focus:border-custom-blue focus:ring-custom-blue rounded-sm shadow-sm transition duration-300 ease-in-out" {{ old('active', $company->active) ? 'checked' : '' }}>
            </div>
            
            @error('active')
            <div class="custom-error">{{ $message }}</div>
            @enderror
        </div>
        @endif
        
        <div class="col-span-full">
            <label for="name" class="custom-label">Nom de l'entreprise : <span class="text-red-600 font-bold">*</span></label>
            <input type="text" name="name" id="name" class="custom-input" placeholder="Saisir le nom de l'entreprise" value="{{ old('name',$company->name) }}" required>
            @error('name')
            <div class="custom-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-span-full">
            <label for="address" class="custom-label">Adresse : <span class="text-red-600 font-bold">*</span></label>
            <input type="text" name="address" id="address" class="custom-input" placeholder="Saisir l'adresse de l'entreprise" value="{{ old('address', $company->address) }}" required>
            @error('address')
            <div class="custom-error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="w-full">
            <label for="city" class="custom-label">Ville : <span class="text-red-600 font-bold">*</span></label>
            <input type="text" name="city" id="city" class="custom-input" placeholder="Saisir la ville de l'entreprise" value="{{ old('city', $company->city) }}" required>
            @error('city')
            <div class="custom-error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="w-full">
            <label for="zip_code" class="custom-label">Code postal : <span class="text-red-600 font-bold">*</span></label>
            <input type="text" name="zip_code" id="zip_code" class="custom-input" placeholder="Saisir le code postal de l'entreprise" value="{{ old('zip_code', $company->zip_code) }}" required>
            @error('zip_code')
            <div class="custom-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="w-full">
            <label for="siret" class="custom-label">SIRET : </label>
            <input type="text" name="siret" id="siret" class="custom-input" placeholder="Saisir le SIRET de l'entreprise" value="{{ old('siret', $company->siret) }}">
            @error('siret')
            <div class="custom-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="w-full">
            <label for="code_ape" class="custom-label">Code APE : </label>
            <input type="text" name="code_ape" id="code_ape" class="custom-input" placeholder="Saisir le code APE de l'entreprise" value="{{ old('code_ape', $company->code_ape) }}">
            @error('code_ape')
            <div class="custom-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="w-full">
            <label for="phone" class="custom-label">Téléphone : </label>
            <input type="tel" name="phone" id="phone" class="custom-input" placeholder="Saisir le téléphone de l'entreprise" value="{{ old('phone', $company->phone) }}">
            @error('phone')
            <div class="custom-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="w-full">
            <label for="email" class="custom-label">Email : <span class="text-red-600 font-bold">*</span></label>
            <input type="email" name="email" id="email" class="custom-input" placeholder="Saisir l'adresse email de l'entreprise" value="{{ old('email', $company->email) }}" required>
            @error('email')
            <div class="custom-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="block col-span-full text-red-600 mb-5 ml-4">* les champs obligatoires</div>

        <div class="col-span-full">
            <button type="submit" class="@if(Route::currentRouteName() == 'profile.edit') btn-comment-orange @else btn-orange @endif">Modifier</button>
        </div>
    </div>
</form>

@else

<div class="grid sm:grid-cols-2 gap-x-8 gap-y-4 p-4">    
    <div class="col-span-full">
        <label for="name" class="custom-label">Nom de l'entreprise : </label>
        <p>{{ $company->name }}</p>
    </div>
    
    <div class="col-span-full">
        <label for="address" class="custom-label">Adresse : </label>
        <p>{{ $company->address }}</p>
    </div>
    
    <div class="w-full">
        <label for="city" class="custom-label">Ville : </label>
        <p>{{ $company->city }}</p>
    </div>
    
    <div class="w-full">
        <label for="zip_code" class="custom-label">Code postal : <span class="text-red-600 font-bold">*</span></label>
        <p>{{ $company->zip_code }}</p>
    </div>

    <div class="w-full">
        <label for="siret" class="custom-label">SIRET : </label>
        <p>{{ $company->siret }}</p>
    </div>

    <div class="w-full">
        <label for="code_ape" class="custom-label">Code APE : </label>
        <p>{{ $company->code_ape }}</p>
    </div>

    <div class="w-full">
        <label for="phone" class="custom-label">Téléphone : </label>
        <p>{{ $company->phone }}</p>
    </div>

    <div class="w-full">
        <label for="email" class="custom-label">Email : <span class="text-red-600 font-bold">*</span></label>
        <p>{{ $company->email }}</p>
    </div>

</div>

@endif
