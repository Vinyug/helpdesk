<div>
    {{-----------------------------------------------------------------------------------------------------}}
    {{-------------------------------------- MESSAGE FLASH ------------------------------------------------}}
    {{-----------------------------------------------------------------------------------------------------}}
        
    <div x-show="showMessage">
        @if(session()->has('status'))
        <div class="custom-status-error">
            <p>{{ session('status') }}</p>
        </div>
        @endif
        
        @if (session()->has('success'))
        <div class="custom-status">
            <p>{{ session('success') }}</p>
        </div>
        @endif
    </div>

    {{-----------------------------------------------------------------------------------------------------}}
    {{-------------------------------------- CREATE COMMENT -----------------------------------------------}}
    {{-----------------------------------------------------------------------------------------------------}}

    @if ($ticket->state !== 'Résolu' && !$editMode)
    <div class="flex flex-col border border-gray-400 rounded-t-md rounded-sm mb-4 shadow shadow-grey-500">
        <div class="p-2 font-medium border-b border-gray-400 text-white bg-custom-light-blue">Ecrire un nouveau message</div>
        <form class="p-4 rounded-b-sm">

            <div class="col-span-full mb-4">
                <textarea class="custom-input h-20" wire:model="input.content" x-on:click="showMessage = false" name="content" id="content" placeholder="Saisir un message"></textarea>
                @if($storeSubmitted)
                    @error('input.content')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                @endif
            </div>

            <div class="col-span-full">
                <input type="file" class="custom-input-file" wire:model="input.filenames" name="filename[]" id="filename" multiple>
                @if($storeSubmitted)
                    @error('input.filenames.*')
                    <div class="custom-error pt-0 mb-4">{{ $message }}</div>
                    @enderror
                @endif
            </div>

            @if (auth()->user()->can('all-access'))
            <div class="flex flex-col w-full">
                <div class="flex items-center sm:w-3/5 lg:w-1/3 xl:w-1/4 mb-4">
                    <label for="time_spent" class="custom-label font-medium pr-2 mb-0 whitespace-nowrap self-center">Temps d'intervention : </label>
                    <input type="text" wire:model="input.time_spent" name="time_spent" id="time_spent" class="custom-input h-8 text-right" placeholder="en heure" value=""><span class="font-medium pl-1">h</span>
                </div>
                @if($storeSubmitted)
                    @error('input.time_spent')
                    <div class="custom-error mb-2">{{ $message }}</div>
                    @enderror
                @endif
            </div>
            @endif

            <div class="col-span-full">
                <button type="submit" wire:click.prevent="store" class="btn-comment-orange">Envoyer</button>
            </div>
        </form>
    </div>
    @else 
    <div style="height: 311px"></div>
    @endif



    {{-----------------------------------------------------------------------------------------------------}}
    {{----------------------------------------- COMMENTS --------------------------------------------------}}
    {{-----------------------------------------------------------------------------------------------------}}

    <h3 class="border-t-[2px] border-b-[2px] mt-12 mb-8 p-4 border-custom-blue text-2xl">Fil de discussion</h3>

    @foreach ($comments as $comment)
    <div 
    {{-- if comments.update has error on content, toggle to see textarea --}}
    x-data="
    @if ($loop->first && $storeSubmitted)
    { editComment: 
        @if ($errors->has('input.content') || $errors->has('input.filenames.*') || $errors->has('input.time_spent')) true 
        @else false 
        @endif } 
    @else
        { editComment: false}
    @endif"
        
        class="flex flex-col border border-gray-400  rounded-t-md rounded-sm mb-4 shadow shadow-grey-500">

        {{----------------------------------------- HEAD COMMENT --------------------------------------------------}}
        <div class="flex flex-wrap flex-col sm:flex-row justify-between border-b border-gray-400 text-white bg-custom-light-blue">
            <div class="mx-2 my-2">
                Par <span class="font-medium">@if($comment->user){{ $comment->user->firstname }} {{ $comment->user->lastname }} @else Anonyme @endif</span>, @if($comment->created_at == $comment->updated_at) écrit le {{ $comment->created_at->format('d/m/Y à H\hi') }} @else modifié le {{ $comment->updated_at->format('d/m/Y à H\hi') }} @endif
            </div>

            @if (auth()->user()->can('all-access') && $comment->time_spent)
            <div class="ml-2 sm:ml-auto mr-2 my-1 sm:my-2">
                <span>Temps sur commentaire : {{ $comment->time_spent }}h</span>
            </div>
            @endif
            
            @if ($loop->first && $comment->user_id == Auth::id() && $comment->editable && $ticket->state !== 'Résolu')
            <div>
                {{--------------------------- BUTTON EDIT ------------------------}}
                <a 
                    class="btn-white text-sm my-1 sm:my-2 ml-2 mr-0 cursor-pointer"
                    @if($ticket->comments->first() && $comment->id === $ticket->comments->first()->id)  
                        href="{{ route('tickets.edit', $ticket->uuid) }}"> 
                    @else
                        @click="editComment = !editComment" 
                        x-text="editComment ? 'Restituer' : 'Modifier'" 
                        x-bind:class="{ 'btn-white': !editComment, 'btn-dark-blue hover:shadow-none': editComment }"
                        @if($editMode) wire:click.prevent="cancel"
                        @else wire:click.prevent="edit({{ $comment->id }})"
                        @endif
                        >
                    @endif 
                    Modifier
                </a>

                {{--------------------------- BUTTON DELETE ------------------------}}
                <a class="btn-red hover:shadow-none text-sm my-1 sm:my-2 mx-2 cursor-pointer"
                    @click="editComment = false"
                    wire:click.prevent="cancel"
                    x-data="" 
                    x-on:click.prevent="$dispatch('open-modal',
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
                        <form class="p-6">
                
                            <h2 class="text-lg font-medium text-custom-grey">
                                {{ __('Êtes-vous sûr de vouloir supprimer ce commentaire ?') }}
                            </h2>
                
                            <p class="mt-1 text-sm text-custom-grey">
                                {{ __('Une fois ce commentaire supprimé, toutes ses ressources et données seront définitivement effacées.') }}
                            </p>
                
                            <div class="mt-2 flex flex-wrap justify-end">
                                <x-secondary-button class="mt-4 ml-0 w-full sm:w-auto" x-on:click="$dispatch('close')">
                                    {{ __('Annuler') }}
                                </x-secondary-button>
                
                                <x-danger-button wire:click.prevent="delete({{ $comment->id }})" x-on:click="$dispatch('close')" class="ml-0 mt-4 sm:ml-3 w-full sm:w-auto">
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
            @if ($editMode)
                <form x-cloak x-show="editComment" class="mb-2">

                    <textarea class="custom-input border-custom-blue shadow-gray-400 shadow-sm h-20 mb-2" wire:model="input.content" x-on:click="showMessage = false" name="content" id="content" placeholder="Saisir un message"></textarea>
                    @if($updateSubmitted)
                        @error('input.content')
                            <div class="custom-error">{{ $message }}</div>
                        @enderror
                    @endif

                    <input type="file" class="custom-input-file" wire:model="input.filenames" x-on:click="showMessage = false" name="filename[]" id="filename" multiple>
                    @if($updateSubmitted)
                        @error('input.filenames.*')
                        <div class="custom-error pt-0 mb-4">{{ $message }}</div>
                        @enderror
                    @endif

                    @if (auth()->user()->can('all-access'))
                    <div class="flex flex-col w-full mb-4">
                        <div class="flex items-center sm:w-3/5 lg:w-1/3 xl:w-1/4">
                            <label for="time_spent" class="custom-label font-medium pr-2 mb-0 whitespace-nowrap self-center">Temps d'intervention : </label>
                            <input type="text" class="custom-input h-8 text-right" wire:model="input.time_spent" x-on:click="showMessage = false" name="time_spent" id="time_spent" placeholder="en heure"><span class="font-medium pl-1">h</span>
                        </div>
                        @if($updateSubmitted)
                            @error('input.time_spent')
                            <div class="custom-error mb-2">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>
                    @endif

                    <button type="submit" wire:click.prevent="update({{ $comment }})" @click="editComment = !editComment" class="btn-comment-orange">Modifier</button>
                </form>
            @endif
        </div>
    </div>
    @endforeach

</div>