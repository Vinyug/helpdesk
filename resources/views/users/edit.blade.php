@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 mt-5">
            <div class="pull-left">
                <h2>Modifier un utilisateur</h2>
            </div>
            <div class="pull-right my-3">
                <a class="btn btn-info" href="{{ route('users.index') }}"> Retour </a>
            </div>
        </div>
    </div>
    
    
    @if (count($errors) > 0)
      <div class="alert alert-danger">
        <strong>Whoops!</strong> Something went wrong.<br><br>
        <ul>
           @foreach ($errors->all() as $error)
             <li>{{ $error }}</li>
           @endforeach
        </ul>
      </div>
    @endif
    
    
    {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}
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
                {!! Form::select('company_id', $company, $userCompany, array('placeholder' => 'Choisir une entreprise', 'class' => 'form-control')) !!}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Poste:</strong>
                {!! Form::select('job', $job, $userJob, array('placeholder' => 'Choisir un poste', 'class' => 'form-control')) !!}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Role:</strong>
                {!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control','multiple')) !!}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <button type="submit" class="btn btn-outline-primary">Modifier</button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection