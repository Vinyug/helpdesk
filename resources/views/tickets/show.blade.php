@extends('layouts.main')

@section('content')
    <div class="container mb-16 mx-auto px-4 text-sm sm:text-base">
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

        {{-- Header --}}
        <div class="flex flex-wrap mb-12">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <p class="mb-2"><span class="font-bold">Sujet : </span>{{ $ticket->subject }}</p>
                <p class="mb-2"><span class="font-bold">Service : </span>{{ $ticket->service }}</p>
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


        {{-- CREATE COMMENT --}}
        <div class="flex flex-col border border-gray-300 rounded-t-md rounded-sm mb-4">
            <div class="p-2 font-medium border-b border-gray-300 bg-sky-50 rounded-t-md">Ecrire un nouveau message</div>
            <form class="p-4 rounded-b-sm" action="{{ route('comments.store', $ticket->uuid) }}" method="POST" enctype="multipart/form-data">
                @csrf
    
                <div class="col-span-full mb-4">
                    <textarea class="custom-input h-20" name="content" id="content" placeholder="Saisir un message">{{ old('content') }}</textarea>
                    @error('content')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <input type="file" name="file" id="file" class="custom-input-file" multiple>
    
                <div class="col-span-full">
                    <button type="submit" class="btn-comment-orange">Envoyer</button>
                </div>
            </form>
        </div>


        {{-- COMMENTS --}}
        <h3 class="border-t-[2px] border-b-[2px] mt-12 mb-8 p-4 border-custom-blue text-2xl">Fil de discussion</h3>

        @foreach ($comments as $comment)
        <div class="flex flex-col border border-gray-300 rounded-t-md rounded-sm mb-4">
            <div class="flex flex-wrap justify-between border-b border-gray-300 bg-sky-50 rounded-t-md">
                <div class="mx-2 mt-2">
                    Par <span class="font-medium">{{ $comment->user->firstname }} {{ $comment->user->lastname }}</span>, @if($comment->created_at == $comment->updated_at) écrit le {{ $comment->created_at->format('d/m/Y à H\hi') }} @else modifié le {{ $comment->updated_at->format('d/m/Y à H\hi') }} @endif
                </div>
                
                @if ($loop->first)
                <div>
                    {{-- @can('comment-edit') --}}
                        <a class="btn-blue text-sm my-1 sm:my-2" href="{{ route('tickets.edit', $ticket->uuid) }}">Modifier</a>
                    {{-- @endcan --}}
                    
                    {{-- @can('comment-delete') --}}
                        <form class="btn-red text-sm my-1 sm:my-2 mr-2" action="" method="Post">
                            @csrf
                            @method('DELETE')
                            
                            <button type="submit" >Annuler</button>
                        </form>
                    {{-- @endcan --}}
                </div>
                @endif
            </div>
            <div class="p-4 rounded-b-sm">
                <p>{!! nl2br(e($comment->content)) !!}</p> 
                <div class="flex flex-wrap mt-2">
                    {{-- @foreach ($collection as $item) --}}
                    <img class="m-1" src="http://via.placeholder.com/100x100" alt="">  
                    {{-- @endforeach --}}
                </div>
            </div>
        </div>
        @endforeach
        
    </div>
@endsection