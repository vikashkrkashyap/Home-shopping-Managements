<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="{{url('bootstrap/css/bootstrap.min.css')}}">
</head>
<body >

<div class="container">
    <div class="row">
        <div class="col-md-6">

            <form class="form-horizontal" action="register" method="POST">
                {!! csrf_field() !!}
                @if(count($errors))
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>{{ $error}}</strong></div>
                    @endforeach
                @endif
                <fieldset>
                    <div id="legend">
                        <legend class="">Register</legend>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="first_name">First Name</label>
                        <div class="controls">
                            <input type="text" id="username" name="first_name" value="{{ old('first_name') }}" placeholder="" class="form-control input-lg">
                            <p class="help-block">First name can contain only letters(Required)</p>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="last_name">First Name</label>
                        <div class="controls">
                            <input type="text" id="username" name="last_name" value="{{ old('last_name') }}" placeholder="" class="form-control input-lg">
                            <p class="help-block">Last name can contain only letters(Not required)</p>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="email">E-mail</label>
                        <div class="controls">
                            <input type="email" id="email" name="email" value= "{{old('email')}}"placeholder="" class="form-control input-lg">
                            <p class="help-block">Please provide your E-mail</p>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="password">Password</label>
                        <div class="controls">
                            <input type="password" id="password" name="password" placeholder="" class="form-control input-lg">
                            <p class="help-block">Password should be at least 6 characters</p>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="password_confirmation">Password (Confirm)</label>
                        <div class="controls">
                            <input type="password" id="password_confirm" name="password_confirmation" placeholder="" class="form-control input-lg">
                            <p class="help-block">Please confirm password</p>
                        </div>
                    </div>

                    <div class="control-group">
                        <!-- Button -->
                        <div class="controls">
                            <button class="btn btn-success">Register</button>
                        </div>
                    </div>
                </fieldset>
            </form>

        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="{{url('bootstrap/js/bootstrap.min.js')}}"></script>
</body>
</html>