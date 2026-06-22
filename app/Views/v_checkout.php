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
          <td><?= number_to_currency($total, 'IDR') ?></td>
      </tr>
      <tr>
          <td colspan="2"></td>
          <td>Total</td>
          <td><span id="total"><?= number_to_currency($total, 'IDR') ?></span></td>
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

function hitungTotal() {
    let total = subtotal + ongkir;

    $("#ongkir").val(ongkir);
    $("#total").text(`IDR ${total.toLocaleString('id-ID')}`);
    $("#total_harga").val(total);
}


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
    // Reset layanan, ongkir, dan status dropdown
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
// 2. Deteksi saat Layanan dipilih untuk meng-update Ongkir & Total Harga
$("#layanan").on('change', function() {
    ongkir = parseFloat($(this).val()) || 0;
    hitungTotal();
});


});
</script>
<?= $this->endSection() ?>
