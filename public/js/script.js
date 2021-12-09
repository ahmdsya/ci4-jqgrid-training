let highlightSearch;
let indexRow = 0;

$(document).ready(function () {
    
    $("#jqGrid").jqGrid({
        url: baseUrl+'/get',
        editurl: 'clientArray',
        mtype: "GET",
        datatype: "json",
        colModel: [
            {
                label: 'ID',
                name: 'id',
                width: 20,
                hidden: true,
                key: true,
            },
            {
                label: 'Tgl Pesanan',
                name: 'tgl_pesanan',
                width: 50,
                editable: true,
                formatter: 'date',
                formatoptions: {
                    newformat:'d-m-Y'
                },
            },
            {
                label: 'Nama',
                name: 'nama',
                width: 100,
            },
            {
                label: 'NIK',
                name: 'nik',
                width: 50,
            },
            {
                label: 'HP',
                name: 'hp',
                width: 60,
            },
            {
                label: 'Email',
                name: 'email',
                width: 100,
            },
            {
                label: 'Alamat',
                name: 'alamat',
                width: 160,
            }
        ],
        loadComplete: function (data) {
            setTimeout(function () {
                $(
                    "#jqGrid tbody tr td:not([aria-describedby=jqGrid_rn])"
                ).highlight(highlightSearch);

                $("[id*=gs_]").on("input", function () {
                    highlightSearch = $(this).val();
                });

                rowNum = $("#jqGrid").jqGrid('getGridParam', 'rowNum');
                ids = $("#jqGrid").jqGrid('getDataIDs');
                $('#jqGrid').jqGrid('setSelection', ids[indexRow]);
                var index = 0
                document.addEventListener("keydown", function(event) {
                    if(event.which == 38){ //tombol atas
                        if(index != 0){
                            $('#jqGrid').jqGrid('setSelection', ids[index = index-1]);
                        }
                    }else if(event.which == 40){ //tombol bawah
                        if(index != rowNum){
                            $('#jqGrid').jqGrid('setSelection', ids[index = index+1]);
                        }
                    }
                })
            })
            
        },
        onSelectRow: function(rowid, selected) {
            if(rowid != null) {
                $("#jqGridDetails").jqGrid('setGridParam',{url: baseUrl+"/pesanan/"+rowid ,datatype: 'json'});
                $("#jqGridDetails").trigger("reloadGrid");
            }					
        },
        viewrecords: true,
        caption: 'Data Pelanggan', 
        width: 1310,
        height: 'auto',
        rowNum: 10,
        rowList: [10, 20, 30],
		rownumbers: true,
        loadonce: false,
        gridview: true,
        sortable: true,
        emptyrecords: 'Tidak Ada data.',
        pager: "#jqGridPager",
        // toolbar: [true, "top"],
    })
    .jqGrid('filterToolbar', {
        stringResult: true,
        searchOperators: false,
        searchOnEnter: false,
        beforeSearch: function(){
            document.getElementById('keyword').value = '';
        }
    })
    .navGrid('#jqGridPager',
        {
            edit: false,
            add: false,
            del: false,
            search: false,
            refresh: true,
            view: false,
            position: "left",
            cloneToTop: true
        }
    );
    
    //master detail
    var pelanggan_id = $("#jqGrid").jqGrid('getGridParam', "selrow");
    $("#jqGridDetails").jqGrid({
        url: baseUrl+"/pesanan/"+pelanggan_id,
        mtype: "GET",
        datatype: "json",
        page: 1,
        colModel: [
                { label: 'Nama Produk', name: 'nama_produk', width: 100 },
                { label: 'Harga', name: 'harga', width: 75, align: "right", formatter:'currency', formatoptions:{thousandsSeparator: ".", prefix: "Rp. "}},
                { label: 'Kuantitas', name: 'qty', width: 50, align: "right" },
                { label: 'Total Harga', name: 'total_harga', width: 75, align: "right", formatter:'currency', formatoptions:{thousandsSeparator: ".", prefix: "Rp. "}},
        ],
        width: 780,
        rowNum: 10,
        loadonce: true,
        height: 'auto',
        viewrecords: true,
        footerrow: true,
        userDataOnFooter: true,
        caption: 'Data Pesanan',
        pager: "#jqGridDetailsPager"
    });
    //end master detail

    $('#keyword').on('keyup', function(){
        var value = $(this).val();
        $('[id*="gs_"]').val("");
        $("#jqGrid").jqGrid('setGridParam', {
            url: baseUrl+'/get',
            editurl: 'clientArray',
            mtype: "GET",
            datatype: 'json',
            postData: {
                filters: '{"groupOp":"OR","rules":[{"field":"nama","op":"in","data":"'+value+'"},{"field":"nik","op":"in","data":"'+value+'"},{"field":"hp","op":"in","data":"'+value+'"},{"field":"email","op":"in","data":"'+value+'"},{"field":"alamat","op":"in","data":"'+value+'"}]}'
            },
            search: true,
        }).trigger('reloadGrid',[{page:1}]);
        highlightSearch = value
    });

    $('#gsh_jqGrid_rn').append(
        '<button id="clearBtn" data-toggle="tooltip" title="Clear Search Filter!">X</button>'
    );

    $("#clearBtn").click(function () {
        $('[id*="gs_"]').val("");
        document.getElementById('keyword').value   = '';
        $("#jqGrid").jqGrid('setGridParam', {
            datatype: 'json',
            postData: {
                filters: []
            },
            search: false,
        }).trigger('reloadGrid');
        highlightSearch = "undefined";
    });

    //btn delete
    $('#jqGrid').navButtonAdd('#jqGridPager', {
        caption: "",
        buttonicon: "glyphicon glyphicon-trash",
        position: "first",
        title: "Delete",
        onClickButton: function () {
            var id = $("#jqGrid").jqGrid('getGridParam', "selrow");
            if(id !== null){
                $("#Dialog")
                .load(baseUrl+'/delete/'+id)
                .dialog({
                    width   : 600,
                    height: 500,
                    position: 'top',
                    modal   : true,
                    title   : "Delete Data",
                    buttons : [{
                            text: "Delete",
                            click: function () {
                                $.ajax({
                                    type: "POST",
                                    url: baseUrl+"/destroy/"+id,
                                    dataType: "text",
                                    success: function (result) {
                                        $("#Dialog").dialog('close');
                                        $("#jqGrid").jqGrid('setGridParam', {
                                            datatype: 'json',
                                            postData: {
                                                filters: []
                                            },
                                            search: false,
                                        }).trigger('reloadGrid');
                                    }
                                });
                            }
                        },
                        {
                            text: "Cancel",
                            click: function () {
                                $(this).dialog('close');
                            }
                        }
                    ]
                });
            }else{
                alert('No selected row. Please select a row.')
            }
        }
    });

    //btn edit
    $('#jqGrid').navButtonAdd('#jqGridPager', {
        caption: "",
        buttonicon: "glyphicon glyphicon-pencil",
        position: "first",
        title: "Edit",
        onClickButton: function () {
            var id = $("#jqGrid").jqGrid('getGridParam', "selrow");
            if(id !== null){
                $("#Dialog")
                .load(baseUrl+'/edit/'+id)
                .dialog({
                    width   : 600,
                    height: 500,
                    position: 'top',
                    modal   : true,
                    title   : "Update Data",
                    buttons : [{
                            text: "Save",
                            click: function () {
                                var data = $('#updateForm').serialize()
                                // console.log(data)
                                $.ajax({
                                    type: "POST",
                                    url: baseUrl+"/update/"+id,
                                    data: data,
                                    dataType: "text",
                                    success: function (result) {
                                        var res = JSON.parse(result)
                                        var msg = res.msg
                                        if(res.status == 'error'){
                                            $('#tgl_pesanan').append(`<p style="color:red;">${(msg.tgl_pesanan ? msg.tgl_pesanan : "")}</p>`)
                                            $('#nama').append(`<p style="color:red;">${(msg.nama ? msg.nama : "")}</p>`)
                                            $('#nik').append(`<p style="color:red;">${(msg.nik ? msg.nik : "")}</p>`)
                                            $('#hp').append(`<p style="color:red;">${(msg.hp ? msg.hp : "")}</p>`)
                                            $('#email').append(`<p style="color:red;">${(msg.email ? msg.email : "")}</p>`)
                                            $('#alamat').append(`<p style="color:red;">${(msg.alamat ? msg.alamat : "")}</p>`)
                                        }else{
                                            $("#Dialog").dialog('close');
                                            $("#jqGrid").jqGrid('setGridParam', {
                                                datatype: 'json',
                                                postData: {
                                                    filters: []
                                                },
                                                search: false,
                                            }).trigger('reloadGrid');
                                        }
                                    }
                                });
                            }
                        },
                        {
                            text: "Cancel",
                            click: function () {
                                $(this).dialog('close');
                            }
                        }
                    ]
                });
            }else{
                alert('No selected row. Please select a row.')
            }
        }
    });

    //btn add
    $('#jqGrid').navButtonAdd('#jqGridPager', {
        caption: "",
        buttonicon: "glyphicon glyphicon-plus",
        position: "first",
        title: "Add",
        onClickButton: function () {
            $("#Dialog")
            .load(baseUrl+'/add')
            .dialog({
                width   : 600,
                height  : 500,
                position: 'top',
                modal   : true,
                title   : "Add Data",
                buttons : [{
                        text: "Submit",
                        click: function () {
                            var data = $('#storeForm').serialize()
                            $.ajax({
                                type: "POST",
                                url: baseUrl+"/store",
                                data: data,
                                dataType: "text",
                                success: function (result) {
                                    var res = JSON.parse(result)
                                    var msg = res.msg
                                    if(res.status == 'error'){
                                        $('#tgl_pesanan').append(`<p style="color:red;">${(msg.tgl_pesanan ? msg.tgl_pesanan : "")}</p>`)
                                        $('#nama').append(`<p style="color:red;">${(msg.nama ? msg.nama : "")}</p>`)
                                        $('#nik').append(`<p style="color:red;">${(msg.nik ? msg.nik : "")}</p>`)
                                        $('#hp').append(`<p style="color:red;">${(msg.hp ? msg.hp : "")}</p>`)
                                        $('#email').append(`<p style="color:red;">${(msg.email ? msg.email : "")}</p>`)
                                        $('#alamat').append(`<p style="color:red;">${(msg.alamat ? msg.alamat : "")}</p>`)
                                    }else{
                                        $("#Dialog").dialog('close');
                                        
                                        $.ajax({
                                            url:
                                                baseUrl +
                                                "/pelanggan/show/" +
                                                res.nama +
                                                "/" +
                                                $("#jqGrid").jqGrid("getGridParam", "sortorder") +
                                                "/" +
                                                $("#jqGrid").jqGrid("getGridParam", "postData").rows,
                                            type: "GET",
                                            dataType: "JSON",
                                            success: function (result2) {
                                                // var res2 = JSON.parse(result2)
                                                if (result2.row) {
                                                    indexRow = result2.row - 1;
                                                }
                                                selectedPage = result2.page;
                                                setTimeout(function () {
                                                    $("#jqGrid").trigger("reloadGrid", [{ page: selectedPage }]);
                                                }, 50);
                                            },
                                        });
                                    }
                                }
                            });
                        }
                    },
                    {
                        text: "Cancel",
                        click: function () {
                            $(this).dialog('close');
                        }
                    }
                ]
            });
        }
    });

    //btn report
    $('#jqGrid').navButtonAdd('#jqGridPager', {
        caption: "",
        title: "Report",
        buttonicon: "glyphicon glyphicon-file",
        position: "last",
        onClickButton: function () {
            $('#Dialog').html(`
                <div class="ui-state-default" style="padding: 5px;">
                    <h5> Tentukan Baris </h5>
                    
                    <label> Dari: </label>
                    <input type="number" min="1" id="reportStart" name="start" value="${$(this).getInd($(this).getGridParam('selrow'))}" class="ui-widget-content ui-corner-all autonumeric" style="padding: 5px; text-transform: uppercase;" required>

                    <label> Sampai: </label>
                    <input type="number" id="reportLimit" name="limit" value="${$(this).getGridParam('records')}" class="ui-widget-content ui-corner-all autonumeric" style="padding: 5px; text-transform: uppercase;" required>
                </div>
            `)
            .dialog({
                width   : 'auto',
                height  : 250,
                position: 'top',
                modal   : true,
                title   : "Report",
                buttons : [
                    {
                        text: "StimulSoft",
                        click: function () {
                            report('stimulsoft')
                        }
                    },
                    {
                    	text: "Excel",
                    	click: function() {
                    		report('excel')
                    	}
                    },
                    {
                        text: "Cancel",
                        click: function () {
                            $(this).dialog('close');
                        }
                    }
                ]
            });
        }
    });

    function report(type) {

        let start = document.getElementById('reportStart').value
        let limit = document.getElementById('reportLimit').value

        if (parseInt(start) > parseInt(limit)) {
            return alert('Nilai "Sampai" harus lebih besar')
        }

        var sidx   = $("#jqGrid").jqGrid('getGridParam','sortname');
        var sord   = $("#jqGrid").jqGrid('getGridParam','sortorder');
        var filter = ($("#jqGrid").getGridParam("postData").filters ? $("#jqGrid").getGridParam("postData").filters : '');
        var global = document.getElementById('keyword').value

        window.open(baseUrl+'/pelanggan/report?global='+global+'&filter='+filter+'&start='+start+'&limit='+limit+'&sidx='+sidx+'&sord='+sord+'&type='+type)
    }
});
