@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $user->name }}'s profile</div>

                <div class="panel-body">
                    Hello {{ Auth::user()->name }}, this is a {{ $user->name }}'s profile.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
