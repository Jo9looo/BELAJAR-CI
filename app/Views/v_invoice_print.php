<?= $this->extend('layout_clear') ?>
<?= $this->section('content') ?>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h3 class="mb-0 text-primary fw-bold">NOTA PEMBELIAN</h3>
            <span class="text-muted">#Invoice: <?= $transaction['id'] ?></span>
        </div>
        <div class="text-end">
            <h5 class="fw-bold mb-0">Toko Online</h5>
            <small class="text-muted">Tanggal: <?= date('d-m-Y H:i', strtotime($transaction['created_at'])) ?></small>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-6">
            <h6 class="text-secondary fw-bold border-bottom pb-1">Pelanggan:</h6>
            <table class="table table-sm table-borderless mb-0">
                <tr>
                    <td style="width: 100px;" class="text-muted">Username</td>
                    <td>: <?= esc($transaction['username']) ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Status</td>
                    <td>: <strong><?= ($transaction['status'] == "1") ? 'Sudah Selesai' : 'Belum Selesai' ?></strong></td>
                </tr>
            </table>
        </div>
        <div class="col-6">
            <h6 class="text-secondary fw-bold border-bottom pb-1">Alamat Pengiriman:</h6>
            <p class="mb-0 fw-semibold"><?= esc($transaction['username']) ?></p>
            <p class="text-muted mb-0" style="white-space: pre-wrap;"><?= esc($transaction['alamat']) ?></p>
        </div>
    </div>

    <div class="card border-0 mb-4">
        <div class="card-body p-0">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 50px;">#</th>
                        <th scope="col">Nama Produk</th>
                        <th scope="col" class="text-end" style="width: 150px;">Harga Satuan</th>
                        <th scope="col" class="text-center" style="width: 100px;">Jumlah</th>
                        <th scope="col" class="text-end" style="width: 180px;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $calculatedSubtotal = 0;
                    if (!empty($products)) :
                        foreach ($products as $index => $item) :
                            $calculatedSubtotal += $item['subtotal_harga'];
                    ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <span class="text-dark fw-semibold"><?= esc($item['nama']) ?></span>
                                </td>
                                <td class="text-end"><?= number_to_currency($item['harga'], 'IDR') ?></td>
                                <td class="text-center"><?= $item['jumlah'] ?></td>
                                <td class="text-end fw-semibold"><?= number_to_currency($item['subtotal_harga'], 'IDR') ?></td>
                            </tr>
                    <?php 
                        endforeach;
                    endif; 
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row justify-content-end">
        <div class="col-6">
            <div class="border p-3 rounded">
                <h6 class="fw-bold border-bottom pb-2 mb-2">Ringkasan Pembayaran</h6>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Total Harga (Subtotal)</td>
                        <td class="text-end"><?= number_to_currency($calculatedSubtotal, 'IDR') ?></td>
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
                            <td class="text-muted">Voucher (<?= esc($transaction['voucher_code']) ?>)</td>
                            <td class="text-end text-success">-<?= number_to_currency($transaction['diskon_voucher'], 'IDR') ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="text-muted">Ongkos Kirim</td>
                        <td class="text-end text-danger">+<?= number_to_currency($transaction['ongkir'], 'IDR') ?></td>
                    </tr>
                    <tr class="border-top">
                        <td class="pt-2"><h5 class="fw-bold mb-0 text-dark">Total Bayar</h5></td>
                        <td class="pt-2 text-end"><h5 class="fw-bold mb-0 text-primary"><?= number_to_currency($transaction['total_harga'], 'IDR') ?></h5></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    window.onload = function() {
        window.print();
    }
</script>

<?= $this->endSection() ?>
