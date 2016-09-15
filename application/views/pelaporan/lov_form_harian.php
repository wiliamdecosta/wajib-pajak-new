<script type="text/javascript" src="<?php echo base_url(); ?>/assets/global/plugins/moment.min.js"></script>
<div id="modal_lov_form_harian" class="modal fade"  tabindex="-1">
    <div class="modal-dialog" style="width:960px;">
        <div class="modal-content">
            <!-- modal title -->
            <div class="modal-header no-padding">
                <div class="table-header">
                    <span class="form-add-edit-title"> Isi Form Harian</span>
                </div>
            </div>
            <input type="hidden" id="modal_lov_form_harian_id_val" value="" />
            <input type="hidden" id="modal_lov_form_harian_code_val" value="" />
            <input type="hidden" id="modal_lov_vat_pct_val" value="" />


            <!-- modal body -->
            <div class="modal-body" style="overflow-y:scroll;height:300px;">

				<div class="tab-pane active">
					<table id="grid-table-laporan"></table>
					<div id="grid-pager-laporan"></div>
				</div>

            </div>
            <!-- modal footer -->
            <div class="modal-footer no-margin-top">
                <div class="bootstrap-dialog-footer" id="dialog_footer">
                    <div class="bootstrap-dialog-footer-buttons">
                        <button class="btn btn-default btn-md radius-4" id="simpan">
                            <i class="ace-icon fa fa-floppy"></i>
                            Simpan
                        </button>
						<button class="btn btn-danger btn-md radius-4" data-dismiss="" id="exitmodal">
                            <i class="ace-icon fa fa-times"></i>
                            Close
                        </button>
                    </div>
                </div>
				<div id="footer_notif_close" style="display: none;">
					<h5>
						Apakah Anda yakin ingin menutup form ini? Semua perubahan tidak akan tersimpan
					</h5>					
					<button class="btn btn-default btn-md radius-4" id="cancel_footer">
						Tidak
					</button>
					<button class="btn btn-danger btn-md radius-4" data-dismiss="modal" >
						Ya, tutup form harian
					</button>
				</div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.end modal -->

<script>
	$('#exitmodal').click(function(){
		$('#footer_notif_close').show(500);		
		$('#dialog_footer').hide(500);		
	});
	$('#cancel_footer').click(function(){
		$('#footer_notif_close').hide(500);		
		$('#dialog_footer').show(500);		
	});
	$('#simpan').click(function(){
		var $grid = $('#grid-table-laporan');
		var colSum = $grid.jqGrid('getCol', 'jum_penjualan', false, 'sum');
		// $('#omzet_value').html(omzet_value);
		$('#omzet_value').val(colSum);

		$.ajax({
            async: false,
			url: "<?php echo WS_JQGRID ?>pelaporan.pelaporan_pajak_controller/p_vat_type_dtl_cls",
			datatype: "json",
            type: "POST",
            success: function (response) {
					var data = $.parseJSON(response);
					var i = 0;
					if (data.rows.length > 0){
						vat_pct = $('#rincian').find(':selected').data('id');
						$('#val_pajak').val( parseFloat((vat_pct * parseInt($('#omzet_value').val())) / 100).toFixed(2) );
						// }
					} else
					{
						$('#rincian_form').hide(100);
						$.ajax({
							url: "<?php echo WS_JQGRID ?>pelaporan.pelaporan_pajak_controller/p_vat_type_dtl",
							datatype: "json",
							type: "POST",
							success: function (response) {
								var data = $.parseJSON(response);
								$('#val_pajak').val( parseFloat((data.rows[0].vat_pct * parseInt($('#omzet_value').val())) / 100).toFixed(2) );
							}
						});
					}
					$('#modal_lov_form_harian').modal('hide');
			}
		});
		// Hitung Denda
		var date_denda_signed = false;
			$.ajax
			({
				async: false,
				url: "<?php echo WS_JQGRID ?>pelaporan.pelaporan_pajak_controller/get_fined_start",
				datatype: "json",
				type: "POST",
				data: {nowdate:moment($('#datepicker').val()).format("YYYY-MM-"),
						getdate:moment($('#datepicker').val()).format("MM-YYYY")
				},
				success: function (response) {
					var data = $.parseJSON(response);
					kelipatan_denda = data.rows[0].booldendamonth;
					if(parseInt(data.rows[0].booldenda) >= 0){
							if(parseInt(kelipatan_denda > 24)){
								kelipatan_denda = 24;
							};
							$('#val_denda').val( parseFloat(0.02 * $('#val_pajak').val() * kelipatan_denda ).toFixed(2) );
						// }
					}else
					{
							$('#val_denda').val((0));
					};
				}
			});

		$('#totalBayar').val(  parseFloat(   $('#val_pajak').val()  )  + parseFloat(  $('#val_denda').val()   ) );
		i=0; k=0; j=0;;
		dataupdate = new Array(); datecreate=new Array();
		while (i < $("#grid-table-laporan").getRowData().length){
			keyidchecker = $('#grid-table-laporan').jqGrid('getCell',i+1,'keyid');
			Tanggal = $('#grid-table-laporan').jqGrid('getCell',i+1,'Tanggal');
			No_UrutAwal = $('#grid-table-laporan').jqGrid('getCell',i+1,'No_UrutAwal');
			No_UrutAkhir = $('#grid-table-laporan').jqGrid('getCell',i+1,'No_UrutAkhir');
			jum_faktur = parseInt($('#grid-table-laporan').jqGrid('getCell',i+1,'jum_faktur'));
			jum_penjualan = parseInt($('#grid-table-laporan').jqGrid('getCell',i+1,'jum_penjualan'));
			t_cust_dtl = $('#grid-table-laporan').jqGrid('getCell',i+1,'t_cust_acc_dtl');
			vat_pct = parseFloat($('#val_pajak').val()) / parseFloat($('#totalBayar').val());
			description = $('#grid-table-laporan').jqGrid('getCell',i+1,'descript');
			if(keyidchecker == -1){
				datecreate.push ({
					't_cust_acc_dtl_trans_id' : 0,
					'p_vat_type_dtl_id' : '<?php echo $this->session->userdata('vat_type_dtl'); ?>',
					'npwd' : '<?php echo $this->session->userdata('npwd'); ?>',
					't_cust_account_id' : <?php echo $this->session->userdata('cust_account_id'); ?>,
					'trans_date' : Tanggal,
					'trans_date_txt' : '',
					'bill_no' : No_UrutAwal,
					'bill_no_end' : No_UrutAkhir,
					'bill_count' : parseInt(jum_faktur),
					'service_desc' : '',
					'service_charge' : jum_penjualan,
					'vat_charge' : parseFloat(jum_penjualan * vat_pct),
					'description' : description,
					'_display_field_' : ''
				});
				j++;
			}

			if(keyidchecker == -99){
				dataupdate.push ({
					't_cust_acc_dtl_trans_id' : t_cust_dtl,
					'p_vat_type_dtl_id' : '<?php echo $this->session->userdata('vat_type_dtl'); ?>',
					'npwd' : '<?php echo $this->session->userdata('npwd'); ?>',
					't_cust_account_id' : <?php echo $this->session->userdata('cust_account_id'); ?>,
					'trans_date' : Tanggal,
					'trans_date_txt' : '',
					'bill_no' : No_UrutAwal,
					'bill_no_end' : No_UrutAkhir,
					'bill_count' : parseInt(jum_faktur),
					'service_desc' : '',
					'service_charge' : parseFloat(jum_penjualan),
					'vat_charge' : parseFloat(jum_penjualan * 2 / 100),
					'description' : description,
					'_display_field_' : ''
				});

				k++;
			}
			i++;
		};

		if(j>0)
			{
				$.ajax
				({
						url: "<?php echo WS_JQGRID ?>transaksi.cust_acc_trans_controller/create",
						datatype: "json",
						type: "POST",
						data:
							{
								end_period 	 : 	moment($('#modal_lov_form_harian_id_val').val()).format('YYYY-MM-DD'),
								items : JSON.stringify(datecreate),
								p_vat_type_dtl_id : '<?php echo $this->session->userdata('vat_type_dtl'); ?>',
								start_period : 	moment($('#modal_lov_form_harian_code_val').val()).format('YYYY-MM-DD'),
								t_cust_account_id : '<?php echo $this->session->userdata('cust_account_id'); ?>'
							},
						success: function (response) {

						}
				});
			}
		if(k>0)
			{
				$.ajax
				({
						url: "<?php echo WS_JQGRID ?>transaksi.cust_acc_trans_controller/update",
						datatype: "json",
						type: "POST",
						data:
							{
								end_period 	 : 	moment($('#modal_lov_form_harian_id_val').val()).format('YYYY-MM-DD') +'T00:00:00',
								items : JSON.stringify(dataupdate),
								p_vat_type_dtl_id : '<?php echo $this->session->userdata('vat_type_dtl'); ?>',
								start_period : 	moment($('#modal_lov_form_harian_code_val').val()).format('YYYY-MM-DD') +'T00:00:00',
								t_cust_account_id : '<?php echo $this->session->userdata('cust_account_id'); ?>'
							},
						success: function (response) {

						}
				});
			}
	});

    jQuery(function($) {
        $("#modal_lov_form_harian_btn_blank").on('click', function() {
            $("#"+ $("#modal_lov_form_harian_id_val").val()).val("");
            $("#"+ $("#modal_lov_form_harian_code_val").val()).val("");
            $("#modal_lov_form_harian").modal("toggle");
        });
    });

    function modal_lov_form_harian_show(the_id_field, the_code_field, diffDays)
	{
        modal_lov_form_harian_set_field_value(the_id_field, the_code_field);
        $("#modal_lov_form_harian").modal({backdrop: 'static'});
		i = 0;

		mydata = [];

		var date_denda_signed = false;
		$.ajax({
			async: false,
			url: "<?php echo WS_JQGRID ?>pelaporan.pelaporan_pajak_controller/get_fined_start",
			datatype: "json",
			type: "POST",
			data: {nowdate:moment($('#datepicker').val()).format("YYYY-MM-"),
					getdate:moment($('#datepicker').val()).format("MM-YYYY")
			},
			success: function (response) {
				var data = $.parseJSON(response);

				if(data.rows[0].booldenda);
				date_denda_signed = data.rows[0].due_in_day;
			}
		});
		while(i < diffDays+1){
			dateM1 = moment(the_id_field).add(i,'days');
			dateFormatted = moment(dateM1).format('DD-MM-YYYY');
			mydata[i] = {Tanggal:''};
			mydata[i] = {keyid:''};
			mydata[i] = {jum_faktur:''};
			mydata[i] = {jum_penjualan:''};
			mydata[i] = {descript:''};
			mydata[i] = {No_UrutAwal:''};
			mydata[i] = {No_UrutAkhir:''};
			mydata[i] = {t_cust_acc_dtl:''};
			mydata[i] = {t_cust_account:''};
			mydata[i].jum_penjualan = 0;
			mydata[i].keyid = i;
			mydata[i].jum_faktur = 0;
			mydata[i].descript = '';
			mydata[i].No_UrutAwal = '';
			mydata[i].No_UrutAkhir = '';
			mydata[i].t_cust_acc_dtl = '';
			mydata[i].t_cust_account = '';
			mydata[i].Tanggal = dateFormatted;
			i++;
		}

		dateFormatted1 = moment(the_id_field).format('YYYY-MM-DD');
		dateFormatted2 = moment(the_code_field).format('YYYY-MM-DD');


		$.ajax({
			async: false,
			url: "<?php echo WS_JQGRID ?>transaksi.cust_acc_trans_controller/read_acc_trans",
			datatype: "json",
			type: "POST",
			data: {	t_cust_account_id:'<?php echo $this->session->userdata('cust_account_id'); ?>',
					start_period : dateFormatted1,
					end_period : dateFormatted2,
					vat_type_dtl :'<?php echo $this->session->userdata('vat_type_dtl'); ?>'
			},
			success: function (response)
			{
				i = 0;
				var data = $.parseJSON(response);
				if(data.rows.length > 0){
					while( i < mydata.length)
					{	k=0;
						while( k < data.rows.length)
						{
							if(mydata[i].Tanggal == data.rows[k].trans_date_jqgrid)
							{
								// alert(mydata[i].Tanggal +' == '+ data.rows[k].trans_date_jqgrid);
								mydata[i].jum_penjualan = 	data.rows[k].service_charge;
								mydata[i].descript 		= 	data.rows[k].description;
								mydata[i].jum_faktur 	= 	data.rows[k].bill_count;
								mydata[i].No_UrutAwal 	= 	data.rows[k].bill_no;
								mydata[i].No_UrutAkhir 	= 	data.rows[k].bill_no_end;
								mydata[i].t_cust_acc_dtl= 	data.rows[k].t_cust_acc_dtl_trans_id;
								mydata[i].t_cust_account= 	data.rows[k].t_cust_account_id;
							}
						k++;
						}
					i++;
					}
				}// else
				// {

				// }
			}
		});

		$('#grid-table-laporan').trigger("GridUnload");
		$('#grid-table-laporan').trigger( 'reloadGrid' );
		jQuery("#grid-table-laporan").jqGrid('setGridParam',
				{
					datatype: "local",
					data:mydata
				})
		.trigger("reloadGrid"); //reload data if transaction button pushed

		jQuery(function($) {
			var grid_selector = "#grid-table-laporan";
			var pager_selector = "#grid-pager-laporan";

			jQuery("#grid-table-laporan").jqGrid({
				// url: '<?php echo WS_JQGRID."history.history_transaksi_controller/read"; ?>',
				datatype: "json",
				data: mydata,
				datatype: "local",
				// mtype: "POST",
				colNames: ["keyid","t_cust_acc_dtl","t_cust_account","Tanggal","No. Urut Faktur Awal", "No. Urut Faktur Akhir","Jml.Faktur","Jml.Penjualan","Deskripsi"],
				colModel: [
					{label: 'keyid', name: 'keyid', hidden: true, cellEdit: true},
					{label: 't_cust_acc_dtl_id', name: 't_cust_acc_dtl', hidden: true, cellEdit: false},
					{label: 't_cust_account_id', name: 't_cust_account', hidden: true, cellEdit: false},
					{label: 'Tanggal', name: 'Tanggal', width:100, sortable:false, hidden: false,align:'center', cellEdit: false},
					{label: 'No. Urut Faktur Awal', width:170, sortable:false, name: 'No_UrutAwal', align:'right', hidden: false, editable: true, cellEdit: true},
					{label: 'No. Urut Faktur Akhir', width:170, sortable:false, name: 'No_UrutAkhir', align:'right', hidden: false, editable: true, cellEdit: true},
					{label: 'Jml.Faktur', name: 'jum_faktur', sortable:false, width:120, hidden: false, align:'right', formatter:'currency', formatoptions: {thousandsSeparator : '.', decimalPlaces: 0},  editable: true, cellEdit: true, editrules:{number:true},
						edittype:"text", editoptions:
						{
							size: 25, maxlengh: 30,
							dataInit: function(element)
							{
								$(element).keypress(function(e)
								{
									if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
										return false;
									}
								});
							}
						}
					},
					{label: 'Jml.Penjualan', name: 'jum_penjualan', sortable:false, width:150, hidden: false, align:'right', formatter:'currency', formatoptions: {thousandsSeparator : '.', decimalPlaces: 0}, editable: true, cellEdit: true, editrules:{number:true},
						edittype:"text", editoptions:
						{
							size: 25, maxlengh: 30,
							dataInit: function(element)
							{
								$(element).keypress(function(e)
								{
									if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
										return false;
									}
								});
							}
						}
					},
					{label: 'Deskripsi', name: 'descript', hidden: false, sortable:false, editable: true, align:'center',cellEdit: true}
				],
				height: '100%',
				width:700,
				autowidth: true,
				viewrecords: true,
				rowNum: 100,
				rowList: [100,200,500],
				rownumbers: true, // show row numbers
				altRows: true,
				shrinkToFit: true,
				multiboxonly: true,
				cellEdit : true,
				cellsubmit : 'clientArray',
				onSelectRow: function (rowid){

				},
				onCellSelect: function(rowid){
				},
				ondblClickRow: function (rowid, iRow, iCol) {
					// var $this = $(this);
					$this.jqGrid('setGridParam', {cellEdit: true});
					$this.jqGrid('editCell', iRow, iCol, true);
					$this.jqGrid('setGridParam', {cellEdit: false});
				},
				beforeEditCell:function(rowid){
					// $('#simpan').unbind("click");
					$('#simpan').attr('disabled', true);
				},
				afterSubmitCell:function(){
					$('#simpan').attr('disabled', false);
				},
				afterRestoreCell:function(){
					$('#simpan').attr('disabled', true);
				},
				afterSaveCell:function(rowid){
					// alert('d');
					$('#simpan').attr('disabled', false);
				},
				beforeSaveCell:function(rowid){
					// $('#simpan').bind("click");
					$('#simpan').attr('disabled', false);
					t_cust_acc_dtl_val = $('#grid-table-laporan').jqGrid('getCell',rowid,'t_cust_acc_dtl');
					if(t_cust_acc_dtl_val.length == 0)
					{
						$("#grid-table-laporan").jqGrid('setCell', rowid, 'keyid', -1);
					} else
					{
						$("#grid-table-laporan").jqGrid('setCell', rowid, 'keyid', -99);
					};
				},
				afterEditCell: function (rowid, cellName, cellValue, iRow) {
					var cellDOM = this.rows[iRow], oldKeydown,
						$cellInput = $('input, select, textarea', cellDOM),
						events = $cellInput.data('events'),
						$this = $(this);
					if (events && events.keydown && events.keydown.length) {
						oldKeydown = events.keydown[0].handler;
						$cellInput.unbind('keydown', oldKeydown);
						$cellInput.bind('keydown', function (e) {
							$this.jqGrid('setGridParam', {cellEdit: true});
							oldKeydown.call(this, e);
							$this.jqGrid('setGridParam', {cellEdit: false});
						});
					}
					// $('#simpan').attr('disabled', false);
				},
				sortorder:'',
				pager: '#grid-pager-laporan',
				jsonReader: {
					root: 'rows',
					id: 'id',
					repeatitems: false
				},
				loadComplete: function (response) {
					if(response.success == false) {
						swal({title: 'Attention', text: response.message, html: true, type: "warning"});
					}
					//responsive_jqgrid(grid_selector,pager_selector);
				},
				//memanggil controller jqgrid yang ada di controller crud
				editurl: '',
				caption: "Tax Details"

			});
			jQuery('#grid-table-laporan').jqGrid('navGrid', '#grid-pager-laporan',
				{   //navbar options
					edit: false,
					excel: true,
					editicon: 'fa fa-pencil blue bigger-120',
					add: false,
					addicon: 'fa fa-plus-circle purple bigger-120',
					del: false,
					delicon: 'fa fa-trash-o red bigger-120',
					search: false,
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

					},
					onClick: function (e) {
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



	}


    function modal_lov_form_harian_set_field_value(the_id_field, the_code_field) {
         $("#modal_lov_form_harian_id_val").val(the_id_field);
         $("#modal_lov_form_harian_code_val").val(the_code_field);
    }

    function modal_lov_form_harian_set_value(the_id_val, the_code_val) {
         $("#"+ $("#modal_lov_form_harian_id_val").val()).val(the_id_val);
         $("#"+ $("#modal_lov_form_harian_code_val").val()).val(the_code_val);
         $("#modal_lov_form_harian").modal("toggle");

         $("#"+ $("#modal_lov_form_harian_id_val").val()).change();
    }

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
		$(grid_selector).jqGrid( 'setGridWidth', $(".modal-body").width()-20 );
		$(pager_selector).jqGrid( 'setGridWidth', parent_column.width() );

	}
</script>

