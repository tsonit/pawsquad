@extends('layouts.clients')
@section('css')
    <style>
        .breadcrumb {
            background: none !important;
        }

        html,
        body {
            overflow-x: hidden;
        }
    </style>
@endsection
@section('content')
    @include('clients.partials.breadcrum', [
        'breadcrumbs' => [['name' => 'Dịch vụ ' . $data->name, 'url' => '#']],
        'title' => 'Dịch vụ ' . $data->name,
    ])

    <div class="h1-story-area two ">
        <div class="container">
            {!! $content !!}
        </div>
    </div>
    <div class="h2-contact-area row justify-content-center">
        <div class="container">
            @include('clients.partials.booking')
        </div>
    </div>
@endsection
