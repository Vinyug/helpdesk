@extends('layouts.main')

@section('content')

    <div class="container mb-16 mx-auto px-4">
        <div class="flex flex-col flex-wrap">
            
            <div>
                @include('includes.home.services')
            </div>

            <div>
                @include('includes.home.reviews')
            </div>

            <div>
                @include('includes.home.contact')
            </div>
        </div>
    </div>

@endsection
