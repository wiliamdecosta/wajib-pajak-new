<script type="text/javascript" src="<?php echo base_url(); ?>/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<style>
.top-buffer { margin-top:7px; }
</style>

<!-- breadcrumb -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php base_url();?>">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>Pelaporan Pajak</span>
        </li>
    </ul>
</div>
<!-- end breadcrumb -->
<div class="space-4"></div>
	<div id="modal_lov_add_laporan" class="portlet light bordered">

		<!-- modal title -->		
		<div class="portlet-title">
			<div class="caption font-red-sunglo">
				<span class="caption-subject bold uppercase">Tambah Data Pembayaran</span>
			</div>
		</div>
		<input type="hidden" id="modal_lov_add_laporan_id_val" value="" />
		<input type="hidden" id="modal_lov_add_laporan_code_val" value="" />
		<input type="hidden" id="month_id" value="" />
		<input type="hidden" id="t_cust_acc_dtl_trans_id" value="" />
		<!-- modal body -->
		<div class="portlet-body form">
			
			<form class="form-horizontal">
			  <div class="form-group">
				<label class="col-md-2 control-label">NPWPD:</label>
				<div class="col-md-3">
				  <input type="text" class="form-control" id="npwd" readonly="" value="<?php echo $this->session->userdata('npwd'); ?>">
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-md-2 control-label">PERIODE:</label>
				<div class="col-md-3">
				  <select id="months" class="form-control"></select>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-md-2 control-label">Klasifikasi:</label>
				<div class="col-md-3">
				  <select id="klasifikasi" class="form-control"></select>
				</div>
			  </div>
			  <div class="form-group" id="rincian_form">
				<label class="col-md-2 control-label">Rincian:</label>
				<div class="col-md-3">
				  <select id="rincian" class="form-control"></select>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-md-2 control-label">Masa Pajak:</label>
				<div class="col-md-6 form-inline">
					<input class="form-control date-picker" type="text" id="datepicker" readonly=""> 
					<label>s/d</label>
					<input class="form-control date-picker" type="text" id="datepicker2" readonly="">
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-md-offset-2 col-md-6 form-inline">
					<a class="btn btn-primary" id="isiformupload">Upload File Transaksi</a>
					<label>atau</label>
					<a class="btn btn-primary" id="isiformtransaksi">Isi Form Transaksi</a>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-md-2 control-label">Nilai Omzet:</label>
				<div class="col-md-3">
				  <input class="form-control" readonly="" id="omzet_value"  style="text-align:right;">
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-md-2 control-label">Pajak yang Harus dibayar:</label>
				<div class="col-md-3">
				  <input class="form-control" readonly=""  id="val_pajak" style="text-align:right;">
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-md-2 control-label">Denda:</label>
				<div class="col-md-3">
				  <input class="form-control" readonly=""  id="val_denda" style="text-align:right;">
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-md-2 control-label">Total Bayar:</label>
				<div class="col-md-3">
				  <input class="form-control" readonly=""  id="totalBayar" style="text-align:right;">
				</div>
			  </div>
			</form>
			
			<div class="form-actions">
				<div class="row">
					<div class="col-md-offset-11">
						<button class="btn green" type="button" id="submit-btn">Submit</button>
						<input type="hidden" id="hasExcelUploaded" value=0 />
					</div>
				</div>
			</div>
		</div>			
	</div><!-- /.end modal -->

<?php  $this->load->view('pelaporan/lov_form_harian.php'); ?>
<?php  $this->load->view('pelaporan/lov_upload_file.php'); ?>

<script>
	$(document).ready(function(){
		$.ajax({
			url: "<?php echo WS_JQGRID ?>pelaporan.pelaporan_pajak_controller/p_vat_type_dtl",
			datatype: "json",            
            type: "POST",
            success: function (response) {
					var data = $.parseJSON(response);
					$('#klasifikasi').append('<option selected value='+ data.rows[0].vat_code +'>'+ data.rows[0].vat_code +'</option>');
					$('#vat_pct').append('<option value='+ data.rows[0].vat_code +' data-id='+ data.rows[0].vat_pct +' >'+ data.rows[0].vat_code +'</option>');
				}
        });
	});
	$(document).ready(function(){
		$.ajax({
			url: "<?php echo WS_JQGRID ?>pelaporan.pelaporan_pajak_controller/p_vat_type_dtl_cls",
			datatype: "json",            
            type: "POST",
            success: function (response) {
					var data1 = $.parseJSON(response);
					i=0;
					if (data1.rows.length >0){
						while(i<data1.rows.length){
							$('#rincian').append('<option value="'+ data1.rows[i].vat_code +'" data-id='+ data1.rows[i].vat_pct +'>'+ data1.rows[i].vat_code +'</option>');
						i++;	
						}
					} else{
						$('#rincian_form').hide(100);
						$('#rincian').append('<option value="" data-id= ""></option>');
					}					
			}
        });
	});	
    

    $('#months').click(function(){
		$.ajax({
            // async: false,
			url: "<?php echo WS_JQGRID ?>pelaporan.pelaporan_pajak_controller/pelaporan_bulan",
			datatype: "json",            
            type: "POST",
            success: function (response) {
				var data = $.parseJSON(response);
				i = 0;
				while(i < data.rows.length){
				var months = data.rows[i].code;
				var start_date = data.rows[i].start_date_string;
				var end_date = data.rows[i].end_date_string;
				var p_id = data.rows[i].p_finance_period_id;
				$('#months').append('<option value="'+ start_date +'" data-id="'+ end_date +'" data-idkey = "'+ p_id +'">' + months + '</option>');			
				i++;
				}
			}
        });
	});
	
	$('#months').change(function(){
		StartDate = $('#months').find(':selected').val();		
		EndDate = $('#months').find(':selected').data("id");
				
		$("#datepicker").datepicker('setDate',StartDate);
		$("#datepicker2").datepicker('setDate',EndDate);
		$('#omzet_value').val("");
		$('#val_pajak').val("");
		$('#val_denda').val("");
		$('#totalBayar').val("");		
	});
	
	$('#rincian').change(function(){
		// $('#omzet_value').val();
		nilai_pajak = $('#rincian').find(':selected').data('id');
		$('#val_pajak').val(  $('#omzet_value').val() * nilai_pajak * 0.01);
		if ($('#val_denda').val() != 0)
		{
			$('#val_denda').val(  parseFloat(0.02 * $('#val_pajak').val()).toFixed(2)  );
		}		
		$('#totalBayar').val(   parseFloat( $('#val_pajak').val() )  +  parseFloat(  $('#val_denda').val() ) );
	});
	
	
	$(function() {
        $( "#datepicker" ).datepicker();
        $( "#datepicker2" ).datepicker();
    });

	$('#isiformtransaksi').on('click',function() {
		var date = $("#datepicker").datepicker('getDate');
		var date1 = $("#datepicker2").datepicker('getDate');
		var dates = $("#datepicker").val();
		var dates1 = $("#datepicker2").val();
		var datesFormat = moment(date).format('YYYY-MM-DD');
		var dates1Format = moment(date1).format('YYYY-MM-DD');
				
		if ((dates.length != 0) && (dates1.length != 0)){
			var diffDays = Math.ceil((date1.getTime() - date.getTime())/1000/3600/24);
			var numDaysMonth = new Date(date1.getYear(), date1.getMonth()+1, 0).getDate();
			var division = parseInt($("#number").val())/numDaysMonth*diffDays;
				if (diffDays>=0){					
					modal_lov_form_harian_show(date,date1,diffDays);
				} else
				{
					swal('error','Input masa pajak tidak valid. Penanggalan awal pajak harus lebih awal dari akhir pajak','error');
				}
		} else
		{
			swal('error','Isi terlebih dahulu periode masa pajak secara lengkap','error');
		}
		
    });
	$('#isiformupload').on('click',function() {
		var dates = $("#datepicker").val();
		var dates1 = $("#datepicker2").val();
				
		if ((dates.length != 0) && (dates1.length != 0)){					
					modal_upload_file_show();				
		} else
		{
			swal('error','Isi terlebih dahulu periode masa pajak secara lengkap','error');
		}
						
    });
	
	$('#submit-btn').on('click',function() {		
		nilai_total = $('#totalBayar').val();
		
		text_submit = 	"<h5>Wajib Pajak yang terhormat, "+
						"Anda Melaporkan pajak daerah untuk : </h5>"+
						"<pre style='text-align:left;'>" + 
						"NPWPD 		 	: "+ $('#npwd').val() + "\n" +
						"Klasifikasi 		: "+ $('#klasifikasi').find(':selected').val() + "\n" +
						"Masa Pajak  		: "+ $('#months').find(':selected').val() + "\n" +
						"Pajak Pokok 		: Rp."+ $('#val_pajak').val() + "\n" +
						"Denda 		 	: Rp."+ $('#val_denda').val() + "\n" +
						"Jumlah Pajak yang harus dibayar : Rp."+ $('#totalBayar').val() +
						"</pre>"+
						"<h5>Apakah anda yakin akan mengirim laporan dimaksud?</h5>";
		if(nilai_total.length == 0 && $('#hasExcelUploaded').val() == 0)
		{
			swal('Error','Harap mengisi data secara lengkap sebelum submit','error');
		} else
		{
			swal(
			{
				title: "<b>Konfirmasi</b>",
				html: text_submit,
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Ya",
				cancelButtonText: "Tidak",
				confirmButtonClass: 'btn btn-success',
				cancelButtonClass: 'btn btn-danger'
				// closeOnConfirm: true,
				// closeOnCancel: true
			}).then	
			(function()
				{
					items = new Array();
						items.push 
						({
									'user_name' : '<?php echo $this->session->userdata('user_name'); ?>',
									'npwd' : '<?php echo $this->session->userdata('npwd'); ?>',	
									't_cust_accounts_id' : parseInt(<?php echo $this->session->userdata('cust_account_id'); ?>),	
									'finance_period' : $('#months').find(':selected').data("idkey"),	
									'p_vat_type_dtl_id' : <?php echo $this->session->userdata('vat_type_dtl'); ?>,	
									'p_vat_type_dtl_cls_id' : '',	
									'start_period' : moment($('#datepicker').val()).format('DD-MM-YYYY'),	
									'end_period' : moment($('#datepicker2').val()).format('DD-MM-YYYY'),	
									'total_trans_amount' :  $('#omzet_value').val(),	
									'total_vat_amount' : $('#totalBayar').val()
						});	
						$.ajax
						({
							url: "<?php echo WS_JQGRID ?>transaksi.t_vat_settlement_controller/createSPTPD",
							datatype: "json",            
							type: "POST",
							data: 
								{
									end_period : moment($('#datepicker2').val()).format('DD-MM-YYYY'),
									items: JSON.stringify(items),
									t_cust_account_id : parseInt(<?php echo $this->session->userdata('cust_account_id'); ?>),
									npwd : '<?php echo $this->session->userdata('npwd'); ?>',
									p_finance_period : $('#months').find(':selected').val(),
									p_vat_type_dtl_cls_id : '',
									p_vat_type_dtl_id : <?php echo $this->session->userdata('vat_type_dtl'); ?>,
									penalty_amount : $('#val_denda').find(':selected').val(),
									percentage : 7,
									start_period : 	moment($('#datepicker').val()).format('YYYY-MM-DD'),
									t_cust_account_id : <?php echo $this->session->userdata('cust_account_id');?>,
									total_amount :  $('#totalBayar').find(':selected').val(),
									total_trans_amount : $('#omzet_value').val(),
									total_vat_amount : $('#totalBayar').val()
								},
							success: function (response) 
								{
									var data = $.parseJSON(response);
									swal('info',data.items.o_mess,'info');
								}
						});
				},
				function(dismiss) {
					
				}
			);
		}
		
	});
</script>