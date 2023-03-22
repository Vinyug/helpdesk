@extends('layouts.main')

@section('content')
    <div class="container mb-16 mx-auto sm:px-4">
        <div class="flex flex-wrap">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <div class="pull-left mb-2">
                    <h2 class="font-share-tech mt-8 mb-12 text-4xl">{{ $ticket->company->name }} - Ticket N° {{ $ticket->ticket_number }}</h2>
                </div>
                <div class="pull-right my-3">
                    <a class="btn-blue" href="{{ route('tickets.index') }}"> Retour</a>
                </div>
            </div>
        </div>

        @if(session('status'))
        <div class="custom-status">
            {{ session('status') }}
        </div>
        @endif

        <div class="flex flex-wrap">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <p class="mb-2"><span class="font-bold">Sujet : </span>{{ $ticket->service }}</p>
                <p class="mb-2"><span class="font-bold">N° Ticket : </span>{{ $ticket->ticket_number }}</p>
                <p class="mb-2"><span class="font-bold">Crée par : </span>{{ $ticket->user->firstname }} {{ $ticket->user->lastname }}</p>
                <p class="mb-2"><span class="font-bold">État : </span>{{ $ticket->state }}</p>
                <div>
                    <div class="group inline relative mb-2">
                        <span class="font-bold cursor-help hover:text-custom-blue mb-2">Visibilité : </span>
                        <div class="hidden w-[220px] sm:w-[360px] group-hover:block bg-slate-50 transition-opacity p-2 text-sm italic rounded-sm border border-gray-300 absolute mb-4">
                            <p><span class="font-bold">Publique : </span>visible par tous les membres de l'entreprise.</p>
                            <p><span class="font-bold">Privée : </span>visible par vous et l'administrateur entreprise.</p>
                        </div>
                    </div>
                    <span> @if ($ticket->visibility) publique @else privée @endif</span>
                </div>
            </div>
        </div>


        
    </div>
@endsection