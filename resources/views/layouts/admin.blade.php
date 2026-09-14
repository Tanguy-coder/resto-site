<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - NIWA FOOD</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #1a1a2e; color: #e0e0e0; display: flex; min-height: 100vh; }
        a { color: #e0e0e0; text-decoration: none; }

        .sidebar { width: 250px; background: #16213e; min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 100; display: flex; flex-direction: column; }
        .sidebar-brand { padding: 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-brand h2 { color: #e94560; font-size: 1.3rem; letter-spacing: 2px; }
        .sidebar-nav { flex: 1; padding: 15px 0; }
        .sidebar-nav a { display: flex; align-items: center; padding: 12px 20px; transition: all 0.3s; border-left: 3px solid transparent; font-size: 0.95rem; }
        .sidebar-nav a:hover { background: rgba(15,52,96,0.5); }
        .sidebar-nav a.active { background: #0f3460; border-left-color: #e94560; color: #fff; }
        .sidebar-nav a span.icon { margin-right: 12px; font-size: 1.1rem; width: 24px; text-align: center; }

        .main { margin-left: 250px; flex: 1; display: flex; flex-direction: column; }
        .topbar { background: #16213e; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .topbar h1 { font-size: 1.1rem; font-weight: 600; }
        .topbar .logout-btn { background: #e94560; color: #fff; border: none; padding: 8px 18px; border-radius: 6px; cursor: pointer; font-size: 0.85rem; transition: background 0.3s; }
        .topbar .logout-btn:hover { background: #c73651; }

        .content { padding: 30px; flex: 1; }

        .flash { padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; }
        .flash-success { background: rgba(16,185,129,0.2); border: 1px solid rgba(16,185,129,0.4); color: #6ee7b7; }
        .flash-error { background: rgba(239,68,68,0.2); border: 1px solid rgba(239,68,68,0.4); color: #fca5a5; }

        .admin-table { width: 100%; border-collapse: collapse; background: #16213e; border-radius: 10px; overflow: hidden; }
        .admin-table th { background: #0f3460; padding: 14px 16px; text-align: left; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; }
        .admin-table td { padding: 12px 16px; border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 0.9rem; }
        .admin-table tr:hover td { background: rgba(15,52,96,0.3); }

        .btn { display: inline-block; padding: 8px 18px; border-radius: 6px; font-size: 0.85rem; cursor: pointer; border: none; transition: all 0.3s; text-align: center; }
        .btn-primary { background: #e94560; color: #fff; }
        .btn-primary:hover { background: #c73651; }
        .btn-secondary { background: #0f3460; color: #fff; }
        .btn-secondary:hover { background: #1a4a8a; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-sm { padding: 5px 12px; font-size: 0.8rem; }

        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-size: 0.9rem; color: #94a3b8; }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group input[type="number"],
        .form-group input[type="url"],
        .form-group textarea,
        .form-group select { width: 100%; padding: 10px 14px; background: #1a1a2e; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; color: #e0e0e0; font-size: 0.9rem; transition: border 0.3s; }
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus { outline: none; border-color: #e94560; }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .form-group .checkbox-wrap { display: flex; align-items: center; gap: 8px; }
        .form-group .checkbox-wrap input[type="checkbox"] { width: 18px; height: 18px; accent-color: #e94560; }
        .form-group .error { color: #fca5a5; font-size: 0.8rem; margin-top: 4px; }

        .card { background: #16213e; border-radius: 10px; padding: 24px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: #16213e; border-radius: 10px; padding: 24px; text-align: center; }
        .stat-card .stat-value { font-size: 2.5rem; font-weight: 700; color: #e94560; }
        .stat-card .stat-label { font-size: 0.9rem; color: #94a3b8; margin-top: 5px; }

        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .page-header h2 { font-size: 1.4rem; font-weight: 600; }

        .pagination { display: flex; gap: 6px; margin-top: 20px; justify-content: center; }
        .pagination a, .pagination span { padding: 6px 12px; border-radius: 4px; font-size: 0.85rem; }
        .pagination a { background: #0f3460; color: #e0e0e0; }
        .pagination a:hover { background: #1a4a8a; }
        .pagination span { background: #e94560; color: #fff; }

        .img-preview { max-width: 120px; max-height: 80px; border-radius: 6px; margin-top: 8px; }
        .img-thumb { width: 60px; height: 40px; object-fit: cover; border-radius: 4px; }

        .section-header { font-size: 1.1rem; font-weight: 600; color: #e94560; margin: 25px 0 15px; padding-bottom: 8px; border-bottom: 1px solid rgba(233,69,96,0.3); text-transform: capitalize; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>NIWA FOOD</h2>
            <small style="color:#94a3b8;">Administration</small>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="icon">📊</span> Dashboard
            </a>
            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <span class="icon">📋</span> Commandes
            </a>
            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <span class="icon">🍔</span> Produits
            </a>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <span class="icon">📁</span> Catégories
            </a>
            <a href="{{ route('admin.testimonials.index') }}" class="{{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                <span class="icon">💬</span> Témoignages
            </a>
            <a href="{{ route('admin.locations.index') }}" class="{{ request()->routeIs('admin.locations.*') ? 'active' : '' }}">
                <span class="icon">📍</span> Adresses
            </a>
            <a href="{{ route('admin.hero-slides.index') }}" class="{{ request()->routeIs('admin.hero-slides.*') ? 'active' : '' }}">
                <span class="icon">🖼️</span> Slides Hero
            </a>
            <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <span class="icon">⚙️</span> Paramètres
            </a>
        </nav>
    </aside>

    <div class="main">
        <header class="topbar">
            <h1>NIWA FOOD - Admin</h1>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">Déconnexion</button>
            </form>
        </header>

        <div class="content">
            @if(session('success'))
                <div class="flash flash-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="flash flash-error">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </div>
</body>
</html>
