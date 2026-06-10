<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Sistem Peminjaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen flex items-start justify-center py-8 px-4 relative bg-slate-900">
    
    <!-- Full Background Image -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <img src="{{ asset('images/sekolah_nyata.jpg') }}" class="w-full h-full object-cover opacity-60" alt="School Background">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900/90 via-slate-900/60 to-slate-900/90"></div>
    </div>

    <div class="w-full @yield('max-width', 'max-w-md') relative z-10 my-auto">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>
