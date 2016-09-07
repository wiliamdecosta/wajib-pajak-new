<?php $this->load->view('frontend/header'); ?>

	<section class="call-to-action call-to-action-default call-to-action-in-footer mt-none no-top-arrow">
		<!-- <div class="vc_btn3-container vc_btn3-inline">
			<button class="vc_general vc_btn3 vc_btn3-size-lg vc_btn3-shape-rounded vc_btn3-style-flat vc_btn3-color-success vc_label"><?php echo $value_title ?></button>
		</div>
		</div> -->
		<div class="container">

			<?php if($this->session->flashdata('error_message') != ""): ?>
			<div class="row">
				<div class="col-md-12">
					<div class="alert alert-secondary alert-dismissible" role="alert">
						<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
						<strong><?php echo $this->session->flashdata('error_message'); ?></strong>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<div class="row">
			    <div class="col-md-12">
			        <div class="featured-boxes">
			            <div class="row">
			                <div class="col-sm-6 col-sm-offset-3">
			                    <div class="featured-box featured-box-primary align-left mt-xlg" style="height: 327px;">
			                        <div class="box-content">
			                            <h4 class="heading-primary text-uppercase mb-md">Login</h4>
			                            <form method="post" action="<?php echo $login_url; ?>">
			                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

			                                <div class="row">
			                                    <div class="form-group">
			                                        <div class="col-md-12">
			                                            <label>Username</label>
			                                            <input type="text" name="username" class="form-control input-lg" value="">
			                                        </div>
			                                    </div>
			                                </div>
			                                <div class="row">
			                                    <div class="form-group">
			                                        <div class="col-md-12">
			                                            <label>Password</label>
			                                            <input type="password" name="password" class="form-control input-lg" value="">
			                                        </div>
			                                    </div>
			                                </div>
			                                <div class="row">
			                                    <div class="col-md-6">
			                                    </div>
			                                    <div class="col-md-6">
			                                        <a href="<?php echo base_url(); ?>" class="btn btn-primary mb-xl">
			                                        	<i class="fa fa-arrow-left"></i> Kembali
			                                        </a>
			                                        <input type="submit" class="btn btn-primary mb-xl" value="Login">
			                                    </div>
			                                </div>
			                            </form>
			                        </div>
			                    </div>
			                </div>
			            </div>

			        </div>
			    </div>
			</div>
		</div>
	</section>
<script>

</script>
<?php $this->load->view('frontend/footer'); ?>