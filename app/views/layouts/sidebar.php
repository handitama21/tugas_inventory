<aside class="sidebar">

    <div class="sidebar-logo">
        <div class="logo-icon">
            📦
        </div>

        <div>
            <h2>Inventory</h2>
            <span>Management System</span>
        </div>
    </div>

    <nav class="sidebar-menu">

        <p class="menu-title">MENU UTAMA</p>

        <a href="index.php?page=dashboard" class="menu-item">
            <span class="menu-icon">🏠</span>
            <span>Dashboard</span>
        </a>

        <a href="index.php?page=inventory" class="menu-item">
            <span class="menu-icon">📦</span>
            <span>Inventory</span>
        </a>

        <a href="index.php?page=supplier" class="menu-item">
            <span class="menu-icon">🚚</span>
            <span>Supplier</span>
        </a>

        <a href="index.php?page=storage" class="menu-item">
            <span class="menu-icon">🏢</span>
            <span>Gudang</span>
        </a>

        <a href="index.php?page=transaksi" class="menu-item">
            <span class="menu-icon">🛒</span>
            <span>Transaksi</span>
        </a>

        <a href="index.php?page=history" class="menu-item">
            <span class="menu-icon">📜</span>
            <span>History Transaksi</span>
        </a>

        <p class="menu-title">LAINNYA</p>

        <a href="index.php?page=settings" class="menu-item">
            <span class="menu-icon">⚙️</span>
            <span>Pengaturan</span>
        </a>

    </nav>

    <div class="sidebar-bottom">

        <div class="admin-info">

            <div class="admin-avatar">
                D
            </div>

            <div class="admin-detail">
                <strong>
                    <?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?>
                </strong>

                <span>
                    <?= htmlspecialchars(ucfirst($_SESSION['role'] ?? 'user')) ?>
                </span>
            </div>

        </div>

    </div>

</aside>