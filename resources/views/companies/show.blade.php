@extends('layouts.main')

@section('content')
    <div class="container mb-16 mx-auto px-4">
        <div class="flex flex-wrap">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <div class="pull-left mb-2">
                    <h2 class="font-share-tech mt-8 mb-12 text-4xl">Information de l'entreprise</h2>
                </div>
                <div class="pull-right my-3">
                    <a class="btn-blue" href="{{ route('companies.index') }}"> Retour</a>
                </div>
            </div>
        </div>
        
        <div class="w-full">
            <div class="flex">
                <strong>Nom :</strong>
                <p class="pl-2">{{ $company->name }}</p>
            </div>
            
            <div class="flex">
                <strong>Adresse :</strong>
                <p class="pl-2">{{ $company->address }}</p>
            </div>
            
            <div class="flex">
                <strong>Ville :</strong>
                <p class="pl-2">{{ $company->city }}</p>
            </div>
            
            <div class="flex">
                <strong>Code postal :</strong>
                <p class="pl-2">{{ $company->zip_code }}</p>
            </div>
            
            <div class="flex">
                <strong>Siret :</strong>
                <p class="pl-2">{{ $company->siret }}</p>
            </div>
            
            <div class="flex">
                <strong>Code APE :</strong>
                <p class="pl-2">{{ $company->code_ape }}</p>
            </div>
            
            <div class="flex">
                <strong>Téléphone :</strong>
                <p class="pl-2">{{ $company->phone }}</p>
            </div>
            
            <div class="flex">
                <strong>Email :</strong>
                <p class="pl-2">{{ $company->email }}</p>
            </div>
            
            <div class="mt-4">
                <strong>Membres de l'entreprise :</strong>
            
                <table class="min-w-full text-left mt-2">
                    <thead class="border-b border-custom-blue">
                        <tr class="text-sm md:text-base">
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Roles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($company->users as $user)
                        <tr class="text-sm h-8 border-b border-custom-blue">
      
                            <td class="pr-2">{{ $user->firstname }} {{ $user->lastname }}</td>
                            <td class="pr-2">{{ $user->email }}</td>
                            <td>
                                @if (!empty($user->getRoleNames()))
                                    @foreach ($user->getRoleNames() as $v)
                                        <label class="badge badge-success">{{ $v }}</label>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
@endsection
        