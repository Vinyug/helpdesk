@extends('layouts.main')

@section('content')
    <div class="container mb-16 mx-auto px-4">
        <div class="flex flex-wrap">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <div class="pull-left mb-2">
                    <h2 class="font-share-tech mt-8 mb-12 text-4xl">Modifier un état</h2>
                </div>
                <div class="pull-right my-3">
                    <a class="btn-blue" href="{{ route('states.index') }}"> Retour</a>
                </div>
            </div>
        </div>

        @if(session('status'))
        <div class="custom-status-error">
            {{ session('status') }}
        </div>
        @endif

        <form action="{{ route('states.update',$listing->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid sm:grid-cols-2 gap-4 p-4">    
                <div class="col-span-full">
                    <label for="state" class="custom-label">Nom de l'état : <span class="text-red-600 font-bold">*</span></label>
                    <input type="text" name="state" id="state" class="custom-input" placeholder="Saisir le nom de l'état" value="{{ old('state', $listing->state) }}" >
                    @error('state')
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