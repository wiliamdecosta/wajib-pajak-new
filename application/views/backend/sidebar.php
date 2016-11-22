<!-- BEGIN SIDEBAR MENU -->
<ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
    <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
    <li class="sidebar-toggler-wrapper hide">
        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        <div class="sidebar-toggler">
            <span></span>
        </div>
        <!-- END SIDEBAR TOGGLER BUTTON -->
    </li>
    <li class="sidebar-search-wrapper">
        <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
        <!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
        <!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->
        <div class="sidebar-search">
            <a class="remove" href="javascript:;">
                <i class="icon-close"></i>
            </a>
            <div class="input-group">
                <input type="text" placeholder="Filter Menu..." class="form-control" id="search-menu">
                <span class="input-group-btn">
                    <a class="btn submit" href="javascript:;">
                        <i class="icon-magnifier"></i>
                    </a>
                </span>
            </div>
        </div>
        <!-- END RESPONSIVE QUICK SEARCH FORM -->
    </li>
    <li class="nav-item active" data-source="dashboard">
        <a href="#" class="nav-link nav-toggle">
            <i class="fa fa-institution"></i>
            <span class="title">Dashboard</span>
        </a>
    </li>

    <li class="nav-item" data-source="pelaporan.lov_add_laporan">
        <a href="#" class="nav-link nav-toggle">
            <i class="fa fa-newspaper-o"></i>
            <span class="title">Pelaporan Pajak</span>
        </a>
    </li>

    <li class="nav-item" data-source="history.history_transaksi">
        <a href="#" class="nav-link nav-toggle">
            <i class="fa fa-history"></i>
            <span class="title">History Transaksi</span>
        </a>
    </li>
	
	<li class="nav-item" data-source="transaksi.transaksi_harian">
        <a href="#" class="nav-link nav-toggle">
            <i class="fa fa-calendar-check-o"></i>
            <span class="title">Transaksi Harian WP</span>
        </a>
    </li>
	
	<li class="nav-item" data-source="">
		<a href="#" class="nav-link nav-toggle">
			<i class="fa fa-wifi"></i>
			<span class="title"> Tapping Modem</span>
			<span class="arrow open"></span>
		</a>
		<ul class="sub-menu" style="display: block;">
			<li class="nav-item" data-source="modem.tapping_modem">
				<a href="#" class="nav-link">
					<span class="title">Harian</span>
				</a>
			</li>
			<li class="nav-item" data-source="modem.tapping_modem_bulanan">
				<a href="#" class="nav-link">
					<span class="title">Bulanan</span>
				</a>
			</li>
		</ul>
	</li>
	
	

<!--    <li class="nav-item" data-source="message.inbox_message">
        <a href="#" class="nav-link nav-toggle">
            <i class="fa fa-inbox"></i>
            <span class="title">Inbox</span>
        </a>
    </li>

    <li class="nav-item" data-source="outbox">
        <a href="#" class="nav-link nav-toggle">
            <i class="fa fa-envelope-o"></i>
            <span class="title">Outbox</span>
        </a>
    </li> 
-->


</ul>
<!-- END SIDEBAR MENU -->