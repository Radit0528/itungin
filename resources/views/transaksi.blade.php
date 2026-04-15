@extends('layouts.app')

@section('title', 'Transaksi - Itungin')

@section('content')
    <style>
        /* Kartu Ringkasan Atas */
        .summary-card {
            border: 1px solid #f3f4f6;
            border-radius: 20px;
            padding: 1.5rem 2rem;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.01);
        }
        .summary-title {
            font-size: 0.85rem;
            color: #6b7280;
            font-weight: 600;
        }
        
        /* Utilitas Warna Kustom */
        .text-success-custom { color: #059669 !important; }
        .text-danger-custom { color: #dc2626 !important; }
        .bg-success-light { background-color: #d1fae5; }
        .bg-danger-light { background-color: #fee2e2; }

        /* Tombol Tambah Transaksi */
        .btn-primary-custom {
            background-color: #0d52c6;
            color: #ffffff;
            border: none;
            transition: background-color 0.2s;
        }
        .btn-primary-custom:hover {
            background-color: #0a42a0;
            color: #ffffff;
        }

        /* Toggle Filter */
        .filter-toggle {
            background-color: #f3f4f6;
            border-radius: 50px;
            padding: 4px;
            display: inline-flex;
        }
        .filter-toggle .btn {
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 6px 20px;
            color: #6b7280;
            border: none;
        }
        .filter-toggle .btn.active {
            background-color: #ffffff;
            color: #111827;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        /* Kontainer Daftar Transaksi */
        .transaction-list-container {
            background-color: #ffffff;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }
        
        /* Item Baris Transaksi */
        .transaction-item {
            display: flex;
            align-items: center;
            padding: 1.25rem;
            border: 1px solid #f3f4f6;
            border-radius: 16px;
            margin-bottom: 1rem;
            transition: all 0.2s;
        }
        .transaction-item:hover {
            border-color: #e5e7eb;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }
        .transaction-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-right: 1.25rem;
        }

        /* --- Modal Styling --- */
        .custom-modal-content {
            border-radius: 24px;
            border: none;
            padding: 1rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .modal-header-custom {
            border-bottom: none;
            padding-bottom: 0;
        }
        .btn-close-custom {
            background-size: 0.8rem;
            opacity: 0.5;
        }
        
        /* Toggle Tipe Transaksi dalam Modal */
        .type-toggle {
            background-color: #f3f4f6;
            border-radius: 12px;
            padding: 4px;
            display: flex;
        }
        .type-toggle .btn {
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 8px 0;
            flex: 1;
            color: #6b7280;
            border: none;
            background: transparent;
        }
        .type-toggle .btn.active {
            background-color: #ffffff;
            color: #111827;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        /* Form Inputs Modal */
        .form-control-modal {
            background-color: #f8faff;
            border: 1px solid transparent;
            color: #111827;
            padding: 12px 16px;
            font-size: 0.95rem;
            border-radius: 12px;
            font-weight: 500;
        }
        .form-control-modal:focus {
            box-shadow: 0 0 0 4px rgba(13, 82, 198, 0.1);
            border-color: #0d52c6;
            background-color: #ffffff;
        }
        
        /* Tombol Aksi Modal */
        .btn-modal-action {
            border-radius: 50px;
            padding: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
        }
        .btn-light-custom {
            background-color: #eef2ff;
            color: #4f46e5;
            transition: all 0.2s;
        }
        .btn-light-custom:hover {
            background-color: #e0e7ff;
        }
    </style>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="alert alert-danger" style="border-radius: 12px;">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="page-title">Transaksi</h1>
            <p class="page-subtitle m-0">Monitoring your financial flow with editorial precision.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary-custom text-white d-flex align-items-center px-4 shadow-sm" style="border-radius: 50px; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#modalTambahTransaksi">
                <i class="bi bi-plus-circle me-2"></i> Tambah Transaksi
            </button>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="summary-title">Total Pemasukan</span>
                    <i class="bi bi-arrow-up-right text-success-custom fw-bold"></i>
                </div>
                <h2 class="fw-bold text-success-custom m-0" style="font-size: 2.2rem; letter-spacing: -1px;">
                    Rp {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}
                </h2>
            </div>
        </div>

        <div class="col-md-6">
            <div class="summary-card">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="summary-title">Total Pengeluaran</span>
                    <i class="bi bi-arrow-down-right text-danger-custom fw-bold"></i>
                </div>
                <h2 class="fw-bold text-danger-custom m-0" style="font-size: 2.2rem; letter-spacing: -1px;">
                    Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}
                </h2>
            </div>
        </div>
    </div>

    <div class="transaction-list-container">
        
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
            <h5 class="fw-bold m-0" style="color: #111827;">Daftar Transaksi</h5>
            
            <div class="filter-toggle">
                <a href="{{ route('transaksi', ['filter' => 'semua']) }}" 
                   class="btn text-decoration-none {{ (!isset($filter) || $filter === 'semua') ? 'active' : '' }}">
                   Semua
                </a>
                
                <a href="{{ route('transaksi', ['filter' => 'pemasukan']) }}" 
                   class="btn text-decoration-none {{ (isset($filter) && $filter === 'pemasukan') ? 'active' : '' }}">
                   Pemasukan
                </a>
                
                <a href="{{ route('transaksi', ['filter' => 'pengeluaran']) }}" 
                   class="btn text-decoration-none {{ (isset($filter) && $filter === 'pengeluaran') ? 'active' : '' }}">
                   Pengeluaran
                </a>
            </div>
        </div>

        @forelse ($transactions as $trx)
            <div class="transaction-item">
                <div class="transaction-icon {{ $trx->tipe_transaksi == 'pemasukan' ? 'bg-success-light text-success-custom' : 'bg-danger-light text-danger-custom' }}">
                    <i class="bi {{ $trx->tipe_transaksi == 'pemasukan' ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow' }}"></i>
                </div>
                
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1" style="color: #111827;">{{ $trx->deskripsi }}</h6>
                    <small style="color: #9ca3af; font-weight: 500;">
                        {{ $trx->kategori }} • {{ \Carbon\Carbon::parse($trx->tanggal)->translatedFormat('d F Y') }}
                    </small>
                </div>
                
                <div class="fw-bold {{ $trx->tipe_transaksi == 'pemasukan' ? 'text-success-custom' : 'text-danger-custom' }}" style="font-size: 1.1rem;">
                    {{ $trx->tipe_transaksi == 'pemasukan' ? '+' : '-' }}Rp {{ number_format($trx->jumlah, 0, ',', '.') }}
                </div>

                <div class="d-flex gap-2 ms-4">
                    <button type="button" class="btn btn-sm btn-light text-primary btn-edit-trx" style="border-radius: 8px;"
                        data-id="{{ $trx->id }}"
                        data-tipe="{{ $trx->tipe_transaksi }}"
                        data-jumlah="{{ $trx->jumlah }}"
                        data-kategori="{{ $trx->kategori }}"
                        data-deskripsi="{{ $trx->deskripsi }}"
                        data-tanggal="{{ \Carbon\Carbon::parse($trx->tanggal)->format('Y-m-d') }}"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEditTransaksi">
                        <i class="bi bi-pencil-fill"></i>
                    </button>

                    <form action="{{ route('transaksi.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-light text-danger" style="border-radius: 8px;">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <p class="text-muted">Belum ada data transaksi ditemukan.</p>
            </div>
        @endforelse

    </div>

    <div class="modal fade" id="modalTambahTransaksi" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal-content">
                
                <div class="modal-header modal-header-custom align-items-start">
                    <div>
                        <h4 class="fw-bold mb-1" id="modalLabel" style="color: #111827;">Tambah Transaksi Baru</h4>
                        <p class="text-muted small m-0">Masukkan detail transaksi Anda</p>
                    </div>
                    <button type="button" class="btn-close btn-close-custom mt-1" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body pt-4">
                    <form action="{{ route('transaksi.store') }}" method="POST" id="formTransaksi">
                        @csrf
                        
                        <input type="hidden" name="tipe_transaksi" id="inputTipeTransaksi" value="pemasukan">

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">Tipe Transaksi</label>
                            <div class="type-toggle">
                                <button type="button" id="btnPemasukan" class="btn active">Pemasukan</button>
                                <button type="button" id="btnPengeluaran" class="btn">Pengeluaran</button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">Jumlah</label>
                            <input type="number" name="jumlah" class="form-control form-control-modal" placeholder="50000" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">Kategori</label>
                            <select name="kategori" id="selectKategori" class="form-select form-control-modal" required>
                                <option value="" disabled selected>Pilih kategori</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">Deskripsi</label>
                            <input type="text" name="deskripsi" class="form-control form-control-modal" placeholder="Contoh: Gaji Bulanan" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-dark">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control form-control-modal text-muted" id="inputTanggalAdd" required>
                        </div>

                        <div class="row g-2 mt-2">
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary-custom w-100 btn-modal-action">Simpan</button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-light-custom w-100 btn-modal-action" data-bs-dismiss="modal">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditTransaksi" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal-content">
                
                <div class="modal-header modal-header-custom align-items-start">
                    <div>
                        <h4 class="fw-bold mb-1" id="modalEditLabel" style="color: #111827;">Edit Transaksi</h4>
                        <p class="text-muted small m-0">Perbarui detail transaksi Anda</p>
                    </div>
                    <button type="button" class="btn-close btn-close-custom mt-1" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body pt-4">
                    <form action="" method="POST" id="formEditTransaksi">
                        @csrf
                        @method('PUT')
                        
                        <input type="hidden" name="tipe_transaksi" id="editTipeTransaksi">

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">Tipe Transaksi</label>
                            <div class="type-toggle">
                                <button type="button" id="btnEditPemasukan" class="btn">Pemasukan</button>
                                <button type="button" id="btnEditPengeluaran" class="btn">Pengeluaran</button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">Jumlah</label>
                            <input type="number" name="jumlah" id="editJumlah" class="form-control form-control-modal" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">Kategori</label>
                            <select name="kategori" id="selectEditKategori" class="form-select form-control-modal" required>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">Deskripsi</label>
                            <input type="text" name="deskripsi" id="editDeskripsi" class="form-control form-control-modal" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-dark">Tanggal</label>
                            <input type="date" name="tanggal" id="editTanggal" class="form-control form-control-modal text-muted" required>
                        </div>

                        <div class="row g-2 mt-2">
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary-custom w-100 btn-modal-action">Simpan Perubahan</button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-light-custom w-100 btn-modal-action" data-bs-dismiss="modal">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. Data Kategori Master
        const daftarKategori = {
            pemasukan: ['Gajian', 'Bonus', 'Deviden', 'Pemberian', 'Lainnya'],
            pengeluaran: ['Makanan & Minuman', 'Transportasi', 'Hiburan', 'Belanja', 'Tagihan', 'Lainnya']
        };

        // ==========================================
        // 2. LOGIKA MODAL TAMBAH TRANSAKSI
        // ==========================================
        const btnAddPemasukan = document.getElementById('btnPemasukan');
        const btnAddPengeluaran = document.getElementById('btnPengeluaran');
        const inputAddTipe = document.getElementById('inputTipeTransaksi');
        const selectAddKategori = document.getElementById('selectKategori');
        const inputTanggalAdd = document.getElementById('inputTanggalAdd');

        // Auto-fill tanggal hari ini untuk form Tambah
        if (inputTanggalAdd && !inputTanggalAdd.value) {
            inputTanggalAdd.valueAsDate = new Date();
        }

        // Fungsi Render Dropdown Tambah
        function updateAddKategori(tipe) {
            if(!selectAddKategori) return;
            selectAddKategori.innerHTML = '<option value="" disabled selected>Pilih kategori</option>';
            daftarKategori[tipe].forEach(function(kat) {
                const opt = document.createElement('option');
                opt.value = kat;
                opt.textContent = kat;
                selectAddKategori.appendChild(opt);
            });
        }

        if(btnAddPemasukan && btnAddPengeluaran) {
            updateAddKategori('pemasukan'); // Default awal

            btnAddPemasukan.addEventListener('click', function() {
                this.classList.add('active');
                btnAddPengeluaran.classList.remove('active');
                inputAddTipe.value = 'pemasukan';
                updateAddKategori('pemasukan');
            });

            btnAddPengeluaran.addEventListener('click', function() {
                this.classList.add('active');
                btnAddPemasukan.classList.remove('active');
                inputAddTipe.value = 'pengeluaran';
                updateAddKategori('pengeluaran');
            });
        }

        // ==========================================
        // 3. LOGIKA MODAL EDIT TRANSAKSI
        // ==========================================
        const editButtons = document.querySelectorAll('.btn-edit-trx');
        const formEdit = document.getElementById('formEditTransaksi');
        const inputEditTipe = document.getElementById('editTipeTransaksi');
        const btnEditPemasukan = document.getElementById('btnEditPemasukan');
        const btnEditPengeluaran = document.getElementById('btnEditPengeluaran');
        const selectEditKategori = document.getElementById('selectEditKategori');

        // Fungsi Render Dropdown Edit
        function updateEditKategori(tipe, selectedKategori = '') {
            if(!selectEditKategori) return;
            selectEditKategori.innerHTML = '';
            daftarKategori[tipe].forEach(function(kat) {
                const opt = document.createElement('option');
                opt.value = kat;
                opt.textContent = kat;
                if(kat === selectedKategori) opt.selected = true; // Set opsi lama
                selectEditKategori.appendChild(opt);
            });
        }

        if(editButtons.length > 0) {
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const tipe = this.getAttribute('data-tipe');
                    const kategori = this.getAttribute('data-kategori');
                    
                    // Update Action Form URL (penting untuk Update method)
                    formEdit.action = `/transaksi/${id}`;
                    
                    // Isi value input teks
                    document.getElementById('editJumlah').value = this.getAttribute('data-jumlah');
                    document.getElementById('editDeskripsi').value = this.getAttribute('data-deskripsi');
                    document.getElementById('editTanggal').value = this.getAttribute('data-tanggal');
                    inputEditTipe.value = tipe;

                    // Atur class Active pada Toggle
                    if(tipe === 'pemasukan') {
                        btnEditPemasukan.classList.add('active');
                        btnEditPengeluaran.classList.remove('active');
                    } else {
                        btnEditPengeluaran.classList.add('active');
                        btnEditPemasukan.classList.remove('active');
                    }

                    // Render list kategori dan set select-nya
                    updateEditKategori(tipe, kategori);
                });
            });

            // Toggle Tipe dalam form Edit
            if(btnEditPemasukan && btnEditPengeluaran) {
                btnEditPemasukan.addEventListener('click', function() {
                    this.classList.add('active');
                    btnEditPengeluaran.classList.remove('active');
                    inputEditTipe.value = 'pemasukan';
                    updateEditKategori('pemasukan');
                });

                btnEditPengeluaran.addEventListener('click', function() {
                    this.classList.add('active');
                    btnEditPemasukan.classList.remove('active');
                    inputEditTipe.value = 'pengeluaran';
                    updateEditKategori('pengeluaran');
                });
            }
        }
    });
</script>
@endpush