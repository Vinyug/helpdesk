@extends('layouts.main')

@section('content')
    <div class="container mb-16 mx-auto px-4">
        <div class="flex flex-wrap">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <div class="pull-left mb-2">
                    <h2 class="font-share-tech mt-8 mb-12 text-4xl">Informations sur les heures</h2>
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
        <div class="custom-status">
            <p>{{ $message }}</p>
        </div>
        @endif
        @if ($message = Session::get('status'))
        <div class="custom-status-error">
            <p>{{ $message }}</p>
        </div>
        @endif
        
        
        {{------------------ CREATE HOURLY RATE --------------}}
        <div class="mb-16">
            <h3 class="text-base sm:text-lg font-bold mb-4">Information sur le taux horaire</h3>
            
            @can('hourly_rate-create')
            <form action="{{ route('hourly_rate.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex flex-col sm:flex-row justify-start">
                    <div class="flex flex-col justify-start">
                        <div class="flex items-center">
                            <label for="hourly_rate" class="custom-label font-medium mb-0">Taux horaire actuel : </label>
                            <input type="text" name="hourly_rate" id="hourly_rate" class="custom-input w-[80px] h-8 text-right mx-2" placeholder="€/h" value="{{ old('hourly_rate', $hourlyRate) }}"><span class="font-bold"> €/h</span>
                        </div> 
                        @error('hourly_rate')
                        <div class="custom-error mb-4">{{ $message }}</div>
                        @enderror
                    </div>   
                    
                    <button type="submit" class="btn-comment-orange h-8 sm:flex sm:items-center sm:ml-16">Enregistrer</button>
                </div>
            </form>
            @endcan
        </div>    
        
        {{------------------ TABLE POWERGRID --------------}}
        <div class="mt-12">
            <h3 class="text-base sm:text-lg font-bold mb-4">Liste des heures d'intervention</h3>
            
            @livewire('time-table')
        </div>

    </div>
@endsection