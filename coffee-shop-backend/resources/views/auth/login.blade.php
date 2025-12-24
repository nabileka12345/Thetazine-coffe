@extends('layouts.app')

@section('title', 'Login - Thetazine Coffee')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-thetazine-bg to-thetazine-accent px-4">
    <div class="max-w-md w-full" x-data="loginForm()">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3 mb-4">
                <i data-lucide="coffee" class="h-12 w-12 text-thetazine-primary"></i>
                <h1 class="text-4xl font-bold text-thetazine-primary">Thetazine</h1>
            </div>
            <p class="text-thetazine-secondary">Coffee Shop Management System</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-thetazine-primary mb-6">Login to Dashboard</h2>

            <!-- Alert Messages -->
            <div x-show="error" x-cloak class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <p x-text="error"></p>
            </div>

            <div x-show="success" x-cloak class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <p x-text="success"></p>
            </div>

            <!-- Login Form -->
            <form @submit.prevent="login">
                <div class="mb-4">
                    <label class="block text-thetazine-primary font-semibold mb-2">Email</label>
                    <input 
                        type="email" 
                        x-model="formData.email"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-thetazine-primary"
                        placeholder="admin@thetazine.com"
                        required
                    >
                </div>

                <div class="mb-6">
                    <label class="block text-thetazine-primary font-semibold mb-2">Password</label>
                    <input 
                        type="password" 
                        x-model="formData.password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-thetazine-primary"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <button 
                    type="submit"
                    :disabled="loading"
                    class="w-full bg-thetazine-primary hover:bg-thetazine-secondary text-white font-bold py-3 rounded-lg transition duration-200 disabled:opacity-50"
                >
                    <span x-show="!loading">Login</span>
                    <span x-show="loading" x-cloak>Loading...</span>
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-thetazine-primary hover:underline font-semibold">Register</a>
                </p>
            </div>

            <!-- Demo Credentials -->
            <div class="mt-6 p-4 bg-thetazine-bg rounded-lg">
                <p class="text-sm text-thetazine-primary font-semibold mb-2">Demo Credentials:</p>
                <p class="text-sm text-thetazine-secondary">Email: admin@thetazine.com</p>
                <p class="text-sm text-thetazine-secondary">Password: password123</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function loginForm() {
        return {
            formData: {
                email: '',
                password: ''
            },
            loading: false,
            error: '',
            success: '',

            async login() {
                this.loading = true;
                this.error = '';
                this.success = '';

                try {
                    const response = await axios.post('/auth/login', this.formData);
                    
                    if (response.data.status === 'success') {
                        // Store token and user data
                        const token = response.data.authorization.token;
                        const user = response.data.data;
                        
                        localStorage.setItem('auth_token', token);
                        localStorage.setItem('user', JSON.stringify(user));
                        
                        this.success = 'Login successful! Redirecting...';
                        
                        // Redirect based on role
                        setTimeout(() => {
                            if (user.role === 'admin') {
                                window.location.href = '{{ route("dashboard") }}';
                            } else {
                                window.location.href = '{{ route("home") }}';
                            }
                        }, 1000);
                    }
                } catch (error) {
                    this.error = error.response?.data?.message || 'Login failed. Please try again.';
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
@endpush
@endsection
