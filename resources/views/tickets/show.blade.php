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
                {{-- hidden input to verify if store is called --}}
                <input type="hidden" name="form" value="store">
    
                <div class="col-span-full mb-4">
                    <textarea class="custom-input h-20" name="content" id="content" placeholder="Saisir un message">{{ old('content') }}</textarea>
                    @if(old('form') == 'store')
                        @error('content')
                            <div class="custom-error">{{ $message }}</div>
                        @enderror
                    @endif
                </div>

                <div class="col-span-full">
                    <input type="file" class="custom-input-file"  name="filename[]" id="filename" multiple>
                    @if(old('form') == 'store')
                        @error('filename.*')
                        <div class="custom-error">{{ $message }}</div>
                        @enderror
                    @endif
                </div>
    
                <div class="col-span-full">
                    <button type="submit" class="btn-comment-orange">Envoyer</button>
                </div>
            </form>
        </div>


        {{-- COMMENTS --}}
        <h3 class="border-t-[2px] border-b-[2px] mt-12 mb-8 p-4 border-custom-blue text-2xl">Fil de discussion</h3>


        @foreach ($comments as $comment)
        <div 
            {{-- if comments.update has error on content, toggle to see textarea --}}
            x-data="
            @if ($loop->first && old('form') == 'update')
            { editComment: 
                @if ($errors->has('content') || $errors->has('filename.*')) true 
                @else false 
                @endif } 
            @else
            { editComment: false}
            @endif"

            class="flex flex-col border border-gray-300 rounded-t-md rounded-sm mb-4">
            {{-- HEAD COMMENT --}}
            <div class="flex flex-wrap justify-between border-b border-gray-300 bg-sky-50 rounded-t-md">
                <div class="mx-2 mt-2">
                    Par <span class="font-medium">{{ $comment->user->firstname }} {{ $comment->user->lastname }}</span>, @if($comment->created_at == $comment->updated_at) écrit le {{ $comment->created_at->format('d/m/Y à H\hi') }} @else modifié le {{ $comment->updated_at->format('d/m/Y à H\hi') }} @endif
                </div>
                
                @if ($loop->first && $comment->user_id == Auth::id() && ($ticket->editable || $comment->editable))
                <div>
                    <a @click="editComment = !editComment" x-text="editComment ? 'Restituer' : 'Modifier'" x-bind:class="{ 'btn-blue': !editComment, 'btn-dark-blue': editComment }" class="btn-blue text-sm my-1 sm:my-2 cursor-pointer"></a>
                    
                    <form class="btn-red text-sm my-1 sm:my-2 mr-2" action="{{ route('comments.destroy', $comment->id) }}" method="Post">
                        @csrf
                        @method('DELETE')
                        
                        <button type="submit" >Annuler</button>
                    </form>
                </div>
                @endif
            </div>

            {{-- BODY COMMENT --}}
            <div class="p-4 rounded-b-sm">
                {{-- TOGGLE editComment FALSE --}}
                <div x-show="!editComment" class="py-[9px] px-[13px] rounded-b-sm">
                    <p>{!! nl2br(e($comment->content)) !!}</p> 
                    <div class="flex flex-wrap mt-2">
                        <div class="flex flex-wrap mt-2">
                            @foreach ($comment->uploads as $upload)
                                <a href="{{ $upload->url }}" target="_blank"><img class="thumbnail m-1" src="{{ $upload->thumbnail_url }}" alt="{{ $upload->filename }}"></a>  
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- TOGGLE editComment TRUE --}}
                <form x-show="editComment" class="mb-2" action="{{ route('comments.update', $comment->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    {{-- hidden input to verify if update is called --}}
                    <input type="hidden" name="form" value="update">

                    <textarea class="custom-input border-custom-blue shadow-gray-400 shadow-sm h-20 mb-2" name="content" id="content" placeholder="Saisir un message">@if(empty(old('content'))){{ $comment->content }}@else{{ old('content', $comment->content) }}@endif</textarea>
                    @if(old('form') == 'update')
                        @error('content')
                            <div class="custom-error">{{ $message }}</div>
                        @enderror
                    @endif

                    <input type="file" class="custom-input-file"  name="filename[]" id="filename" multiple>
                    @if(old('form') == 'update')
                        @error('filename.*')
                        <div class="custom-error">{{ $message }}</div>
                        @enderror
                    @endif

                    <button type="submit" class="btn-comment-orange">Modifier</button>
                </form>
            </div>
        </div>
        @endforeach

    </div>
@endsection