<?php $this->load->view('frontend/header'); ?>
<style>

.boxhead a {
    color: #ffffff;
    text-decoration: none;
}

.boxhead h1,h2,h3,h4 {
    color: #2baab1 !important;
    font-weight:bold !important;
    margin-top:15px !important;
}


.box-outside-pajak {
	position:relative;
	height: 240px;
}

.box-jenis-pajak {
	height: 240px;
}

.box-detail-pajak {
	position:absolute;
	bottom:-120px;
}

.box-detail-pajak p {
	text-align:center;
	margin:0px auto !important;
	font-size:12px;
	color: #000000;
}

.box-color {
	background:#edebdf;
}
</style>
	<div class="slider-container rev_slider_wrapper" style="height: 400px;">
		<div id="revolutionSlider" class="slider rev_slider" data-plugin-revolution-slider data-plugin-options='{"delay": 9000, "gridwidth": 800, "gridheight": 400}'>
			<ul>
				<li data-transition="fade">
					<img src="slider/slider1.jpg"
						alt=""
						data-bgposition="center center"
						data-bgfit="cover"
						data-bgrepeat="no-repeat"
						class="rev-slidebg">
				</li>
				<li data-transition="fade">
					<img src="slider/slider2.jpg"
						alt=""
						data-bgposition="center center"
						data-bgfit="cover"
						data-bgrepeat="no-repeat"
						class="rev-slidebg">
				</li>

				<li data-transition="fade">
					<img src="slider/disyanjak_slider.jpg"
						alt=""
						data-bgposition="center center"
						data-bgfit="cover"
						data-bgrepeat="no-repeat"
						class="rev-slidebg">
				</li>

			</ul>
		</div>
	</div>

	<section class="call-to-action call-to-action-default call-to-action-in-footer mt-none no-top-arrow" >
		<div class="container">
			<div class="featured-boxes featured-boxes-flat">
				<div class="row">

					<div class="col-md-4 col-sm-6">
						<div class="boxhead">
							<a href="<?php echo base_url().'gate?type=hotel'?>">
								<div class="image-gallery-item">
									<span class="thumb-info thumb-info-centered-info thumb-info-no-borders mt-lg box-color">
										<span class="thumb-info-wrapper" id="cbg">
											<div class="boxhead">
													<div class="box-content box-outside-pajak">
														<div class="col-md-12">
															<h2>Hotel</h2>
															<div class="row">
															<div class="space-4"></div>
																<div class="col-xs-12 box-detail-pajak" >
																	<p>Peraturan Walikota Nomor 386 tahun 2012</p>
																</div>
															</div>
														</div>
													</div>
											</div>
											<span class="thumb-info-title">
												<span class="thumb-info-inner"><img alt="" class="img-responsive" src="<?php echo base_url().'theme/img/homepages/hotel1s.png'?>"></span>
											</span>
										</span>
									</span>
								</div>
							</a>
						</div>
					</div>


					<div class="col-md-4 col-sm-6">
						<div class="boxhead">
							<a href="<?php echo base_url().'gate?type=restoran'?>">
								<div class="image-gallery-item" >
									<span class="thumb-info thumb-info-centered-info thumb-info-no-borders mt-lg box-color">
										<span class="thumb-info-wrapper" id="cbg">
											<div class="boxhead">
													<div class="box-content box-outside-pajak">
														<div class="col-md-12">
															<h2>Restoran</h2>
															<div class="row">
															<div class="space-4"></div>
																<div class="col-xs-12 box-detail-pajak">
																	<p>Peraturan Walikota Nomor 387 tahun 2012</p>
																</div>
															</div>
														</div>
													</div>
											</div>
											<span class="thumb-info-title">
												<span class="thumb-info-inner"><img alt="" class="img-responsive" src="<?php echo base_url().'theme/img/homepages/restoran5.png'?>"></span>
											</span>
										</span>
									</span>
								</div>
							</a>
						</div>
					</div>

					<div class="col-md-4 col-sm-6">
						<div class="boxhead">
							<a href="<?php echo base_url().'gate?type=hiburan'?>">
								<div class="image-gallery-item">
									<span class="thumb-info thumb-info-centered-info thumb-info-no-borders mt-lg box-color">
										<span class="thumb-info-wrapper" id="cbg">
											<div class="boxhead">
													<div class="box-content box-outside-pajak">
														<div class="col-md-12">
															<h2>Hiburan</h2>
															<div class="row">
															<div class="space-4"></div>
																<div class="col-xs-12 box-detail-pajak">
																	<p>Peraturan Walikota Nomor 388 tahun 2012</p>
																</div>
															</div>
														</div>
													</div>
											</div>
											<span class="thumb-info-title">
												<span class="thumb-info-inner"><img alt="" class="img-responsive" src="<?php echo base_url().'theme/img/homepages/Hiburan23.png'?>"></span>
											</span>
										</span>
									</span>
								</div>
							</a>
						</div>
					</div>

					<div class="col-md-4 col-sm-6">
						<div class="boxhead">
							<a href="<?php echo base_url().'gate?type=reklame'?>">
								<div class="image-gallery-item">
									<span class="thumb-info thumb-info-centered-info thumb-info-no-borders mt-lg box-color">
										<span class="thumb-info-wrapper" id="cbg">
											<div class="boxhead">
													<div class="box-content box-outside-pajak">
														<div class="col-md-12">
															<h2>Reklame</h2>
															<div class="row">
															<div class="space-4"></div>
																<div class="col-xs-12 box-detail-pajak">
																	<p>Peraturan Walikota Nomor 389 tahun 2012</p>
																</div>
															</div>
														</div>
													</div>
											</div>
											<span class="thumb-info-title">
												<span class="thumb-info-inner"><img alt="" class="img-responsive" src="<?php echo base_url().'theme/img/homepages/reklame1.png'?>"></span>
											</span>
										</span>
									</span>
								</div>
							</a>
						</div>
					</div>

					<div class="col-md-4 col-sm-6">
						<div class="boxhead">
							<a href="<?php echo base_url().'gate?type=penjal'?>">
								<div class="image-gallery-item">
									<span class="thumb-info thumb-info-centered-info thumb-info-no-borders mt-lg box-color">
										<span class="thumb-info-wrapper" id="cbg">
											<div class="boxhead">
													<div class="box-content box-outside-pajak">
														<div class="col-md-12">
															<h2>Penerangan Jalan</h2>
															<div class="row">
															<div class="space-4"></div>
																<div class="col-xs-12 box-detail-pajak">
																	<p>Peraturan Walikota Nomor 390 tahun 2012</p>
																</div>
															</div>
														</div>
													</div>
											</div>
											<span class="thumb-info-title">
												<span class="thumb-info-inner"><img alt="" class="img-responsive" src="<?php echo base_url().'theme/img/homepages/Penerangan-jalan3.png'?>"></span>
											</span>
										</span>
									</span>
								</div>
							</a>
						</div>
					</div>

					<div class="col-md-4 col-sm-6">
						<div class="boxhead">
							<a href="<?php echo base_url().'gate?type=parkir'?>">
								<div class="image-gallery-item">
									<span class="thumb-info thumb-info-centered-info thumb-info-no-borders mt-lg box-color">
										<span class="thumb-info-wrapper" id="cbg">
											<div class="boxhead">
													<div class="box-content box-outside-pajak">
														<div class="col-md-12">
															<h2>Parkir</h2>
															<div class="row">
															<div class="space-4"></div>
																<div class="col-xs-12 box-detail-pajak">
																	<p>Peraturan Walikota Nomor 391 tahun 2012</p>
																</div>
															</div>
														</div>
													</div>
											</div>
											<span class="thumb-info-title">
												<span class="thumb-info-inner"><img alt="" class="img-responsive" src="<?php echo base_url().'theme/img/homepages/parkir.png'?>"></span>
											</span>
										</span>
									</span>
								</div>
							</a>
						</div>
					</div>

					<div class="col-md-4 col-sm-6">
						<div class="boxhead">
							<a href="<?php echo base_url().'gate?type=pat'?>">
								<div class="image-gallery-item">
									<span class="thumb-info thumb-info-centered-info thumb-info-no-borders mt-lg box-color" >
										<span class="thumb-info-wrapper" id="cbg">
											<div class="boxhead">
													<div class="box-content box-outside-pajak">
														<div class="col-md-12">
															<h2>Pajak Air Tanah</h2>
															<div class="row">
															<div class="space-4"></div>
																<div class="col-xs-12 box-detail-pajak">
																	<p>Peraturan Walikota Nomor 392 tahun 2012</p>
																</div>
															</div>
														</div>
													</div>
											</div>
											<span class="thumb-info-title">
												<span class="thumb-info-inner"><img alt="" class="img-responsive" src="<?php echo base_url().'theme/img/homepages/air1.png'?>"></span>
											</span>
										</span>
									</span>
								</div>
							</a>
						</div>
					</div>

					<div class="col-md-4 col-sm-6">
						<div class="boxhead">
							<a href="<?php echo base_url().'gate?type=bphatb'?>">
								<div class="image-gallery-item">
									<span class="thumb-info thumb-info-centered-info thumb-info-no-borders mt-lg box-color">
										<span class="thumb-info-wrapper" id="cbg">
											<div class="boxhead">
													<div class="box-content box-outside-pajak">
														<div class="col-md-12">
															<h4>Bea Perolehan Hak Atas Tanah dan Bangunan</h4>
															<div class="row">
															<div class="space-4"></div>
																<div class="col-xs-12 box-detail-pajak">
																	<p>Peraturan Walikota Nomor 393 tahun 2012</p>
																</div>
															</div>
														</div>
													</div>
											</div>
											<span class="thumb-info-title">
												<span class="thumb-info-inner"><img alt="" class="img-responsive" src="<?php echo base_url().'theme/img/homepages/BPHTB1.png'?>"></span>
											</span>
										</span>
									</span>
								</div>
							</a>
						</div>
					</div>

					<div class="col-md-4 col-sm-6">
						<div class="boxhead">
							<a href="<?php echo base_url().'gate?type=pbb'?>">
								<div class="image-gallery-item">
									<span class="thumb-info thumb-info-centered-info thumb-info-no-borders mt-lg box-color">
										<span class="thumb-info-wrapper" id="cbg">
											<div class="box-content box-outside-pajak">
												<div class="col-md-12">
													<h3>Pajak Bumi dan Bangunan</h3>
													<div class="row">
													<div class="space-4"></div>
														<div class="col-xs-12 box-detail-pajak">
															<p></p>
														</div>
													</div>
												</div>
											</div>
											<span class="thumb-info-title">
												<span class="thumb-info-inner"><img alt="" class="img-responsive" src="<?php echo base_url().'theme/img/homepages/BUMI-BANGUNAN1.png'?>"></span>
											</span>
										</span>
									</span>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php $this->load->view('frontend/footer'); ?>

<script>
</script>