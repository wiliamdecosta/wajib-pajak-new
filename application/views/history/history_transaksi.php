 <!-- breadcrumb -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php base_url();?>">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>History Transaksi</span>
        </li>
    </ul>
</div>
<!-- end breadcrumb -->

<div class="space-4"></div>

<div class="row">
	<div class="portlet box blue-hoki">
		<div class="portlet-title">
			<div class="caption" id="labelnpwd"><?php echo $this->session->userdata('npwd');?></div>
			<div class="actions">
				<a id="btn-SPTPD" href="#" class="btn default"><i class="fa fa-print"></i> Cetak SPTPD </a>
				<a id="btn-SSPD" href="#" class="btn default"><i class="fa fa-print"></i> Cetak SSPD </a>
				<a id="btn-RekapPenjualan" href="#" class="btn default"><i class="fa fa-print"></i> Rekap Penjualan </a>
				<a id="btn-CetakBayar" href="#" class="btn default"><i class="fa fa-print"></i> Cetak No Bayar </a>
			</div>
		</div>
		<div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    <table id="grid-table"></table>
                    <div id="grid-pager"></div>
                </div>
            </div>
        </div>
	</div>
</div>

<script>
	jQuery(function($) {
        var grid_selector = "#grid-table";
        var pager_selector = "#grid-pager";

        jQuery("#grid-table").jqGrid({
            url: '<?php echo WS_JQGRID."history.history_transaksi_controller/read"; ?>',
            datatype: "json",
            mtype: "POST",
            colModel: [
                {label: 'ID', name: 't_vat_setllement_id', key: true, hidden: true},
                {label: 'p_vat_type_id', name: 'p_vat_type_id', hidden: true},
                {label: 'p_vat_type_dtl_id', name: 'p_vat_type_dtl_id', hidden: true},
                {label: 't_customer_order_id', name: 't_customer_order_id', hidden: true},
                {label: 't_cust_account_id', name: 't_cust_account_id', hidden: true},
                {label: 'start_period', name: 'start_period', hidden: true},
                {label: 'end_period', name: 'end_period', hidden: true},

                {label: 'Jenis', name: 'type_code', sortable:false, hidden: false},
                {label: 'Periode', name: 'periode_pelaporan', sortable:false, hidden: false, editable: true},
                {label: 'Tgl Lapor', name: 'tgl_pelaporan', align:'center', sortable:false, hidden: false, editable: true},
                {label: 'Total Transaksi (Rp)', name: 'total_transaksi', sortable:false, formatter:'currency', formatoptions: {thousandsSeparator : '.', decimalPlaces: 0}, align:'right', hidden: false, editable: true},
                {label: 'Pajak Terutang (Rp)', name: 'total_pajak', sortable:false, formatter:'currency', formatoptions: {thousandsSeparator : '.', decimalPlaces: 0}, align:'right', hidden: false, editable: true},
                {label: 'No Bayar 2', name: 'payment_key2', hidden: true, sortable:false, editable: true}, 
                {label: 'No Bayar', name: 'payment_key1', hidden: false, sortable:false, editable: true, 
				formatter:function(cellvalue,options,rowObject){
					var is_employee = rowObject['is_employee'];
					var is_surveyed = rowObject['is_surveyed'];
					if(is_employee == 'Y') return cellvalue;
					if(is_surveyed == 'Y' && cellvalue != "" && cellvalue != null) return cellvalue;
					if(is_employee == 'N' && is_surveyed == null && cellvalue !=null) return 'Cetak No Bayar';
					return '';
				}
				},
                {label: 'Sanksi Adm 25% (Rp)', name: 'kenaikan', sortable:false, formatter:'currency', formatoptions: {thousandsSeparator : '.', decimalPlaces: 0}, align:'right', hidden: false, editable: true},
                {label: 'Sanksi Adm 2% (Rp)', name: 'kenaikan1', sortable:false, formatter:'currency', formatoptions: {thousandsSeparator : '.', decimalPlaces: 0}, align:'right', hidden: false, editable: true},
                {label: 'Denda (Rp)', name: 'total_denda', sortable:false, formatter:'currency', formatoptions: {thousandsSeparator : '.', decimalPlaces: 0}, align:'right', hidden: false, editable: true},
                {label: 'No. Kuitansi', name: 'kuitansi_pembayaran', sortable:false, width:450, hidden: false, editable: true},
                {label: 'Jumlah Bayar (Rp)', name: 'total_hrs_bayar', sortable:false, formatter:'currency', formatoptions: {thousandsSeparator : '.', decimalPlaces: 0}, align:'right', hidden: false, editable: true},
                {label: 'Keterangan', name: 'lunas', align:'center', sortable:false, hidden: false, editable: true},
				{label: 'is_employee', name: 'is_employee', hidden: false},
				{label: 'is_surveyed', name: 'is_surveyed', hidden: false}
			],
            height: '100%',
			width:'100%',
			cmTemplate: { sortable: false },
            autowidth: false,
            viewrecords: true,
            rowNum: 10,
            rowList: [10,20,50],
            rownumbers: true, // show row numbers
            // rownumWidth: 35, // the width of the row numbers columns
            altRows: true,
            shrinkToFit: false,
            multiboxonly: true,
			// width:'100%',
            onSelectRow: function (rowid) {

            },
            sortorder:'',
            pager: '#grid-pager',
            jsonReader: {
                root: 'rows',
                id: 'id',
                repeatitems: false
            },
            loadComplete: function (response) {
                if(response.success == false) {
                    swal({title: 'Attention', text: response.message, html: true, type: "warning"});
                }
				responsive_jqgrid(grid_selector,pager_selector);
            },
            //memanggil controller jqgrid yang ada di controller crud
            editurl: '',
            caption: "Tax Details"

        });
        jQuery('#grid-table').jqGrid('navGrid', '#grid-pager',
            {   //navbar options
                edit: false,
				excel: true,
                editicon: 'fa fa-pencil blue bigger-120',
                add: false,
                addicon: 'fa fa-plus-circle purple bigger-120',
                del: false,
                delicon: 'fa fa-trash-o red bigger-120',
                search: true,
                searchicon: 'fa fa-search orange bigger-120',
                refresh: true,
                afterRefresh: function () {
                    // some code here
                },

                refreshicon: 'fa fa-refresh green bigger-120',
                view: false,
                viewicon: 'fa fa-search-plus grey bigger-120'
            },

            {
                // options for the Edit Dialog
                closeAfterEdit: true,
                closeOnEscape:true,
                recreateForm: true,
                serializeEditData: serializeJSON,
                width: 'auto',
                errorTextFormat: function (data) {
                    return 'Error: ' + data.responseText
                },
                beforeShowForm: function (e, form) {
                    var form = $(e[0]);
                    style_edit_form(form);

                },
                afterShowForm: function(form) {
                    form.closest('.ui-jqdialog').center();
                },
                afterSubmit:function(response,postdata) {
                    var response = jQuery.parseJSON(response.responseText);
                    if(response.success == false) {
                        return [false,response.message,response.responseText];
                    }
                    return [true,"",response.responseText];
                }
            },
            {
                //new record form
                editData: {
                    p_finance_period_id: function() {
                        return <?php echo $this->input->post('p_finance_period_id'); ?>;
                    }
                },
                closeAfterAdd: false,
                clearAfterAdd : true,
                closeOnEscape:true,
                recreateForm: true,
                width: 'auto',
                errorTextFormat: function (data) {
                    return 'Error: ' + data.responseText
                },
                serializeEditData: serializeJSON,
                viewPagerButtons: false,
                beforeShowForm: function (e, form) {
                    var form = $(e[0]);
                    style_edit_form(form);
                },
                afterShowForm: function(form) {
                    form.closest('.ui-jqdialog').center();
                },
                afterSubmit:function(response,postdata) {
                    var response = jQuery.parseJSON(response.responseText);
                    if(response.success == false) {
                        return [false,response.message,response.responseText];
                    }

                    $(".tinfo").html('<div class="ui-state-success">' + response.message + '</div>');
                    var tinfoel = $(".tinfo").show();
                    tinfoel.delay(3000).fadeOut();


                    return [true,"",response.responseText];
                }
            },
            {
                //delete record form
                serializeDelData: serializeJSON,
                recreateForm: true,
                beforeShowForm: function (e) {
                    var form = $(e[0]);
                    style_delete_form(form);

                },
                afterShowForm: function(form) {
                    form.closest('.ui-jqdialog').center();
                },
                onClick: function (e) {
                    //alert(1);
                },
                afterSubmit:function(response,postdata) {
                    var response = jQuery.parseJSON(response.responseText);
                    if(response.success == false) {
                        return [false,response.message,response.responseText];
                    }
                    return [true,"",response.responseText];
                }
            },
            {
                //search form
                closeAfterSearch: false,
                recreateForm: true,
                afterShowSearch: function (e) {
                    var form = $(e[0]);
                    style_search_form(form);
                    form.closest('.ui-jqdialog').center();
                },
                afterRedraw: function () {
                    style_search_filters($(this));
                }
            },
            {
                //view record form
                recreateForm: true,
                beforeShowForm: function (e) {
                    var form = $(e[0]);
                }
            }
        )
    });

	function serializeJSON(postdata) {
        var items;
        if(postdata.oper != 'del') {
            items = JSON.stringify(postdata, function(key,value){
                if (typeof value === 'function') {
                    return value();
                } else {
                  return value;
                }
            });
        }else {
            items = postdata.id;
        }

        var jsondata = {items:items, oper:postdata.oper, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'};
        return jsondata;
    }

    function style_edit_form(form) {

        //update buttons classes
        var buttons = form.next().find('.EditButton .fm-button');
        buttons.addClass('btn btn-sm').find('[class*="-icon"]').hide();//ui-icon, s-icon
        buttons.eq(0).addClass('btn-primary');
        buttons.eq(1).addClass('btn-danger');


    }

    function style_delete_form(form) {
        var buttons = form.next().find('.EditButton .fm-button');
        buttons.addClass('btn btn-sm btn-white btn-round').find('[class*="-icon"]').hide();//ui-icon, s-icon
        buttons.eq(0).addClass('btn-danger');
        buttons.eq(1).addClass('btn-default');
    }

    function style_search_filters(form) {
        form.find('.delete-rule').val('X');
        form.find('.add-rule').addClass('btn btn-xs btn-primary');
        form.find('.add-group').addClass('btn btn-xs btn-success');
        form.find('.delete-group').addClass('btn btn-xs btn-danger');
    }

    function style_search_form(form) {
        var dialog = form.closest('.ui-jqdialog');
        var buttons = dialog.find('.EditTable')
        buttons.find('.EditButton a[id*="_reset"]').addClass('btn btn-sm btn-info').find('.ui-icon').attr('class', 'fa fa-retweet');
        buttons.find('.EditButton a[id*="_query"]').addClass('btn btn-sm btn-inverse').find('.ui-icon').attr('class', 'fa fa-comment-o');
        buttons.find('.EditButton a[id*="_search"]').addClass('btn btn-sm btn-success').find('.ui-icon').attr('class', 'fa fa-search');
    }

    function responsive_jqgrid(grid_selector, pager_selector) {

        var parent_column = $(grid_selector).closest('[class*="col-"]');
        $(grid_selector).jqGrid( 'setGridWidth', $(".portlet-body").width() );
        $(pager_selector).jqGrid( 'setGridWidth', parent_column.width() );

    }
</script>

<script>
    /**
     * Cetak SPTPD
     * @param  {[type]} e){var rowId [description]
     * @return {[type]}      [description]
     */
    $('#btn-SPTPD').on('click',function(e){
        var rowId =$("#grid-table").jqGrid('getGridParam','selrow');
        if(rowId == null) {
            swal('Informasi','Pilih salah satu baris data','info');
            return;
        }
        var rowData = $("#grid-table").getRowData(rowId);
        var reqId = rowData['p_vat_type_id'];
        var pid = rowData['t_vat_setllement_id'];
        var urlref;

        if(rowData['kuitansi_pembayaran'] != "") {
            if (reqId == '1'){
                    urlref="http://45.118.112.231/mpd/report/cetak_sptpd_hotel_pdf.php?t_vat_setllement_id="+pid;
                    window.open(urlref, "_blank", "toolbar=0,location=0,menubar=0");
            }else if(reqId == '2'){
                    urlref="http://45.118.112.231/mpd/report/cetak_sptpd_restoran_pdf.php?t_vat_setllement_id="+pid;
                    window.open(urlref, "_blank", "toolbar=0,location=0,menubar=0");
            }else if(reqId == 3){
                    lurlref="http://45.118.112.231/mpd/report/cetak_sptpd_hiburan_pdf.php?t_vat_setllement_id="+pid;
                    window.open(urlref, "_blank", "toolbar=0,location=0,menubar=0");
            }else if(reqId == 4){
                    urlref="http://45.118.112.231/mpd/report/cetak_sptpd_parkir_pdf.php?t_vat_setllement_id="+pid;
                    window.open(urlref, "_blank", "toolbar=0,location=0,menubar=0");
            }else if(reqId == 5){
                    urlref="http://45.118.112.231/mpd/report/cetak_sptpd_ppj_pdf.php?t_vat_setllement_id="+pid;
                    window.open(urlref, "_blank", "toolbar=0,location=0,menubar=0");
            }else{
                    swal('Informasi','Jenis Permohonan Tidak Diketahui','info');
            }
        }else {
            swal('Informasi','Maaf, Cetak SPTPD tidak dapat dilakukan karena record yang dipilih belum dibayar','info');
        }

    });

    /**
     * Cetak SSPD
     * @param  {[type]} e){                     var rowId [description]
     * @return {[type]}      [description]
     */
    $('#btn-SSPD').on('click',function(e){
        var rowId =$("#grid-table").jqGrid('getGridParam','selrow');
        if(rowId == null) {
            swal('Informasi','Pilih salah satu baris data','info');
            return;
        }
        var rowData = $("#grid-table").getRowData(rowId);

        if(rowData['kuitansi_pembayaran'] != "") {
            var t_customer_order_id = rowData['t_customer_order_id'];
            var urlref = "http://45.118.112.231/mpd/report/cetak_formulir_sspd_pdf.php?t_customer_order_id="+t_customer_order_id;
            window.open(urlref, "_blank", "toolbar=0,location=0,menubar=0");
        }else {
            swal('Informasi','Maaf, Cetak SSPD tidak dapat dilakukan karena record yang dipilih belum dibayar','info');
        }

    });

    /**
     * Cetak Rekap Penjualan
     * @param  {[type]} e){                     var rowId [description]
     * @return {[type]}      [description]
     */
    $('#btn-RekapPenjualan').on('click',function(e){
        var rowId =$("#grid-table").jqGrid('getGridParam','selrow');
        if(rowId == null) {
            swal('Informasi','Pilih salah satu baris data','info');
            return;
        }
        var rowData = $("#grid-table").getRowData(rowId);
        var reqId = rowData['p_vat_type_dtl_id'];

        var start_date = rowData['start_period'];
        var end_date = rowData['end_period'];
        var t_cust_account_id = rowData['t_cust_account_id'];

        urlref = "<?php echo base_url();?>transaksi_harian/print_transaksi_harian?";
        urlref +="date_end="+end_date+"&date_start="+start_date+"&p_vat_type_dtl_id="+reqId+"&t_cust_account_id="+t_cust_account_id;
        window.open(urlref, "_blank", "toolbar=0,location=0,menubar=0");
    });

    /**
     * Cetak Bayar
     * @param  {[type]} e){                     var rowId [description]
     * @return {[type]}      [description]
     */
    $('#btn-CetakBayar').on('click',function(e){
        var rowId =$("#grid-table").jqGrid('getGridParam','selrow');
        if(rowId == null) {
            swal('Informasi','Pilih salah satu baris data','info');
            return;
        }
        var rowData = $("#grid-table").getRowData(rowId);
        var no_bayar = rowData['payment_key2'];
        var kuitansi = rowData['kuitansi_pembayaran'];
		if(no_bayar ==""){
			swal('Informasi','Laporan masih dalam tahap verifikasi. Mohon cek history transaksi secara berkala','info');
		} else
		if(kuitansi == ""){
			// swal('Informasi','Isilah terlebih dahulu IKM  (Indeks Kepuasan Masyarakat) sebelum mencetak No Bayar Anda','info');
			// swal({title: "Pemberitahuan",
				// text: "Isilah terlebih dahulu IKM  (Indeks Kepuasan Masyarakat) sebelum mencetak No Bayar Anda",
				// type: "info",
				// showCancelButton: true,
				// confirmButtonColor: "#bdeefb",
				// confirmButtonText: "Isi Survey",
				// cancelButtonText: "Tutup",
				// closeOnConfirm: false
				// },
				// function(isConfirm){
				// if (isConfirm) {
					// swal("Deleted!", "Your imaginary file has been deleted.", "success");
				// } else {
						// swal("Cancelled", "Your imaginary file is safe :)", "error");
				// }
				// });


			swal({
			title: 'Pemberitahuan',
			text: "Isilah terlebih dahulu IKM  (Indeks Kepuasan Masyarakat) sebelum mencetak No Bayar Anda",
			type: 'info',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Isi Survey',
			cancelButtonText: 'Tutup'
			}).then(function() {
				// alert();
				var urlref = "http://45.118.112.232/trans/t_survey_kepuasan_pelanggan_pelaporan_pertanyaan.php?payment_key="+no_bayar;
						window.open(urlref, "_blank", "toolbar=0,location=0,menubar=0");
			})
			
		} else if(no_bayar != "") {
            var urlref = "http://45.118.112.232/mpd/report/cetak_no_bayar.php?no_bayar="+no_bayar;
            window.open(urlref, "_blank", "toolbar=0,location=0,menubar=0");
        }else {
            swal('Informasi','Laporan Anda masih dalam proses verifikasi.','info');
        }
		

    });
</script>