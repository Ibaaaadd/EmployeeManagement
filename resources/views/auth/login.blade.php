@extends('layouts.auth')

@section('content')
    <div class="container">
        <div class="login-container">
            <h2 class="text-center mb-4">LOGIN</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

               <button type="submit" class="btn w-100 text-white" style="background-color: #001f3f; border-color: #001f3f;">
                    Login
                </button>


                @if ($errors->has('login'))
                    <div class="alert alert-danger">
                        {{ $errors->first('login') }}
                    </div>
                @endif


                <div class="mt-3 text-center">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Forgot Your Password?</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
