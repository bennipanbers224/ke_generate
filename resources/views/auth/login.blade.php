@extends('auth.auth-app')

@section('content')
    <div class="container" style="width: 400px; height:150px; margin-top:50px">
        <form method="post" action="/loginPost">
            
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <img src="{{asset('images/logo.png')}}" class="card-img-top" alt="..." style="width: 200px; height:100px;">
            
            <h1 class="h3 mb-3 fw-normal">Login</h1>

            @include('auth.messages')

            <div class="form-group form-floating mb-3">
                <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username" required="required" autofocus>
                <label for="floatingName">Email or Username</label>
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

            <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
            
            <p>Don't have account? </p><a href="/register">Sign up here</a>
        </form>
    </div>
@endsection