<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-500 to-cyan-400 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white rounded-[2rem] shadow-2xl p-10 max-w-md w-full">
        <h1 class="text-3xl font-bold mb-2 text-gray-800">Create Account</h1>
        <p class="text-gray-400 mb-6">Join our clinic system</p>

        @if ($errors->any())
            <div class="bg-red-50 text-red-500 p-4 rounded-lg mb-6 text-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-600 text-sm mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 bg-blue-50 rounded-lg outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 text-sm mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 bg-blue-50 rounded-lg outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 text-sm mb-1">Password (Min 8 chars)</label>
                <input type="password" name="password" class="w-full px-4 py-2 bg-blue-50 rounded-lg outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div class="mb-8">
                <label class="block text-gray-600 text-sm mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-2 bg-blue-50 rounded-lg outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl shadow-lg hover:bg-blue-700 transition">
                Create Account
            </button>
        </form>

        <p class="mt-6 text-center text-sm">
            <a href="{{ route('login') }}" class="text-blue-600 font-semibold">Already have an account? Login</a>
        </p>
    </div>
</body>
</html>