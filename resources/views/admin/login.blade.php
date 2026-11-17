@extends('admin.layout')

@section('title', 'Admin Login')

@section('content')
    <div class="card">
        <h1 style="margin-top:0;font-size:22px;">Admin Login</h1>
        <p class="hint">Melden Sie sich an, um das Zahlungskonto zu bearbeiten.</p>

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <label for="email">E-Mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')<div class="error">{{ $message }}</div>@enderror

            <label for="password">Passwort</label>
            <input id="password" type="password" name="password" required>
            @error('password')<div class="error">{{ $message }}</div>@enderror

            <label style="font-weight:500;">
                <input type="checkbox" name="remember" value="1" style="width:auto;"> Angemeldet bleiben
            </label>

            <div style="margin-top:18px;">
                <button type="submit">Anmelden</button>
            </div>
        </form>
    </div>
@endsection
