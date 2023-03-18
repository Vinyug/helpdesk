@extends('layouts.main')

@section('content')
    <div class="container mb-16 mx-auto sm:px-4">
        <div class="flex flex-wrap">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <div class="pull-left mb-2">
                    <h2 class="font-share-tech mt-8 mb-12 text-4xl">Modifier un rôle</h2>
                </div>
                <div class="pull-right my-3">
                    <a class="btn-blue" href="{{ route('roles.index') }}"> Retour</a>
                </div>
            </div>
        </div>

        @if(session('status'))
        <div class="custom-status">
            {{ session('status') }}
        </div>
        @endif

        <form action="{{ route('roles.update',$role->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid sm:grid-cols-2 gap-4 p-4">    
                <div class="col-span-full">
                    <label for="name" class="custom-label">Nom du rôle : <span class="text-red-600 font-bold">*</span></label>
                    <input type="text" name="name" id="name" class="custom-input" placeholder="Saisir le nom du rôle" value="{{ $role->name }}" >
                    @error('name')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-full">
                    <label class="custom-label">Permissions : <span class="text-red-600 font-bold">*</span></label>
                    <div class="grid sm:grid-cols-3 gap-4">
                        @foreach ($permissions as $permission)
                        <div class="w-full">
                            <input type="checkbox" class="border-gray-300 focus:border-custom-blue focus:ring-custom-blue rounded-sm shadow-sm transition duration-300 ease-in-out" name="permission[]" value="{{ $permission->id }}" id="{{ $permission->name }}" @if($role->permissions->contains($permission->id)) checked @endif>
                            <label class="pl-2" for="{{ $permission->name }}">{{ $permission->name }}</label>
                        </div>
                        @endforeach
                    </div>
                    @error('permission')
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