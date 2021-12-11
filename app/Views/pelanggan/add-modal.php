<!-- <style type="text/css">
	input, textarea {
		text-transform: uppercase;
		/* padding: 5px; */
	}
</style> -->

<form id="storeForm">
    <div class="form-group row">
        <label for="edit_tgl_pesanan" class="col-sm-3 col-form-label">Tgl Pesanan</label>
        <div class="col-sm-9">
            <input type="text" class="form-control hasDatePicker" name="tgl_pesanan" id="edit_tgl_pesanan">
            <div id="err_tgl_pesanan" style="font-size: 10px;"></div>
        </div>
    </div>

    <div class="form-group row">
        <label for="edit_nama" class="col-sm-3 col-form-label">Nama Lengkap</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="nama" id="edit_nama">
            <div id="err_nama" style="font-size: 10px;"></div>
        </div>
    </div>

    <div class="form-group row">
        <label for="edit_nik" class="col-sm-3 col-form-label">NIK</label>
        <div class="col-sm-9">
            <input type="text" class="form-control nik-number" name="nik" id="edit_nik">
            <div id="err_nik" style="font-size: 10px;"></div>
        </div>
    </div>

    <div class="form-group row">
        <label for="edit_hp" class="col-sm-3 col-form-label">HP</label>
        <div class="col-sm-9">
            <input type="text" class="form-control hp-number" name="hp" id="edit_hp">
            <div id="err_hp" style="font-size: 10px;"></div>
        </div>
    </div>

    <div class="form-group row">
        <label for="edit_email" class="col-sm-3 col-form-label">Email</label>
        <div class="col-sm-9">
            <input type="email" class="form-control" name="email" id="edit_email">
            <div id="err_email" style="font-size: 10px;"></div>
        </div>
    </div>

    <div class="form-group row">
        <label for="edit_alamat" class="col-sm-3 col-form-label">Alamat</label>
        <div class="col-sm-9">
            <textarea id="edit_alamat" name="alamat" class="form-control"></textarea>
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
            </tr>
        </thead>
        <tbody id="clearBody">
            <tr>
                <td>
                    <input type="text" name="nama_produk[]" id="edit_nama_produk" class="form-control" required
                        autocomplete="off">
                </td>
                <td>
                    <input type="text" name="qty[]" id="edit_qty" class="form-control im-numeric" required
                        autocomplete="off">
                </td>
                <td>
                    <input type="text" name="harga[]" id="edit_harga" class="form-control im-currency" required
                        autocomplete="off">
                </td>
                <td>
                    <a href="javascript:">
                        <span class="ui-icon ui-icon-trash" style="margin-top: 10px;"
                            onclick="$(this).parent().parent().parent().remove()"></span>
                    </a>
                </td>
            </tr>
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
    setDateFormat()
    setNumericFormat()
})

function addRow() {
    $('#masterDetail tbody tr').last().before(`
        <tr>
            <td>
                <input type="text" name="nama_produk[]" id="edit_nama_produk" class="form-control" required autocomplete="off">
            </td>
            <td>
                <input type="text" name="qty[]" id="edit_qty" class="form-control im-numeric" required autocomplete="off">
            </td>
            <td>
                <input type="text" name="harga[]" id="edit_harga" class="form-control im-currency" required autocomplete="off">
            </td>
            <td>
                <a href="javascript:">
                    <span class="ui-icon ui-icon-trash" style="margin-top: 10px;" onclick="$(this).parent().parent().parent().remove()"></span>
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

    $('.nik-number, .hp-number').inputmask('integer', {
        alias: 'numeric',
        groupSeparator: '',
        autoGroup: true,
        digitsOptional: false,
        allowMinus: false,
        placeholder: '',
    })
}

</script>