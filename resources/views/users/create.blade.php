@extends('layouts.main')

@section('content')
    <div class="container mb-16 mx-auto sm:px-4">
        <div class="flex flex-wrap">
            <div class="lg:w-full pr-4 pl-4 mt-5">
                <div class="pull-left mb-2">
                    <h2 class="font-share-tech mt-8 mb-12 text-4xl">Créer un utilisateur</h2>
                </div>
                <div class="pull-right my-3">
                    <a class="btn-blue" href="{{ route('users.index') }}"> Retour</a>
                </div>
            </div>
        </div>

        @if(session('status'))
        <div class="custom-status">
            {{ session('status') }}
        </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid sm:grid-cols-2 gap-4 p-4">    
                <div class="col-span-full">
                    <label for="firstname" class="custom-label">Prénom : <span class="text-red-600 font-bold">*</span></label>
                    <input type="text" name="firstname" id="firstname" class="custom-input" placeholder="Saisir le Prénom" value="{{ old('firstname') }}" required>
                    @error('firstname')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-full">
                    <label for="lastname" class="custom-label">Nom : <span class="text-red-600 font-bold">*</span></label>
                    <input type="text" name="lastname" id="lastname" class="custom-input" placeholder="Saisir le Nom" value="{{ old('lastname') }}" required>
                    @error('lastname')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-span-full">
                    <label for="email" class="custom-label">Email : <span class="text-red-600 font-bold">*</span></label>
                    <input type="email" name="email" id="email" class="custom-input" placeholder="Saisir l'email" value="{{ old('email') }}" required>
                    @error('email')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="w-full">
                    <label for="password" class="custom-label">Mot de passe : <span class="text-red-600 font-bold">*</span></label>
                    <input type="password" name="password" id="password" class="custom-input" placeholder="Saisir le mot de passe" required>
                    @error('password')
                    <div class="custom-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="w-full">
                    <label for="confirm-password" class="custom-label">Confirmation du mot de passe : <span class="text-red-600 font-bold">*</span></label>
                    <input type="password" name="confirm-password" id="confirm-password" class="custom-input" placeholder="Saisir la confirmation de mot de passe" required>
                </div>
                
                <div class="w-full">
                    <label for="company" class="custom-label">Entreprise : </label>
                    <select class="custom-input"name="company" id="company">
                        <option value="">Choisir une entreprise</option>
                        @foreach ($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->name }}</option>   
                        @endforeach
                    </select>
                </div>

                <div class="w-full">
                    <label for="role" class="custom-label">Rôle : <span class="text-red-600 font-bold">*</span></label>
                    <select class="custom-input"name="role" id="role">
                        <option value="">Choisir un rôle</option>
                        @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>   
                        @endforeach
                    </select>
                </div>

                <div class="block col-span-full text-red-600 mb-5 ml-4">* les champs obligatoires</div>

                <div class="col-span-full">
                    <button type="submit" class="btn-orange">Créer</button>
                </div>
            </div>
        </form>

        {{-- {!! Form::open(array('route' => 'users.store','method'=>'POST')) !!}
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Email:</strong>
                    {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Password:</strong>
                    {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Confirm Password:</strong>
                    {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Entreprise:</strong>
                    {!! Form::select('company_id',$company, null, array('placeholder' => 'Choisir une entreprise', 'class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Poste:</strong>
                    {!! Form::select('job',$job, null, array('placeholder' => 'Choisir un poste', 'class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Role:</strong>
                    {!! Form::select('roles[]', $roles,[], array('class' => 'form-control','multiple')) !!}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-outline-primary">Créer</button>
            </div>
        </div>
    {!! Form::close() !!} --}}
    </div>
@endsection