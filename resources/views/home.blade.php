@extends('main')
@section('page-body')
    <div class="well-lg" style="margin-left:15%;color:white;">
        <h1 style="font-size: 45px">Welcome to your purchase Manager </h1>
        <h3 style="margin-left:35px;font-style:italic">Login or signup to create the list or view the trending shop</h3>

        <div class="col-sm-offset-4" style="margin: 10% 0 0 15%;">
            <a href="{{url('login')}}">
                <button type="button" class="btn btn-success custom-button-width .navbar-right">Login</button>
            </a>
            <a href="{{url('register')}}">
                <button type="button" class="btn btn-primary custom-button-width .navbar-right ">Signup</button>
            </a>
        </div>

    </div>

    @endsection
@stop