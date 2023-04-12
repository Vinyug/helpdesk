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
        
        
        {{-----------------------------------------------------------------------------------------------------}}
        {{------------------------------------------ HEADER ---------------------------------------------------}}
        {{-----------------------------------------------------------------------------------------------------}}
        <div class="flex flex-wrap mb-12">
            <div class="w-full pr-4 pl-4 mt-5">
                <p class="mb-2"><span class="font-bold">Sujet : </span>{{ $ticket->subject }}</p>
                <p class="mb-2"><span class="font-bold">Service : </span>{{ $ticket->service }}</p>
                <p class="mb-2"><span class="font-bold">N° Ticket : </span>{{ $ticket->ticket_number }}</p>
                <p class="mb-2"><span class="font-bold">Crée par : </span>{{ $ticket->user->firstname }} {{ $ticket->user->lastname }}</p>
                
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
                    <form action="" method="POST" enctype="multipart/form-data">
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
                
                                    <input type="checkbox" name="visibility" id="visibility" value="{{ $ticket->visibility }}" class="ml-4 border-gray-300 text-custom-blue focus:border-custom-blue focus:ring-custom-blue rounded-sm shadow-sm transition duration-300 ease-in-out" {{ old('visibility', !$ticket->visibility) ? 'checked' : '' }}>
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
                                <button type="submit" class="btn-comment-orange">Modifier</button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-4">
                        <p class="mb-2"><span class="font-bold">Temps total du ticket : </span>{{ $totalTime }}h</p>
                        <p class="mb-2"><span class="font-bold">Montant total H.T. du ticket : </span>x.xxx,xx Euros</p>
                    </div>
                @endif
            </div>
        </div>


        {{-----------------------------------------------------------------------------------------------------}}
        {{-------------------------------------- CREATE COMMENT -----------------------------------------------}}
        {{-----------------------------------------------------------------------------------------------------}}
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

                @if (auth()->user()->can('all-access'))
                <div class="flex flex-col w-full sm:w-3/5 lg:w-1/3 xl:w-1/4 mr-4 mb-2">
                    <div class="flex items-center">
                        <label for="time_spent" class="custom-label font-medium pr-2 mb-0 whitespace-nowrap self-center">Temps d'intervention : </label>
                        <input type="text" name="time_spent" id="time_spent" class="custom-input h-8 text-right" placeholder="en heure" value="{{ old('time_spent') }}"><span class="font-medium pl-1">h</span>
                    </div>
                    @error('time_spent')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>
                @endif
    
                <div class="col-span-full">
                    <button type="submit" class="btn-comment-orange">Envoyer</button>
                </div>
            </form>
        </div>


        {{-----------------------------------------------------------------------------------------------------}}
        {{----------------------------------------- COMMENTS --------------------------------------------------}}
        {{-----------------------------------------------------------------------------------------------------}}
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

            {{----------------------------------------- HEAD COMMENT --------------------------------------------------}}
            <div class="flex flex-wrap justify-between border-b border-gray-300 bg-sky-50 rounded-t-md">
                <div class="mx-2 mt-2">
                    Par <span class="font-medium">{{ $comment->user->firstname }} {{ $comment->user->lastname }}</span>, @if($comment->created_at == $comment->updated_at) écrit le {{ $comment->created_at->format('d/m/Y à H\hi') }} @else modifié le {{ $comment->updated_at->format('d/m/Y à H\hi') }} @endif
                </div>
                
                @if ($loop->first && $comment->user_id == Auth::id() && $comment->editable)
                <div>
                    {{--------------------------- BUTTON EDIT ------------------------}}
                    <a 
                        class="btn-blue text-sm my-1 sm:my-2 cursor-pointer"
                        @if($ticket->comments->first() && $comment->id === $ticket->comments->first()->id)  
                            href="{{ route('tickets.edit', $ticket->uuid) }}">Modifier 
                        @else
                            @click="editComment = !editComment" x-text="editComment ? 'Restituer' : 'Modifier'" x-bind:class="{ 'btn-blue': !editComment, 'btn-dark-blue': editComment }">
                        @endif 
                    </a>

                    {{--------------------------- BUTTON DELETE ------------------------}}
                    <a class="btn-red text-sm my-1 sm:my-2 mr-2 cursor-pointer" x-data="" x-on:click.prevent="$dispatch('open-modal',
                    @if($ticket->comments->first() && $comment->id === $ticket->comments->first()->id)  
                        'confirm-ticket-delete'
                    @else
                        'confirm-comment-delete'
                    @endif 
                    )"
                    >Annuler</a>
                    
                    {{----------------------- MODAL TICKET OR COMMENT ------------------------}}
                    @if($ticket->comments->first() && $comment->id === $ticket->comments->first()->id)  
                        <x-modal name="confirm-ticket-delete">
                            <form method="post" action="{{ route('tickets.destroy', $ticket->uuid) }}" class="p-6">
                                @csrf
                                @method('DELETE')
                    
                                <h2 class="text-lg font-medium">
                                    {{ __('Êtes-vous sûr de vouloir supprimer ce ticket ?') }}
                                </h2>
                    
                                <p class="mt-1 text-sm text-custom-grey">
                                    {{ __('Une fois ce ticket supprimé, toutes ses ressources et données seront définitivement effacées.') }}
                                </p>
                    
                                <div class="mt-2 flex flex-wrap justify-end">
                                    <x-secondary-button class="mt-4 ml-0 w-full sm:w-auto" x-on:click="$dispatch('close')">
                                        {{ __('Annuler') }}
                                    </x-secondary-button>
                    
                                    <x-danger-button class="ml-0 mt-4 sm:ml-3 w-full sm:w-auto">
                                        {{ __('Confirmer suppression') }}
                                    </x-danger-button>
                                </div>
                            </form>
                        </x-modal>
                    @else
                        <x-modal name="confirm-comment-delete">
                            <form method="post" action="{{ route('comments.destroy', $comment->id) }}" class="p-6">
                                @csrf
                                @method('DELETE')
                    
                                <h2 class="text-lg font-medium">
                                    {{ __('Êtes-vous sûr de vouloir supprimer ce commentaire ?') }}
                                </h2>
                    
                                <p class="mt-1 text-sm text-custom-grey">
                                    {{ __('Une fois ce commentaire supprimé, toutes ses ressources et données seront définitivement effacées.') }}
                                </p>
                    
                                <div class="mt-2 flex flex-wrap justify-end">
                                    <x-secondary-button class="mt-4 ml-0 w-full sm:w-auto" x-on:click="$dispatch('close')">
                                        {{ __('Annuler') }}
                                    </x-secondary-button>
                    
                                    <x-danger-button class="ml-0 mt-4 sm:ml-3 w-full sm:w-auto">
                                        {{ __('Confirmer suppression') }}
                                    </x-danger-button>
                                </div>
                            </form>
                        </x-modal>
                    @endif 
                </div>
                @endif
            </div>

            {{----------------------------------------- BODY COMMENT --------------------------------------------------}}
            <div class="p-4 rounded-b-sm">
                {{---------------- TOGGLE editComment FALSE -----------------}}
                <div x-show="!editComment" class="py-[9px] px-[13px] rounded-b-sm">
                    <p>{!! nl2br(e($comment->content)) !!}</p> 
                    <div class="flex flex-wrap mt-2">
                        @foreach ($comment->uploads as $upload)
                            <a href="{{ $upload->url }}" target="_blank"><img class="thumbnail m-1" src="{{ $upload->thumbnail_url }}" alt="{{ $upload->filename }}"></a>  
                        @endforeach
                    </div>
                </div>

                {{---------------- TOGGLE editComment TRUE -----------------}}
                <form x-cloak x-show="editComment" class="mb-2" action="{{ route('comments.update', $comment->id) }}" method="POST" enctype="multipart/form-data">
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