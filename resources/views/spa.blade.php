@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <div class="w-full flex flex-row">
            <div class="w-1/4 p-4">
                Menu Goes Here.
            </div>

            <div class="w-3/4 p-4 bg-white rounded-md">
                <router-view>
                    <h1>Hola SPA</h1>
                </router-view>
            </div>
        </div>
    </div>
@endsection
