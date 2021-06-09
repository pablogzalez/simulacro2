@extends('layout')

@section('title', 'Editar usuario')

@section('content')
    @card
        @slot('header', 'Editar usuario')
        @include('shared._errors')

        <form method="post" action="{{ route('users.update', $user) }}">
            {{ method_field('PUT') }}

            @include('users._fields')

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">Editar usuario</button>
                <a href="{{ route('users.index') }}" class="btn btn-link">Regresar al listado de usuarios</a>
            </div>
        </form>
    @endcard
@endsection
