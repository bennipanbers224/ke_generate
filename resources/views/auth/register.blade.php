@extends('auth.auth-app')

@section('content')
    <div class ="container" class="container" style="width: 400px; height:150px; margin-top:50px">
        <form method="post" action="/registerPost">

            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <img src="{{asset('images/logo.png')}}" class="card-img-top" alt="..." style="width: 200px; height:100px;">

            <h1 class="h3 mb-3 fw-normal">Register</h1>

            <div class="form-group form-floating mb-3">
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Complete Name" required="required" autofocus>
                <label for="floatingName">Name</label>
                @if ($errors->has('name'))
                    <span class="text-danger text-left">{{ $errors->first('name') }}</span>
                @endif
            </div>

            <div class="form-group form-floating mb-3">
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="name@example.com" required="required" autofocus>
                <label for="floatingEmail">Email address</label>
                @if ($errors->has('email'))
                    <span class="text-danger text-left">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <div class="form-group form-floating mb-3">
                <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username" required="required" autofocus>
                <label for="floatingName">Username</label>
                @if ($errors->has('username'))
                    <span class="text-danger text-left">{{ $errors->first('username') }}</span>
                @endif
            </div>

            <div class="form-group form-floating mb-3">
                <input type="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="Password" required="required">
                <label for="floatingPassword">Password</label>
                @if ($errors->has('password'))
                    <span class="text-danger text-left">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <div class="form-group form-floating mb-3">
                <input type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="Confirm Password" required="required">
                <label for="floatingConfirmPassword">Confirm Password</label>
                @if ($errors->has('password_confirmation'))
                    <span class="text-danger text-left">{{ $errors->first('password_confirmation') }}</span>
                @endif
            </div>

            <div class="form-group form-floating mb-3">
                <input type="hidden" class="form-control" name="status" placeholder="status" value="Public" required="required">
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Register</button>

            <p>Have account? </p><a href="/instansi">Login here</a>

            @include('auth.copy')

        </form>
    </div>
@endsection