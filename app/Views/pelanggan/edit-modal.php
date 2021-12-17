<!-- <style type="text/css">
	input, textarea {
		text-transform: uppercase;
		/* padding: 5px; */
	}
</style> -->

<form id="updateForm">
    <div class="form-group row">
        <label for="edit_tgl_pesanan" class="col-sm-3 col-form-label">Tgl Pesanan</label>
        <div class="col-sm-9">
            <input type="text" class="form-control hasDatePicker" name="tgl_pesanan" id="edit_tgl_pesanan" value="<?= $pelanggan->tgl_pesanan ?>">
            <div id="err_tgl_pesanan" style="font-size: 10px;"></div>
        </div>
    </div>

    <div class="form-group row">
        <label for="edit_nama" class="col-sm-3 col-form-label">Nama Lengkap</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="nama" id="edit_nama" value="<?= $pelanggan->nama ?>">
            <div id="err_nama" style="font-size: 10px;"></div>
        </div>
    </div>

    <div class="form-group row">
        <label for="edit_nik" class="col-sm-3 col-form-label">NIK</label>
        <div class="col-sm-9">
            <input type="text" class="form-control nik-number" name="nik" id="edit_nik" value="<?= $pelanggan->nik ?>">
            <div id="err_nik" style="font-size: 10px;"></div>
        </div>
    </div>

    <div class="form-group row">
        <label for="edit_hp" class="col-sm-3 col-form-label">HP</label>
        <div class="col-sm-9">
            <input type="text" class="form-control hp-number" name="hp" id="edit_hp" value="<?= $pelanggan->hp ?>">
            <div id="err_hp" style="font-size: 10px;"></div>
        </div>
    </div>

    <div class="form-group row">
        <label for="edit_email" class="col-sm-3 col-form-label">Email</label>
        <div class="col-sm-9">
            <input type="email" class="form-control" name="email" id="edit_email" value="<?= $pelanggan->email ?>">
            <div id="err_email" style="font-size: 10px;"></div>
        </div>
    </div>

    <div class="form-group row">
        <label for="edit_alamat" class="col-sm-3 col-form-label">Alamat</label>
        <div class="col-sm-9">
            <textarea id="edit_alamat" name="alamat" class="form-control"><?= $pelanggan->alamat ?></textarea>
            <div id="err_alamat" style="font-size: 10px;"></div>
        </div>
    </div>

    <br>

    <table width="100%" class="table" id="masterDetail" cellpadding="5" cellspacing="0" style="font-size: 15px;">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody id="clearBody">
            <?php $total = 0; $tHarga = []; foreach($pelanggan->pesanan as $pesanan): ?>
            <?php
                $total += $pesanan->total_harga;
                $tHarga[] = (int)$pesanan->total_harga;
            ?>
            <tr>
                <td>
                    <input type="text" name="nama_produk[]" value="<?= $pesanan->nama_produk ?>" id="edit_nama_produk" class="form-control" required
                        autocomplete="off">
                </td>
                <td>
                    <input type="text" name="qty[]" value="<?= $pesanan->qty ?>" id="qty" class="form-control im-numeric" required
                        autocomplete="off" onkeyup="keyUpQty(this)">
                </td>
                <td>
                    <input type="text" name="harga[]" value="<?= $pesanan->harga ?>" id="harga" class="form-control im-currency" required
                        autocomplete="off" onkeyup="keyUpHarga(this)">
                </td>
                <td>
                    <input type="text" name="total_harga[]" id="total_harga" value="<?= $pesanan->total_harga ?>" class="form-control im-currency" readonly>
                </td>
                <td>
                    <a href="javascript:" class="btnDelItem">
                        <span class="ui-icon ui-icon-trash" style="margin-top: 10px;"
                            onclick="btnRemove($(this))"></span>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3"></td>
                <td>
                    <input type="text" name="total[]" id="total" value="<?= $total ?>" class="form-control im-currency" readonly>
                </td>
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

let timeout = null;
let qty     = 0;
let harga   = 0;
let tHarga  = JSON.parse('<?= json_encode($tHarga) ?>');

$(document).ready(function() {
    setNumericFormat()
    setDateFormat()
})

function keyUpQty(e){
    clearTimeout(timeout)
    timeout = setTimeout(function() {
        qty = e.value;
    }, 500)
}

function keyUpHarga(e){
    
    clearTimeout(timeout)
    timeout = setTimeout(function() {
        if(harga.length >= 3){
            tHarga.push(qty * harga.replace('.',''));
            upTotal()
        }
    }, 500)
}

function btnRemove(e){
    if($('.btnDelItem').length > 1){
        var rowIndex = e.parent().parent().parent()[0].rowIndex;
        tHarga.splice(rowIndex - 1, 1);
        e.parent().parent().parent().remove();
        upTotal();
    }
}

function upTotal(){
    var total = 0;
    var total_harga = document.getElementsByName('total_harga[]');
    for (let i = 0; i < total_harga.length; i++) {
        total_harga[i].value = tHarga[i];
        total = total + tHarga[i];
        document.getElementById('total').value = total;
    }
}

function addRow() {
    $('#masterDetail tbody tr').last().before(`
        <tr>
            <td>
                <input type="text" name="nama_produk[]" id="edit_nama_produk" class="form-control" required autocomplete="off">
            </td>
            <td>
                <input type="text" name="qty[]" id="edit_qty" class="form-control im-numeric" required autocomplete="off" onkeyup="keyUpQty(this)">
            </td>
            <td>
                <input type="text" name="harga[]" id="edit_harga" class="form-control im-currency" required autocomplete="off" onkeyup="keyUpHarga(this)">
            </td>
            <td>
                <input type="text" name="total_harga[]" id="total_harga" class="form-control im-currency" readonly>
            </td>
            <td>
                <a href="javascript:" class="btnDelItem">
                    <span class="ui-icon ui-icon-trash" style="margin-top: 10px;" onclick="btnRemove($(this))"></span>
                </a>
            </td>
        </tr>
    `)
}

function setDateFormat() {
    //date format
    $('.hasDatePicker').datepicker({
        dateFormat: 'd-m-yy',
        yearRange: '2000:2099'
    }).inputmask({
        inputFormat: "dd-mm-yyyy",
        alias: "datetime",
        minYear: '01-01-2000'
    })
    .focusout(function(e) {
        let val = $(this).val()
        if (val.match('[a-zA-Z]') == null) {
            if (val.length == 8) {
                $(this).inputmask({
                    inputFormat: "dd-mm-yyyy",
                }).val([val.slice(0, 6), '20', val.slice(6)].join(''))
            }
        } else {
            $(this).focus()
        }
    })
    .focus(function() {
        let val = $(this).val()
        if (val.length == 10) {
            $(this).inputmask({
                inputFormat: 'dd-mm-yyyy',
            }).val([val.slice(0, 6), '', val.slice(8)].join(''))
        }
    })
}

function setNumericFormat() {
    //numeric format
    $('.im-numeric').keypress(function(e){
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          return false;
        }
    })

    //currency format
    $('.im-currency, .im-numeric').inputmask('integer', {
        alias: 'numeric',
        groupSeparator: '.',
        autoGroup: true,
        digitsOptional: false,
        allowMinus: false,
        placeholder: '',
    })

    // $('.nik-number, .hp-number').inputmask('integer', {
    //     alias: 'numeric',
    //     groupSeparator: '',
    //     autoGroup: true,
    //     digitsOptional: false,
    //     allowMinus: false,
    //     placeholder: '',
    // })
}

</script>