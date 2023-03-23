@extends('layouts.main')

@section('content')
    <div class="container mb-16 mx-auto px-4">
        <div class="flex flex-wrap">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <div class="pull-left mb-2">
                    <h2 class="font-share-tech mt-8 mb-12 text-4xl">Liste des rôles</h2>
                </div>
                <div class="pull-right my-3">
                    @can('role-create')
                    <a class="btn-green" href="{{ route('roles.create') }}"> Créer un rôle</a>
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
            @livewire('role-table')
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
                @foreach ($roles as $key => $role)
                <tr class="h-12 border-b dark:border-neutral-500">
                    <td>{{ ++$i }}</td>
                    <td>{{ $role->name }}</td>
                    <td>
                        <div class="flex justify-center">
                            <a class="btn-green" href="{{ route('roles.show', $role->id) }}">Show</a>
                    
                            @can('role-edit')
                            <a class="btn-blue" href="{{ route('roles.edit', $role->id) }}">Edit</a>
                            @endcan
    
                            @can('role-delete')
                            <form action="{{ route('roles.destroy', $role->id) }}" method="Post">
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
        {!! $roles->render() !!} --}}
    </div>
@endsection