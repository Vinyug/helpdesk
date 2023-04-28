@extends('layouts.main')

@section('content')
    <div class="container mt-24 mb-16 mx-auto px-4">  
    
        <div class="flex">
            <a class="shrink-0 flex items-center mx-auto sm:mr-4 sm:ml-0" href="/">
                <x-application-logo/>
            </a>
            <p class="font-share-tech self-end ml-8 text-center text-4xl font-semibold">Oups ! une erreur s'est produite.</p>
        </div>
        
        <p class="font-share-tech mt-8 self-end text-3xl font-medium">{{ $message }}</p>

        <a href="/" class="btn-blue mt-16">Retour à l'accueil</a>
    </div>
@endsection