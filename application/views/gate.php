<?php $this->load->view('frontend/header'); ?>
<style>
.boxhead a {
    color: #FFFFFF;
    text-decoration: none;
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

            </ul>
        </div>
    </div>

	<?php

	?>

	<section class="call-to-action call-to-action-default call-to-action-in-footer mt-none no-top-arrow">
		<!-- <div class="vc_btn3-container vc_btn3-inline">
			<button class="vc_general vc_btn3 vc_btn3-size-lg vc_btn3-shape-rounded vc_btn3-style-flat vc_btn3-color-success vc_label"><?php echo $value_title ?></button>
		</div>
		</div> -->
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<h2 class="mb-none"><?php echo $title ?></h2>
					<hr class="tall">
				</div>
			</div>

			<div class="featured-boxes featured-boxes-flat">
				<div class="row">
					<div class="col-md-4 col-sm-6 col-md-offset-2">
						<div class="boxhead">
							<a href="<?php echo $link_daftar ?>">
								<div class="featured-box featured-box-tertiary featured-box-effect-5" style="height: 203px;">
									<div class="box-content">
										<i class="icon-featured fa fa-laptop"></i>
										<h1>Pendaftaran</h1>
										<p>Bila Anda belum mempunyai NPWPD, klik disini</p>
									</div>
								</div>
							</a>
						</div>
					</div>
					<div class="col-md-4 col-sm-6">
						<div class="boxhead" id="loginbutton">
							<a href="<?php echo $link_login ?>" >
								<div class="featured-box featured-box-tertiary featured-box-effect-5" style="height: 203px;">
									<div  class="box-content">
										<i class="icon-featured fa fa-user"></i>
										<h1>Login</h1>
										<p>Segera lakukan pelaporan pajak Anda tepat waktu</p>
									</div>
								</div>
							</a>
						</div>
					</div>

				</div>
			</div>
			<div class="row">
				<div class="col-md-2 col-md-offset-5">
					<a href="<?php echo base_url();?>" class="btn btn-success btn-lg btn-block">
						<i class="fa fa-arrow-left"></i>
						Kembali
					</a>
				</div>
			</div>
		</div>
	</section>
<script>

</script>
<?php $this->load->view('frontend/footer'); ?>