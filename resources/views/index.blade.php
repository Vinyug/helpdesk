@extends('layouts.main')

@section('content')

    <div class="container mb-16 mx-auto px-4">
        <div class="flex flex-col flex-wrap">
            
            @include('includes.home.services')
        
            @include('includes.home.reviews')
        
            @include('includes.home.contact')
            
        </div>
    </div>

@endsection
