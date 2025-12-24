@extends('layouts.app')

@section('title', 'Register - Thetazine Coffee')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-thetazine-bg to-thetazine-accent px-4 py-8">
    <div class="max-w-md w-full" x-data="registerForm()">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3 mb-4">
                <i data-lucide="coffee" class="h-12 w-12 text-thetazine-primary"></i>
                <h1 class="text-4xl font-bold text-thetazine-primary">Thetazine</h1>
            </div>
            <p class="text-thetazine-secondary">Coffee Shop Management System</p>
        </div>

        <!-- Register Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-thetazine-primary mb-6">Create Account</h2>

            <!-- Alert Messages -->
            <div x-show="error" x-cloak class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                <p x-text="error"></p>
            </div>

            <div x-show="success" x-cloak class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <p x-text="success"></p>
            </div>

            <!-- Register Form -->
            <form @submit.prevent="register">
                <div class="mb-4">
                    <label class="block text-thetazine-primary font-semibold mb-2">Full Name</label>
                    <input 
                        type="text" 
                        x-model="formData.name"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-thetazine-primary"
                        placeholder="John Doe"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label class="block text-thetazine-primary font-semibold mb-2">Email</label>
                    <input 
                        type="email" 
                        x-model="formData.email"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-thetazine-primary"
                        placeholder="john@example.com"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label class="block text-thetazine-primary font-semibold mb-2">Password</label>
                    <input 
                        type="password" 
                        x-model="formData.password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-thetazine-primary"
                        placeholder="••••••••"
                        required
                        minlength="6"
                    >
                </div>

                <div class="mb-6">
                    <label class="block text-thetazine-primary font-semibold mb-2">Confirm Password</label>
                    <input 
                        type="password" 
                        x-model="formData.password_confirmation"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-thetazine-primary"
                        placeholder="••••••••"
                        required
                        minlength="6"
                    >
                </div>

                <button 
                    type="submit"
                    :disabled="loading"
                    class="w-full bg-thetazine-primary hover:bg-thetazine-secondary text-white font-bold py-3 rounded-lg transition duration-200 disabled:opacity-50"
                >
                    <span x-show="!loading">Register</span>
                    <span x-show="loading" x-cloak>Creating Account...</span>
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-thetazine-primary hover:underline font-semibold">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function registerForm() {
        return {
            formData: {
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            },
            loading: false,
            error: '',
            success: '',

            async register() {
                this.loading = true;
                this.error = '';
                this.success = '';

                // Validate passwords match
                if (this.formData.password !== this.formData.password_confirmation) {
                    this.error = 'Passwords do not match';
                    this.loading = false;
                    return;
                }

                try {
                    const response = await axios.post('/auth/register', this.formData);
                    
                    if (response.data.status === 'success') {
                        // Store token and user data
                        const token = response.data.authorization.token;
                        const user = response.data.data;
                        
                        localStorage.setItem('auth_token', token);
                        localStorage.setItem('user', JSON.stringify(user));
                        
                        this.success = 'Account created successfully! Redirecting...';
                        
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
                    if (error.response?.data?.errors) {
                        const errors = Object.values(error.response.data.errors).flat();
                        this.error = errors.join(', ');
                    } else {
                        this.error = error.response?.data?.message || 'Registration failed. Please try again.';
                    }
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
@endpush
@endsection
