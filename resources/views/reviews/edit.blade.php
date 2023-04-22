@extends('layouts.main')

@section('content')
    <div class="h-auto flex flex-col sm:justify-center items-center my-auto pt-6 sm:pt-0 pb-8">
        
        <div class="w-full border-[1px] border-slate-50 sm:max-w-xl mt-12 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            
            <div class="pull-right mb-4">
                <a class="btn-blue" href="{{ route('reviews.index') }}"> Retour</a>
            </div>

            <p class="font-share-tech text-center text-3xl font-medium mb-4">Avis de {{ $review->user->firstname }} {{ $review->user->lastname }}</p>
            <p class="font-share-tech text-center text-3xl font-medium mb-4">de l'entreprise @if($review->user->company){{ $review->user->company->name }} @else Anonyme @endif</p>


            <div class="grid sm:grid-cols-2 gap-4 p-4">  
                
                <div class="col-span-full flex items-center justify-center">
                    <div class="flex rating mb-4 space-x-4">
                        @for($i = 1; $i <= 5; $i++)
                            <img class="star-full cursor-pointer w-10" 
                                src="{{ ($i <= $review->rate) ? asset('assets/images/star-full.png') : asset('assets/images/star-empty.png') }}" 
                                alt="{{ $i }} étoile(s)">
                        @endfor
                    </div>
                </div>

                <p class="col-span-full mb-2"><span class="font-bold">Visibilité : </span>@if($review->visibility) Publique @else Privée @endif</p>

                <div class="col-span-full">
                    <label for="content" class="custom-label">Message : </label>
                    <p class="py-[9px] px-[13px]">{!! nl2br(e($review->content)) !!}</p> 
                </div>

                @if($review->visibility)
                    <form class="col-span-full flex justify-between items-center" action="{{ route('reviews.update', $review->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <div class="">
                            <div class="flex items-center">
                                <div class="group inline relative">
                                    <label for="show" class="custom-label mb-0">Afficher :</label>
                                    
                                    {{-- TOOLTIP --}}
                                    <div class="hidden z-10 w-[280px] group-hover:block bg-slate-50 transition-opacity p-2 text-sm italic rounded-sm border border-gray-300 absolute">
                                        <p><span class="font-bold">Coché : </span>affiche l'avis sur la page d'accueil.</p>
                                    </div>
                                </div>
            
                                <input type="checkbox" name="show" id="show" value="1" class="ml-4 border-gray-300 text-custom-blue focus:border-custom-blue focus:ring-custom-blue rounded-sm shadow-sm transition duration-300 ease-in-out" {{ old('show', !$review->show) ? '' : 'checked' }}>
                            </div>
                        </div>

                        <div class="col-span-full">
                            <button type="submit" class="btn-comment-orange ">Modifier</button>
                        </div>
                    </form>
                @else
                    <p class="col-span-full mb-2"><span class="font-bold">Afficher : </span>Vous ne pouvez pas le modifer car l'auteur souhaite garder son avis privé</p>
                @endif
            </div>
        </div>
    </div>
@endsection