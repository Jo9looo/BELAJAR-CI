<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 text-primary fw-bold">Nota Belanja #<?= $transaction['id'] ?></h4>
            <small class="text-muted">Tanggal: <?= date('d-m-Y H:i', strtotime($transaction['created_at'])) ?></small>
        </div>
        <div class="d-print-none">
            <a href="<?= base_url('transaksi/invoice/print/' . $transaction['id']) ?>" target="_blank" class="btn btn-outline-primary btn-sm me-2">
                <i class="bi bi-printer-fill me-1"></i> Cetak Nota
            </a>
            <a href="<?= base_url('history') ?>" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Riwayat Belanja
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="card shadow-sm border-light h-100">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0 text-secondary fw-semibold">Informasi Pelanggan</h6>
                </div>
                <div class="card-body pt-3">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" style="width: 120px;">Username</td>
                            <td>: <strong class="text-dark"><?= esc($transaction['username']) ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status Pesanan</td>
                            <td>: 
                                <?= ($transaction['status'] == "1")
                                    ? '<span class="badge bg-success">Sudah Selesai</span>'
                                    : '<span class="badge bg-warning text-dark">Belum Selesai</span>' ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-light h-100">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0 text-secondary fw-semibold">Alamat Pengiriman</h6>
                </div>
                <div class="card-body pt-3">
                    <p class="text-dark mb-0 fw-semibold"><?= esc($transaction['username']) ?></p>
                    <p class="text-muted mb-0 mt-1" style="white-space: pre-wrap;"><?= esc($transaction['alamat']) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-light mb-4">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0 text-secondary fw-semibold">Rincian Pembelian</h6>
        </div>
        <div class="card-body px-0 py-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-4" style="width: 80px;">Foto</th>
                            <th scope="col">Nama Produk</th>
                            <th scope="col" class="text-end" style="width: 150px;">Harga Satuan</th>
                            <th scope="col" class="text-center" style="width: 100px;">Jumlah</th>
                            <th scope="col" class="text-end pe-4" style="width: 180px;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $calculatedSubtotal = 0;
                        if (!empty($products)) :
                            foreach ($products as $item) :
                                $calculatedSubtotal += $item['subtotal_harga'];
                        ?>
                                <tr>
                                    <td class="ps-4">
                                        <?php
                                        $imagePath = FCPATH . 'img/' . $item['foto'];
                                        if (!empty($item['foto']) && file_exists($imagePath)) :
                                        ?>
                                            <img src="<?= base_url('img/' . $item['foto']) ?>" width="50" class="img-thumbnail rounded">
                                        <?php else: ?>
                                            <div class="bg-light text-muted d-flex align-items-center justify-content-center rounded" style="width: 50px; height: 50px;">
                                                <i class="bi bi-image" style="font-size: 20px;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <h6 class="mb-0 text-dark fw-semibold"><?= esc($item['nama']) ?></h6>
                                    </td>
                                    <td class="text-end"><?= number_to_currency($item['harga'], 'IDR') ?></td>
                                    <td class="text-center"><?= $item['jumlah'] ?></td>
                                    <td class="text-end pe-4 fw-semibold"><?= number_to_currency($item['subtotal_harga'], 'IDR') ?></td>
                                </tr>
                        <?php 
                            endforeach;
                        endif; 
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row justify-content-end">
        <div class="col-lg-5 col-md-7">
            <div class="card shadow-sm border-light">
                <div class="card-body p-4">
                    <h6 class="card-title p-0 mb-3 text-secondary fw-semibold border-bottom pb-2">Ringkasan Pembayaran</h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted">Total Harga (Subtotal)</td>
                            <td class="text-end fw-medium"><?= number_to_currency($calculatedSubtotal, 'IDR') ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">PPN (11%)</td>
                            <td class="text-end text-danger">+<?= number_to_currency($transaction['ppn'], 'IDR') ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Biaya Administrasi</td>
                            <td class="text-end text-danger">+<?= number_to_currency($transaction['biaya_admin'], 'IDR') ?></td>
                        </tr>
                        <?php if (!empty($transaction['voucher_code'])): ?>
                            <tr>
                                <td class="text-muted">
                                    Voucher <span class="badge bg-light text-primary border border-primary"><?= esc($transaction['voucher_code']) ?></span>
                                </td>
                                <td class="text-end text-success">-<?= number_to_currency($transaction['diskon_voucher'], 'IDR') ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td class="text-muted">Ongkos Kirim</td>
                            <td class="text-end text-danger">+<?= number_to_currency($transaction['ongkir'], 'IDR') ?></td>
                        </tr>
                        <tr class="border-top">
                            <td class="pt-3"><h5 class="mb-0 text-dark fw-bold">Total Bayar</h5></td>
                            <td class="pt-3 text-end"><h5 class="mb-0 text-primary fw-bold"><?= number_to_currency($transaction['total_harga'], 'IDR') ?></h5></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
