<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" :class="$errors->get('email') ? 'is-invalid' : ''" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div class="mt-3">
            <x-input-label for="password" :value="__('Password')" />

            <div class="position-relative">
                <x-text-input id="password" type="password" name="password"
                    class="{{ $errors->get('password') ? 'is-invalid' : '' }} pe-5"
                    required autocomplete="current-password" />

                <!-- Show/Hide Icon -->
                <span id="togglePassword" 
                    style="position:absolute; right:10px; top:50%; transform:translateY(-50%); cursor:pointer; display:none;">
                    <i id="eyeIcon" class="bi bi-eye"></i>
                    <i id="eyeSlashIcon" class="bi bi-eye-slash" style="display:none;"></i>
                </span>
            </div>

            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Remember Me -->
        <div class="form-check mt-3">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label">
                {{ __('Remember me') }}
            </label>
        </div>

        <div class="text-end mt-3">
            @if (Route::has('password.request'))
                <a class="text-secondary" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Script Show/Hide Password -->
    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeSlashIcon = document.getElementById('eyeSlashIcon');

        passwordInput.addEventListener('input', () => {
            togglePassword.style.display = passwordInput.value.length > 0 ? 'block' : 'none';
        });

        togglePassword.addEventListener('click', () => {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            eyeIcon.style.display = isPassword ? 'none' : 'inline';
            eyeSlashIcon.style.display = isPassword ? 'inline' : 'none';
        });
    </script>

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</x-guest-layout>
