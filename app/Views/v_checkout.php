<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-6">
        <?= form_open('buy', 'class="row g-3"') ?>

<?= form_hidden('username', session()->get('username')) ?>
<?= form_input([
    'type'  => 'hidden',
    'name'  => 'total_harga',
    'id'    => 'total_harga',
    'value' => ''
]) ?>

<div class="col-12">
    <?= form_label('Nama', 'nama', ['class' => 'form-label']) ?>
    <?= form_input([
        'name'     => 'nama',
        'id'       => 'nama',
        'class'    => 'form-control',
        'value'    => session()->get('username'),
        'readonly' => true]) ?>
</div>
<div class="col-12">
    <?= form_label('Alamat', 'alamat', ['class' => 'form-label']) ?>
    <?= form_input([
        'name'  => 'alamat',
        'id'    => 'alamat',
        'class' => 'form-control']) ?>
</div> 
<div class="col-12"> 
    <?= form_label('Kelurahan', 'kelurahan', ['class' => 'form-label']) ?>
<?= form_dropdown('kelurahan', [], '', ['id' => 'kelurahan', 'class' => 'form-select']) ?>

</div>
<div class="col-12"> 
    <?= form_label('Layanan', 'layanan', ['class' => 'form-label']) ?> 
    <?= form_dropdown('layanan', [], '', ['id' => 'layanan', 'class' => 'form-select', 'disabled' => true]) ?>

</div>
<div class="col-12">
    <?= form_label('Ongkir', 'ongkir', ['class' => 'form-label']) ?>
    <?= form_input([
        'name'     => 'ongkir',
        'id'       => 'ongkir',
        'class'    => 'form-control',
        'readonly' => true]) ?>
</div>
<div class="col-12">
    <?= form_label('Kode Voucher', 'voucher_code', ['class' => 'form-label']) ?>
    <?= form_input([
        'name'        => 'voucher_code',
        'id'          => 'voucher_code',
        'class'       => 'form-control',
        'placeholder' => 'Masukkan kode voucher']) ?>
    <small class="text-muted">Tersedia: FLASH10, FLASH15, MEMBER20</small>
</div>
<div class="col-12">
    <?= form_submit(
        'submit',
        'Buat Pesanan',
        ['class' => 'btn btn-primary']) ?>
</div>

<?= form_close() ?> 
    </div>
    <div class="col-lg-6">
        <table class="table">
  <thead>
      <tr>
          <th scope="col">Nama</th>
          <th scope="col">Harga</th>
          <th scope="col">Jumlah</th>
          <th scope="col">Sub Total</th>
      </tr>
  </thead>
  <tbody>
      <?php 
      if (!empty($items)) :
          foreach ($items as $index => $item) :
      ?>
              <tr>
                  <td><?= $item['name'] ?></td>
                  <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                  <td><?= $item['qty'] ?></td>
                  <td><?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
              </tr>
      <?php
          endforeach;
      endif;
      ?>
      <tr>
          <td colspan="2"></td>
          <td>Subtotal</td>
          <td>IDR <?= number_format($total, 0, ',', '.') ?></td>
      </tr>
      <tr class="text-danger" id="row_voucher" style="display: none;">
          <td colspan="2"></td>
          <td>
              Diskon Voucher<br>
              <small id="voucher_percentage" class="text-danger">(0%)</small>
          </td>
          <td>-IDR <span id="voucher_discount">0</span></td>
      </tr>
      <tr>
          <td colspan="2"></td>
          <td>PPN (11%)</td>
          <td>IDR <span id="ppn_amount">0</span></td>
      </tr>
      <tr>
          <td colspan="2"></td>
          <td>Biaya Admin</td>
          <td>IDR <span id="admin_amount">0</span></td>
      </tr>
      <tr class="fw-bold">
          <td colspan="2"></td>
          <td>
              Subtotal<br>
              <small class="text-success">(+PPN+Admin-Voucher)</small>
          </td>
          <td>IDR <span id="subtotal_calc">0</span></td>
      </tr>
      <tr class="fw-bold">
          <td colspan="2"></td>
          <td>
              Grand Total<br>
              <small class="text-muted">(incl. Ongkir)</small>
          </td>
          <td><span id="total">IDR 0</span></td>
      </tr>
  </tbody>
</table>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
$(document).ready(function() {
      let ongkir = 0;
let subtotal = <?= $total ?>;
hitungTotal();

function formatCurrency(val) {
    return val.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
}

function hitungTotal() {
    let voucherCode = $("#voucher_code").val().trim().toUpperCase();
    let discountPercent = 0;
    if (voucherCode === "FLASH10") {
        discountPercent = 10;
    } else if (voucherCode === "FLASH15") {
        discountPercent = 15;
    } else if (voucherCode === "MEMBER20") {
        discountPercent = 20;
    }

    let discountAmount = subtotal * (discountPercent / 100);
    let ppnAmount = subtotal * 0.11;

    let adminPercent = 0.006;
    if (subtotal > 20000000 && subtotal <= 40000000) {
        adminPercent = 0.008;
    } else if (subtotal > 40000000) {
        adminPercent = 0.010;
    }
    let adminAmount = subtotal * adminPercent;

    let subtotalCalc = subtotal + ppnAmount + adminAmount - discountAmount;
    let total = subtotalCalc + ongkir;

    if (discountPercent > 0) {
        $("#voucher_percentage").text(`(${discountPercent}%)`);
        $("#voucher_discount").text(formatCurrency(discountAmount));
        $("#row_voucher").show();
    } else {
        $("#row_voucher").hide();
    }

    $("#ongkir").val(ongkir);
    $("#ppn_amount").text(formatCurrency(ppnAmount));
    $("#admin_amount").text(formatCurrency(adminAmount));
    $("#subtotal_calc").text(formatCurrency(subtotalCalc));
    $("#total").text(`IDR ${formatCurrency(total)}`);
    $("#total_harga").val(total);
}

$("#voucher_code").on('input', function() {
    hitungTotal();
});


    $('#kelurahan').select2({
        placeholder: 'Cari kelurahan tujuan (Ketik min. 3 karakter)',
        minimumInputLength: 3,
        ajax: {
            url: '<?= base_url('ajax/destinations') ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term 
                };
            },
            processResults: function (data) {
                return {
                    results: data.results
                };
            },
            cache: true
        }
    });
$("#kelurahan").on('change', function () {
    let id_kelurahan = $(this).val();
    $("#layanan").html('<option value="">Memuat layanan...</option>').prop('disabled', true);
    ongkir = 0;
    hitungTotal(); 
    if (id_kelurahan) {
        $.ajax({
            url: "<?= site_url('ajax/costs') ?>", 
            dataType: "json",
            data: {
                destination: id_kelurahan
            },
            success: function (data) { 
                let options = '<option value="">-- Pilih Layanan --</option>';
                if (data.length > 0) {
                    data.forEach(function (item) {
                        options += `<option value="${item.cost}">${item.service} - ${item.description} (IDR ${parseInt(item.cost).toLocaleString('id-ID')}) : estimasi ${item.etd}</option>`;
                    });
                    $("#layanan").html(options).prop('disabled', false);
                } else {
                    $("#layanan").html('<option value="">Layanan tidak tersedia</option>').prop('disabled', true);
                }
            },
            error: function() {
                $("#layanan").html('<option value="">Gagal memuat layanan</option>').prop('disabled', true);
            }
        });
    }
});
$("#layanan").on('change', function() {
    ongkir = parseFloat($(this).val()) || 0;
    hitungTotal();
});


});
</script>
<?= $this->endSection() ?>
