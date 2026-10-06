@extends('layouts.app')

@section('title', 'Segera Hadir')
@section('page-title', 'Segera Hadir')
@section('page-subtitle', 'Fitur ini sedang dibangun')

@section('content')
    <div class="bg-white border border-[#E3EAE3] rounded-2xl">
        <x-empty-state
            icon="clock"
            title="Fitur ini akan segera hadir"
            description="Kami sedang menyiapkannya. Sementara itu, jelajahi fitur lainnya." />
    </div>
@endsection