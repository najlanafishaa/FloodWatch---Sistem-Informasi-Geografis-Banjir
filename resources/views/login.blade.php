@extends('layouts.app')

@section('title', 'Login Admin — FloodWatch')

@section('styles')
    <style>
        .login-page-bg {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            background: radial-gradient(circle at 50% 30%, rgba(252, 246, 189, 0.4) 0%, rgba(255, 255, 255, 1) 90%);
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
            background: #FFFFFF;
            border: 2px solid var(--primary-dark-blue);
            box-shadow: var(--shadow-flat);
            border-radius: 20px;
        }

        .login-header {
            text-align: center;
        }

        .login-logo {
            font-size: 1.8rem;
            font-weight: 800;
            font-family: 'Poppins', sans-serif;
            color: var(--primary-dark-blue);
            margin-bottom: 0.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .login-logo span {
            color: var(--primary-dark-blue);
        }
        
        .login-logo span span.brand-dot {
            color: var(--primary-blue);
        }

        .login-subtitle {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1.2rem;
        }

        .form-label {
            font-family: 'Poppins', sans-serif;
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--primary-dark-blue);
        }

        .form-input {
            width: 100%;
            background: #FFFFFF;
            border: 2px solid var(--primary-dark-blue);
            padding: 0.9rem 1rem;
            border-radius: 12px;
            color: var(--primary-dark-blue);
            font-weight: 600;
            font-size: 0.95rem;
            transition: var(--transition-smooth);
            box-shadow: 2px 2px 0 var(--primary-dark-blue);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-blue);
            box-shadow: 4px 4px 0 var(--primary-dark-blue);
            transform: translate(-1px, -1px);
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: var(--primary-dark-blue);
            font-weight: 600;
            cursor: pointer;
        }

        .checkbox-input {
            accent-color: var(--primary-blue);
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .login-back-btn {
            font-family: 'Poppins', sans-serif;
            font-size: 0.88rem;
            color: var(--primary-dark-blue);
            font-weight: 700;
            text-align: center;
            margin-top: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }

        .login-back-btn:hover {
            color: var(--primary-blue);
        }

        .error-message {
            color: #EF476F;
            font-size: 0.8rem;
            margin-top: 0.3rem;
            font-weight: 600;
        }
    </style>
@endsection

@section('content')
    <div class="login-page-bg">
        <div class="login-card">
            
            <!-- Login Brand Header -->
            <div class="login-header">
                <a href="{{ route('landing') }}" class="login-logo">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary-blue);"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span>FloodWatch<span class="brand-dot">.</span></span>
                </a>
                <p class="login-subtitle">Sistem Informasi Geografis Banjir Lampung</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" autocomplete="off">
                @csrf
                
                <!-- Success Alert -->
                @if (session('success'))
                    <div class="alert-banner alert-success" style="padding: 0.8rem; font-size: 0.82rem; margin-bottom: 1.2rem;">
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Email Input -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Admin</label>
                    <input type="email" name="email" id="email" class="form-input" placeholder="admin@floodwatch.id" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-input" placeholder="Masukkan password admin..." required>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember and Action Panel -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.8rem; flex-wrap: wrap; gap: 0.8rem;">
                    <label class="checkbox-container">
                        <input type="checkbox" name="remember" class="checkbox-input">
                        <span>Ingat Saya</span>
                    </label>
                </div>

                <!-- Action Button -->
                <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; font-size: 1rem; padding: 0.9rem;">
                    Masuk ke Dashboard
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                </button>
            </form>

            <!-- Back link -->
            <a href="{{ route('landing') }}" class="login-back-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali ke Beranda
            </a>

        </div>
    </div>
@endsection
