<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-500 to-cyan-400 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white rounded-[2rem] shadow-2xl flex flex-col md:flex-row max-w-4xl w-full overflow-hidden">
        <div class="md:w-1/2 bg-blue-50 p-10 flex flex-col items-center text-center">
            <img src="https://i.imgur.com/your-image-here.jpg" class="rounded-lg shadow-md mb-6 w-full object-cover aspect-square" alt="Clinic">
            <h2 class="text-blue-900 font-bold text-xl leading-tight">Login your Account to get full User Experience</h2>
            <p class="text-blue-500 text-sm mt-2">You depend on our clinic to get good patient solutions.</p>
        </div>

        <div class="md:w-1/2 p-10 lg:p-16">
            <h1 class="text-3xl font-bold text-gray-800">Hello!</h1>
            <h2 class="text-xl font-semibold text-gray-700 mb-1">Good Morning</h2>
            <p class="text-gray-400 text-sm mb-8">Login your account</p>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-600 text-sm mb-1">Username (Email)</label>
                    <input type="text" name="email" class="w-full px-4 py-2 bg-blue-50 border-none rounded-lg focus:ring-2 focus:ring-blue-400 outline-none" placeholder="ken123@example.com">
                </div>

                <div class="mb-2">
                    <label class="block text-gray-600 text-sm mb-1">Password</label>
                    <input type="password" name="password" class="w-full px-4 py-2 bg-blue-50 border-none rounded-lg focus:ring-2 focus:ring-blue-400 outline-none" placeholder="···">
                </div>

                <div class="text-right mb-6">
                    <a href="#" class="text-xs text-blue-600">Forgot password?</a>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg transition">Login</button>
            </form>

            <div class="mt-8 text-center">
                <a href="{{ route('register') }}" class="text-sm text-blue-600 font-semibold hover:underline">Create Account</a>
                
            </div>
        </div>
    </div>
</body>
</html>