@extends('layouts.main')

@section('content')

    <div class="container mb-16 mx-auto px-4">
            
        @include('includes.home.services')
    
        @include('includes.home.reviews')
    
        @include('includes.home.contact')
            
    </div>

@endsection
