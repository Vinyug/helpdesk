@extends('layouts.main')

@section('content')
    <div x-data="{ showMessage: true }" class="container mb-16 mx-auto px-4 text-sm sm:text-base">
        <div class="flex flex-wrap">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <div class="pull-left mb-2">
                    <h2 class="font-share-tech mt-8 mb-12 text-4xl">@if($ticket->company) {{ $ticket->company->name }} @else Entreprise anonyme @endif - Ticket N° {{ $ticket->ticket_number }}</h2>
                </div>
                <div class="pull-right my-3">
                    <a class="btn-blue" href="{{ route('tickets.index') }}"> Retour</a>
                </div>
            </div>
        </div>

    
        {{-----------------------------------------------------------------------------------------------------}}
        {{------------------------------------------ HEADER ---------------------------------------------------}}
        {{-----------------------------------------------------------------------------------------------------}}
        <div class="flex flex-wrap mb-12">
            <div class="w-full pr-4 pl-4 mt-5">
                <p class="mb-2"><span class="font-bold">Sujet : </span>{{ $ticket->subject }}</p>
                <p class="mb-2"><span class="font-bold">Service : </span>{{ $ticket->service }}</p>
                <p class="mb-2"><span class="font-bold">N° Ticket : </span>{{ $ticket->ticket_number }}</p>
                <p class="mb-2"><span class="font-bold">Crée par : </span>@if($ticket->user){{ $ticket->user->firstname }} {{ $ticket->user->lastname }} @else Anonyme @endif</p>
                
                @if(!auth()->user()->can('all-access'))
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
                @else
                    {{---------------------------- UPDATE VISIBILITY OR STATE OF TICKET -------------------------}}
                    <form action="{{ route('tickets.updateVisibilityState', $ticket->uuid)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
            
                        <div class="grid sm:grid-cols-2 gap-2">  
                            <div class="col-span-full">
                                <div class="flex items-center">
                                    <div class="group inline relative">
                                        <label for="visibility" class="custom-label mb-0">Visibilité :</label>
                                        
                                        {{-- TOOLTIP --}}
                                        <div class="hidden w-[220px] sm:w-[410px] group-hover:block bg-slate-50 transition-opacity p-2 text-sm italic rounded-sm border border-gray-300 absolute">
                                            <p><span class="font-bold">Publique : </span>visible par tous les membres de l'entreprise.</p>
                                            <p><span class="font-bold">Privée (coché) : </span>visible par vous et l'administrateur entreprise.</p>
                                        </div>
                                    </div>
                
                                    <input type="checkbox" name="visibility" id="visibility" value="1" class="ml-4 border-gray-300 text-custom-blue focus:border-custom-blue focus:ring-custom-blue rounded-sm shadow-sm transition duration-300 ease-in-out" {{ old('visibility', !$ticket->visibility) ? 'checked' : '' }}>
                                </div>
                
                                @error('visibility')
                                <div class="custom-error">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="flex flex-col col-span-full sm:w-3/5 lg:w-1/3 xl:w-1/4">
                                <div class="flex">
                                    <label for="state" class="custom-label pr-2 mb-0 whitespace-nowrap self-center">État :</label>
                                    <select class="custom-input py-0 h-8" name="state" id="state">
                                        <option value="{{ $ticket->state }}">{{ $ticket->state }}</option>
                            
                                        @foreach ($states as $state)
                                            <option value="{{ $state }}">{{ $state }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('state')
                                <div class="custom-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-full">
                                <button type="submit" class="btn-comment-orange mt-4 sm:mt-0">Modifier</button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-4">
                        <p class="mb-2"><span class="font-bold">Temps total du ticket : </span>{{ $totalTime }}h</p>
                        <p class="mb-2"><span class="font-bold">Montant total H.T. du ticket : </span>{{ $totalPrice }} Euros</p>
                    </div>
                @endif
            </div>
        </div>


        {{-----------------------------------------------------------------------------------------------------}}
        {{---------------------------------------- LIVEWIRE ---------------------------------------------------}}
        {{-----------------------------------------------------------------------------------------------------}}
        
        @livewire('comments', ['ticket' => $ticket], key($ticket->uuid))
    
    </div>
@endsection