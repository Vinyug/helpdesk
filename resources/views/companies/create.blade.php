@extends('layouts.main')

@section('content')
    <div class="container mb-16 mx-auto sm:px-4">
        <div class="flex flex-wrap">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <div class="pull-left mb-2">
                    <h2 class="font-share-tech mt-8 mb-12 text-4xl">Créer une entreprise</h2>
                </div>
                <div class="pull-right my-3">
                    <a class="btn-blue" href="{{ route('companies.index') }}"> Retour</a>
                </div>
            </div>
        </div>

        @if(session('status'))
        <div class="custom-status">
            {{ session('status') }}
        </div>
        @endif

        <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid sm:grid-cols-2 gap-4 p-4">    
                <div class="col-span-full">
                    <label for="name" class="custom-label">Nom de l'entreprise : <span class="text-red-600 font-bold">*</span></label>
                    <input type="text" name="name" id="name" class="custom-input" placeholder="Saisir le nom de l'entreprise" value="{{ old('name') }}" required>
                    @error('name')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-full">
                    <label for="address" class="custom-label">Adresse : <span class="text-red-600 font-bold">*</span></label>
                    <input type="text" name="address" id="address" class="custom-input" placeholder="Saisir l'adresse de l'entreprise" value="{{ old('address') }}" required>
                    @error('address')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="w-full">
                    <label for="city" class="custom-label">Ville : <span class="text-red-600 font-bold">*</span></label>
                    <input type="text" name="city" id="city" class="custom-input" placeholder="Saisir la ville de l'entreprise" value="{{ old('city') }}" required>
                    @error('city')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="w-full">
                    <label for="zip_code" class="custom-label">Code postal : <span class="text-red-600 font-bold">*</span></label>
                    <input type="text" name="zip_code" id="zip_code" class="custom-input" placeholder="Saisir le code postal de l'entreprise" value="{{ old('zip_code') }}" required>
                    @error('zip_code')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="w-full">
                    <label for="siret" class="custom-label">SIRET : </label>
                    <input type="text" name="siret" id="siret" class="custom-input" placeholder="Saisir le SIRET de l'entreprise" value="{{ old('siret') }}">
                    @error('siret')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="w-full">
                    <label for="code_ape" class="custom-label">Code APE : </label>
                    <input type="text" name="code_ape" id="code_ape" class="custom-input" placeholder="Saisir le code APE de l'entreprise" value="{{ old('code_ape') }}">
                    @error('code_ape')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="w-full">
                    <label for="phone" class="custom-label">Téléphone : </label>
                    <input type="tel" name="phone" id="phone" class="custom-input" placeholder="Saisir le téléphone de l'entreprise" value="{{ old('phone') }}">
                    @error('phone')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="w-full">
                    <label for="email" class="custom-label">Email : <span class="text-red-600 font-bold">*</span></label>
                    <input type="email" name="email" id="email" class="custom-input" placeholder="Saisir l'adresse email de l'entreprise" value="{{ old('email') }}" required>
                    @error('email')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="block col-span-full text-red-600 mb-5 ml-4">* les champs obligatoires</div>

                <div class="col-span-full">
                    <button type="submit" class="btn-orange">Créer</button>
                </div>
            </div>
        </form>
    </div>
@endsection