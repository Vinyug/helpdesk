@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left mt-5">
                    <h2>Liste des entreprises</h2>
                </div>
                <div class="pull-right my-3">
                    @can('company-create')
                    <a class="btn btn-info mr-3" href="{{ route('companies.create') }}">Créer une entreprise</a>
                    @endcan
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif


        @livewire('company-table')




        {{-- <table class="table table-bordered">
            <thead>
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
                    <tr>
                        <td>{{ $company->id }}</td>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->address }}</td>
                        <td>{{ $company->email }}</td>
                        <td>
                            <form action="{{ route('companies.destroy',$company->id) }}" method="Post">
                                @can('company-edit')
                                <a class="btn btn-primary" href="{{ route('companies.edit',$company->id) }}">Edit</a>
                                @endcan

                                @csrf
                                @method('DELETE')
                                @can('company-delete')
                                <button type="submit" class="btn btn-outline-danger">Delete</button>
                                @endcan
                            </form>
                        </td>
                    </tr>
                    @endforeach
            </tbody>
        </table>
        {!! $companies->links() !!} --}}
    </div>
@endsection