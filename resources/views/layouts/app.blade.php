<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sport Center') - Sistem Sewa Lapangan Olahraga</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons 1.11.3 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --sc-emerald: #10b981;
            --sc-emerald-dark: #059669;
            --sc-indigo: #4f46e5;
            --sc-indigo-dark: #4338ca;
            --sc-dark: #0f172a;
            --sc-slate: #1e293b;
            --sc-muted: #64748b;
            --sc-light-bg: #f8fafc;
            --font-sans: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--sc-light-bg);
            color: #334155;
            min-height: 100vh;
        }

        /* Branding Colors & Buttons */
        .btn-emerald {
            background-color: var(--sc-emerald);
            color: #ffffff;
            border: 1px solid var(--sc-emerald);
            font-weight: 600;
        }
        .btn-emerald:hover, .btn-emerald:focus {
            background-color: var(--sc-emerald-dark);
            border-color: var(--sc-emerald-dark);
            color: #ffffff;
        }

        .btn-indigo {
            background-color: var(--sc-indigo);
            color: #ffffff;
            border: 1px solid var(--sc-indigo);
            font-weight: 600;
        }
        .btn-indigo:hover, .btn-indigo:focus {
            background-color: var(--sc-indigo-dark);
            border-color: var(--sc-indigo-dark);
            color: #ffffff;
        }

        .text-emerald { color: var(--sc-emerald) !important; }
        .text-indigo { color: var(--sc-indigo) !important; }
        .bg-slate-900 { background-color: var(--sc-dark) !important; }
        .bg-slate-800 { background-color: var(--sc-slate) !important; }

        /* Card & UI Styling */
        .card-custom {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            background-color: #ffffff;
        }
        .card-custom-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-custom-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0,0,0,0.08);
        }

        .auth-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
            background: #ffffff;
        }

        .badge-status {
            padding: 0.4em 0.75em;
            font-weight: 600;
            font-size: 0.75rem;
            border-radius: 9999px;
            letter-spacing: 0.025em;
            text-transform: uppercase;
        }

        /* Sidebar Styles */
        .app-sidebar {
            width: 260px;
            background-color: var(--sc-dark);
            color: #cbd5e1;
            min-height: 100vh;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .app-sidebar .nav-link {
            color: #94a3b8;
            font-weight: 500;
            font-size: 0.875rem;
            padding: 0.65rem 0.9rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.15s ease-in-out;
        }
        .app-sidebar .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.07);
        }
        .app-sidebar .nav-link.active {
            color: #ffffff;
            background-color: var(--sc-indigo);
        }
        .app-sidebar .sidebar-heading {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 700;
            padding: 1rem 0.9rem 0.35rem;
        }

        /* Table custom styling */
        .table-custom {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        .table-custom thead th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.05em;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .table-custom tbody td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.875rem;
            vertical-align: middle;
        }
        .table-custom tbody tr:hover {
            background-color: #f8fafc;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
            }
            .card-custom {
                box-shadow: none !important;
                border: 1px solid #ccc !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="@yield('body-class', '')">
    @hasSection('layout-content')
        @yield('layout-content')
    @else
        <main class="py-4 my-auto d-flex flex-column justify-content-center min-vh-100">
            @yield('content')
        </main>
    @endif

    <!-- Bootstrap 5.3.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>