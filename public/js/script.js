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
            rowNum = $("#jqGrid").jqGrid('getGridParam', 'rowNum');
            ids = $("#jqGrid").jqGrid('getDataIDs');
            $('#jqGrid').jqGrid('setSelection', ids[0]);
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
        },
        onSelectRow: function(rowid, selected) {
            // console.log(rowid)
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
        emptyrecords: 'Tidak Ada data.',
        pager: "#jqGridPager"
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
        document.getElementById('gs_nama').value   = '';
        document.getElementById('gs_nik').value    = '';
        document.getElementById('gs_hp').value     = '';
        document.getElementById('gs_email').value  = '';
        document.getElementById('gs_alamat').value = '';
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
    });

    $('#gsh_jqGrid_rn').append(
        '<button id="clearBtn" data-toggle="tooltip" title="Clear Search Filter!">X</button>'
    );

    $("#clearBtn").click(function () {
        document.getElementById('gs_nama').value   = '';
        document.getElementById('gs_nik').value    = '';
        document.getElementById('gs_hp').value     = '';
        document.getElementById('gs_email').value  = '';
        document.getElementById('gs_alamat').value = '';
        document.getElementById('gs_tgl_pesanan').value = '';
        document.getElementById('keyword').value   = '';
        $("#jqGrid").jqGrid('setGridParam', {
            datatype: 'json',
            postData: {
                filters: []
            },
            search: false,
        }).trigger('reloadGrid');
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
                            console.log(data)
                            $.ajax({
                                type: "POST",
                                url: baseUrl+"/store",
                                data: data,
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
                    // {
                    // 	text: "Excel",
                    // 	click: function() {
                    // 		report('excel')
                    // 	}
                    // },
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

        var sidx = $("#jqGrid").jqGrid('getGridParam','sortname');
        var sord = $("#jqGrid").jqGrid('getGridParam','sortorder');

        var getData = $('#jqGrid').jqGrid('getRowData');
        var data    = window.btoa(JSON.stringify(getData)); //base64 encode
        
        window.open(baseUrl+'/pelanggan/report?data='+data+'&start='+start+'&limit='+limit+'&sidx='+sidx+'&sord='+sord+'&type='+type)
    }
});
