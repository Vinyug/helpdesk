@extends('layouts.main')

@section('content')
    <div class="container mb-16 mx-auto px-4">
        <div class="flex flex-wrap">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <div class="pull-left mb-2">
                    <h2 class="font-share-tech mt-8 mb-12 text-4xl">Créer un ticket</h2>
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

        <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid sm:grid-cols-2 gap-4 p-4">  
                
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
    
                        <input type="checkbox" name="visibility" id="visibility" value="1" class="ml-4 border-gray-300 text-custom-blue focus:border-custom-blue focus:ring-custom-blue rounded-sm shadow-sm transition duration-300 ease-in-out" {{ old('visibility') ? 'checked' : '' }}>
                    </div>
 
                    @error('visibility')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                @if (auth()->user()->can('all-access'))
                    @can('all-access')
                    <div class="col-span-full">
                        <label for="company_id" class="custom-label">Entreprise : <span class="text-red-600 font-bold">*</span></label>
                        <select class="custom-input" name="company_id" id="company_id">
                            <option value="">Choisir une entreprise</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                        @error('company_id')
                        <div class="custom-error">{{ $message }}</div>
                        @enderror
                    </div>
                    @endcan
                @else 
                    <input type="text" name="company_id" id="company_id" value="{{ auth()->user()->company_id }}" class="hidden" >
                @endif

                <div class="col-span-full">
                    <label for="subject" class="custom-label">Sujet : <span class="text-red-600 font-bold">*</span></label>
                    <input type="text" name="subject" id="subject" class="custom-input" placeholder="Saisir le Sujet" value="{{ old('subject') }}" required>
                    @error('subject')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-full">
                    <label for="service" class="custom-label">Service : <span class="text-red-600 font-bold">*</span></label>
                    <select class="custom-input" name="service" id="service">
                        <option value="">Choisir un service</option>
                        @foreach($services as $service)
                            <option value="{{ $service }}">{{ $service }}</option>
                        @endforeach
                    </select>
                    @error('service')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-full">
                    <label for="content" class="custom-label">Message : <span class="text-red-600 font-bold">*</span></label>
                    <textarea class="custom-input h-80" name="content" id="content" placeholder="Saisir un message">{{ old('content') }}</textarea>
                    @error('content')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-full">
                    <label for="filename" class="custom-label">Fichier :</label>
                    <input type="file" class="custom-input-file"  name="filename[]" id="filename" multiple>
                    @error('filename.*')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="block col-span-full text-red-600 mb-5 ml-4">* les champs obligatoires</div>

                <div class="col-span-full">
                    <button type="submit" class="btn-orange">Créer</button>
                </div>
            </div>
        </form>
    </div>
@endsection