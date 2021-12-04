<form id="updateForm">
    <div class="form-group row">
        <label for="edit_tgl_pesanan" class="col-sm-3 col-form-label">Tgl Pesanan</label>
        <div class="col-sm-9">
            <input type="date" class="form-control" name="tgl_pesanan" id="edit_tgl_pesanan" value="<?= $pelanggan->tgl_pesanan ?>">
        </div>
    </div>

    <div class="form-group row">
        <label for="edit_nama" class="col-sm-3 col-form-label">Nama Lengkap</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="nama" id="edit_nama" value="<?= $pelanggan->nama ?>">
        </div>
    </div>

    <div class="form-group row">
        <label for="edit_nik" class="col-sm-3 col-form-label">NIK</label>
        <div class="col-sm-9">
            <input type="number" class="form-control" name="nik" id="edit_nik" value="<?= $pelanggan->nik ?>">
        </div>
    </div>

    <div class="form-group row">
        <label for="edit_hp" class="col-sm-3 col-form-label">HP</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="hp" id="edit_hp" value="<?= $pelanggan->hp ?>">
        </div>
    </div>

    <div class="form-group row">
        <label for="edit_email" class="col-sm-3 col-form-label">Email</label>
        <div class="col-sm-9">
            <input type="email" class="form-control" name="email" id="edit_email" value="<?= $pelanggan->email ?>">
        </div>
    </div>

    <div class="form-group row">
        <label for="edit_alamat" class="col-sm-3 col-form-label">Alamat</label>
        <div class="col-sm-9">
            <textarea id="edit_alamat" name="alamat" class="form-control"><?= $pelanggan->alamat ?></textarea>
        </div>
    </div>

    <br>

    <table width="100%" class="table" id="masterDetail" cellpadding="5" cellspacing="0" style="font-size: 15px;">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Qty</th>
            </tr>
        </thead>
        <tbody id="clearBody">
            <?php foreach($pelanggan->pesanan as $pesanan): ?>
            <tr>
                <td>
                    <input type="text" name="nama_produk[]" value="<?= $pesanan->nama_produk ?>" id="edit_nama_produk" class="form-control" required
                        autocomplete="off">
                </td>
                <td>
                    <input type="text" name="harga[]" value="<?= $pesanan->harga ?>" id="edit_harga" class="form-control im-currency" required
                        autocomplete="off">
                </td>
                <td>
                    <input type="text" name="qty[]" value="<?= $pesanan->qty ?>" id="edit_qty" class="form-control im-numeric" required
                        autocomplete="off">
                </td>
                <td>
                    <a href="javascript:">
                        <span class="ui-icon ui-icon-trash" style="margin-top: 10px;"
                            onclick="$(this).parent().parent().parent().remove()"></span>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3"></td>
                <td>
                    <a href="javascript:" onclick="addRow();setNumericFormat()">
                        <span class="glyphicon glyphicon-plus"></span>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</form>

<script>

$(document).ready(function() {
    setNumericFormat()
})

function addRow() {
    $('#masterDetail tbody tr').last().before(`
        <tr>
            <td>
                <input type="text" name="nama_produk[]" id="edit_nama_produk" class="form-control" required autocomplete="off">
            </td>
            <td>
                <input type="text" name="harga[]" id="edit_harga" class="form-control im-currency" required autocomplete="off">
            </td>
            <td>
                <input type="text" name="qty[]" id="edit_qty" class="form-control im-numeric" required autocomplete="off">
            </td>
            <td>
                <a href="javascript:">
                    <span class="ui-icon ui-icon-trash" style="margin-top: 10px;" onclick="$(this).parent().parent().parent().remove()"></span>
                </a>
            </td>
        </tr>
    `)
}

function setNumericFormat() {
    //numeric format
    $('.im-numeric').keypress(function(e){
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          return false;
        }
    })

    //currency format
    $('.im-currency').inputmask('integer', {
        alias: 'numeric',
        groupSeparator: '.',
        autoGroup: true,
        digitsOptional: false,
        allowMinus: false,
        placeholder: '',
    })
}

</script>