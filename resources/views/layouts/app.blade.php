<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden">

    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col">
        <div class="p-6 flex items-center gap-3">
            <div class="bg-red-500 text-white p-2 rounded-lg">
                <i class="fa-solid fa-house-medical"></i>
            </div>
            <span class="text-xl font-bold text-slate-800">Clinic System</span>
        </div>

        <nav class="flex-1 px-4 space-y-2 mt-4">
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->is('dashboard*') ? 'text-blue-600 bg-blue-50 font-bold' : 'text-gray-500 hover:bg-gray-50' }} rounded-xl transition">
                <i class="fa-solid fa-table-columns"></i> Dashboard
            </a>

            <a href="{{ route('patients.index') }}" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->is('patients*') ? 'text-blue-600 bg-blue-50 font-bold' : 'text-gray-500 hover:bg-gray-50' }} rounded-xl transition">
                <i class="fa-solid fa-users"></i> Patients
            </a>

            <a href="{{ route('calendar') }}" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->is('calendar*') ? 'text-blue-600 bg-blue-50 font-bold' : 'text-gray-500 hover:bg-gray-50' }} rounded-xl transition">
                <i class="fa-solid fa-calendar-days"></i> Calendar
            </a>

            <a href="#" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->is('services*') ? 'text-blue-600 bg-blue-50 font-bold' : 'text-gray-500 hover:bg-gray-50' }} rounded-xl transition">
                <i class="fa-solid fa-stethoscope"></i> Services
            </a>

            <a href="#" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->is('medicines*') ? 'text-blue-600 bg-blue-50 font-bold' : 'text-gray-500 hover:bg-gray-50' }} rounded-xl transition">
                <i class="fa-solid fa-pills"></i> Medicines
            </a>
        </nav>

        <div class="p-4 border-t border-gray-100">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 w-full rounded-xl transition font-medium">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-y-auto">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 shrink-0">
            <div class="text-sm text-gray-500">Welcome to Barangay Banilad Clinic Management System</div>
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <p class="text-sm font-bold text-slate-800">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500">Administrator</p>
                </div>
                <div class="w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center text-slate-500 font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        <div class="p-8">
            @yield('content')
        </div>
    </main>

</body>
</html>