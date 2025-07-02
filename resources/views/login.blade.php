<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('modules/login.login') }}</title>
    <link rel="stylesheet" href="{{ asset('build/assets/css/login.css') }}">
</head>
<body>
    <div class="login-container">
        <h1>{{ __('modules/login.admin_login') }}</h1>

        @if(session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">{{ __('models/user.email') }}</label>
                <input type="email" id="email" name="email" class="form-control"
                       value="{{ app()->environment('local') ? 'test@example.com' : '' }}" required>
            </div>

            <div class="form-group">
                <label for="password">{{ __('models/user.password') }}</label>
                <input type="password" id="password" name="password" class="form-control"
                       value="{{ app()->environment('local') ? 'password' : '' }}" required>
            </div>

            <button type="submit" class="btn-login">{{ __('modules/login.login') }}</button>
        </form>
    </div>
</body>
</html>
