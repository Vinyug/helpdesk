@extends('layouts.main')

@section('content')
    <div class="container mb-16 mx-auto px-4">
        <div class="flex flex-wrap">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <div class="pull-left mb-2">
                    <h2 class="font-share-tech mt-8 mb-12 text-4xl">Modifier vos informations</h2>
                </div>
                <div class="pull-right my-3">
                    <a class="btn-blue" href="{{ route('index') }}"> Retour</a>
                </div>
            </div>
        </div>

        @if(session('status'))
        <div class="custom-status">
            {{ session('status') }}
        </div>
        @endif

        <div class="mt-4 sm:mt-6 p-4 sm:p-8 shadow sm:rounded-sm">
            @include('includes.profile.update-profile-information-form')
        </div>

        <div class="mt-4 sm:mt-6 p-4 sm:p-8 shadow sm:rounded-sm">
            @include('includes.profile.update-password-form')
        </div>

        <div class="mt-4 sm:mt-6 p-4 sm:p-8 shadow sm:rounded-sm">
            @include('includes.profile.delete-user-form')
        </div>
    </div>
@endsection
    

