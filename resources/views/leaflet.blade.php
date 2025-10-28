document.getElementById('lahanForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            var submitBtn = document.getElementById('lahanSubmitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';
            
            var isEditing = document.getElementById('isEditingLahanData').value;
            var lahanId = document.getElementById('lahanId').value;
            
            if (isEditing && lahanId) {
                // Mode Edit Data
                var lahan = lahanData.find(function(l) { return l.id === lahanId; });
                if (lahan) {
                    lahan.pemilik = document.getElementById('pemilik_lahan').value;
                    lahan.peruntukan = document.getElementById('peruntukan').value;
                    lahan.melewati = document.getElementById('melewati').value;
                    lahan.batasUtara = document.getElementById('batas_utara').value;
                    lahan.batasSelatan = document.getElementById('batas_selatan').value;
                    lahan.batasTimur = document.getElementById('batas_timur').value;
                    lahan.batasBarat = document.getElementById('batas_barat').value;
                    lahan.keterangan = document.getElementById('keterangan').value;
                    
                    // Update popup
                    var pemilik = pendudukData.find(function(p) { 
                        return p.NIK == lahan.pemilik; 
                    });
                    
                    var popupContent = '<b>' + lahan.peruntukan + '</b><br>' +
                        'Pemilik: ' + (pemilik ? pemilik.NAMA : '-') + '<br>' +
                        'Luas: ' + lahan.luas.toFixed(2) + ' m²';
                    
                    lahan.layer.setPopupContent(popupContent);
                    
                    renderLahanList();
                    showMessage('Data lahan berhasil diupdate!', 'success', 'messageLahan');
                }
            } else {
                // Mode Tambah Baru
                lahanId = 'lahan_' + Date.now();
                var polygon = L.polygon(currentDrawing.latlngs, {
                    color: '#667eea',
                    fillColor: '#667eea',
                    fillOpacity: 0.4
                }).addTo(drawnItems);
                
                var pemilik = pendudukData.find(function(p) { 
                    return p.NIK == document.getElementById('pemilik_lahan').value; 
                });
                
                var popupContent = '<b>' + document.getElementById('peruntukan').value + '</b><br>' +
                    'Pemilik: ' + (pemilik ? pemilik.NAMA : '-') + '<br>' +
                    'Luas: ' + currentDrawing.area.toFixed(2) + ' m²';
                
                polygon.bindPopup(popupContent);
                
                var lahanObj = {
                    id: lahanId,
                    pemilik: document.getElementById('pemilik_lahan').value,
                    peruntukan: document.getElementById('peruntukan').value,
                    luas: currentDrawing.area,
                    melewati: document.getElementById('melewati').value,
                    batasUtara: document.getElementById('batas_utara').value,
                    batasSelatan: document.getElementById('batas_selatan').value,
                    batasTimur: document.getElementById('batas_timur').value,
                    batasBarat: document.getElementById('batas_barat').value,
                    keterangan: document.getElementById('keterangan').value,
                    coordinates: currentDrawing.latlngs,
                    layer: polygon
                };
                
                lahanData.push(lahanObj);
                updateLahanStats();
                renderLahanList();
                showMessage('Data lahan berhasil disimpan!', 'success', 'messageLahan');
                drawingPoints = [];
            }
            
            submitBtn.disabled = false;
            submitBtn.textContent = '💾 Simpan Data Lahan';
            closeLahanModal();
        });

        document.getElementById('markerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            var submitBtn = document.getElementById('markerSubmitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';
            
            var isEditing = document.getElementById('isEditingMarkerData').value;
            var markerId = document.getElementById('markerId').value;
            
            if (isEditing && markerId) {
                // Mode Edit Data
                var marker = markerData.find(function(m) { return m.id === markerId; });
                if (marker) {
                    marker.nama = document.getElementById('nama_marker').value;
                    marker.kategori = document.getElementById('kategori_marker').value;
                    marker.deskripsi = document.getElementById('deskripsi_marker').value;
                    
                    // Update popup
                    var popupContent = '<b>' + marker.nama + '</b><br>' +
                        marker.kategori + '<br>' +
                        marker.deskripsi;
                    
                    marker.layer.setPopupContent(popupContent);
                    
                    renderLahanList();
                    showMessage('Data marker berhasil diupdate!', 'success', 'messageLahan');
                }
            } else {
                // Mode Tambah Baru
                markerId = 'marker_' + Date.now();
                var markerObj = L.marker(currentDrawing.latlng).addTo(drawnItems);
                
                var popupContent = '<b>' + document.getElementById('nama_marker').value + '</b><br>' +
                    document.getElementById('kategori_marker').value + '<br>' +
                    document.getElementById('deskripsi_marker').value;
                
                markerObj.bindPopup(popupContent);
                
                var markerDataObj = {
                    id: markerId,
                    nama: document.getElementById('nama_marker').value,
                    kategori: document.getElementById('kategori_marker').value,
                    deskripsi: document.getElementById('deskripsi_marker').value,
                    latlng: currentDrawing.latlng,
                    layer: markerObj
                };
                
                markerData.push(markerDataObj);
                updateLahanStats();
                renderLahanList();
                showMessage('Marker berhasil ditambahkan!', 'success', 'messageLahan');
            }
            
            submitBtn.disabled = false;
            submitBtn.textContent = '💾 Simpan Marker';
            closeMarkerModal();
        });<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Informasi Pertanahan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .container {
            display: flex;
            height: calc(100vh - 90px);
        }
        #map {
            flex: 1;
            height: 100%;
        }
        .sidebar {
            width: 380px;
            background: white;
            overflow-y: auto;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
        }
        .tab-buttons {
            display: flex;
            background: #f8f9fa;
            border-bottom: 2px solid #e0e0e0;
        }
        .tab-button {
            flex: 1;
            padding: 15px;
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #666;
            transition: all 0.3s;
        }
        .tab-button.active {
            background: white;
            color: #667eea;
            border-bottom: 3px solid #667eea;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .search-box {
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
        }
        .search-box input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border 0.3s;
        }
        .search-box input:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn-add {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.3s;
        }
        .btn-add:hover {
            background: #5568d3;
        }
        .btn-group-draw {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .btn-draw {
            flex: 1;
            padding: 10px;
            border: 2px solid #667eea;
            background: white;
            color: #667eea;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-draw:hover {
            background: #667eea;
            color: white;
        }
        .btn-draw.active {
            background: #667eea;
            color: white;
        }
        .data-list {
            padding: 10px;
        }
        .data-item {
            background: white;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            cursor: pointer;
            transition: all 0.3s;
        }
        .data-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-color: #667eea;
        }
        .data-item h3 {
            font-size: 16px;
            color: #333;
            margin-bottom: 8px;
        }
        .data-item p {
            font-size: 13px;
            color: #666;
            margin: 4px 0;
        }
        .stats {
            padding: 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
        }
        .stats h3 {
            font-size: 14px;
            margin-bottom: 15px;
            color: #333;
        }
        .stat-item {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            font-size: 13px;
        }
        .stat-value {
            font-weight: 600;
            color: #667eea;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            overflow: auto;
        }
        .modal-content {
            background-color: white;
            margin: 2% auto;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .modal-header h2 {
            font-size: 20px;
            color: #333;
        }
        .close {
            font-size: 28px;
            font-weight: bold;
            color: #aaa;
            cursor: pointer;
            line-height: 20px;
        }
        .close:hover {
            color: #000;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            transition: border 0.3s;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        .form-group input[readonly] {
            background: #f5f5f5;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .btn-edit, .btn-delete, .btn-view {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            margin-right: 5px;
            transition: all 0.3s;
        }
        .btn-edit {
            background: #ffc107;
            color: #333;
        }
        .btn-edit:hover {
            background: #e0a800;
        }
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        .btn-delete:hover {
            background: #c82333;
        }
        .btn-view {
            background: #17a2b8;
            color: white;
        }
        .btn-view:hover {
            background: #138496;
        }
        .edit-mode-info {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 12px;
            border-radius: 6px;
            margin: 10px 0;
            font-size: 13px;
            color: #856404;
            display: none;
        }
        .edit-mode-info.active {
            display: block;
        }
        .action-buttons {
            margin-top: 10px;
        }
        .loading {
            text-align: center;
            padding: 20px;
            color: #666;
        }
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            border: 1px solid #c3e6cb;
        }
        .info-box {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 13px;
            color: #004085;
        }
        .coordinate-display {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            margin-top: 10px;
            font-size: 13px;
            font-family: monospace;
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #667eea;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #667eea;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 5px;
        }
        .badge-polygon {
            background: #d4edda;
            color: #155724;
        }
        .badge-marker {
            background: #cce5ff;
            color: #004085;
        }
        .badge-polyline {
            background: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🗺 Sistem Informasi Pertanahan</h1>
        <p>Kelurahan Sukamaju, Kecamatan Makmur</p>
    </div>
    
    <div class="container">
        <div id="map"></div>
        
        <div class="sidebar">
            <div class="tab-buttons">
                <button class="tab-button active" onclick="switchTab('penduduk')">👥 Penduduk</button>
                <button class="tab-button" onclick="switchTab('lahan')">🗺 Lahan & Marker</button>
            </div>

            <div id="pendudukTab" class="tab-content active">
                <div class="stats">
                    <h3>📊 Statistik Penduduk</h3>
                    <div class="stat-item">
                        <span>Total Penduduk:</span>
                        <span class="stat-value" id="totalPenduduk">0</span>
                    </div>
                </div>
                
                <div class="search-box">
                    <input type="text" id="searchPenduduk" placeholder="🔍 Cari berdasarkan nama atau NIK...">
                    <button class="btn-add" onclick="openPendudukModal(null)">➕ Tambah Data Penduduk</button>
                </div>
                
                <div id="messagePenduduk"></div>
                <div class="data-list" id="pendudukList">
                    <div class="loading">Memuat data...</div>
                </div>
            </div>

            <div id="lahanTab" class="tab-content">
                <div class="stats">
                    <h3>📊 Statistik</h3>
                    <div class="stat-item">
                        <span>Total Lahan:</span>
                        <span class="stat-value" id="totalLahan">0</span>
                    </div>
                    <div class="stat-item">
                        <span>Total Marker:</span>
                        <span class="stat-value" id="totalMarker">0</span>
                    </div>
                    <div class="stat-item">
                        <span>Total Polyline:</span>
                        <span class="stat-value" id="totalPolyline">0</span>
                    </div>
                </div>
                
                <div class="search-box">
                    <input type="text" id="searchLahan" placeholder="🔍 Cari lahan atau marker...">
                    <div class="info-box">
                        💡 Klik pada peta untuk mulai menggambar lahan atau menambahkan marker
                    </div>
                    <div class="edit-mode-info" id="editModeInfo">
                        ✏️ Mode Edit Aktif - Klik dan drag titik untuk mengubah bentuk. Klik "Simpan Perubahan" jika selesai.
                    </div>
                    <div class="btn-group-draw">
                        <button class="btn-draw" onclick="startDrawPolygon()">📐 Gambar Lahan</button>
                        <button class="btn-draw" onclick="startDrawMarker()">📍 Tambah Marker</button>
                    </div>
                    <div class="btn-group-draw">
                        <button class="btn-draw" onclick="startDrawPolyline()">📏 Ukur Jarak</button>
                        <button class="btn-draw" onclick="clearDrawing()">🗑 Bersihkan</button>
                    </div>
                    <div class="btn-group-draw" id="editControls" style="display: none;">
                        <button class="btn-draw" style="background: #28a745; color: white;" onclick="saveEdit()">💾 Simpan Perubahan</button>
                        <button class="btn-draw" style="background: #dc3545; color: white;" onclick="cancelEdit()">❌ Batal Edit</button>
                    </div>
                </div>
                
                <div id="messageLahan"></div>
                <div class="data-list" id="lahanList">
                    <div class="loading">Belum ada data. Mulai dengan menggambar lahan atau menambahkan marker pada peta</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Penduduk -->
    <div id="pendudukModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="pendudukModalTitle">Tambah Data Penduduk</h2>
                <span class="close" onclick="closePendudukModal()">&times;</span>
            </div>
            <form id="pendudukForm">
                <input type="hidden" id="pendudukId">
                
                <div class="form-group">
                    <label for="nik">NIK <span style="color: red;">*</span></label>
                    <input type="number" id="nik" name="nik" required placeholder="Contoh: 3201012345670001">
                </div>
                
                <div class="form-group">
                    <label for="nomor_kk">Nomor KK <span style="color: red;">*</span></label>
                    <input type="number" id="nomor_kk" name="nomor_kk" required placeholder="Contoh: 3201012345670000">
                </div>
                
                <div class="form-group">
                    <label for="nama">Nama Lengkap <span style="color: red;">*</span></label>
                    <input type="text" id="nama" name="nama" required maxlength="30" placeholder="Contoh: Budi Santoso">
                </div>
                
                <div class="form-group">
                    <label for="alamat">Alamat <span style="color: red;">*</span></label>
                    <textarea id="alamat" name="alamat" required maxlength="100" rows="3" placeholder="Contoh: Jl. Merdeka No. 12"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="tgl_lahir">Tanggal Lahir <span style="color: red;">*</span></label>
                    <input type="date" id="tgl_lahir" name="tgl_lahir" required max="2007-12-31">
                </div>
                
                <div class="form-group">
                    <label for="no_telp">No. Telepon <span style="color: red;">*</span></label>
                    <input type="text" id="no_telp" name="no_telp" required maxlength="30" placeholder="Contoh: 081234567890">
                </div>
                
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closePendudukModal()">Batal</button>
                    <button type="submit" class="btn btn-primary" id="pendudukSubmitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Lahan -->
    <div id="lahanModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="lahanModalTitle">Form Data Lahan</h2>
                <span class="close" onclick="closeLahanModal()">&times;</span>
            </div>
            <form id="lahanForm">
                <input type="hidden" id="lahanId">
                <input type="hidden" id="isEditingLahanData">
                
                <div class="info-box">
                    📍 Koordinat telah diambil dari peta. Isi informasi lahan di bawah ini.
                </div>

                <div class="section-title">📋 Informasi Dasar</div>
                
                <div class="form-group">
                    <label for="pemilik_lahan">Pemilik Lahan <span style="color: red;">*</span></label>
                    <select id="pemilik_lahan" required>
                        <option value="">-- Pilih Pemilik --</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="peruntukan">Peruntukan Lahan <span style="color: red;">*</span></label>
                        <select id="peruntukan" required>
                            <option value="">-- Pilih Peruntukan --</option>
                            <option value="Perumahan">Perumahan</option>
                            <option value="Pertanian">Pertanian</option>
                            <option value="Perkebunan">Perkebunan</option>
                            <option value="Komersial">Komersial</option>
                            <option value="Industri">Industri</option>
                            <option value="Fasilitas Umum">Fasilitas Umum</option>
                            <option value="Tanah Kosong">Tanah Kosong</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="luas_lahan">Luas Lahan (m²) <span style="color: red;">*</span></label>
                        <input type="number" id="luas_lahan" step="0.01" readonly required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="melewati">Lahan Melewati</label>
                    <input type="text" id="melewati" placeholder="Contoh: Jalan Raya, Sungai Ciliwung">
                    <small style="color: #666;">Kosongkan jika tidak melewati bangunan/jalan/sungai</small>
                </div>

                <div class="section-title">🧭 Batas Arah Mata Angin</div>

                <div class="form-group">
                    <label for="batas_utara">Sebelah Utara <span style="color: red;">*</span></label>
                    <input type="text" id="batas_utara" required placeholder="Contoh: Rumah Pak Budi">
                </div>

                <div class="form-group">
                    <label for="batas_selatan">Sebelah Selatan <span style="color: red;">*</span></label>
                    <input type="text" id="batas_selatan" required placeholder="Contoh: Rumah Sakit Umum">
                </div>

                <div class="form-group">
                    <label for="batas_timur">Sebelah Timur <span style="color: red;">*</span></label>
                    <input type="text" id="batas_timur" required placeholder="Contoh: SD Negeri 1">
                </div>

                <div class="form-group">
                    <label for="batas_barat">Sebelah Barat <span style="color: red;">*</span></label>
                    <input type="text" id="batas_barat" required placeholder="Contoh: Masjid Al-Ikhlas">
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan Tambahan</label>
                    <textarea id="keterangan" rows="3" placeholder="Informasi tambahan tentang lahan..."></textarea>
                </div>

                <div class="coordinate-display" id="coordinateDisplay">
                    Koordinat akan muncul di sini
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeLahanModal()">Batal</button>
                    <button type="submit" class="btn btn-primary" id="lahanSubmitBtn">💾 Simpan Data Lahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Marker -->
    <div id="markerModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="markerModalTitle">Form Data Marker</h2>
                <span class="close" onclick="closeMarkerModal()">&times;</span>
            </div>
            <form id="markerForm">
                <input type="hidden" id="markerId">
                <input type="hidden" id="isEditingMarkerData">
                
                <div class="info-box">
                    📍 Marker telah ditambahkan pada peta. Isi informasi marker di bawah ini.
                </div>

                <div class="form-group">
                    <label for="nama_marker">Nama Marker <span style="color: red;">*</span></label>
                    <input type="text" id="nama_marker" required placeholder="Contoh: Kantor Kelurahan">
                </div>

                <div class="form-group">
                    <label for="kategori_marker">Kategori <span style="color: red;">*</span></label>
                    <select id="kategori_marker" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Fasilitas Umum">Fasilitas Umum</option>
                        <option value="Perkantoran">Perkantoran</option>
                        <option value="Rumah">Rumah</option>
                        <option value="Toko">Toko</option>
                        <option value="Sekolah">Sekolah</option>
                        <option value="Tempat Ibadah">Tempat Ibadah</option>
                        <option value="Kesehatan">Kesehatan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="deskripsi_marker">Deskripsi</label>
                    <textarea id="deskripsi_marker" rows="3" placeholder="Deskripsi marker..."></textarea>
                </div>

                <div class="coordinate-display" id="markerCoordinateDisplay">
                    Koordinat akan muncul di sini
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="closeMarkerModal()">Batal</button>
                    <button type="submit" class="btn btn-primary" id="markerSubmitBtn">💾 Simpan Marker</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script>
        var pendudukData = [];
        var lahanData = [];
        var markerData = [];
        var polylineData = [];
        var currentEditId = null;
        var drawnItems = new L.FeatureGroup();
        var currentDrawing = null;
        var tempMarker = null;
        var tempPolygon = null;
        var tempPolyline = null;
        var isDrawingMode = false;
        var drawingType = null;
        var isEditMode = false;
        var editingObject = null;
        var editingType = null;
        var originalLayer = null;

        var map = L.map('map').setView([-6.2063, 106.8466], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        map.addLayer(drawnItems);

        // Fungsi untuk menghitung jarak antara 2 titik (Haversine formula)
        function calculateDistance(lat1, lon1, lat2, lon2) {
            var R = 6371; // Radius bumi dalam kilometer
            var dLat = (lat2 - lat1) * Math.PI / 180;
            var dLon = (lon2 - lon1) * Math.PI / 180;
            var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon/2) * Math.sin(dLon/2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            var distance = R * c;
            return distance;
        }

        // Fungsi untuk menghitung luas polygon
        function calculateArea(latlngs) {
            var area = L.GeometryUtil.geodesicArea(latlngs);
            return area; // dalam m²
        }

        // Polyfill untuk L.GeometryUtil.geodesicArea
        L.GeometryUtil = L.GeometryUtil || {};
        L.GeometryUtil.geodesicArea = function (latLngs) {
            var pointsCount = latLngs.length,
                area = 0.0,
                d2r = Math.PI / 180,
                p1, p2;

            if (pointsCount > 2) {
                for (var i = 0; i < pointsCount; i++) {
                    p1 = latLngs[i];
                    p2 = latLngs[(i + 1) % pointsCount];
                    area += ((p2.lng - p1.lng) * d2r) *
                            (2 + Math.sin(p1.lat * d2r) + Math.sin(p2.lat * d2r));
                }
                area = area * 6378137.0 * 6378137.0 / 2.0;
            }

            return Math.abs(area);
        };

        function startDrawPolygon() {
            clearDrawing();
            isDrawingMode = true;
            drawingType = 'polygon';
            document.querySelectorAll('.btn-draw').forEach(function(btn) {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            showMessage('Klik pada peta untuk membuat titik polygon. Klik ganda untuk selesai.', 'success', 'messageLahan');
        }

        function startDrawMarker() {
            clearDrawing();
            isDrawingMode = true;
            drawingType = 'marker';
            document.querySelectorAll('.btn-draw').forEach(function(btn) {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            showMessage('Klik pada peta untuk menambahkan marker', 'success', 'messageLahan');
        }

        function startDrawPolyline() {
            clearDrawing();
            isDrawingMode = true;
            drawingType = 'polyline';
            document.querySelectorAll('.btn-draw').forEach(function(btn) {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            showMessage('Klik pada peta untuk membuat garis pengukur jarak. Klik ganda untuk selesai.', 'success', 'messageLahan');
        }

        function clearDrawing() {
            isDrawingMode = false;
            drawingType = null;
            if (tempMarker) {
                map.removeLayer(tempMarker);
                tempMarker = null;
            }
            if (tempPolygon) {
                map.removeLayer(tempPolygon);
                tempPolygon = null;
            }
            if (tempPolyline) {
                map.removeLayer(tempPolyline);
                tempPolyline = null;
            }
            if (currentDrawing) {
                currentDrawing = null;
            }
            document.querySelectorAll('.btn-draw').forEach(function(btn) {
                btn.classList.remove('active');
            });
            drawingPoints = [];
        }

        function enableEditMode(obj, type) {
            if (isEditMode) {
                cancelEdit();
            }
            
            isEditMode = true;
            editingObject = obj;
            editingType = type;
            
            document.getElementById('editModeInfo').classList.add('active');
            document.getElementById('editControls').style.display = 'flex';
            
            if (type === 'lahan' || type === 'polyline') {
                originalLayer = obj.layer;
                
                // Buat layer baru yang bisa di-edit
                var editableLayer;
                if (type === 'lahan') {
                    editableLayer = L.polygon(obj.coordinates, {
                        color: '#ffc107',
                        fillColor: '#ffc107',
                        fillOpacity: 0.4
                    }).addTo(map);
                } else {
                    editableLayer = L.polyline(obj.coordinates, {
                        color: '#ffc107',
                        weight: 4
                    }).addTo(map);
                }
                
                // Tambahkan fitur editing dengan menambahkan titik-titik yang bisa di-drag
                obj.editLayer = editableLayer;
                obj.editMarkers = [];
                
                obj.coordinates.forEach(function(latlng, index) {
                    var marker = L.marker(latlng, {
                        draggable: true,
                        icon: L.divIcon({
                            className: 'edit-marker',
                            html: '<div style="background: #ffc107; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 4px rgba(0,0,0,0.5);"></div>',
                            iconSize: [12, 12]
                        })
                    }).addTo(map);
                    
                    marker.on('drag', function(e) {
                        obj.coordinates[index] = e.target.getLatLng();
                        if (type === 'lahan') {
                            editableLayer.setLatLngs(obj.coordinates);
                            var newArea = calculateArea(obj.coordinates);
                            editableLayer.bindPopup('Luas: ' + newArea.toFixed(2) + ' m² (editing)').openPopup();
                        } else {
                            editableLayer.setLatLngs(obj.coordinates);
                            var totalDistance = 0;
                            for (var i = 0; i < obj.coordinates.length - 1; i++) {
                                totalDistance += calculateDistance(
                                    obj.coordinates[i].lat, obj.coordinates[i].lng,
                                    obj.coordinates[i+1].lat, obj.coordinates[i+1].lng
                                );
                            }
                            var distanceText = totalDistance >= 1 ? 
                                totalDistance.toFixed(2) + ' km' : 
                                (totalDistance * 1000).toFixed(2) + ' m';
                            editableLayer.bindPopup('Jarak: ' + distanceText + ' (editing)').openPopup();
                        }
                    });
                    
                    obj.editMarkers.push(marker);
                });
                
                // Sembunyikan layer asli
                originalLayer.setStyle({opacity: 0.2, fillOpacity: 0.1});
                
                map.fitBounds(editableLayer.getBounds());
                
            } else if (type === 'marker') {
                originalLayer = obj.layer;
                
                // Buat marker yang bisa di-drag
                var editableMarker = L.marker(obj.latlng, {
                    draggable: true,
                    icon: L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-gold.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    })
                }).addTo(map);
                
                editableMarker.bindPopup('Drag untuk memindahkan marker').openPopup();
                
                editableMarker.on('drag', function(e) {
                    obj.latlng = e.target.getLatLng();
                });
                
                obj.editLayer = editableMarker;
                
                // Sembunyikan marker asli
                originalLayer.setOpacity(0.2);
                
                map.setView(obj.latlng, 18);
            }
            
            showMessage('Mode edit aktif. Drag titik-titik untuk mengubah bentuk.', 'success', 'messageLahan');
        }

        function saveEdit() {
            if (!isEditMode || !editingObject) return;
            
            if (editingType === 'lahan') {
                // Update koordinat dan luas
                var newArea = calculateArea(editingObject.coordinates);
                editingObject.luas = newArea;
                
                // Hapus layer lama
                drawnItems.removeLayer(originalLayer);
                
                // Buat layer baru dengan koordinat yang diupdate
                var newPolygon = L.polygon(editingObject.coordinates, {
                    color: '#667eea',
                    fillColor: '#667eea',
                    fillOpacity: 0.4
                }).addTo(drawnItems);
                
                var pemilik = pendudukData.find(function(p) { 
                    return p.NIK == editingObject.pemilik; 
                });
                
                var popupContent = '<b>' + editingObject.peruntukan + '</b><br>' +
                    'Pemilik: ' + (pemilik ? pemilik.NAMA : '-') + '<br>' +
                    'Luas: ' + newArea.toFixed(2) + ' m²';
                
                newPolygon.bindPopup(popupContent);
                editingObject.layer = newPolygon;
                
                // Hapus marker edit
                editingObject.editMarkers.forEach(function(m) {
                    map.removeLayer(m);
                });
                map.removeLayer(editingObject.editLayer);
                
                showMessage('Perubahan lahan berhasil disimpan! Luas baru: ' + newArea.toFixed(2) + ' m²', 'success', 'messageLahan');
                
            } else if (editingType === 'polyline') {
                // Update jarak
                var totalDistance = 0;
                for (var i = 0; i < editingObject.coordinates.length - 1; i++) {
                    totalDistance += calculateDistance(
                        editingObject.coordinates[i].lat, editingObject.coordinates[i].lng,
                        editingObject.coordinates[i+1].lat, editingObject.coordinates[i+1].lng
                    );
                }
                
                editingObject.distance = totalDistance;
                editingObject.distanceText = totalDistance >= 1 ? 
                    totalDistance.toFixed(2) + ' km' : 
                    (totalDistance * 1000).toFixed(2) + ' m';
                
                // Hapus layer lama
                drawnItems.removeLayer(originalLayer);
                
                // Buat layer baru
                var newPolyline = L.polyline(editingObject.coordinates, {
                    color: 'green',
                    weight: 3
                }).addTo(drawnItems);
                
                newPolyline.bindPopup('Jarak: ' + editingObject.distanceText);
                editingObject.layer = newPolyline;
                
                // Hapus marker edit
                editingObject.editMarkers.forEach(function(m) {
                    map.removeLayer(m);
                });
                map.removeLayer(editingObject.editLayer);
                
                showMessage('Perubahan jarak berhasil disimpan! Jarak baru: ' + editingObject.distanceText, 'success', 'messageLahan');
                
            } else if (editingType === 'marker') {
                // Update posisi marker
                drawnItems.removeLayer(originalLayer);
                
                var newMarker = L.marker(editingObject.latlng).addTo(drawnItems);
                
                var popupContent = '<b>' + editingObject.nama + '</b><br>' +
                    editingObject.kategori + '<br>' +
                    editingObject.deskripsi;
                
                newMarker.bindPopup(popupContent);
                editingObject.layer = newMarker;
                
                map.removeLayer(editingObject.editLayer);
                
                showMessage('Posisi marker berhasil diupdate!', 'success', 'messageLahan');
            }
            
            renderLahanList();
            cancelEdit();
        }

        function cancelEdit() {
            if (!isEditMode) return;
            
            if (editingObject) {
                if (editingObject.editMarkers) {
                    editingObject.editMarkers.forEach(function(m) {
                        map.removeLayer(m);
                    });
                }
                if (editingObject.editLayer) {
                    map.removeLayer(editingObject.editLayer);
                }
                
                // Kembalikan opacity layer asli
                if (originalLayer) {
                    if (editingType === 'lahan' || editingType === 'polyline') {
                        originalLayer.setStyle({opacity: 1, fillOpacity: 0.4});
                    } else if (editingType === 'marker') {
                        originalLayer.setOpacity(1);
                    }
                }
            }
            
            isEditMode = false;
            editingObject = null;
            editingType = null;
            originalLayer = null;
            
            document.getElementById('editModeInfo').classList.remove('active');
            document.getElementById('editControls').style.display = 'none';
            
            showMessage('Edit dibatalkan', 'success', 'messageLahan');
        }

        var drawingPoints = [];
        
        map.on('click', function(e) {
            if (!isDrawingMode) return;

            if (drawingType === 'marker') {
                if (tempMarker) map.removeLayer(tempMarker);
                tempMarker = L.marker(e.latlng).addTo(map);
                currentDrawing = {
                    type: 'marker',
                    latlng: e.latlng
                };
                openMarkerModal();
            } else if (drawingType === 'polygon') {
                drawingPoints.push(e.latlng);
                L.circle(e.latlng, {radius: 2, color: 'red'}).addTo(map);
                
                if (drawingPoints.length > 1) {
                    if (tempPolygon) map.removeLayer(tempPolygon);
                    tempPolygon = L.polygon(drawingPoints, {
                        color: 'blue',
                        fillColor: '#30f',
                        fillOpacity: 0.3
                    }).addTo(map);
                    
                    var area = calculateArea(drawingPoints);
                    tempPolygon.bindPopup('Luas: ' + area.toFixed(2) + ' m²').openPopup();
                }
            } else if (drawingType === 'polyline') {
                drawingPoints.push(e.latlng);
                L.circle(e.latlng, {radius: 2, color: 'green'}).addTo(map);
                
                if (drawingPoints.length > 1) {
                    if (tempPolyline) map.removeLayer(tempPolyline);
                    tempPolyline = L.polyline(drawingPoints, {
                        color: 'green',
                        weight: 3
                    }).addTo(map);
                    
                    var totalDistance = 0;
                    for (var i = 0; i < drawingPoints.length - 1; i++) {
                        totalDistance += calculateDistance(
                            drawingPoints[i].lat, drawingPoints[i].lng,
                            drawingPoints[i+1].lat, drawingPoints[i+1].lng
                        );
                    }
                    
                    var distanceText = totalDistance >= 1 ? 
                        totalDistance.toFixed(2) + ' km' : 
                        (totalDistance * 1000).toFixed(2) + ' m';
                    
                    tempPolyline.bindPopup('Jarak: ' + distanceText).openPopup();
                }
            }
        });

        map.on('dblclick', function(e) {
            if (!isDrawingMode) return;
            
            if (drawingType === 'polygon' && drawingPoints.length >= 3) {
                var area = calculateArea(drawingPoints);
                currentDrawing = {
                    type: 'polygon',
                    latlngs: drawingPoints.slice(),
                    area: area
                };
                openLahanModal();
            } else if (drawingType === 'polyline' && drawingPoints.length >= 2) {
                var totalDistance = 0;
                for (var i = 0; i < drawingPoints.length - 1; i++) {
                    totalDistance += calculateDistance(
                        drawingPoints[i].lat, drawingPoints[i].lng,
                        drawingPoints[i+1].lat, drawingPoints[i+1].lng
                    );
                }
                
                var polylineId = 'polyline_' + Date.now();
                var distanceText = totalDistance >= 1 ? 
                    totalDistance.toFixed(2) + ' km' : 
                    (totalDistance * 1000).toFixed(2) + ' m';
                
                var polylineObj = L.polyline(drawingPoints.slice(), {
                    color: 'green',
                    weight: 3
                }).addTo(drawnItems);
                
                polylineObj.bindPopup('Jarak: ' + distanceText);
                
                polylineData.push({
                    id: polylineId,
                    coordinates: drawingPoints.slice(),
                    distance: totalDistance,
                    distanceText: distanceText,
                    layer: polylineObj
                });
                
                updateLahanStats();
                renderLahanList();
                showMessage('Pengukuran jarak berhasil: ' + distanceText, 'success', 'messageLahan');
                clearDrawing();
                drawingPoints = [];
            }
        });

        function switchTab(tabName) {
            var buttons = document.querySelectorAll('.tab-button');
            var contents = document.querySelectorAll('.tab-content');
            buttons.forEach(function(btn) { btn.classList.remove('active'); });
            contents.forEach(function(content) { content.classList.remove('active'); });
            event.target.classList.add('active');
            document.getElementById(tabName + 'Tab').classList.add('active');
        }

        function showMessage(message, type, container) {
            var className = type === 'success' ? 'success-message' : 'error-message';
            document.getElementById(container).innerHTML = '<div class="' + className + '">' + message + '</div>';
            setTimeout(function() {
                document.getElementById(container).innerHTML = '';
            }, 3000);
        }

        function fetchPenduduk() {
            fetch('/api/penduduk')
                .then(function(response) {
                    if (!response.ok) throw new Error('Gagal mengambil data');
                    return response.json();
                })
                .then(function(data) {
                    pendudukData = data;
                    renderPendudukList(pendudukData);
                    updatePendudukStats();
                    populatePemilikSelect();
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    showMessage('Gagal memuat data penduduk: ' + error.message, 'error', 'messagePenduduk');
                    document.getElementById('pendudukList').innerHTML = '<div class="loading">Gagal memuat data</div>';
                });
        }

        function populatePemilikSelect() {
            var select = document.getElementById('pemilik_lahan');
            select.innerHTML = '<option value="">-- Pilih Pemilik --</option>';
            pendudukData.forEach(function(p) {
                var option = document.createElement('option');
                option.value = p.NIK;
                option.textContent = p.NAMA + ' (NIK: ' + p.NIK + ')';
                select.appendChild(option);
            });
        }

        function updatePendudukStats() {
            document.getElementById('totalPenduduk').textContent = pendudukData.length;
        }

        function updateLahanStats() {
            document.getElementById('totalLahan').textContent = lahanData.length;
            document.getElementById('totalMarker').textContent = markerData.length;
            document.getElementById('totalPolyline').textContent = polylineData.length;
        }

        function renderPendudukList(data) {
            var list = document.getElementById('pendudukList');
            list.innerHTML = '';
            if (data.length === 0) {
                list.innerHTML = '<div class="loading">Tidak ada data</div>';
                return;
            }
            data.forEach(function(p) {
                var item = document.createElement('div');
                item.className = 'data-item';
                item.innerHTML = '<h3>' + p.NAMA + '</h3>' +
                    '<p>🆔 NIK: ' + p.NIK + '</p>' +
                    '<p>👨‍👩‍👧‍👦 KK: ' + p.NOMOR_KK + '</p>' +
                    '<p>📍 ' + p.ALAMAT + '</p>' +
                    '<p>📞 ' + p.NO_TELP + '</p>' +
                    '<p>🎂 ' + p.TGL_LAHIR + '</p>' +
                    '<div class="action-buttons">' +
                    '<button class="btn-edit" onclick="editPenduduk(' + p.NIK + ')">✏ Edit</button>' +
                    '<button class="btn-delete" onclick="deletePenduduk(' + p.NIK + ')">🗑 Hapus</button>' +
                    '</div>';
                list.appendChild(item);
            });
        }

        function renderLahanList() {
            var list = document.getElementById('lahanList');
            list.innerHTML = '';
            
            var allData = [];
            
            lahanData.forEach(function(l) {
                allData.push({type: 'lahan', data: l});
            });
            
            markerData.forEach(function(m) {
                allData.push({type: 'marker', data: m});
            });
            
            polylineData.forEach(function(p) {
                allData.push({type: 'polyline', data: p});
            });
            
            if (allData.length === 0) {
                list.innerHTML = '<div class="loading">Belum ada data. Mulai dengan menggambar lahan atau menambahkan marker pada peta</div>';
                return;
            }
            
            allData.forEach(function(item) {
                var div = document.createElement('div');
                div.className = 'data-item';
                
                if (item.type === 'lahan') {
                    var l = item.data;
                    var pemilik = pendudukData.find(function(p) { return p.NIK == l.pemilik; });
                    var pemilikNama = pemilik ? pemilik.NAMA : 'Tidak diketahui';
                    
                    div.innerHTML = '<span class="badge badge-polygon">LAHAN</span>' +
                        '<h3>Lahan ' + l.peruntukan + '</h3>' +
                        '<p>👤 Pemilik: ' + pemilikNama + '</p>' +
                        '<p>📐 Luas: ' + l.luas.toFixed(2) + ' m²</p>' +
                        '<p>🧭 Utara: ' + l.batasUtara + '</p>' +
                        '<p>🧭 Selatan: ' + l.batasSelatan + '</p>' +
                        '<div class="action-buttons">' +
                        '<button class="btn-view" onclick="viewLahan(\'' + l.id + '\')">👁 Lihat</button>' +
                        '<button class="btn-edit" onclick="editLahanGeometry(\'' + l.id + '\')">✏ Edit Bentuk</button>' +
                        '<button class="btn-edit" onclick="editLahanData(\'' + l.id + '\')">📝 Edit Data</button>' +
                        '<button class="btn-delete" onclick="deleteLahan(\'' + l.id + '\')">🗑 Hapus</button>' +
                        '</div>';
                } else if (item.type === 'marker') {
                    var m = item.data;
                    div.innerHTML = '<span class="badge badge-marker">MARKER</span>' +
                        '<h3>' + m.nama + '</h3>' +
                        '<p>📂 Kategori: ' + m.kategori + '</p>' +
                        '<p>📍 ' + m.deskripsi + '</p>' +
                        '<div class="action-buttons">' +
                        '<button class="btn-view" onclick="viewMarker(\'' + m.id + '\')">👁 Lihat</button>' +
                        '<button class="btn-edit" onclick="editMarkerPosition(\'' + m.id + '\')">✏ Edit Posisi</button>' +
                        '<button class="btn-edit" onclick="editMarkerData(\'' + m.id + '\')">📝 Edit Data</button>' +
                        '<button class="btn-delete" onclick="deleteMarker(\'' + m.id + '\')">🗑 Hapus</button>' +
                        '</div>';
                } else if (item.type === 'polyline') {
                    var p = item.data;
                    div.innerHTML = '<span class="badge badge-polyline">PENGUKURAN</span>' +
                        '<h3>Jarak: ' + p.distanceText + '</h3>' +
                        '<p>📏 Total ' + p.coordinates.length + ' titik</p>' +
                        '<div class="action-buttons">' +
                        '<button class="btn-view" onclick="viewPolyline(\'' + p.id + '\')">👁 Lihat</button>' +
                        '<button class="btn-edit" onclick="editPolyline(\'' + p.id + '\')">✏ Edit</button>' +
                        '<button class="btn-delete" onclick="deletePolyline(\'' + p.id + '\')">🗑 Hapus</button>' +
                        '</div>';
                }
                
                list.appendChild(div);
            });
        }

        function openLahanModal() {
            if (!currentDrawing || currentDrawing.type !== 'polygon') return;
            
            document.getElementById('lahanModalTitle').textContent = 'Form Data Lahan';
            document.getElementById('lahanId').value = '';
            document.getElementById('isEditingLahanData').value = '';
            document.getElementById('luas_lahan').value = currentDrawing.area.toFixed(2);
            
            var coords = 'Koordinat Polygon:\n';
            currentDrawing.latlngs.forEach(function(point, index) {
                coords += 'Titik ' + (index + 1) + ': ' + point.lat.toFixed(6) + ', ' + point.lng.toFixed(6) + '\n';
            });
            document.getElementById('coordinateDisplay').textContent = coords;
            
            document.getElementById('lahanModal').style.display = 'block';
        }

        function closeLahanModal() {
            document.getElementById('lahanModal').style.display = 'none';
            document.getElementById('lahanForm').reset();
            if (!document.getElementById('isEditingLahanData').value) {
                clearDrawing();
                drawingPoints = [];
            }
        }

        function openMarkerModal() {
            if (!currentDrawing || currentDrawing.type !== 'marker') return;
            
            document.getElementById('markerModalTitle').textContent = 'Form Data Marker';
            document.getElementById('markerId').value = '';
            document.getElementById('isEditingMarkerData').value = '';
            
            var coords = 'Koordinat: ' + currentDrawing.latlng.lat.toFixed(6) + ', ' + currentDrawing.latlng.lng.toFixed(6);
            document.getElementById('markerCoordinateDisplay').textContent = coords;
            
            document.getElementById('markerModal').style.display = 'block';
        }

        function closeMarkerModal() {
            document.getElementById('markerModal').style.display = 'none';
            document.getElementById('markerForm').reset();
            if (!document.getElementById('isEditingMarkerData').value) {
                clearDrawing();
            }
        }

        document.getElementById('lahanForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            var lahanId = 'lahan_' + Date.now();
            var polygon = L.polygon(currentDrawing.latlngs, {
                color: '#667eea',
                fillColor: '#667eea',
                fillOpacity: 0.4
            }).addTo(drawnItems);
            
            var pemilik = pendudukData.find(function(p) { 
                return p.NIK == document.getElementById('pemilik_lahan').value; 
            });
            
            var popupContent = '<b>' + document.getElementById('peruntukan').value + '</b><br>' +
                'Pemilik: ' + (pemilik ? pemilik.NAMA : '-') + '<br>' +
                'Luas: ' + currentDrawing.area.toFixed(2) + ' m²';
            
            polygon.bindPopup(popupContent);
            
            var lahanObj = {
                id: lahanId,
                pemilik: document.getElementById('pemilik_lahan').value,
                peruntukan: document.getElementById('peruntukan').value,
                luas: currentDrawing.area,
                melewati: document.getElementById('melewati').value,
                batasUtara: document.getElementById('batas_utara').value,
                batasSelatan: document.getElementById('batas_selatan').value,
                batasTimur: document.getElementById('batas_timur').value,
                batasBarat: document.getElementById('batas_barat').value,
                keterangan: document.getElementById('keterangan').value,
                coordinates: currentDrawing.latlngs,
                layer: polygon
            };
            
            lahanData.push(lahanObj);
            updateLahanStats();
            renderLahanList();
            showMessage('Data lahan berhasil disimpan!', 'success', 'messageLahan');
            closeLahanModal();
            drawingPoints = [];
        });

        document.getElementById('markerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            var markerId = 'marker_' + Date.now();
            var marker = L.marker(currentDrawing.latlng).addTo(drawnItems);
            
            var popupContent = '<b>' + document.getElementById('nama_marker').value + '</b><br>' +
                document.getElementById('kategori_marker').value + '<br>' +
                document.getElementById('deskripsi_marker').value;
            
            marker.bindPopup(popupContent);
            
            var markerObj = {
                id: markerId,
                nama: document.getElementById('nama_marker').value,
                kategori: document.getElementById('kategori_marker').value,
                deskripsi: document.getElementById('deskripsi_marker').value,
                latlng: currentDrawing.latlng,
                layer: marker
            };
            
            markerData.push(markerObj);
            updateLahanStats();
            renderLahanList();
            showMessage('Marker berhasil ditambahkan!', 'success', 'messageLahan');
            closeMarkerModal();
        });

        function viewLahan(id) {
            var lahan = lahanData.find(function(l) { return l.id === id; });
            if (lahan && lahan.layer) {
                map.fitBounds(lahan.layer.getBounds());
                lahan.layer.openPopup();
            }
        }

        function editLahanGeometry(id) {
            var lahan = lahanData.find(function(l) { return l.id === id; });
            if (lahan) {
                enableEditMode(lahan, 'lahan');
            }
        }

        function editLahanData(id) {
            var lahan = lahanData.find(function(l) { return l.id === id; });
            if (!lahan) return;
            
            // Isi form dengan data lahan yang ada
            document.getElementById('lahanModalTitle').textContent = 'Edit Data Lahan';
            document.getElementById('lahanId').value = lahan.id;
            document.getElementById('isEditingLahanData').value = 'true';
            document.getElementById('pemilik_lahan').value = lahan.pemilik;
            document.getElementById('peruntukan').value = lahan.peruntukan;
            document.getElementById('luas_lahan').value = lahan.luas.toFixed(2);
            document.getElementById('melewati').value = lahan.melewati || '';
            document.getElementById('batas_utara').value = lahan.batasUtara;
            document.getElementById('batas_selatan').value = lahan.batasSelatan;
            document.getElementById('batas_timur').value = lahan.batasTimur;
            document.getElementById('batas_barat').value = lahan.batasBarat;
            document.getElementById('keterangan').value = lahan.keterangan || '';
            
            var coords = 'Koordinat Polygon:\n';
            lahan.coordinates.forEach(function(point, index) {
                coords += 'Titik ' + (index + 1) + ': ' + point.lat.toFixed(6) + ', ' + point.lng.toFixed(6) + '\n';
            });
            document.getElementById('coordinateDisplay').textContent = coords;
            
            document.getElementById('lahanModal').style.display = 'block';
            
            // Zoom ke lahan
            map.fitBounds(lahan.layer.getBounds());
        }

        function viewMarker(id) {
            var marker = markerData.find(function(m) { return m.id === id; });
            if (marker && marker.layer) {
                map.setView(marker.latlng, 18);
                marker.layer.openPopup();
            }
        }

        function editMarkerPosition(id) {
            var marker = markerData.find(function(m) { return m.id === id; });
            if (marker) {
                enableEditMode(marker, 'marker');
            }
        }

        function editMarkerData(id) {
            var marker = markerData.find(function(m) { return m.id === id; });
            if (!marker) return;
            
            // Isi form dengan data marker yang ada
            document.getElementById('markerModalTitle').textContent = 'Edit Data Marker';
            document.getElementById('markerId').value = marker.id;
            document.getElementById('isEditingMarkerData').value = 'true';
            document.getElementById('nama_marker').value = marker.nama;
            document.getElementById('kategori_marker').value = marker.kategori;
            document.getElementById('deskripsi_marker').value = marker.deskripsi || '';
            
            var coords = 'Koordinat: ' + marker.latlng.lat.toFixed(6) + ', ' + marker.latlng.lng.toFixed(6);
            document.getElementById('markerCoordinateDisplay').textContent = coords;
            
            document.getElementById('markerModal').style.display = 'block';
            
            // Zoom ke marker
            map.setView(marker.latlng, 18);
        }

        function viewPolyline(id) {
            var polyline = polylineData.find(function(p) { return p.id === id; });
            if (polyline && polyline.layer) {
                map.fitBounds(polyline.layer.getBounds());
                polyline.layer.openPopup();
            }
        }

        function editPolyline(id) {
            var polyline = polylineData.find(function(p) { return p.id === id; });
            if (polyline) {
                enableEditMode(polyline, 'polyline');
            }
        }

        function deleteLahan(id) {
            if (!confirm('Hapus data lahan ini?')) return;
            
            var lahan = lahanData.find(function(l) { return l.id === id; });
            if (lahan) {
                drawnItems.removeLayer(lahan.layer);
                lahanData = lahanData.filter(function(l) { return l.id !== id; });
                updateLahanStats();
                renderLahanList();
                showMessage('Data lahan berhasil dihapus', 'success', 'messageLahan');
            }
        }

        function deleteMarker(id) {
            if (!confirm('Hapus marker ini?')) return;
            
            var marker = markerData.find(function(m) { return m.id === id; });
            if (marker) {
                drawnItems.removeLayer(marker.layer);
                markerData = markerData.filter(function(m) { return m.id !== id; });
                updateLahanStats();
                renderLahanList();
                showMessage('Marker berhasil dihapus', 'success', 'messageLahan');
            }
        }

        function deletePolyline(id) {
            if (!confirm('Hapus pengukuran ini?')) return;
            
            var polyline = polylineData.find(function(p) { return p.id === id; });
            if (polyline) {
                drawnItems.removeLayer(polyline.layer);
                polylineData = polylineData.filter(function(p) { return p.id !== id; });
                updateLahanStats();
                renderLahanList();
                showMessage('Pengukuran berhasil dihapus', 'success', 'messageLahan');
            }
        }

        document.getElementById('searchPenduduk').addEventListener('input', function(e) {
            var searchTerm = e.target.value.toLowerCase();
            var filtered = pendudukData.filter(function(p) {
                return p.NAMA.toLowerCase().includes(searchTerm) ||
                    p.NIK.toString().includes(searchTerm) ||
                    p.ALAMAT.toLowerCase().includes(searchTerm);
            });
            renderPendudukList(filtered);
        });

        document.getElementById('searchLahan').addEventListener('input', function(e) {
            var searchTerm = e.target.value.toLowerCase();
            // Implementasi pencarian untuk lahan dan marker
            renderLahanList();
        });

        function openPendudukModal(penduduk) {
            var modal = document.getElementById('pendudukModal');
            var form = document.getElementById('pendudukForm');
            var title = document.getElementById('pendudukModalTitle');
            form.reset();
            currentEditId = null;
            if (penduduk) {
                title.textContent = 'Edit Data Penduduk';
                document.getElementById('pendudukId').value = penduduk.NIK;
                document.getElementById('nik').value = penduduk.NIK;
                document.getElementById('nomor_kk').value = penduduk.NOMOR_KK;
                document.getElementById('nama').value = penduduk.NAMA;
                document.getElementById('alamat').value = penduduk.ALAMAT;
                document.getElementById('tgl_lahir').value = penduduk.TGL_LAHIR;
                document.getElementById('no_telp').value = penduduk.NO_TELP;
                document.getElementById('nik').readOnly = true;
                currentEditId = penduduk.NIK;
            } else {
                title.textContent = 'Tambah Data Penduduk';
                document.getElementById('nik').readOnly = false;
            }
            modal.style.display = 'block';
        }

        function closePendudukModal() {
            document.getElementById('pendudukModal').style.display = 'none';
            document.getElementById('pendudukForm').reset();
            currentEditId = null;
        }

        document.getElementById('pendudukForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var submitBtn = document.getElementById('pendudukSubmitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';
            
            var formData = {
                NIK: parseInt(document.getElementById('nik').value),
                NOMOR_KK: parseInt(document.getElementById('nomor_kk').value),
                NAMA: document.getElementById('nama').value,
                ALAMAT: document.getElementById('alamat').value,
                TGL_LAHIR: document.getElementById('tgl_lahir').value,
                NO_TELP: document.getElementById('no_telp').value
            };
            
            var url = '/api/penduduk';
            var method = 'POST';
            
            if (currentEditId) {
                url = '/api/penduduk/' + currentEditId;
                method = 'PUT';
            }
            
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            })
            .then(function(response) {
                if (!response.ok) {
                    return response.json().then(function(err) {
                        throw new Error(err.error || 'Terjadi kesalahan');
                    });
                }
                return response.json();
            })
            .then(function(result) {
                if (result.success) {
                    showMessage(currentEditId ? 'Data berhasil diupdate' : 'Data berhasil ditambahkan', 'success', 'messagePenduduk');
                    closePendudukModal();
                    fetchPenduduk();
                } else {
                    showMessage(result.error || 'Terjadi kesalahan', 'error', 'messagePenduduk');
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                showMessage('Gagal menyimpan data: ' + error.message, 'error', 'messagePenduduk');
            })
            .finally(function() {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Simpan';
            });
        });

        function editPenduduk(nik) {
            var penduduk = pendudukData.find(function(p) { return p.NIK == nik; });
            if (penduduk) openPendudukModal(penduduk);
        }

        function deletePenduduk(nik) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) return;
            
            fetch('/api/penduduk/' + nik, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(function(response) { 
                if (!response.ok) throw new Error('Gagal menghapus');
                return response.json(); 
            })
            .then(function(result) {
                if (result.success) {
                    showMessage('Data berhasil dihapus', 'success', 'messagePenduduk');
                    fetchPenduduk();
                } else {
                    showMessage(result.error || 'Gagal menghapus data', 'error', 'messagePenduduk');
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                showMessage('Gagal menghapus data: ' + error.message, 'error', 'messagePenduduk');
            });
        }

        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
            }
        }

        // Load data saat halaman pertama kali dibuka
        fetchPenduduk();
        updateLahanStats();
    </script>
</body>
</html>