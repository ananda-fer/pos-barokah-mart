<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>403 - Akses Ditolak | POS Barokah Mart</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-lg shadow-sm max-w-md w-full text-center">
        <div class="text-6xl font-bold text-red-600 mb-2">403</div>
        <h1 class="text-xl font-semibold text-gray-800 mb-2">Akses Ditolak</h1>
        <p class="text-sm text-gray-600 mb-6">
            Maaf, akun Anda (<strong>{{ auth()->user()->name ?? 'Pengguna' }}</strong>) tidak memiliki izin untuk membuka halaman ini.
        </p>
        <a href="{{ route('dashboard') }}" 
            class="inline-block w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition">
            Kembali ke Dashboard
        </a>
    </div>
</body>
</html>
