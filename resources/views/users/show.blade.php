@extends('layouts.main')

@section('content')
    <div class="container mb-16 mx-auto sm:px-4">
        <div class="flex flex-wrap">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <div class="pull-left mb-2">
                    <h2 class="font-share-tech mt-8 mb-12 text-4xl">Liste des rôles</h2>
                </div>
            </div>
        </div>
        
        <div class="w-full">
            <div class="flex">
                <strong>Prénom :</strong>
                <p class="pl-2">{{ $user->firstname }}</p>
            </div>
           
            <div class="flex">
                <strong>Nom :</strong>
                <p class="pl-2">{{ $user->lastname }}</p>
            </div>

            <div class="flex">
                <strong>Email :</strong>
                <p class="pl-2">{{ $user->email }}</p>
            </div>

            

            <div>
                <strong class="pr-2">Role :</strong>
                @if (!empty($user->getRoleNames()))
                    @foreach ($user->getRoleNames() as $v)
                        <label class="badge badge-success">{{ $v }}</label>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
        