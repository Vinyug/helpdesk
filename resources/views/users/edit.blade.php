@extends('layouts.main')

@section('content')
    <div class="container mb-16 mx-auto sm:px-4">
        <div class="flex flex-wrap">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <div class="pull-left mb-2">
                    <h2 class="font-share-tech mt-8 mb-12 text-4xl">Modifier un utilisateur</h2>
                </div>
                <div class="pull-right my-3">
                    <a class="btn-blue" href="{{ route('users.index') }}"> Retour</a>
                </div>
            </div>
        </div>

        @if(session('status'))
        <div class="custom-status">
            {{ session('status') }}
        </div>
        @endif

        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid sm:grid-cols-2 gap-4 p-4">    
                <div class="col-span-full">
                    <label for="firstname" class="custom-label">Prénom : <span class="text-red-600 font-bold">*</span></label>
                    <input type="text" name="firstname" id="firstname" class="custom-input" placeholder="Saisir le Prénom" value="{{ $user->firstname }}" required>
                    @error('firstname')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-full">
                    <label for="lastname" class="custom-label">Nom : <span class="text-red-600 font-bold">*</span></label>
                    <input type="text" name="lastname" id="lastname" class="custom-input" placeholder="Saisir le Nom" value="{{ $user->lastname }}" required>
                    @error('lastname')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-full">
                    <label for="email" class="custom-label">Email : <span class="text-red-600 font-bold">*</span></label>
                    <input type="email" name="email" id="email" class="custom-input" placeholder="Saisir l'email" value="{{ $user->email }}" required>
                    @error('email')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>
                
                {{-- <div class="w-full">
                    <label for="company" class="custom-label">Entreprise : </label>
                    <select class="custom-input"name="company" id="company">
                        @if (empty($user->company_id))    
                        <option value="">Choisir une entreprise</option>
                        @else
                        <option value="{{ $user->company_id }}">{{ $user->company_id }}</option>
                        @endif
                        
                        @foreach ($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->name }}</option>   
                        @endforeach
                    </select>
                    @error('company')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div> --}}

                <div class="w-full">
                    <label for="company_id" class="custom-label">Entreprise : </label>
                    <select class="custom-input" name="company_id" id="company_id">
                        @if (empty($user->company_id))    
                        <option value="">Choisir une entreprise</option>
                        @else
                        <option value="{{ $user->company_id }}">{{ $user->company->name }}</option>
                        @endif

                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                    @error('company_id')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-full">                    
                    <label for="role" class="custom-label">Rôle : <span class="text-red-600 font-bold">*</span></label>
                    <select class="custom-input" name="roles[]" id="role" multiple="">
                        <option value="">Choisir un rôle </option> 
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" @if(in_array($role->name, $userRole)) selected="selected" @endif>{{ $role->name }}</option>
                        @endforeach
                    </select>

                    @error('roles')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="block col-span-full text-red-600 mb-5 ml-4">* les champs obligatoires</div>

                <div class="col-span-full">
                    <button type="submit" class="btn-orange">Modifier</button>
                </div>
            </div>
        </form>
    </div>
@endsection