@extends('layouts.main')

@section('content')
    <div class="container mb-16 mx-auto px-4">
        <div class="flex flex-wrap">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <div class="pull-left mb-2">
                    <h2 class="font-share-tech mt-8 mb-12 text-4xl">Modifier un poste</h2>
                </div>
                <div class="pull-right my-3">
                    <a class="btn-blue" href="{{ route('jobs.index') }}"> Retour</a>
                </div>
            </div>
        </div>

        @if(session('status'))
        <div class="custom-status">
            {{ session('status') }}
        </div>
        @endif

        <form action="{{ route('jobs.update',$listing->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid sm:grid-cols-2 gap-4 p-4">    
                <div class="col-span-full">
                    <label for="job" class="custom-label">Nom du poste : <span class="text-red-600 font-bold">*</span></label>
                    <input type="text" name="job" id="job" class="custom-input" placeholder="Saisir le nom du poste" value="{{ old('job', $listing->job) }}" >
                    @error('job')
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