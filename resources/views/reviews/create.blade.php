@extends('layouts.main')

@section('content')
    <div class="h-auto flex flex-col sm:justify-center items-center my-auto pt-6 sm:pt-0 pb-8">
        <div class="mt-8">
            <a href="/">
                <x-application-logo/>
            </a>
        </div>
    
        <div class="w-full border-[1px] border-slate-50 sm:max-w-xl mt-12 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <p class="font-share-tech text-center text-3xl font-medium mb-4">Votre avis nous intéresse !</p>

            <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
    
                <div class="grid sm:grid-cols-2 gap-4 p-4">  
                    
                    <div class="col-span-full flex items-center justify-center">
                        <div x-data="{rating: 5}" class="flex rating mb-4 space-x-4">
                            <template x-for="value in [1, 2, 3, 4, 5]">
                                <label :for="'star'+value" x-on:click="rating = value">
                                    <input type="radio" :id="'star'+value" name="rate" :value="value" x-model="rating"/>
                                    <img class="star-full cursor-pointer w-10" :src="(value <= rating) ? '{{ asset('assets/images/star-full.png') }}' : '{{ asset('assets/images/star-empty.png') }}'" :alt="value + ' étoile(s)'"/>
                                </label>
                            </template>
                        </div>
                        @error('rate')
                        <div class="custom-error">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <div class="col-span-full">
                        <div class="flex items-center">
                            <div class="group inline relative">
                                <label for="visibility" class="custom-label mb-0">Visibilité :</label>
                                
                                {{-- TOOLTIP --}}
                                <div class="hidden z-10 w-[260px] sm:w-[410px] group-hover:block bg-slate-50 transition-opacity p-2 text-sm italic rounded-sm border border-gray-300 absolute">
                                    <p class="mb-2"><span class="font-bold">Publique (coché) : </span>Nous pourrons utiliser votre avis pour l'afficher sur notre page d'accueil.</p>
                                    <p><span class="font-bold">Privée : </span>Votre avis restera privé et ne sera pas diffusé. Seulement un administrateur {{ config('app.name') }} peut en prendre connaissance.</p>
                                </div>
                            </div>
        
                            <input type="checkbox" name="visibility" id="visibility" value="1" class="ml-4 border-gray-300 text-custom-blue focus:border-custom-blue focus:ring-custom-blue rounded-sm shadow-sm transition duration-300 ease-in-out" {{ old('visibility') ? 'checked' : '' }}>
                        </div>
     
                        @error('visibility')
                        <div class="custom-error">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <div class="col-span-full">
                        <label for="content" class="custom-label">Message : </label>
                        <textarea class="custom-input h-48" name="content" id="content" placeholder="Saisir un message">{{ old('content') }}</textarea>
                        @error('content')
                        <div class="custom-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="col-span-full mt-4" :status="session('status')" />
    
                    <div class="col-span-full mt-8">
                        <button type="submit" class="btn-orange w-full">Envoyer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection