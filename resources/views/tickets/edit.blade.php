@extends('layouts.main')

@section('content')
    <div class="container mb-16 mx-auto sm:px-4">
        <div class="flex flex-wrap">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <div class="pull-left mb-2">
                    <h2 class="font-share-tech mt-8 mb-12 text-4xl">Modifier un ticket</h2>
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

        <form action="{{ route('tickets.update',$ticket->uuid) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid sm:grid-cols-2 gap-x-8 gap-y-4 p-4">    
                <div class="w-full">
                    <label for="service" class="custom-label">Service : </label>
                    <select class="custom-input" name="service" id="service">
                        @if (empty($ticket->service))    
                        <option value="">Choisir un service</option>
                        @else
                        <option value="{{ $ticket->service }}">{{ $ticket->service }}</option>
                        @endif

                        @foreach($services as $service)
                            <option value="{{ $service }}">{{ $service }}</option>
                        @endforeach
                    </select>
                    @error('service')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="block col-span-full text-red-600 mb-5 ml-4">* les champs obligatoires</div>

                <div class="col-span-full">
                    <button type="submit" class="btn-orange">Modifier</button>
                </div>
            </div>
        </form>
    </div>
@endsection