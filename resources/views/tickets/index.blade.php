@extends('layouts.main')

@section('content')
    <div class="container mb-16 mx-auto px-4">
        <div class="flex flex-wrap">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <div class="pull-left mb-2">
                    <h2 class="font-share-tech mt-8 mb-12 text-4xl">Liste des tickets
                        
                    @if (auth()->user()->company() && !auth()->user()->can('all-access'))
                        - Entreprise : {{ auth()->user()->company->name }}
                    @endif</h2>
                </div>
                <div class="pull-right my-3">
                    @can('ticket-create')
                    <a class="btn-solid-orange" href="{{ route('tickets.create') }}"> Créer un ticket</a>
                    @endcan
                </div>
            </div>
        </div>

        @if($message = Session::get('status'))
        <div class="custom-status-error">
            {{ $message }}
        </div>
        @endif

        @if ($message = Session::get('success'))
            <div class="custom-status">
                <p>{{ $message }}</p>
            </div>
        @endif

        <div class="mt-12">
            @livewire('ticket-table')
        </div>

    </div>
@endsection