@extends('layouts.app')

@section('title', 'Targetku - Itungin')

@section('content')
    <style>
        .target-card {
            border: none;
            border-radius: 20px;
            padding: 1.5rem 1.75rem;
            background-color: #ffffff;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
        }

        .target-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.1);
        }

        .badge-kategori {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 5px;
            background-color: #d1fae5;
            color: #006d3c;
        }

        .btn-primary-custom {
            background-color: #0d52c6;
            color: #ffffff;
            border: none;
            transition: background-color 0.2s;
        }

        .btn-primary-custom:hover {
            background-color: #0a42a0;
        }

        .action-icons {
            font-size: 1.25rem;
            color: #9ca3af;
            cursor: pointer;
        }

        .action-icons:hover {
            color: #6b7280;
        }

        .progress-container {
            height: 10px;
            background-color: #e5e7eb;
            border-radius: 9999px;
            overflow: hidden;
            margin: 1.25rem 0 1rem;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(to right, #3b82f6, #10b981);
            border-radius: 9999px;
            transition: width 0.8s ease;
        }

        .info-box {
            background-color: #f1f3ff;
            border-radius: 5px;
            padding: 1rem 1.25rem;
            margin-top: 1rem;
        }

        .info-grid {
            display: flex;
            flex-direction: row;
            gap: 0.5rem;
            justify-content: space-between;
            text-align: center;
        }

        .info-label {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 1.05rem;
            font-weight: 500;
            color: #0f172a;
        }

        .kekurangan .info-value {
            color: #ef4444;
        }

        .terkumpul .info-value{
            color: #10b981;
        }

        .btn-tambah-dana {
            background-color: #dbeafe;
            color: #1e40af;
            border: none;
            border-radius: 9999px;
            padding: 14px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            margin-top: 1.25rem;
            transition: all 0.2s;
        }

        .btn-tambah-dana:hover {
            background-color: #bfdbfe;
            transform: translateY(-1px);
        }

        .btn-tambah-dana i {
            margin-right: 8px;
        }

        .custom-modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .form-control-modal {
            background-color: #f8faff;
            border: 1px solid transparent;
            padding: 12px 16px;
            border-radius: 12px;
        }

        .form-control-modal:focus {
            box-shadow: 0 0 0 4px rgba(13, 82, 198, 0.1);
            border-color: #0d52c6;
            background-color: #ffffff;
        }

        .btn-light-custom {
            background-color: #eef2ff;
            color: #4f46e5;
        }
    </style>

    @php
        if (!function_exists('formatRupiahShort')) {
            function formatRupiahShort($angka)
            {
                if (!$angka || $angka == 0) {
                    return '0';
                }
                $angka = (int) $angka;

                if ($angka >= 1_000_000) {
                    $jt = $angka / 1_000_000;
                    return (floor($jt) == $jt ? number_format($jt, 0) : number_format($jt, 1)) . 'jt';
                } elseif ($angka >= 1_000) {
                    return round($angka / 1_000) . 'rb';
                }
                return number_format($angka, 0, ',', '.');
            }
        }
    @endphp

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px;">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="page-title">Targetku</h1>
            <p class="page-subtitle m-0">Curation your financial milestones with editorial precision.</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="bg-white px-4 py-2 rounded-pill shadow-sm d-flex align-items-center">
                <i class="bi bi-wallet2 text-primary me-2"></i>
                <span class="fw-bold">Rp {{ number_format($user->saldo ?? 0, 0, ',', '.') }}</span>
            </div>
            <button type="button" class="btn btn-primary-custom text-white d-flex align-items-center px-4 shadow-sm"
                style="border-radius: 50px; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#modalTambahTarget">
                <i class="bi bi-plus-circle me-2"></i> Tambah Target
            </button>
        </div>
    </div>

    <div class="row g-4">
        @forelse ($targets as $target)
            <div class="col-lg-4 col-md-6">
                <div class="target-card">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge-kategori">
                            {{ $target->kategori ?? 'Tabungan' }}
                        </span>
                        <div class="d-flex gap-3">
                            <i class="bi bi-pencil action-icons" data-bs-toggle="modal" data-bs-target="#modalEditTarget"
                                data-id="{{ $target->id }}" data-nama="{{ $target->nama_target }}"
                                data-target-jumlah="{{ $target->target_jumlah }}"
                                data-tanggal-target="{{ $target->tanggal_target }}"
                                data-kategori="{{ $target->kategori ?? '' }}"
                                data-deskripsi="{{ $target->deskripsi ?? '' }}"></i>

                            <form action="{{ route('targets.destroy', $target->id) }}" method="POST"
                                style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus target ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link p-0 border-0 bg-transparent">
                                    <i class="bi bi-trash action-icons text-danger"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Nama Target -->
                    <h5 class="fw-bold mb-3" style="color: #0f172a; line-height: 1.3;">
                        {{ $target->nama_target }}
                    </h5>

                    <!-- Persentase & Waktu (LOGIC SUDAH DIPERBAIKI) -->
                    @php
                        $persentase =
                            $target->target_jumlah > 0
                                ? round(($target->jumlah_terkumpul / $target->target_jumlah) * 100)
                                : 0;

                        $tanggalTarget = \Carbon\Carbon::parse($target->tanggal_target);
                        $hariTersisa = now()->diffInDays($tanggalTarget, false); // Perbaikan di sini

                        if ($hariTersisa > 0) {
                            $hariText = round($hariTersisa) . ' hari lagi';
                            $hariClass = 'text-success';
                        } elseif ($hariTersisa < 0) {
                            $hariText = round(abs($hariTersisa)) . ' hari yang lalu';
                            $hariClass = 'text-danger';
                        } else {
                            $hariText = 'Hari ini';
                            $hariClass = 'text-warning';
                        }
                    @endphp

                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <h2 class="fw-bold mb-0" style="font-size: 2.4rem; letter-spacing: -2px;">
                            {{ $persentase }}<span style="font-size: 1.1rem;">%</span>
                        </h2>
                        <small class="{{ $hariClass }} fw-medium">
                            <i class="bi bi-clock"></i> {{ $hariText }}
                        </small>
                    </div>

                    <!-- Progress Bar -->
                    <div class="progress-container">
                        <div class="progress-bar" style="width: {{ $persentase }}%"></div>
                    </div>

                    <!-- Info Box -->
                    <div class="info-box">
                        <div class="info-grid">
                            <div class="terkumpul">
                                <div class="info-label">TERKUMPUL</div>
                                <div class="info-value">{{ formatRupiahShort($target->jumlah_terkumpul) }}</div>
                            </div>
                            <div>
                                <div class="info-label">TARGET</div>
                                <div class="info-value">{{ formatRupiahShort($target->target_jumlah) }}</div>
                            </div>
                            <div class="kekurangan">
                                <div class="info-label">KEKURANGAN</div>
                                <div class="info-value">
                                    {{ formatRupiahShort(max(0, $target->target_jumlah - $target->jumlah_terkumpul)) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Tambah Dana -->
                    <button type="button" class="btn btn-tambah-dana" data-bs-toggle="modal"
                        data-bs-target="#modalTambahDana" data-id="{{ $target->id }}"
                        data-nama="{{ $target->nama_target }}">
                        <i class="bi bi-graph-up-arrow"></i> Tambah Dana
                    </button>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-piggy-bank" style="font-size: 4.5rem; color: #e2e8f0;"></i>
                <h5 class="mt-4 text-muted">Belum ada target tabungan</h5>
                <p class="text-muted">Buat target pertama Anda sekarang</p>
            </div>
        @endforelse
    </div>

    <!-- Modal Tambah Target -->
    <div class="modal fade" id="modalTambahTarget" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal-content">
                <div class="modal-header modal-header-custom">
                    <h4 class="fw-bold">Tambah Target Baru</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('targets.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Nama Target</label>
                            <input type="text" name="nama_target" class="form-control form-control-modal" required
                                placeholder="Contoh: Liburan ke Bali">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Target Jumlah</label>
                            <input type="number" name="target_jumlah" class="form-control form-control-modal" required
                                placeholder="50000000">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Tanggal Target</label>
                            <input type="date" name="tanggal_target" class="form-control form-control-modal" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Kategori (Opsional)</label>
                            <input type="text" name="kategori" class="form-control form-control-modal"
                                placeholder="Dana Darurat, Kendaraan, dll">
                        </div>

                        <div class="row g-3 mt-4">
                            <div class="col-6">
                                <button type="button" class="btn btn-light-custom w-100"
                                    data-bs-dismiss="modal">Batal</button>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary-custom w-100">Buat Target</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Dana -->
    <div class="modal fade" id="modalTambahDana" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal-content">
                <div class="modal-header modal-header-custom">
                    <h5 class="fw-bold" id="modalDanaTitle">Tambah Dana ke Target</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('targets.add-fund', ':id') }}" method="POST" id="formTambahDana">
                        @csrf
                        <input type="hidden" name="target_id" id="tambah_dana_target_id">

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Jumlah yang Ditambahkan</label>
                            <input type="number" name="jumlah_fund" class="form-control form-control-modal" required
                                placeholder="1000000">
                        </div>


                        <div class="row g-3 mt-4">
                            <div class="col-6">
                                <button type="button" class="btn btn-light-custom w-100"
                                    data-bs-dismiss="modal">Batal</button>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary-custom w-100">Tambahkan Dana</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Target -->
    <div class="modal fade" id="modalEditTarget" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal-content">
                <div class="modal-header modal-header-custom">
                    <h4 class="fw-bold">Edit Target</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" id="formEditTarget">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="edit_id">

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Nama Target</label>
                            <input type="text" name="nama_target" id="edit_nama_target"
                                class="form-control form-control-modal" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Target Jumlah</label>
                            <input type="number" name="target_jumlah" id="edit_target_jumlah"
                                class="form-control form-control-modal" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Tanggal Target</label>
                            <input type="date" name="tanggal_target" id="edit_tanggal_target"
                                class="form-control form-control-modal" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Kategori (Opsional)</label>
                            <input type="text" name="kategori" id="edit_kategori"
                                class="form-control form-control-modal">
                        </div>

                        <div class="row g-3 mt-4">
                            <div class="col-6">
                                <button type="button" class="btn btn-light-custom w-100"
                                    data-bs-dismiss="modal">Batal</button>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary-custom w-100">Simpan Perubahan</button>
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

            // Modal Edit Target
            const editModal = document.getElementById('modalEditTarget');
            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                document.getElementById('edit_id').value = button.getAttribute('data-id');
                document.getElementById('edit_nama_target').value = button.getAttribute('data-nama');
                document.getElementById('edit_target_jumlah').value = button.getAttribute(
                    'data-target-jumlah');
                document.getElementById('edit_tanggal_target').value = button.getAttribute(
                    'data-tanggal-target');
                document.getElementById('edit_kategori').value = button.getAttribute('data-kategori');

                document.getElementById('formEditTarget').action =
                    `/targets/${button.getAttribute('data-id')}`;
            });

            // Modal Tambah Dana
            const danaModal = document.getElementById('modalTambahDana');
            danaModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const targetId = button.getAttribute('data-id');
                const targetName = button.getAttribute('data-nama');

                document.getElementById('tambah_dana_target_id').value = targetId;
                document.getElementById('modalDanaTitle').textContent = `Tambah Dana ke "${targetName}"`;

                const form = document.getElementById('formTambahDana');
                let action = form.getAttribute('action');
                form.setAttribute('action', action.replace(':id', targetId));
            });

        });
    </script>
@endpush
