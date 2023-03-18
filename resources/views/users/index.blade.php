@extends('layouts.main')

@section('content')
    <div class="container mb-16 mx-auto sm:px-4">
        <div class="flex flex-wrap">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <div class="pull-left mb-2">
                    <h2 class="font-share-tech mt-8 mb-12 text-4xl">Liste des utilisateurs</h2>
                </div>
                <div class="pull-right my-3">
                    @can('company-create')
                    <a class="btn-green" href="{{ route('users.create') }}"> Créer un utilisateur</a>
                    @endcan
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="custom-status">
                <p>{{ $message }}</p>
            </div>
        @endif

        <table class="min-w-full text-left">
            <thead class="border-b dark:border-neutral-500">
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Entreprise</th>
                    <th>Poste</th>
                    <th>Roles</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $key => $user)
                <tr class="h-12 border-b dark:border-neutral-500">
                    <td>{{ ++$i }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ optional($user->company)->name }}</td>
                    <td>{{ $user->job }}</td>
                    <td>
                        @if (!empty($user->getRoleNames()))
                            @foreach ($user->getRoleNames() as $v)
                                <label class="badge badge-success">{{ $v }}</label>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        <div class="flex justify-center">
                            <a class="btn-green" href="{{ route('users.show', $user->id) }}">Show</a>
                    
                            @can('user-edit')
                            <a class="btn-blue" href="{{ route('users.edit', $user->id) }}">Edit</a>
                            @endcan
    
                            @can('user-delete')
                            <form action="{{ route('users.destroy', $user->id) }}" method="Post">
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

        {{-- <table class="w-full table-auto">
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Entreprise</th>
                <th>Poste</th>
                <th>Roles</th>
                <th width="280px">Action</th>
            </tr>
            @foreach ($users as $key => $user)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ optional($user->company)->name }}</td>
                    <td>{{ $user->job }}</td>
                    <td>
                        @if (!empty($user->getRoleNames()))
                            @foreach ($user->getRoleNames() as $v)
                                <label class="badge badge-success">{{ $v }}</label>
                            @endforeach
                        @endif
                    </td>
                    <td class="flex justify-center">

                        <a class="btn-green" href="{{ route('users.show', $user->id) }}">Show</a>
                
                        @can('user-edit')
                        <a class="btn-blue" href="{{ route('users.edit', $user->id) }}">Edit</a>
                        @endcan

                        @can('user-delete')
                        <form action="{{ route('users.destroy', $user->id) }}" method="Post">
                            @csrf
                            @method('DELETE')
                            
                            <button type="submit" class="btn-red">Delete</button>
                        </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table> --}}

        {!! $users->render() !!}
    </div>
@endsection
