@extends('layouts.main')

@section('content')
    <div class="container mb-16 mx-auto sm:px-4">
        <div class="flex flex-wrap">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <div class="pull-left mb-2">
                    <h2 class="font-share-tech mt-8 mb-12 text-4xl">Liste des entreprises</h2>
                </div>
                <div class="pull-right my-3">
                    <a class="btn-green" href="{{ route('companies.create') }}"> Créer une entreprise</a>
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="custom-status">
                <p>{{ $message }}</p>
            </div>
        @endif


        {{-- @livewire('company-table') --}}




        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>N° entreprise</th>
                    <th>Nom entreprise</th>
                    <th>Adresse entreprise</th>
                    <th>Email entreprise</th>
                    <th>uuid</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($companies as $company)
                    <tr>
                        <td>{{ $company->id }}</td>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->address }}</td>
                        <td>{{ $company->email }}</td>
                        <td>{{ $company->uuid }}</td>
                        <td>
                            <form action="{{ route('companies.destroy', $company->uuid) }}" method="Post">
                                {{-- @can('company-edit') --}}
                                <a class="btn btn-primary" href="{{ route('companies.edit', $company->uuid) }}">Edit</a>
                                {{-- @endcan --}}

                                @csrf
                                @method('DELETE')
                                {{-- @can('company-delete') --}}
                                <button type="submit" class="btn btn-outline-danger">Delete</button>
                                {{-- @endcan --}}
                            </form>
                        </td>
                    </tr>
                    @endforeach
            </tbody>
        </table>
        {!! $companies->links() !!}
    </div>
@endsection