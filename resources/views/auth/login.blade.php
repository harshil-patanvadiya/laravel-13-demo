<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div
        class="w-full max-w-md bg-white shadow-md rounded-lg p-8"
        x-data="loginForm()"
    >
        <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>

        <template x-if="error">
            <div class="mb-4 text-sm text-red-600" x-text="error"></div>
        </template>

        <form class="space-y-4" x-on:submit.prevent="submit">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input
                    type="email"
                    id="email"
                    x-model="email"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input
                    type="password"
                    id="password"
                    x-model="password"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
            </div>

            <button
                type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Login
            </button>
        </form>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function loginForm() {
            return {
                email: '',
                password: '',
                error: null,
                async submit() {
                    this.error = null;

                    try {
                        const response = await fetch('{{ url('/api/login') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                email: this.email,
                                password: this.password,
                            }),
                        });

                        const data = await response.json().catch(() => ({}));

                        if (!response.ok) {
                            let message = 'Login failed.';

                            if (data?.message) {
                                message = data.message;
                            } else if (data?.errors) {
                                message = Object.values(data.errors).flat().join(' ');
                            }

                            this.error = message;

                            return;
                        }

                        if (data?.token) {
                            localStorage.setItem('auth_token', data.token);
                        }

                        window.location.href = '{{ route('admin.categories.index') }}';
                    } catch (e) {
                        this.error = 'Something went wrong. Please try again.';
                    }
                },
            };
        }
    </script>
</body>
</html>

