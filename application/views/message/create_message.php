<link rel="stylesheet" href="assets/global/plugins/bootstrap-summernote/summernote.css">
<script src="assets/global/plugins/bootstrap-summernote/summernote.min.js"></script>
<script src="assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
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
	<div class="col-md-2">
		<label>Jenis Pesan:</label>
	</div>
	<div class="col-md-2">
		<select id="tpref" class="form-control">
			<option value="1">Komplain</option>
			<option value="2">Saran</option>
		</select>
	</div>
</div>

<div class="space-4"></div>

<div class="row">
	<div class="col-md-2">
		<label>Isi:</label>
	</div>
	<div class="col-md-10">
		<div class="summernote"></div>
	</div>
</div>
<div class="row">
	<div class="col-md-offset-10 col-md-2">
		<div><a id="SPTPD" class="btn blue"> Kirim File </a></div>
	</div>
</div>
<script>
$(document).ready(function() {
  $('.summernote').summernote({minHeight: 275});
});
</script>