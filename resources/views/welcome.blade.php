<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Sistem Informasi Pertanahan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-green: #067002ff;
            --secondary-green: #0a5a08;
            --light-green: #e8f5e9;
            --accent-gold: #ffd700;
        }
        
        body { 
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', sans-serif; 
            min-height: 100vh;
        }
        
        .sidebar { 
            height: 100vh; 
            background: linear-gradient(180deg, var(--primary-green) 0%, var(--secondary-green) 100%);
            color: #fff; 
            position: fixed; 
            width: 260px; 
            padding: 20px;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            overflow-y: auto;
        }
        
        .sidebar h2 { 
            font-size: 18px; 
            margin-bottom: 25px;
            border-bottom: 2px solid var(--accent-gold);
            padding-bottom: 10px;
            font-weight: 700;
        }
        
        .sidebar a { 
            display: flex;
            align-items: center;
            color: #fff; 
            padding: 12px 15px; 
            margin: 5px 0; 
            text-decoration: none; 
            border-radius: 8px; 
            transition: all 0.3s;
            font-size: 14px;
        }
        
        .sidebar a:hover, .sidebar a.active { 
            background: rgba(255,255,255,0.2);
            transform: translateX(5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .sidebar a i {
            margin-right: 10px;
            font-size: 18px;
        }
        
        .content { 
            margin-left: 280px; 
            padding: 30px; 
        }
        
        .card-custom { 
            border-radius: 15px; 
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            border: none;
            transition: transform 0.3s;
        }
        
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .header-title { 
            font-weight: 700; 
            font-size: 32px;
            color: var(--primary-green);
            margin-bottom: 10px;
        }
        
        .breadcrumb-custom {
            background: transparent;
            font-size: 14px;
            padding: 0;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
        }
        
        .stat-card h5 {
            font-size: 14px;
            margin-bottom: 10px;
            opacity: 0.9;
            position: relative;
        }
        
        .stat-card h2 {
            font-size: 36px;
            font-weight: 700;
            margin: 0;
            position: relative;
        }
        
        .stat-card .icon {
            position: absolute;
            right: 20px;
            bottom: 20px;
            font-size: 48px;
            opacity: 0.3;
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }
        
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .table-header h5 {
            color: var(--primary-green);
            font-weight: 700;
            font-size: 20px;
            margin: 0;
        }
        
        .entries-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .entries-control label {
            font-size: 14px;
            color: #666;
            margin: 0;
        }
        
        .entries-control select {
            padding: 6px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            transition: border 0.3s;
            cursor: pointer;
        }
        
        .entries-control select:focus {
            outline: none;
            border-color: var(--primary-green);
        }
        
        .search-box {
            position: relative;
        }
        
        .search-box input {
            padding: 10px 40px 10px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            width: 300px;
            transition: all 0.3s;
        }
        
        .search-box input:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(6, 112, 2, 0.1);
        }
        
        .search-box::after {
            content: '🔍';
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .table-custom {
            width: 100%;
            margin-top: 20px;
        }
        
        .table-custom thead {
            background: var(--primary-green);
            color: white;
        }
        
        .table-custom thead th {
            padding: 15px;
            font-weight: 600;
            font-size: 14px;
            border: none;
        }
        
        .table-custom tbody td {
            padding: 12px 15px;
            font-size: 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .table-custom tbody tr:hover {
            background: var(--light-green);
            transition: background 0.3s;
        }
        
        .pagination-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        
        .pagination-custom {
            display: flex;
            gap: 5px;
        }
        
        .btn-pagination {
            padding: 8px 16px;
            border: 2px solid var(--primary-green);
            background: white;
            color: var(--primary-green);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            font-size: 14px;
            min-width: 45px;
        }
        
        .btn-pagination:hover:not(:disabled) {
            background: var(--primary-green);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(6, 112, 2, 0.3);
        }
        
        .btn-pagination:disabled {
            opacity: 0.3;
            cursor: not-allowed;
            border-color: #ccc;
            color: #ccc;
        }
        
        .btn-pagination.active {
            background: var(--primary-green);
            color: white;
            box-shadow: 0 4px 8px rgba(6, 112, 2, 0.3);
        }
        
        .pagination-dots {
            padding: 8px;
            color: #666;
            display: inline-flex;
            align-items: center;
        }
        
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            height: 400px;
        }
        
        .chart-title {
            color: var(--primary-green);
            font-weight: 700;
            font-size: 18px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 14px;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        
        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>🗺 SISTEM INFORMASI PERTANAHAN</h2>
    <a href="#" class="active">🏠 Dashboard</a>
    <a href="#">🌍 Data Wilayah</a>
    <a href="#">👨‍👩‍👧‍👦 Data Penduduk</a>
    <a href="{{ route('peta.lahan') }}">🗺 Peta Lahan</a>
    <a href="#">📊 Statistik Penduduk</a>
    <a href="#">📈 Statistik Lahan</a>
    <a href="#">⚙ Pengaturan</a>
    <a href="#">👤 Profil</a>
    <a href="#">🚪 Logout</a>
</div>

<div class="content">
    <nav class="breadcrumb-custom">
        <span>🏠 Home / Dashboard</span>
    </nav>
    
    <h1 class="header-title">Dashboard Utama</h1>
    <p style="color: #666; margin-bottom: 30px;">Selamat datang di Sistem Informasi Pertanahan</p>

    <!-- Statistik Kartu -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <h5>Total Penduduk</h5>
                <h2 id="totalPenduduk">0</h2>
                <span class="icon">👥</span>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%);">
                <h5>Total Lahan</h5>
                <h2>0</h2>
                <span class="icon">🗺</span>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #f57c00 0%, #e65100 100%);">
                <h5>Total Wilayah</h5>
                <h2>4</h2>
                <span class="icon">🌍</span>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%);">
                <h5>Total Marker</h5>
                <h2>0</h2>
                <span class="icon">📍</span>
            </div>
        </div>
    </div>

    <!-- Tabel Data Penduduk -->
    <div class="table-container mb-4">
        <div class="table-header">
            <h5>📋 Data Penduduk Terdaftar</h5>
            <div class="d-flex gap-3 align-items-center">
                <div class="entries-control">
                    <label>Tampilkan:</label>
                    <select id="entriesSelect" onchange="changeEntries()">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span>entries</span>
                </div>
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Cari nama atau NIK..." onkeyup="searchTable()">
                </div>
            </div>
        </div>
        
        <div id="tableContent">
            <div class="loading">Memuat data...</div>
        </div>
        
        <div class="pagination-info" id="paginationInfo" style="display: none;">
            <div>
                Menampilkan <strong id="showingStart">0</strong> sampai <strong id="showingEnd">0</strong> dari <strong id="totalEntries">0</strong> data
            </div>
            <div class="pagination-custom" id="paginationButtons"></div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="chart-container">
                <h5 class="chart-title">📊 Statistik Penduduk per Wilayah</h5>
                <canvas id="chartPenduduk"></canvas>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="chart-container">
                <h5 class="chart-title">📈 Luas Lahan per Penggunaan</h5>
                <canvas id="chartLahan"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let allData = [];
    let filteredData = [];
    let currentPage = 1;
    let entriesPerPage = 10;

    // Data dummy untuk demo (ganti dengan fetch API sebenarnya)
    function generateDummyData() {
        const dummyData = [
            { NIK: '3201234567890001', NAMA: 'Ahmad Hidayat', ALAMAT: 'Jl. Merdeka No. 10', NO_TELP: '081234567890', TGL_LAHIR: '1990-05-15' },
            { NIK: '3201234567890002', NAMA: 'Siti Nurhaliza', ALAMAT: 'Jl. Sudirman No. 25', NO_TELP: '081234567891', TGL_LAHIR: '1992-08-20' },
            { NIK: '3201234567890003', NAMA: 'Budi Santoso', ALAMAT: 'Jl. Ahmad Yani No. 5', NO_TELP: '081234567892', TGL_LAHIR: '1988-03-10' },
            { NIK: '3201234567890004', NAMA: 'Dewi Lestari', ALAMAT: 'Jl. Gatot Subroto No. 15', NO_TELP: '081234567893', TGL_LAHIR: '1995-11-25' },
            { NIK: '3201234567890005', NAMA: 'Agus Setiawan', ALAMAT: 'Jl. Diponegoro No. 30', NO_TELP: '081234567894', TGL_LAHIR: '1987-07-08' },
            { NIK: '3201234567890006', NAMA: 'Rina Marlina', ALAMAT: 'Jl. Pemuda No. 12', NO_TELP: '081234567895', TGL_LAHIR: '1993-02-14' },
            { NIK: '3201234567890007', NAMA: 'Dedi Kurniawan', ALAMAT: 'Jl. Veteran No. 8', NO_TELP: '081234567896', TGL_LAHIR: '1991-09-30' },
            { NIK: '3201234567890008', NAMA: 'Maya Sari', ALAMAT: 'Jl. Pahlawan No. 20', NO_TELP: '081234567897', TGL_LAHIR: '1994-06-18' },
            { NIK: '3201234567890009', NAMA: 'Eko Prasetyo', ALAMAT: 'Jl. Kartini No. 7', NO_TELP: '081234567898', TGL_LAHIR: '1989-12-05' },
            { NIK: '3201234567890010', NAMA: 'Lina Wijaya', ALAMAT: 'Jl. Cut Nyak Dien No. 18', NO_TELP: '081234567899', TGL_LAHIR: '1996-04-22' },
            { NIK: '3201234567890011', NAMA: 'Rudi Hermawan', ALAMAT: 'Jl. Imam Bonjol No. 14', NO_TELP: '081234567800', TGL_LAHIR: '1990-10-11' },
            { NIK: '3201234567890012', NAMA: 'Ani Suryani', ALAMAT: 'Jl. RA Kartini No. 22', NO_TELP: '081234567801', TGL_LAHIR: '1992-01-28' },
            { NIK: '3201234567890013', NAMA: 'Wawan Setiawan', ALAMAT: 'Jl. Juanda No. 9', NO_TELP: '081234567802', TGL_LAHIR: '1988-08-16' },
            { NIK: '3201234567890014', NAMA: 'Sri Rahayu', ALAMAT: 'Jl. Hayam Wuruk No. 11', NO_TELP: '081234567803', TGL_LAHIR: '1995-03-09' },
            { NIK: '3201234567890015', NAMA: 'Hendra Gunawan', ALAMAT: 'Jl. Gajah Mada No. 16', NO_TELP: '081234567804', TGL_LAHIR: '1991-07-24' }
        ];
        
        allData = dummyData;
        filteredData = dummyData;
        document.getElementById('totalPenduduk').textContent = dummyData.length;
        renderTable();
        updateCharts();
    }

    // Fetch data dari API (gunakan ini untuk data real)
    function fetchPenduduk() {
        fetch('/api/penduduk')
            .then(response => response.json())
            .then(data => {
                allData = data;
                filteredData = data;
                document.getElementById('totalPenduduk').textContent = data.length;
                renderTable();
                updateCharts();
            })
            .catch(error => {
                console.error('Error:', error);
                // Jika API gagal, gunakan data dummy
                console.log('Menggunakan data dummy...');
                generateDummyData();
            });
    }

    function renderTable() {
        const start = (currentPage - 1) * entriesPerPage;
        const end = start + entriesPerPage;
        const paginatedData = filteredData.slice(start, end);

        if (filteredData.length === 0) {
            document.getElementById('tableContent').innerHTML = 
                '<div class="empty-state"><i>📭</i><p>Tidak ada data yang ditemukan</p></div>';
            document.getElementById('paginationInfo').style.display = 'none';
            return;
        }

        let tableHTML = `
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th>Alamat</th>
                        <th>No. Telepon</th>
                        <th>Tanggal Lahir</th>
                    </tr>
                </thead>
                <tbody>
        `;

        paginatedData.forEach((item, index) => {
            const no = start + index + 1;
            tableHTML += `
                <tr>
                    <td>${no}</td>
                    <td>${item.NIK}</td>
                    <td><strong>${item.NAMA}</strong></td>
                    <td>${item.ALAMAT}</td>
                    <td>${item.NO_TELP}</td>
                    <td>${item.TGL_LAHIR}</td>
                </tr>
            `;
        });

        tableHTML += `
                </tbody>
            </table>
        `;

        document.getElementById('tableContent').innerHTML = tableHTML;
        document.getElementById('paginationInfo').style.display = 'flex';
        
        // Update pagination info
        document.getElementById('showingStart').textContent = filteredData.length > 0 ? start + 1 : 0;
        document.getElementById('showingEnd').textContent = Math.min(end, filteredData.length);
        document.getElementById('totalEntries').textContent = filteredData.length;
        
        renderPagination();
    }

    function renderPagination() {
        const totalPages = Math.ceil(filteredData.length / entriesPerPage);
        let paginationHTML = '';

        // Previous button
        paginationHTML += `
            <button class="btn-pagination" onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>
                ‹ Prev
            </button>
        `;

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            if (
                i === 1 || 
                i === totalPages || 
                (i >= currentPage - 1 && i <= currentPage + 1)
            ) {
                paginationHTML += `
                    <button 
                        class="btn-pagination ${i === currentPage ? 'active' : ''}"
                        onclick="changePage(${i})">
                        ${i}
                    </button>
                `;
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                paginationHTML += '<span class="pagination-dots">...</span>';
            }
        }

        // Next button
        paginationHTML += `
            <button class="btn-pagination" onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>
                Next ›
            </button>
        `;

        document.getElementById('paginationButtons').innerHTML = paginationHTML;
    }

    function changePage(page) {
        const totalPages = Math.ceil(filteredData.length / entriesPerPage);
        if (page < 1 || page > totalPages) return;
        currentPage = page;
        renderTable();
    }

    function changeEntries() {
        entriesPerPage = parseInt(document.getElementById('entriesSelect').value);
        currentPage = 1;
        renderTable();
    }

    function searchTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        filteredData = allData.filter(item => {
            return item.NAMA.toLowerCase().includes(searchTerm) ||
                   item.NIK.toString().includes(searchTerm) ||
                   item.ALAMAT.toLowerCase().includes(searchTerm);
        });
        currentPage = 1;
        renderTable();
    }

    function updateCharts() {
        // Chart Penduduk
        const ctx1 = document.getElementById('chartPenduduk');
        new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['Laki-laki', 'Perempuan'],
                datasets: [{
                    data: [60, 40],
                    backgroundColor: ['#1976d2', '#c2185b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Chart Lahan
        const ctx2 = document.getElementById('chartLahan');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Perumahan', 'Pertanian', 'Perkebunan', 'Komersial', 'Industri'],
                datasets: [{
                    label: 'Luas (m²)',
                    data: [5000, 8000, 3000, 2000, 1500],
                    backgroundColor: '#067002ff',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Load data saat halaman dibuka
    // Gunakan fetchPenduduk() untuk data dari API
    // atau generateDummyData() untuk demo
    fetchPenduduk();
</script>
</body>
</html>