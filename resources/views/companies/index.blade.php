@extends('layouts.main')

@section('content')
    <div class="container mb-16 mx-auto px-4">
        <div class="flex flex-wrap">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <div class="pull-left mb-2">
                    <h2 class="font-share-tech mt-8 mb-12 text-4xl">Liste des entreprises</h2>
                </div>
                <div class="pull-right my-3">
                    @can('company-create')
                    <a class="btn-green" href="{{ route('companies.create') }}"> Créer une entreprise</a>
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
            @livewire('company-table')
        </div>

        {{-- without powergrid --}}
        {{-- <table class="min-w-full text-left">
            <thead class="border-b dark:border-neutral-500">
                <tr>
                    <th>N° entreprise</th>
                    <th>Nom entreprise</th>
                    <th>Adresse entreprise</th>
                    <th>Email entreprise</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($companies as $company)
                <tr class="h-12 border-b dark:border-neutral-500">
                    <td>{{ $company->id }}</td>
                    <td>{{ $company->name }}</td>
                    <td>{{ $company->address }}</td>
                    <td>{{ $company->email }}</td>
                    <td>
                        <div class="flex justify-center">
                            @can('company-edit')
                            <a class="btn-blue" href="{{ route('companies.edit', $company->uuid) }}">Edit</a>
                            @endcan

                            @can('company-delete')
                            <form action="{{ route('companies.destroy', $company->uuid) }}" method="Post">
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
        {!! $companies->links() !!} --}}

    </div>
@endsection