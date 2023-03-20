@extends('layouts.main')

@section('content')
    <div class="container mb-16 mx-auto sm:px-4">
        <div class="flex flex-wrap">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <div class="pull-left mb-2">
                    <h2 class="font-share-tech mt-8 mb-12 text-4xl">Liste des états</h2>
                </div>
                <div class="pull-right my-3">
                    @can('state-create')
                    <a class="btn-green" href="{{ route('states.create') }}"> Créer un état</a>
                    @endcan
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="custom-status">
                <p>{{ $message }}</p>
            </div>
        @endif
        
        <div class="mt-12">
            @livewire('state-table')
        </div>

        {{-- without powergrid --}}
        {{-- <table class="min-w-full text-left">
            <thead class="border-b dark:border-neutral-500">
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th width="280px">Action</th>
                </tr>
            
            </thead>
            <tbody>
                @foreach ($listings as $listing)
                <tr class="h-12 border-b dark:border-neutral-500">
                    <td>{{ ++$i }}</td>
                    <td>{{ $listing->state }}</td>
                    <td>
                        <div class="flex justify-center">               
                            @can('state-edit')
                                <a class="btn-blue" href="{{ route('states.edit', $listing->id) }}">Edit</a>
                            @endcan
    
                            @can('state-delete')
                            <form action="{{ route('states.destroy', $listing->id) }}" method="Post">
                                @csrf
                                @method('DELETE')
                                
                                <button type="submit" class="btn-red">Delete</button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {!! $listings->render() !!} --}}
    </div>
@endsection