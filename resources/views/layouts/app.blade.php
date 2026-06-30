<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema de Inventario') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Custom Styles -->
    <style>
        :root {
            --sidebar-width: 280px;
            --header-height: 64px;
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --primary-soft: #eef2ff;
            --secondary-color: #6366f1;
            --accent-purple: #8b5cf6;
            --accent-pink: #ec4899;
            --accent-cyan: #06b6d4;
            --accent-emerald: #10b981;
            --bg-base: #f6f8fb;
            --bg-elevated: #ffffff;
            --bg-subtle: #f1f5f9;
            --bg-muted: #f8fafc;
            --border-color: #e5e7eb;
            --border-soft: #eef2f7;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --shadow-xs: 0 1px 2px 0 rgba(15, 23, 42, 0.04);
            --shadow-sm: 0 1px 3px 0 rgba(15, 23, 42, 0.06), 0 1px 2px -1px rgba(15, 23, 42, 0.04);
            --shadow-md: 0 4px 12px -2px rgba(15, 23, 42, 0.08), 0 2px 4px -2px rgba(15, 23, 42, 0.04);
            --shadow-lg: 0 12px 24px -8px rgba(15, 23, 42, 0.12), 0 4px 8px -4px rgba(15, 23, 42, 0.06);
        }

        /* ============ Dark Mode ============ */
        [data-theme="dark"] {
            --bg-base: #0f172a;
            --bg-elevated: #1e293b;
            --bg-subtle: #1e293b;
            --bg-muted: #162032;
            --border-color: #334155;
            --border-soft: #1e2d42;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --primary-soft: #1e1b4b;
        }
        [data-theme="dark"] body { color-scheme: dark; }
        [data-theme="dark"] .main-header {
            background: rgba(15,23,42,0.92);
            border-bottom-color: #1e293b;
        }
        [data-theme="dark"] .card {
            background: var(--bg-elevated);
            border-color: var(--border-soft);
        }
        [data-theme="dark"] .card-header {
            background: linear-gradient(180deg, #1a2740 0%, #162032 100%);
            border-bottom-color: var(--border-soft);
            color: var(--text-primary);
        }
        [data-theme="dark"] .table thead th {
            background: linear-gradient(180deg, #1a2740 0%, #162032 100%);
            color: var(--text-secondary);
        }
        [data-theme="dark"] .table tbody td { color: var(--text-primary); border-bottom-color: var(--border-soft); }
        [data-theme="dark"] .table tbody tr:nth-child(even) { background-color: #162032; }
        [data-theme="dark"] .table tbody tr:hover { background-color: #1e1b4b !important; }
        [data-theme="dark"] .form-control, [data-theme="dark"] .form-select {
            background: #1e293b; border-color: #334155; color: var(--text-primary);
        }
        [data-theme="dark"] .form-control:focus, [data-theme="dark"] .form-select:focus {
            background: #1e293b; color: var(--text-primary);
        }
        [data-theme="dark"] .dropdown-menu {
            background: #1e293b; border-color: #334155;
        }
        [data-theme="dark"] .dropdown-item { color: var(--text-primary); }
        [data-theme="dark"] .dropdown-item:hover { background: #0f172a; }
        [data-theme="dark"] .page-title { color: var(--text-primary); }
        [data-theme="dark"] .header-search .form-control { background: #1e293b; color: var(--text-primary); }
        [data-theme="dark"] .btn-back { background: #1e293b; color: var(--text-secondary); border-color: #334155; }
        [data-theme="dark"] .alert-success { background: #064e3b; border-color: #065f46; color: #6ee7b7; }
        [data-theme="dark"] .alert-danger { background: #450a0a; border-color: #7f1d1d; color: #fca5a5; }
        [data-theme="dark"] .alert-info { background: #0c4a6e; border-color: #075985; color: #7dd3fc; }
        [data-theme="dark"] .alert-warning { background: #451a03; border-color: #78350f; color: #fcd34d; }
        [data-theme="dark"] .alert-secondary { background: #1e293b; border-color: #334155; color: #94a3b8; }
        [data-theme="dark"] .text-muted { color: #94a3b8 !important; }
        [data-theme="dark"] .text-dark { color: var(--text-primary) !important; }
        [data-theme="dark"] .form-label { color: var(--text-primary); }
        [data-theme="dark"] .list-group-item {
            background: var(--bg-elevated);
            border-color: var(--border-soft);
            color: var(--text-primary);
        }
        [data-theme="dark"] .modal-content {
            background: var(--bg-elevated);
            border-color: var(--border-soft);
            color: var(--text-primary);
        }
        [data-theme="dark"] .modal-header {
            border-bottom-color: var(--border-soft);
        }
        [data-theme="dark"] .modal-footer {
            border-top-color: var(--border-soft);
        }
        [data-theme="dark"] .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        [data-theme="dark"] .nav-link {
            color: var(--text-secondary);
        }
        [data-theme="dark"] .nav-link:hover, [data-theme="dark"] .nav-link.active {
            color: var(--text-primary);
        }
        [data-theme="dark"] .pagination .page-link {
            background: var(--bg-elevated);
            border-color: var(--border-soft);
            color: var(--text-primary);
        }
        [data-theme="dark"] .pagination .page-link:hover {
            background: var(--bg-muted);
        }
        [data-theme="dark"] .pagination .page-item.active .page-link {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* ============ Skeleton Loader ============ */
        .skeleton {
            background: linear-gradient(90deg, #f0f2f5 25%, #e8eaed 50%, #f0f2f5 75%);
            background-size: 400% 100%;
            animation: skeleton-wave 1.4s ease infinite;
            border-radius: 0.4rem;
        }
        [data-theme="dark"] .skeleton {
            background: linear-gradient(90deg, #1e293b 25%, #273549 50%, #1e293b 75%);
            background-size: 400% 100%;
        }
        @keyframes skeleton-wave { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
        .skeleton-row td { padding: 0.85rem 1rem; }
        .skeleton-cell { height: 16px; display: block; }

        /* ============ Sidebar Badge ============ */
        .nav-link .badge-dot {
            width: 18px; height: 18px; border-radius: 50%;
            background: #ef4444; color: #fff;
            font-size: 0.6rem; font-weight: 700;
            display: inline-flex; align-items: center; justify-content: center;
            margin-left: auto; animation: badge-pulse 2s infinite;
        }
        @keyframes badge-pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.4); }
            50% { box-shadow: 0 0 0 4px rgba(239,68,68,0); }
        }

        /* ============ Dark Mode Toggle ============ */
        .dark-toggle {
            width: 38px; height: 38px;
            border-radius: 50%;
            border: 1px solid var(--border-soft);
            background: var(--bg-subtle);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s ease;
            color: var(--text-secondary);
        }
        .dark-toggle:hover { background: var(--primary-soft); color: var(--primary-color); border-color: var(--primary-color); }

        /* ============ Photo Preview ============ */
        .photo-preview-container { position: relative; display: inline-block; }
        .photo-preview-img { width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 3px solid var(--border-soft); }
        .photo-preview-placeholder { width: 100px; height: 100px; border-radius: 50%; background: var(--bg-subtle); border: 3px dashed var(--border-color); display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 2rem; cursor: pointer; }

        /* ============ Timeline ============ */
        .timeline { position: relative; padding-left: 2rem; }
        .timeline::before { content: ''; position: absolute; left: 0.6rem; top: 0; bottom: 0; width: 2px; background: var(--border-soft); }
        .timeline-item { position: relative; padding-bottom: 1.5rem; }
        .timeline-item:last-child { padding-bottom: 0; }
        .timeline-dot {
            position: absolute; left: -1.68rem; top: 0.15rem;
            width: 22px; height: 22px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.65rem; font-weight: 700; color: #fff;
            border: 2px solid var(--bg-elevated);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }
        .timeline-dot.assigned { background: #3b82f6; }
        .timeline-dot.returned { background: #10b981; }
        .timeline-dot.maintenance { background: #f59e0b; }
        .timeline-dot.created { background: #8b5cf6; }
        .timeline-dot.transferred { background: #06b6d4; }
        .timeline-dot.retired { background: #64748b; }
        .timeline-content { background: var(--bg-subtle); border-radius: 0.6rem; padding: 0.85rem 1rem; border: 1px solid var(--border-soft); }
        .timeline-date { font-size: 0.75rem; color: var(--text-muted); }

        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-base);
            background-image:
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.04) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(139, 92, 246, 0.03) 0px, transparent 50%);
            background-attachment: fixed;
            color: var(--text-primary);
        }

        /* ============ Sidebar ============ */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #111827 0%, #0b1120 100%);
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
            border-right: 1px solid rgba(255,255,255,0.06);
        }

        .sidebar-brand {
            padding: 1.4rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.18) 0%, rgba(139, 92, 246, 0.08) 100%);
        }

        .sidebar-brand h4 {
            color: #fff;
            font-weight: 700;
            margin: 0;
            font-size: 1.05rem;
            letter-spacing: -0.01em;
        }

        .sidebar-brand h4 i {
            background: linear-gradient(135deg, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sidebar-brand small {
            color: rgba(255,255,255,0.55);
            font-size: 0.72rem;
            letter-spacing: 0.02em;
        }

        .sidebar-nav { padding: 0.75rem 0 2rem; }

        .nav-section {
            padding: 0.85rem 1.5rem 0.35rem;
            margin-top: 0.4rem;
        }

        .nav-section-title {
            color: rgba(255,255,255,0.38);
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.72);
            padding: 0.65rem 1.5rem;
            margin: 0.1rem 0.6rem;
            border-radius: 0.55rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.18s ease;
            border-left: none;
            font-size: 0.92rem;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.06);
            transform: translateX(2px);
        }

        .sidebar .nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.85) 0%, rgba(99, 102, 241, 0.85) 100%);
            box-shadow: 0 4px 14px -2px rgba(79, 70, 229, 0.45);
        }

        .sidebar .nav-link.active i { color: #fff; }

        .sidebar .nav-link i {
            font-size: 1.05rem;
            width: 22px;
            text-align: center;
            color: rgba(255,255,255,0.55);
            transition: color 0.18s ease;
        }

        .sidebar .nav-link:hover i { color: #c7d2fe; }

        /* ============ Main ============ */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .main-header {
            height: var(--header-height);
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: saturate(180%) blur(12px);
            -webkit-backdrop-filter: saturate(180%) blur(12px);
            border-bottom: 1px solid var(--border-soft);
            padding: 0 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-search { max-width: 420px; min-width: 260px; }

        .header-search .form-control {
            border-radius: 0.6rem;
            padding-left: 2.5rem;
            background: var(--bg-subtle);
            border: 1px solid transparent;
            font-size: 0.9rem;
            transition: all 0.18s ease;
        }

        .header-search .form-control:focus {
            background: #fff;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
        }

        .header-search .search-icon {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        /* ============ Page ============ */
        .page-content { padding: 1.75rem 1.75rem 2.5rem; }

        .page-header { margin-bottom: 1.5rem; }

        .page-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            letter-spacing: -0.02em;
        }

        .breadcrumb {
            margin: 0.35rem 0 0;
            padding: 0;
            background: none;
            font-size: 0.85rem;
        }

        .breadcrumb-item a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.18s ease;
        }

        .breadcrumb-item a:hover { color: var(--primary-color); }
        .breadcrumb-item.active { color: var(--text-primary); font-weight: 500; }
        .breadcrumb-item + .breadcrumb-item::before { color: var(--text-muted); }

        /* ============ Cards ============ */
        .card {
            border: 1px solid var(--border-soft);
            border-radius: 0.85rem;
            box-shadow: var(--shadow-sm);
            background: var(--bg-elevated);
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }

        .card:hover { box-shadow: var(--shadow-md); }

        .card-header {
            background: linear-gradient(180deg, #fbfcfe 0%, #f7f9fc 100%);
            border-bottom: 1px solid var(--border-soft);
            padding: 0.9rem 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.93rem;
            letter-spacing: -0.005em;
            border-top-left-radius: 0.85rem !important;
            border-top-right-radius: 0.85rem !important;
        }

        .card-header i {
            color: var(--primary-color);
            margin-right: 0.4rem;
        }

        .card-body { padding: 1.25rem; }

        .card-footer {
            background: var(--bg-muted);
            border-top: 1px solid var(--border-soft);
            padding: 0.85rem 1.25rem;
            border-bottom-left-radius: 0.85rem !important;
            border-bottom-right-radius: 0.85rem !important;
        }

        /* Filter card distinction */
        .card:has(form .form-label) .card-body {
            background: linear-gradient(180deg, #fafbfd 0%, #f6f8fb 100%);
            border-radius: 0.85rem;
        }

        /* ============ Stats Cards ============ */
        .stat-card {
            border-radius: 0.85rem;
            padding: 1.4rem 1.5rem;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(255,255,255,0.18), transparent 60%);
            pointer-events: none;
        }

        .stat-card.primary { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); }
        .stat-card.success { background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
        .stat-card.warning { background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); }
        .stat-card.danger  { background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); }
        .stat-card.info    { background: linear-gradient(135deg, #0284c7 0%, #06b6d4 100%); }

        .stat-card .stat-icon {
            position: absolute;
            right: 1.1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 3.2rem;
            opacity: 0.18;
        }

        .stat-card .stat-value {
            font-size: 2.1rem;
            font-weight: 700;
            line-height: 1;
            letter-spacing: -0.02em;
        }

        .stat-card .stat-label {
            font-size: 0.85rem;
            opacity: 0.92;
            margin-top: 0.35rem;
            font-weight: 500;
        }

        /* ============ Tables ============ */
        .table { margin-bottom: 0; }

        .table thead th {
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.74rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border-soft);
            background: linear-gradient(180deg, #fafbfd 0%, #f4f6fa 100%);
            padding: 0.85rem 1rem;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 0.85rem 1rem;
            vertical-align: middle;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-soft);
            font-size: 0.9rem;
        }

        .table tbody tr {
            transition: background-color 0.15s ease;
        }

        .table tbody tr:nth-child(even) { background-color: #fafbfd; }

        .table tbody tr:hover {
            background-color: var(--primary-soft) !important;
        }

        .table-hover tbody tr:last-child td { border-bottom: none; }

        /* Code chips inside tables */
        .table code {
            background: var(--bg-subtle);
            color: var(--primary-color);
            padding: 0.15rem 0.45rem;
            border-radius: 0.35rem;
            font-size: 0.82rem;
            border: 1px solid var(--border-soft);
        }

        /* ============ Badges ============ */
        .badge {
            font-weight: 600;
            padding: 0.4em 0.7em;
            border-radius: 0.4rem;
            font-size: 0.72rem;
            letter-spacing: 0.02em;
        }

        .badge.bg-primary   { background: linear-gradient(135deg, #4f46e5, #6366f1) !important; }
        .badge.bg-success   { background: linear-gradient(135deg, #059669, #10b981) !important; }
        .badge.bg-warning   { background: linear-gradient(135deg, #d97706, #f59e0b) !important; color: #fff !important; }
        .badge.bg-danger    { background: linear-gradient(135deg, #dc2626, #ef4444) !important; }
        .badge.bg-info      { background: linear-gradient(135deg, #0284c7, #06b6d4) !important; }
        .badge.bg-secondary { background: linear-gradient(135deg, #475569, #64748b) !important; }

        /* ============ Buttons ============ */
        .btn {
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.55rem;
            transition: all 0.18s ease;
            font-size: 0.9rem;
            letter-spacing: -0.005em;
        }

        .btn:active { transform: translateY(1px); }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, #6366f1 100%);
            border: none;
            box-shadow: 0 2px 6px -1px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%);
            box-shadow: 0 4px 12px -1px rgba(79, 70, 229, 0.4);
            transform: translateY(-1px);
        }

        .btn-success {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            border: none;
            box-shadow: 0 2px 6px -1px rgba(16, 185, 129, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            border: none;
            box-shadow: 0 2px 6px -1px rgba(239, 68, 68, 0.3);
        }

        .btn-warning {
            background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
            border: none;
            color: #fff;
            box-shadow: 0 2px 6px -1px rgba(245, 158, 11, 0.3);
        }

        .btn-warning:hover { color: #fff; }

        .btn-outline-primary {
            border-color: var(--border-color);
            color: var(--text-secondary);
            background: #fff;
        }

        .btn-outline-primary:hover {
            background: var(--primary-soft);
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline-secondary {
            border-color: var(--border-color);
            color: var(--text-secondary);
            background: #fff;
        }

        .btn-outline-secondary:hover {
            background: var(--bg-subtle);
            border-color: var(--text-muted);
            color: var(--text-primary);
        }

        .btn-outline-danger:hover {
            background: #fef2f2;
            border-color: #fca5a5;
            color: #dc2626;
        }

        .btn-group .btn { box-shadow: none; }

        /* ============ Forms ============ */
        .form-control, .form-select {
            border-radius: 0.55rem;
            border: 1px solid var(--border-color);
            padding: 0.6rem 0.85rem;
            font-size: 0.9rem;
            background: #fff;
            transition: all 0.15s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
            background: #fff;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 0.4rem;
            font-size: 0.85rem;
            letter-spacing: -0.005em;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-switch .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* ============ Alerts ============ */
        .alert {
            border: 1px solid transparent;
            border-radius: 0.6rem;
            padding: 0.85rem 1.1rem;
            font-size: 0.9rem;
            box-shadow: var(--shadow-xs);
        }

        .alert-success { background: #ecfdf5; border-color: #a7f3d0; color: #047857; }
        .alert-danger  { background: #fef2f2; border-color: #fecaca; color: #b91c1c; }
        .alert-warning { background: #fffbeb; border-color: #fde68a; color: #92400e; }
        .alert-info    { background: #eff6ff; border-color: #bfdbfe; color: #1d4ed8; }

        /* ============ User dropdown ============ */
        .user-dropdown .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.35rem 0.75rem 0.35rem 0.4rem;
            border-radius: 0.6rem;
            background: var(--bg-subtle);
            border: 1px solid transparent;
            color: var(--text-primary);
            font-weight: 500;
            font-size: 0.88rem;
            transition: all 0.18s ease;
        }

        .user-dropdown .dropdown-toggle:hover {
            background: #fff;
            border-color: var(--border-color);
            box-shadow: var(--shadow-sm);
        }

        .user-dropdown .dropdown-toggle::after { display: none; }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .dropdown-menu {
            border: 1px solid var(--border-soft);
            border-radius: 0.7rem;
            box-shadow: var(--shadow-lg);
            padding: 0.4rem;
        }

        .dropdown-item {
            border-radius: 0.4rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.88rem;
            transition: all 0.15s ease;
        }

        .dropdown-item:hover {
            background: var(--primary-soft);
            color: var(--primary-color);
        }

        .dropdown-divider { margin: 0.35rem 0; border-color: var(--border-soft); }
        .dropdown-header { font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; }

        /* ============ Pagination ============ */
        .pagination { gap: 0.25rem; }
        .page-link {
            border-radius: 0.45rem !important;
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-size: 0.85rem;
            padding: 0.4rem 0.7rem;
        }
        .page-link:hover { background: var(--primary-soft); color: var(--primary-color); border-color: var(--primary-color); }
        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-color), #6366f1);
            border-color: var(--primary-color);
            box-shadow: 0 2px 6px -1px rgba(79, 70, 229, 0.4);
        }

        /* ============ Mobile ============ */
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .sidebar-overlay {
                position: fixed;
                top: 0; left: 0;
                width: 100%; height: 100%;
                background: rgba(15, 23, 42, 0.5);
                backdrop-filter: blur(2px);
                z-index: 999;
                display: none;
            }
            .sidebar-overlay.show { display: block; }
            .page-content { padding: 1.25rem; }
        }

        /* ============ Scrollbar ============ */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; border: 2px solid var(--bg-base); }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border: none; }
        .sidebar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

        /* ============ Utility / polish ============ */
        a { transition: color 0.15s ease; }

        .text-decoration-none:hover { text-decoration: none; }

        hr { border-color: var(--border-soft); opacity: 1; }

        pre {
            background: #0f172a;
            color: #e2e8f0;
            padding: 1rem;
            border-radius: 0.55rem;
            font-size: 0.82rem;
        }

        /* Animated entry for cards */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .page-content > * {
            animation: fadeInUp 0.35s ease both;
        }

        /* Notifications bell */
        .main-header .btn-link {
            color: var(--text-secondary) !important;
            transition: color 0.15s ease;
        }

        .main-header .btn-link:hover { color: var(--primary-color) !important; }

        /* ============ Section Themes ============ */
        /* Each section overrides --section-color and --section-gradient */
        body[data-section="dashboard"]   { --section-color: #4f46e5; --section-color-2: #7c3aed; --section-soft: #eef2ff; --section-name: 'Dashboard'; }
        body[data-section="equipment"]   { --section-color: #2563eb; --section-color-2: #06b6d4; --section-soft: #eff6ff; --section-name: 'Equipos'; }
        body[data-section="employees"]   { --section-color: #059669; --section-color-2: #10b981; --section-soft: #ecfdf5; --section-name: 'Empleados'; }
        body[data-section="assignments"] { --section-color: #0891b2; --section-color-2: #06b6d4; --section-soft: #ecfeff; --section-name: 'Asignaciones'; }
        body[data-section="history"]     { --section-color: #7c3aed; --section-color-2: #a855f7; --section-soft: #f5f3ff; --section-name: 'Historial'; }
        body[data-section="maintenance"] { --section-color: #ea580c; --section-color-2: #f97316; --section-soft: #fff7ed; --section-name: 'Mantenimiento'; }
        body[data-section="reports"]     { --section-color: #9333ea; --section-color-2: #c026d3; --section-soft: #faf5ff; --section-name: 'Reportes'; }
        body[data-section="catalogs"]    { --section-color: #db2777; --section-color-2: #ec4899; --section-soft: #fdf2f8; --section-name: 'Catálogos'; }
        body[data-section="users"]       { --section-color: #dc2626; --section-color-2: #f43f5e; --section-soft: #fef2f2; --section-name: 'Usuarios'; }
        body[data-section="audit"]       { --section-color: #475569; --section-color-2: #64748b; --section-soft: #f1f5f9; --section-name: 'Auditoría'; }
        body[data-section="settings"]    { --section-color: #0f766e; --section-color-2: #14b8a6; --section-soft: #f0fdfa; --section-name: 'Configuración'; }
        body[data-section="default"]     { --section-color: #4f46e5; --section-color-2: #7c3aed; --section-soft: #eef2ff; }

        /* Section accent on top of content */
        .page-content::before {
            content: '';
            position: fixed;
            top: var(--header-height);
            left: var(--sidebar-width);
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--section-color), var(--section-color-2));
            z-index: 50;
            opacity: 0.85;
        }

        @media (max-width: 991.98px) {
            .page-content::before { left: 0; }
        }

        /* Page title gets accent underline */
        .page-title {
            position: relative;
            display: inline-block;
            padding-bottom: 0.4rem;
        }

        .page-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 40px;
            height: 3px;
            border-radius: 3px;
            background: linear-gradient(90deg, var(--section-color), var(--section-color-2));
        }

        /* Card headers tinted by section */
        .card-header {
            background: linear-gradient(180deg, var(--section-soft) 0%, #fbfcfe 100%) !important;
            border-bottom: 1px solid var(--border-soft);
        }

        /* Card headers con color explícito (style inline) conservan su color */
        .card-header[style*="background"] {
            background: revert !important;
        }

        .card-header i { color: var(--section-color); }

        /* Card headers coloreados: icono en blanco */
        .card-header[style*="background"] i { color: #fff !important; }

        /* Primary buttons inherit section accent */
        .btn-primary,
        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--section-color), var(--section-color-2)) !important;
            border-color: var(--section-color) !important;
            box-shadow: 0 2px 6px -1px color-mix(in srgb, var(--section-color) 35%, transparent);
        }

        .btn-primary:hover {
            filter: brightness(0.95);
            box-shadow: 0 4px 12px -1px color-mix(in srgb, var(--section-color) 45%, transparent);
        }

        /* Outline + active focus inherit accent */
        .btn-outline-primary:hover {
            background: var(--section-soft);
            border-color: var(--section-color);
            color: var(--section-color);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--section-color);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--section-color) 15%, transparent);
        }

        /* Table hover row uses section soft */
        .table tbody tr:hover { background-color: var(--section-soft) !important; }

        /* Sidebar active link uses section gradient */
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--section-color) 0%, var(--section-color-2) 100%) !important;
            box-shadow: 0 4px 14px -2px color-mix(in srgb, var(--section-color) 45%, transparent) !important;
        }

        /* Breadcrumb hover and active link tone */
        .breadcrumb-item a:hover { color: var(--section-color); }

        /* Section pill near page title */
        .page-header > div:first-child::before {
            content: var(--section-name);
            display: inline-block;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--section-color);
            background: var(--section-soft);
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            margin-bottom: 0.5rem;
            border: 1px solid color-mix(in srgb, var(--section-color) 20%, transparent);
        }

        /* Stats cards adopt section color when no specific class */
        .stat-card.section { background: linear-gradient(135deg, var(--section-color) 0%, var(--section-color-2) 100%); }

        /* Code chips use section color */
        .table code {
            color: var(--section-color);
            background: var(--section-soft);
            border-color: color-mix(in srgb, var(--section-color) 18%, transparent);
        }

        /* Notifications bell active hover */
        .main-header .btn-link:hover { color: var(--section-color) !important; }

        /* ============ Back Button ============ */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 1rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-secondary);
            background: #fff;
            border: 1px solid var(--border-soft);
            border-radius: 0.6rem;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        }
        .btn-back:hover {
            color: var(--section-color);
            border-color: var(--section-color);
            background: var(--section-soft);
            box-shadow: 0 2px 8px -2px color-mix(in srgb, var(--section-color) 20%, transparent);
            transform: translateX(-2px);
        }
        .btn-back i { font-size: 0.9rem; transition: transform 0.2s ease; }
        .btn-back:hover i { transform: translateX(-2px); }
    </style>

    @stack('styles')
</head>
<body data-section="@php
    $section = 'default';
    if (request()->routeIs('equipment.*')) $section = 'equipment';
    elseif (request()->routeIs('employees.*')) $section = 'employees';
    elseif (request()->routeIs('assignments.*')) $section = 'assignments';
    elseif (request()->routeIs('history.*')) $section = 'history';
    elseif (request()->routeIs('maintenance.*')) $section = 'maintenance';
    elseif (request()->routeIs('reports.*')) $section = 'reports';
    elseif (request()->routeIs('departments.*', 'positions.*', 'locations.*', 'brands.*', 'suppliers.*', 'categories.*', 'equipment-models.*')) $section = 'catalogs';
    elseif (request()->routeIs('bulk-purchases.*')) $section = 'equipment';
    elseif (request()->routeIs('users.*')) $section = 'users';
    elseif (request()->routeIs('audit.*')) $section = 'audit';
    elseif (request()->routeIs('settings.*')) $section = 'settings';
    elseif (request()->routeIs('dashboard')) $section = 'dashboard';
    echo $section;
@endphp">
    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h4><i class="bi bi-pc-display-horizontal me-2"></i>Inventario TI</h4>
            <small>Sistema de Control de Equipos</small>
        </div>

        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <div class="nav-section">
                    <span class="nav-section-title">Gestión Principal</span>
                </div>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('equipment.*') ? 'active' : '' }}" href="{{ route('equipment.index') }}">
                        <i class="bi bi-laptop"></i>
                        <span>Equipos</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('scan') ? 'active' : '' }}" href="{{ route('scan') }}">
                        <i class="bi bi-qr-code-scan"></i>
                        <span>Escanear QR</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('employees.index') || request()->routeIs('employees.create') || request()->routeIs('employees.show') || request()->routeIs('employees.edit') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                        <i class="bi bi-people"></i>
                        <span>Empleados</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('employees.directory') ? 'active' : '' }}" href="{{ route('employees.directory') }}">
                        <i class="bi bi-journal-bookmark"></i>
                        <span>Directorio</span>
                    </a>
                </li>

                @php
                    $sidebarActiveAssignments = \App\Models\Assignment::where('status', 'active')->count();
                @endphp
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('assignments.*') ? 'active' : '' }}" href="{{ route('assignments.index') }}">
                        <i class="bi bi-arrow-left-right"></i>
                        <span>Asignaciones</span>
                        @if($sidebarActiveAssignments > 0)
                            <span class="badge-dot" style="background:#0891b2;">{{ $sidebarActiveAssignments > 9 ? '9+' : $sidebarActiveAssignments }}</span>
                        @endif
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('history.*') ? 'active' : '' }}" href="{{ route('history.index') }}">
                        <i class="bi bi-clock-history"></i>
                        <span>Historial</span>
                    </a>
                </li>

                @php
                    $sidebarPendingMaintenance = \App\Models\MaintenanceRecord::whereIn('status', ['pending', 'in_progress'])->count();
                    $sidebarWarrantyExpiring = \App\Models\Equipment::whereNotNull('warranty_end_date')->where('warranty_end_date', '<=', now()->addDays(30))->where('warranty_end_date', '>=', now())->count();
                @endphp
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('maintenance.*') ? 'active' : '' }}" href="{{ route('maintenance.index') }}">
                        <i class="bi bi-tools"></i>
                        <span>Mantenimiento</span>
                        @if($sidebarPendingMaintenance > 0)
                            <span class="badge-dot">{{ $sidebarPendingMaintenance > 9 ? '9+' : $sidebarPendingMaintenance }}</span>
                        @endif
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('bulk-purchases.*') ? 'active' : '' }}" href="{{ route('bulk-purchases.index') }}">
                        <i class="bi bi-cart-check"></i>
                        <span>Compras Masivas</span>
                    </a>
                </li>

                <div class="nav-section">
                    <span class="nav-section-title">Reportes</span>
                </div>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        <span>Reportes</span>
                        @if(isset($sidebarWarrantyExpiring) && $sidebarWarrantyExpiring > 0)
                            <span class="badge-dot" style="background:#f59e0b;">{{ $sidebarWarrantyExpiring > 9 ? '9+' : $sidebarWarrantyExpiring }}</span>
                        @endif
                    </a>
                </li>

                <div class="nav-section">
                    <span class="nav-section-title">Catálogos</span>
                </div>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}" href="{{ route('departments.index') }}">
                        <i class="bi bi-building"></i>
                        <span>Departamentos</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('positions.*') ? 'active' : '' }}" href="{{ route('positions.index') }}">
                        <i class="bi bi-person-badge"></i>
                        <span>Puestos</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}" href="{{ route('locations.index') }}">
                        <i class="bi bi-geo-alt"></i>
                        <span>Ubicaciones</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('brands.*') ? 'active' : '' }}" href="{{ route('brands.index') }}">
                        <i class="bi bi-tag"></i>
                        <span>Marcas</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                        <i class="bi bi-collection"></i>
                        <span>Categorías</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                        <i class="bi bi-truck"></i>
                        <span>Proveedores</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('equipment-models.*') ? 'active' : '' }}" href="{{ route('equipment-models.index') }}">
                        <i class="bi bi-box"></i>
                        <span>Modelos de Equipo</span>
                    </a>
                </li>

                <div class="nav-section">
                    <span class="nav-section-title">Administración</span>
                </div>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="bi bi-person-gear"></i>
                        <span>Usuarios</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('audit.*') ? 'active' : '' }}" href="{{ route('audit.index') }}">
                        <i class="bi bi-shield-check"></i>
                        <span>Auditoría</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                        <i class="bi bi-gear"></i>
                        <span>Configuración</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="main-header">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-link text-dark d-lg-none p-0" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>

                <div class="header-search position-relative d-none d-md-block">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" class="form-control" placeholder="Buscar equipos, empleados...">
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- Notifications -->
                @php
                    $notifPending = \App\Models\MaintenanceRecord::whereIn('status', ['pending','in_progress'])->count();
                    $notifWarranty = \App\Models\Equipment::whereNotNull('warranty_end_date')->where('warranty_end_date', '<=', now()->addDays(30))->where('warranty_end_date', '>=', now())->count();
                    $totalNotifs = $notifPending + $notifWarranty;
                @endphp
                <div class="dropdown">
                    <button class="btn btn-link text-dark position-relative" data-bs-toggle="dropdown" style="color: var(--text-secondary) !important;">
                        <i class="bi bi-bell fs-5"></i>
                        @if($totalNotifs > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                            {{ $totalNotifs > 9 ? '9+' : $totalNotifs }}
                        </span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" style="width: 320px;">
                        <h6 class="dropdown-header">Notificaciones</h6>
                        @if($notifWarranty > 0)
                        <a class="dropdown-item py-2" href="{{ route('reports.warranty-expiring') }}">
                            <div class="d-flex align-items-start gap-2">
                                <div class="rounded-circle bg-warning bg-opacity-15 p-2"><i class="bi bi-shield-exclamation text-warning"></i></div>
                                <div>
                                    <div class="small fw-medium">Garantías por vencer</div>
                                    <div class="small text-muted">{{ $notifWarranty }} equipo(s) vencen en 30 días</div>
                                </div>
                            </div>
                        </a>
                        @endif
                        @if($notifPending > 0)
                        <a class="dropdown-item py-2" href="{{ route('maintenance.index') }}">
                            <div class="d-flex align-items-start gap-2">
                                <div class="rounded-circle bg-info bg-opacity-15 p-2"><i class="bi bi-tools text-info"></i></div>
                                <div>
                                    <div class="small fw-medium">Mantenimientos pendientes</div>
                                    <div class="small text-muted">{{ $notifPending }} ticket(s) abiertos</div>
                                </div>
                            </div>
                        </a>
                        @endif
                        @if($totalNotifs == 0)
                        <div class="dropdown-item py-3 text-center text-muted small">
                            <i class="bi bi-check-circle text-success me-1"></i>Sin notificaciones pendientes
                        </div>
                        @endif
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-center small" href="{{ route('reports.index') }}">Ver reportes</a>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="dropdown user-dropdown">
                    <button class="dropdown-toggle" data-bs-toggle="dropdown">
                        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="user-avatar">
                        <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                        <i class="bi bi-chevron-down small"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="dropdown-header">
                            <div class="fw-medium">{{ auth()->user()->name }}</div>
                            <div class="small text-muted">{{ auth()->user()->email }}</div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person me-2"></i>Mi Perfil
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="page-content">
            <!-- Alerts -->
            @if(session('success'))
                <div id="flash-success" data-message="{{ session('success') }}"></div>
            @endif
            @if(session('error'))
                <div id="flash-error" data-message="{{ session('error') }}"></div>
            @endif
            @if(session('warning'))
                <div id="flash-warning" data-message="{{ session('warning') }}"></div>
            @endif
            @if(session('info'))
                <div id="flash-info" data-message="{{ session('info') }}"></div>
            @endif
            @if($errors->any())
                <div id="flash-errors" data-message="{{ implode('<br>', $errors->all()) }}"></div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Sidebar Toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        });

        document.getElementById('sidebarOverlay')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('show');
            this.classList.remove('show');
        });

        // ===== Skeleton Loader Helper (global) =====
        window.buildSkeletonRows = function(columns, rows) {
            rows = rows || 6;
            let html = '';
            for (let r = 0; r < rows; r++) {
                html += '<tr class="skeleton-row">';
                for (let c = 0; c < columns; c++) {
                    html += '<td><span class="skeleton skeleton-cell"></span></td>';
                }
                html += '</tr>';
            }
            return html;
        };

        // ===== SweetAlert2 Flash Messages =====
        const Toastify = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        const flashSuccess = document.getElementById('flash-success');
        if (flashSuccess) {
            Toastify.fire({ icon: 'success', title: flashSuccess.dataset.message });
        }
        const flashError = document.getElementById('flash-error');
        if (flashError) {
            Toastify.fire({ icon: 'error', title: flashError.dataset.message, timer: 6000 });
        }
        const flashWarning = document.getElementById('flash-warning');
        if (flashWarning) {
            Toastify.fire({ icon: 'warning', title: flashWarning.dataset.message, timer: 5000 });
        }
        const flashInfo = document.getElementById('flash-info');
        if (flashInfo) {
            Toastify.fire({ icon: 'info', title: flashInfo.dataset.message, timer: 5000 });
        }
        const flashErrors = document.getElementById('flash-errors');
        if (flashErrors) {
            Swal.fire({
                icon: 'error',
                title: 'Corrige los errores',
                html: flashErrors.dataset.message,
                confirmButtonText: 'Entendido',
                customClass: { confirmButton: 'btn btn-primary' },
                buttonsStyling: false
            });
        }

        // Forzar tema claro
        document.documentElement.setAttribute('data-theme', 'light');
        localStorage.setItem('theme', 'light');
    </script>

    @stack('scripts')
</body>
</html>
