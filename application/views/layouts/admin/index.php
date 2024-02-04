<?php
$project = ($_SERVER['SERVER_NAME']=='localhost') ? strtoupper(substr($_SERVER['PHP_SELF'],5,3)): 'Admin';

//Configuration User Menu Display From Session, 0=Vertical, 1=Horizontal
$user_menu_style = intval($session['menu_display']);
if($user_menu_style == 0){
	$body_class 				= '';
	$horizontal_menu_div_style  = 'display:none;';
	$horizontal_logo_style 		= 'display:none;';
}elseif($user_menu_style == 1){
	$body_class 				= 'horizontal-menu';
	$horizontal_menu_div_style 	= 'display:block;';
	$horizontal_logo_style 		= 'display:block;';
}
$switch_do = !empty($this->session->flashdata('switch_branch')) ?  intval($this->session->flashdata('switch_branch')) : 0;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

    <link rel="manifest" href="<?php echo base_url();?>manifest.json">
    <link rel="shortcut icon" href="<?php echo base_url();?>assets/webarch/favicon.png" type="image/png">

    <!-- Mendeklarasikan warna yang muncul pada address bar Chrome versi seluler -->
    <meta name="theme-color" content="#fff" />

    <!-- Mendeklarasikan ikon untuk iOS -->
    <!-- <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default" />
    <meta name="apple-mobile-web-app-title" content="Nama Situs" />
    <link rel="apple-touch-icon" href="path/to/icons/128x128.png" /> -->

    <!-- Mendeklarasikan ikon untuk Windows -->
    <!-- <meta name="msapplication-TileImage" content="path/to/icons/128x128.png" />
    <meta name="msapplication-TileColor" content="#000000" /> -->

    <meta content="" name="description"/>
	<meta content="" name="author"/>
	
	<title><?php echo $project;?> : <?php echo $title; ?> : <?php echo ucfirst($session['user_data']['user_name']);?></title>
	<link href="<?php echo base_url();?>assets/core/favicon.png" sizes="16x16 32x32" type="image/png" rel="icon"> 

	<!-- Core CSS -->
	<link href="<?php echo base_url();?>assets/core/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url();?>assets/core/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url();?>assets/core/css/<?php echo !empty($theme['user_theme']) ? $theme['user_theme'] : 'white'; ?>.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url();?>assets/core/css/custom.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url();?>assets/core/css/webarch.css" rel="stylesheet" type="text/css"/>
	<!-- <link href="<?php #echo base_url();?>assets/webarch/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen" /> -->
	<!-- <link href="<?php #echo base_url();?>assets/webarch/plugins/animate.min.css" rel="stylesheet" type="text/css"/> -->
	<!-- <link href="<?php #echo base_url();?>assets/webarch/css/dark.css" rel="stylesheet" type="text/css"/>	 -->
	
	<!-- Icon & Notification -->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="<?php echo base_url();?>assets/core/plugins/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />   
	<!-- <link href="<?php #echo base_url();?>assets/core/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/> -->
	<link href="<?php echo base_url();?>assets/core/plugins/jquery-notifications/css/messenger.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo base_url();?>assets/core/plugins/jquery-notifications/css/messenger-theme-flat.css" rel="stylesheet" type="text/css" media="screen"/>  
	<link href="<?php echo base_url();?>assets/core/plugins/sweetalert2/sweetalert2.min.css"  rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url();?>assets/core/plugins/toastr/toastr.min.css">   

	<!-- Form, Confirm, Select, Image -->
	<link href="<?php echo base_url();?>assets/core/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url();?>assets/core/plugins/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/core/plugins/bootstrap-clockpicker/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo base_url();?>assets/core/plugins/select2-4.0.8/css/select2.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="<?php echo base_url();?>assets/core/plugins/jconfirm-3.3.4/dist/jquery-confirm.min.css" rel="stylesheet">  
	<link href="<?php echo base_url();?>assets/core/plugins/croppie/css/croppie.css" rel="stylesheet" type="text/css"/>

	<!-- Datatable -->
	<link href="<?php echo base_url();?>assets/core/plugins/datatables-1.10.24/jquery.dataTables.css" rel="stylesheet" type="text/css"/>
	
	<!-- <link href="<?php #echo base_url();?>assets/core/plugins/datatables-1.13.6/DataTables-1.13.6/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>		 -->
	<!-- <link href="<?php #echo base_url();?>assets/core/plugins/datatables-1.13.6/DataTables-1.13.6/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>	 -->
	<link href="<?php echo base_url();?>assets/core/plugins/datatables-1.13.6/FixedColumns-4.3.0/css/fixedColumns.bootstrap.min.css" rel="stylesheet" type="text/css"/>

	<!-- <link href="<?php #echo base_url();?>assets/core/plugins/datatables-1.10.24/css/responsive.dataTables.min.css" rel="stylesheet" type="text/css"/> -->
	<!-- <link href="<?php #echo base_url();?>assets/core/plugins/datatables-1.10.24/css/rowReorder.dataTables.min.css" rel="stylesheet" type="text/css"/> -->
	<!-- <link href="<?php #echo base_url();?>assets/core/plugins/datatables-1.10.24/css/datatables.checbox.min.css" rel="stylesheet" type="text/css"/> -->
		
	<!-- Other -->
	<link href="<?php echo base_url();?>assets/core/plugins/magnific-popup/magnific-popup.css" rel="stylesheet">

	<!-- Third Party -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.css" rel="stylesheet">
	<!-- <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet"> -->
	<style>
        .btn_switch_branch{
            padding:10px 5px;
        }
        .btn_switch_branch p {
            color:var(--back-primary)!important;
        }
        .btn_switch_branch:hover p{
            color:var(--theme-font)!important;
        }		
        .btn_switch_branch:hover, .btn_switch_branch:active, .btn_switch_branch:focus, .btn_switch_branch:visited, .btn_switch_branch:target{
            background-color: var(--back-primary);
        }
        .btn_switch_branch:hover p, .btn_switch_branch:active p, .btn_switch_branch:focus p, .btn_switch_branch:visited p, .btn_switch_branch:target p{
            color:var(--theme-font);
        }         
    </style>	  	
</head>
<body class="<?php echo $body_class; ?>"> <!-- horizontal-menu -->
	<?php include "header.php"; ?>
	<div class="page-container row">
		<?php 
			include "sidebar_menu.php";                  
		?>
		<div id="page-content" class="page-content">
			<?php include "header_menu.php"; ?>
			<div id="portlet-config" class="modal">
				<div class="modal-header">
					<button data-dismiss="modal" class="close" type="button"></button>
					<h3>Widget Settings</h3>
				</div>
				<div class="modal-body"> Widget settings form goes here 
				</div>
			</div>
			<div class="clearfix"></div>
			<div id="content" class="content col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<?php 
				if(isset($_view) && $_view)
					$this->load->view($_view);
				if(isset($_js_file) && $_js_file)
					$this->load->view($_js_file);
				?>                    
			</div>
		</div>
	  	<?php #include "chat.php";?>
	</div>
	<!-- Modal -->
	<div class="modal fade" id="modal-form" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div id="modal-size" class="modal-dialog">
			<div class="modal-content">
				<form id="form-modal">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
						<h4 class="modal-title" id="myModalLabel">
							Modal Header
						</h4>
					</div>
					<div class="modal-body" style="background: white!important;">
					</div>
					<div class="modal-footer hide">            
					</div>
				</form>
			</div>
		</div>
	</div> 
	<div class="modal fade" id="modal-search-stock" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div id="modal-size" class="modal-dialog">
			<div class="modal-content modal-lg">
				<form id="form-modal-search-stock">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel">
							Cari Stok Produk
						</h4>
						<!-- <p>Ketikkan nama / kode produk untuk mencari tahu saldo akhir setiap lokasi</p> -->
						<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="">
							<span aria-hidden="true" style="color:#888888;">&times;</span>
						</button>
					</div>
					<div class="modal-body" style="">
						<div class="grid simple">
            				<div class="grid-body">
								<div class="row">
									<div class="col-md-12 col-xs-12 col-sm-12">
										<b>Filter Produk Spesifik</b>
										<p>Ketikkan nama / kode produk untuk mencari saldo akhir setiap gudang</p>
									</div>
									<div class="col-md-12 col-xs-12 col-sm-12">
										<div class="form-group">
											<!-- <label class="form-label">Cari Stok Barang Setiap Gudang</label> -->
											<select id="header-goods" name="header-goods" class="form-control">
											</select>
										</div>
									</div>
									<div class="col-md-12 col-xs-12 col-sm-12 table-responsive">
										<table id="table-stock" class="table table-bordered">
											<thead>
												<th>Gudang</th>
												<th class="text-right">Stok</th>
												<th>Action</th>
											</thead>
											<tbody>
												<tr>
													<td colspan="3">Data tidak ada</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="grid simple">
            				<div class="grid-body">
								<div class="row">								
									<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:8px;">
										<div class="col-md-12 col-xs-12 col-sm-12">
											<b>Filter Produk Tingkat Lanjut</b>
											<p>Ketikkan nama / kode produk yang memeliki kemiripan nama</p>
										</div>
										<div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 form-group">
											<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
												<label class="form-label">Produk</label>
												<input id="filter_table_dashboard_stock_search" name="filter_table_dashboard_stock_search" class="form-control" style="border-radius:1px!important;" placeholder="Ketik Nama Produk (minimal 3 huruf)">
											</div>
										</div> 									
										<div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 form-group">
											<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
												<label class="form-label">Gudang</label>
												<select id="filter_table_dashboard_stock_location" name="filter_table_dashboard_stock_location" class="form-control">
													<option value="0">-- Semua --</option>
												</select>
											</div>
										</div>                 
										<div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group">
											<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
												<label class="form-label">Sorting</label>
												<select id="filter_table_dashboard_stock_order" name="filter_table_dashboard_stock_order" class="form-control">
													<option value="1">Nama</option>
													<option value="0">Kode</option>
												</select>
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group">
											<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
											<label class="form-label">Urutan</label>
												<select id="filter_table_dashboard_stock_dir" name="filter_table_dashboard_stock_dir" class="form-control">
													<option value="asc">Ascending</option>
													<option value="desc">Descending</option>
												</select>
											</div>
										</div>                                      
									</div> 							
									<div class="col-md-12 col-xs-12 col-sm-12">
										<table id="table_dashboard_stock" class="table table-bordered" style="width:100%;">
											<thead>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">            
					</div>
				</form>
			</div>
		</div>
	</div> 
	<div class="modal fade" id="modal-search-product-history" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div id="modal-size" class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="form-modal-search-product-history">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel">
							Cari Riwayat Produk
						</h4>
						<p>Ketikkan kode / nama produk untuk mencari tahu riwayat harga jual maupun beli terakhir</p>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="">
							<span aria-hidden="true" style="color:#888888;">&times;</span>
						</button>
					</div>
					<div class="modal-body" style="">
						<div class="row">
							<div class="col-md-6 col-xs-12 col-sm-12">
								<div class="form-group">
									<label class="form-label">Filter Produk</label>
									<select id="header-goods-history" name="header-goods-history" class="form-control">
									</select>
								</div>
							</div>
							<div class="col-md-6 col-xs-12 col-sm-12">
								<div class="form-group">
									<label class="form-label">Filter Customer / Supplier</label>
									<select id="header-contact-history" name="header-contact-history" class="form-control">
									</select>
								</div>
							</div>
							<div class="col-md-12 col-xs-12 col-sm-12 table-responsive">
								<table id="table-product-history" class="table table-bordered">
									<thead>
										<th>Tanggal</th>
										<th class="text-left">Transaksi</th>
										<th class="text-right">Harga Beli</th>
										<th class="text-right">Qty</th>
										<th class="text-right">Harga Jual</th>
										<th class="text-right">Qty</th>
									</thead>
									<tbody>
										<tr>
											<td colspan="6">Data tidak ada</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="modal-footer">
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modal-product-stock-min" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div id="modal-size" class="modal-dialog">
			<div class="modal-content">
				<form id="form-modal-product-stock-min">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel">
							Produk Mendekati Stok Minimal
						</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="">
							<span aria-hidden="true" style="color:#888888;">&times;</span>
						</button>
					</div>
					<div class="modal-body" style="">
						<div class="row">
							<div class="col-md-12 col-xs-12 col-sm-12">
								<table id="table-product-stock-min" class="table table-bordered">
									<thead>
										<th>Produk</th>
										<th class="text-right">Stok Minimal</th>
										<th class="text-right">Stok Saat Ini</th>
										<th>Action</th>
									</thead>
									<tbody>
										<tr>
											<td colspan="4">Data tidak ada</td>
										</tr>
									</tbody>
								</table>
							</div>                
						</div>
					</div>
					<div class="modal-footer">
					</div>
				</form>
			</div>
		</div>
	</div>	 	
	<div class="modal fade" id="modal-search-trans-over-due" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div id="modal-size" class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="form-modal-search-trans-over-due">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel">
							Cari Data Jatuh Tempo
						</h4>
						<p>Ketikkan customer/supplier untuk melihat data yang telah jatuh tempo</p>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="">
							<span aria-hidden="true" style="color:#888888;">&times;</span>
						</button>
					</div>
					<div class="modal-body" style="">
						<div class="row">
							<div class="col-md-6 col-xs-12 col-sm-12">
								<div class="form-group">
									<label class="form-label">Filter Customer</label>
									<select id="header-trans-date-due-customer" name="header-trans-date-due-customer" class="form-control">
									</select>
								</div>
							</div>
							<div class="col-md-6 col-xs-12 col-sm-12">
								<div class="form-group">
									<label class="form-label">Filter Supplier</label>
									<select id="header-trans-date-due-supplier" name="header-trans-date-due-supplier" class="form-control">
									</select>
								</div>
							</div>
							<div class="col-md-6 col-xs-6 col-sm-6 table-responsive">
								<table id="table-trans-date-due-sales" class="table table-bordered">
									<thead>
										<th>Jatuh Tempo</th>
										<th class="text-left">Transaksi</th>
									</thead>
									<tbody>
										<tr>
											<td colspan="2">Data tidak ada</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="col-md-6 col-xs-6 col-sm-6 table-responsive">
								<table id="table-trans-date-due-purchase" class="table table-bordered">
									<thead>
										<th>Jatuh Tempo</th>
										<th class="text-left">Transaksi</th>
									</thead>
									<tbody>
										<tr>
											<td colspan="2">Data tidak ada</td>
										</tr>
									</tbody>
								</table>
							</div>				
						</div>
					</div>
					<div class="modal-footer">
					</div>
				</form>
			</div>
		</div>
	</div>	
	<div class="modal fade" id="modal-search-down-payment" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div id="modal-size" class="modal-dialog">
			<div class="modal-content modal-md">
				<form id="form-modal-search-stock">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel">
							Cari Down Payment
						</h4>
						<!-- <p>Ketikkan nama / kode produk untuk mencari tahu saldo akhir setiap lokasi</p> -->
						<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="">
							<span aria-hidden="true" style="color:#888888;">&times;</span>
						</button>
					</div>
					<div class="modal-body" style="">
						<div class="grid simple">
            				<div class="grid-body">
								<div class="row">
									<div class="col-md-12 col-xs-12 col-sm-12">
										<b>Filter Customer Spesifik</b>
										<p>Ketikkan nama / kode customer untuk mencari saldo akhir down payment</p>
									</div>
									<div class="col-md-12 col-xs-12 col-sm-12">
										<div class="form-group">
											<!-- <label class="form-label">Cari Stok Barang Setiap Gudang</label> -->
											<select id="header-down-payment" name="header-down-payment" class="form-control">
											</select>
										</div>
									</div>
									<div class="col-md-12 col-xs-12 col-sm-12 table-responsive">
										<table id="table-down-payment" class="table table-bordered">
											<thead>
												<th>Customer</th>
												<th class="text-right">Sisa Down Payment</th>
												<th>Action</th>
											</thead>
											<tbody>
												<tr>
													<td colspan="3">Data tidak ada</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">            
					</div>
				</form>
			</div>
		</div>
	</div>        
	<!-- END CONTAINER -->

	<!-- Core JS -->
	<script src="<?php echo base_url();?>assets/core/plugins/pace/pace.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/core/plugins/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>  
	<!-- <script src="<?php #echo base_url();?>assets/core/plugins/jquery/jquery-3.6.3.min.js" type="text/javascript"></script>  	 -->
	<script src="<?php echo base_url();?>assets/core/plugins/bootstrapv3/js/bootstrap.min.js" type="text/javascript"></script>
	<!-- <script src="<?php #echo base_url();?>assets/core/plugins/jquery-scrollbar/jquery.scrollbar.min.js" type="text/javascript"></script> -->
	<!-- <script src="<?php #echo base_url();?>assets/core/plugins/jquery-block-ui/jqueryblockui.min.js" type="text/javascript"></script> -->
	<script src="<?php echo base_url();?>assets/core/js/webarch.js" type="text/javascript"></script>	

	<!-- Icon & Notification -->
	<script src="<?php echo base_url();?>assets/core/plugins/jquery-notifications/js/messenger.min.js" type="text/javascript"></script>
	<!-- <script src="<?php #echo base_url();?>assets/core/plugins/jquery-notifications/js/messenger-theme-future.js" type="text/javascript"></script> -->
	<script src="<?php echo base_url();?>assets/core/plugins/sweetalert2/sweetalert2.min.js"></script>
	<!-- <script src="<?php #echo base_url();?>assets/core/plugins/jquery-notifications/js/demo/demo.js" type="text/javascript"></script> -->
	<!-- <script src="<?php #echo base_url();?>assets/core/plugins/toastr/toastr.min.js"></script> -->

	<!-- Form,  Confirm, Select, Image -->
	<script src="<?php echo base_url();?>assets/core/plugins/autonumeric-4.1.0.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/core/plugins/daterangepicker/moment.min.js"></script>
	<script src="<?php echo base_url();?>assets/core/plugins/daterangepicker/daterangepicker.js"></script>	
	<script src="<?php echo base_url();?>assets/core/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/core/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/core/plugins/bootstrap-clockpicker/bootstrap-clockpicker.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/core/plugins/select2-4.0.8/js/select2.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/core/plugins/jconfirm-3.3.4/dist/jquery-confirm.min.js"></script>
	<script src="<?php echo base_url();?>assets/core/plugins/croppie/js/croppie.min.js"></script>

	<!-- Datatable -->
	<script src="<?php echo base_url();?>assets/core/plugins/datatables-1.10.24/jquery.dataTables.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/core/plugins/datatables-1.10.24/dataTables.rowGroup.js" type="text/javascript"></script>
	
	<!-- <script src="<?php #echo base_url();?>assets/core/plugins/datatables-1.13.6/DataTables-1.13.6/js/jquery.dataTables.min.js" type="text/javascript"></script> -->
	<script src="<?php echo base_url();?>assets/core/plugins/datatables-1.13.6/FixedColumns-4.3.0/js/dataTables.fixedColumns.min.js" type="text/javascript"></script> 

	<!-- <script src="<?php #echo base_url();?>assets/core/plugins/datatables-1.10.24/dataTables.rowReorder.min.js" type="text/javascript"></script> -->
	<!-- <script src="<?php #echo base_url();?>assets/core/plugins/datatables-1.10.24/dataTables.responsive.min.js" type="text/javascript"></script> -->

	<!-- Other -->   
	<script src="<?php echo base_url();?>assets/core/plugins/base64.js" type="text/javascript"></script>  
	<script src="<?php echo base_url();?>assets/core/plugins/jquery.redirect.js" type="text/javascript"></script>   
	<script src="<?php echo base_url();?>assets/core/plugins/magnific-popup/jquery.magnific-popup.js" type="text/javascript"></script> 

	<!-- Third Party -->    
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>  
	<!-- <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>   -->
	<!-- <script src="<?php #echo base_url();?>assets/webarch/plugins/jquery-qrcode/qrcode.js"></script> -->
	<!-- <script src="<?php #echo base_url();?>assets/webarch/plugins/jquery-qrcode/jquery.qrcode.js"></script> -->	
	<!-- <script src="<?php #echo base_url();?>assets/webarch/js/form_elements.js" type="text/javascript"></script> -->
	<!-- <script src="<?php #cho base_url();?>assets/webarch/js/support_ticket.js" type="text/javascript"></script> -->


  	<script type="text/javascript">
		$(document).ready(function() {
			// $.alert('.btn-trans-payment-info');
			// $("#modal-search-trans-over-due").modal('toggle');
			var url_login 		= "<?= base_url('login'); ?>";
			var url_dashboard 	= "<?= base_url('dashboard/manage'); ?>";
			var url_search 		= "<?= base_url('search/manage'); ?>";
			var url_product 	= "<?= base_url('produk/manage'); ?>";
            var url_finance 	= "<?= base_url('keuangan/manage'); ?>";
			var url_trans 		= "<?= base_url('transaksi/manage'); ?>";
			var url_inventory 	= "<?= base_url('inventory/manage'); ?>";						
			var url_stock_card 	= "<?= base_url('report/report_stock_card'); ?>";
			var url_report      = "<?= base_url('report'); ?>";
			var site_url        = "<?= site_url(); ?>";

			var url_print_trans    = "<?= base_url('transaksi/print_history/'); ?>";
			var url_print_journal  = "<?= base_url('keuangan/print/'); ?>";

			var table_stock_operation = 0;
            var switch_do          = parseInt("<?php echo $switch_do;?>");

			// $("#modal-search-stock").modal('show');
			$('#search_input').select2({
				// placeholder: 'Cari Barang ?',
				minimumInputLength: 3,
				ajax: {
					type: "get",
					url: "<?= base_url('Barang/barang_search/');?>",
					dataType: 'json',
					delay: 250,
					processResults: function ( data ) {
						return {
							results: data
						};

					},
					cache: false
				},
				templateSelection: function ( data, container ) {
					// Add custom attributes to the <option> tag for the selected option
					return data.text;
				}
			});
		  	$('#header-goods').select2({
				dropdownParent:$("#modal-search-stock"), //If Select2 Inside Modal
				placeholder: '<i class="fas fa-search"></i> Ketik Kode / Nama Produk',
				//width:'100%',
				tags:true,
				minimumInputLength: 0,
				ajax: {
					type: "get",
					url: url_search,
					dataType: 'json',
					delay: 250,
					data: function (params) {
						var query = {
							search: params.term,
							tipe: 1,
							category:1,
							source: 'products'
                        };
                        return query;
					},
					processResults: function (data) {
						var datas = [];
						$.each(data, function(key, val){
							if(parseInt(val.id)){
								if(val.kode == undefined){
									var kode = '';
								}else{
									var kode = ' - '+val.kode;	
								}

								// if(parseFloat(val.product_stock) > 0){
								// 	var stock = '['+val.product_stock+' '+' '+val.satuan+']';
								// }else{
								// 	var stock = '';
								// }
								datas.push({
									'id' : val.id,
									'text' : val.nama + kode
								});
							}
						});
						return {
							results: datas
						};
					},
					cache: true
				},
				escapeMarkup: function(markup){ 
					return markup; 
				},
				templateResult: function(datas){ //When Select on Click
					if (!datas.id) { return datas.text; }
					return datas.text;          
				},
				templateSelection: function(datas) { //When Option on Click
					if (!datas.id) { return datas.text; }    
					// return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
					return datas.text;
				}
		  	});
            $('#header-down-payment').select2({
                dropdownParent:$("#modal-search-down-payment"), //If Select2 Inside Modal
                placeholder: '<i class="fas fa-search"></i> Ketik Kode / Nama Member / Handphone',
                //width:'100%',
                tags:true,
                minimumInputLength: 0,
                ajax: {
                    type: "get",
                    url: url_search,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var query = {
                            search: params.term,
                            tipe: 2,
                            source: 'contacts'
                        };
                        return query;
                    },
                    processResults: function (data) {
                        var datas = [];
                        $.each(data, function(key, val){
                            if(parseInt(val.id)){
                                if(val.kode == undefined){
                                    var kode = '';
                                }else{
                                    var kode = ' - '+val.kode;	
                                }

                                // if(parseFloat(val.product_stock) > 0){
                                // 	var stock = '['+val.product_stock+' '+' '+val.satuan+']';
                                // }else{
                                // 	var stock = '';
                                // }
                                datas.push({
                                    'id' : val.id,
                                    'text' : val.text
                                });
                            }
                        });
                        return {
                            results: datas
                        };
                    },
                    cache: true
                },
                escapeMarkup: function(markup){ 
                    return markup; 
                },
                templateResult: function(datas){ //When Select on Click
                    if (!datas.id) { return datas.text; }
                    return datas.text;          
                },
                templateSelection: function(datas) { //When Option on Click
                    if (!datas.id) { return datas.text; }    
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                }
            });             
		  	$('#header-goods-history').select2({
				dropdownParent:$("#modal-search-product-history"), //If Select2 Inside Modal
				placeholder: '<i class="fas fa-search"></i> Ketik Kode / Nama Produk',
				//width:'100%',
				tags:true,
				minimumInputLength: 0,
				ajax: {
					type: "get",
					url: url_search,
					dataType: 'json',
					delay: 250,
					data: function (params) {
						var query = {
							search: params.term,
							tipe: 1,
							category:1,
							source: 'products'
						};
						return query;
					},
					processResults: function (data) {
						return {
							results: data
						};
					},
					cache: true
				},
				escapeMarkup: function(markup){ 
					return markup; 
				},
				templateResult: function(datas){ //When Select on Click
					if (!datas.id) { return datas.text; }
					return datas.text;          
				},
				templateSelection: function(datas) { //When Option on Click
					if (!datas.id) { return datas.text; }    
					// return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
					return datas.text;
				}
		  	}); 
		  	$('#header-contact-history').select2({
				dropdownParent:$("#modal-search-product-history"), //If Select2 Inside Modal
				placeholder: '<i class="fas fa-search"></i> Ketik Kode / Nama',
				//width:'100%',
				tags:true,
				minimumInputLength: 0,
				ajax: {
					type: "get",
					url: url_search,
					dataType: 'json',
					delay: 250,
					data: function (params) {
						var query = {
							search: params.term,
							// tipe: 1,
							// category:1,
							source: 'contacts'
						};
						return query;
					},
					processResults: function (data) {
						return {
							results: data
						};
					},
					cache: true
				},
				escapeMarkup: function(markup){ 
					return markup; 
				},
				templateResult: function(datas){ //When Select on Click
					if (!datas.id) { return datas.text; }
					return datas.text;          
				},
				templateSelection: function(datas) { //When Option on Click
					if (!datas.id) { return datas.text; }    
					// return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
					return datas.text;
				}
		  	});
		  	$('#header-trans-date-due-customer').select2({
				dropdownParent:$("#modal-search-trans-over-due"), //If Select2 Inside Modal
				placeholder: '<i class="fas fa-search"></i> Ketik Kode / Nama Customer',
				//width:'100%',
				tags:true,
				minimumInputLength: 0,
				ajax: {
					type: "get",
					url: url_search,
					dataType: 'json',
					delay: 250,
					data: function (params) {
						var query = {
							search: params.term,
							tipe: 2,
							// category:1,
							source: 'contacts'
						};
						return query;
					},
					processResults: function (data) {
						return {
							results: data
						};
					},
					cache: true
				},
				escapeMarkup: function(markup){ 
					return markup; 
				},
				templateResult: function(datas){ //When Select on Click
					if (!datas.id) { return datas.text; }
					return datas.text;          
				},
				templateSelection: function(datas) { //When Option on Click
					if (!datas.id) { 
						if(datas.id == "-"){
							return datas.text; 
						}
					}    
					// return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
					return datas.text;
				}
		  	});		
		  	$('#header-trans-date-due-supplier').select2({
				dropdownParent:$("#modal-search-trans-over-due"), //If Select2 Inside Modal
				placeholder: '<i class="fas fa-search"></i> Ketik Kode / Nama Supplier',
				//width:'100%',
				tags:true,
				minimumInputLength: 0,
				ajax: {
					type: "get",
					url: url_search,
					dataType: 'json',
					delay: 250,
					data: function (params) {
						var query = {
							search: params.term,
							tipe: 1,
							// category:1,
							source: 'contacts'
						};
						return query;
					},
					processResults: function (data) {
						return {
							results: data
						};
					},
					cache: true
				},
				escapeMarkup: function(markup){ 
					return markup; 
				},
				templateResult: function(datas){ //When Select on Click
					if (!datas.id) { return datas.text; }
					return datas.text;          
				},
				templateSelection: function(datas) { //When Option on Click
					if (!datas.id) { 
						if(datas.id == "-"){
							return datas.text; 
						}
					}    
					// return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
					return datas.text;
				}
		  	});	
			$('#filter_table_dashboard_stock_location').select2({
				dropdownParent:$("#modal-search-stock"), //If Select2 Inside Modal
				// placeholder: '<i class="fas fa-warehouse"></i> Search',
				minimumInputLength: 0,
				ajax: {
					type: "get",
					url: "<?= base_url('search/manage');?>",
					dataType: 'json',
					delay: 250,
					data: function(params){
						var query = {
						search: params.term,
						tipe: 1, //1=Supplier, 2=Asuransi
						source: 'locations'
						};      
						return query;  
					},
					processResults: function (datas, params) {
						params.page = params.page || 1;
						return {
							results: datas,
							pagination: {
								more: (params.page * 10) < datas.count_filtered
							}
						};      
					},    
				cache: true
				},
				escapeMarkup: function(markup){ 
					return markup; 
				},
				templateResult: function(datas){ //When Select on Click
					if (!datas.id) { return datas.text; }
					if($.isNumeric(datas.id) == true){
						// return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
						return datas.text;          
					}
				},
				templateSelection: function(datas) { //When Option on Click
					if (!datas.id) { return datas.text; }
					//Custom Data Attribute         
					if($.isNumeric(datas.id) == true){
						return datas.text;
						// return '<i class="fas fa-warehouse '+datas.id.toLowerCase()+'"></i> '+datas.text;
					}
				}
			});			  
			//Datatable Dashboard Stock Config
			/*
			var table_dashboard_stock = $("#table_dashboard_stock").DataTable({
				"serverSide": true,
				"ajax": {
					url: url_inventory,
					type: 'post',
					dataType: 'json',
					cache: 'false',
					data: function(d) {
						d.action = 'load-stock-warehouse';
						d.tipe = 1;
						d.table_reload = table_stock_operation;
						// d.start = 0;
						// d.length = '-1'; 
						d.length = $("#filter_table_dashboard_stock_length").find(':selected').val(); 
						d.location = $("#filter_table_dashboard_stock_location").find(':selected').val();     
						d.product = $("#filter_table_dashboard_stock_product").find(':selected').val();             
						d.order[0]['column'] = $("#filter_table_dashboard_stock_order").find(':selected').val();
						d.order[0]['dir'] = $("#filter_table_dashboard_stock_dir").find(':selected').val();     
						d.search = {
							value:$("#filter_table_dashboard_stock_search").val()
						};               
						// d.user_role =  $("#select_role").val();
					},
					dataSrc: function(data) {
						return data.result;
					}
				},
				"columnDefs": [
					{"targets":0, "title":"Kode Produk","searchable":false,"orderable":false},
					{"targets":1, "title":"Nama Produk","searchable":false,"orderable":false},
					{"targets":2, "title":"Stok","searchable":false,"orderable":false}
				],
				// "order": [
				// 	[0, 'asc']
				// ],
				"columns": [
					{'data': 'product_code'},
					{'data': 'product_name'},
					{
						'data': 'product_name',className:'text-right',
						render:function(data,meta,row){
							var dsp ='';
							// dsp += addCommas(row.balance);
							var dsp = '';
							dsp += '<a href="#" class="btn_dashboard_stock" data-product-id="'+row.product_id+'" data-product-name="'+row.product_name+'" data-product-unit="'+row.product_unit+'"><b>'+addCommas(row.balance)+' '+row.product_unit+'</b></a>';
							return dsp;
						}
					}
				]
			});
			$(document).on("change","#filter_table_dashboard_stock_location",function(e){ table_dashboard_stock.ajax.reload(); });
			// $(document).on("change","#filter_table_dashboard_stock_product",function(e){ table_dashboard_stock.ajax.reload(); });  
			$(document).on("change","#filter_table_dashboard_stock_order",function(e){ table_dashboard_stock.ajax.reload(); });
			$(document).on("change","#filter_table_dashboard_stock_dir",function(e){ table_dashboard_stock.ajax.reload(); }); 
			$(document).on("input","#filter_table_dashboard_stock_search",function(e){ 
				var ln = $(this).val().length; if((parseInt(ln) > 3) || (parseInt(ln) < 1)){ table_dashboard_stock.ajax.reload(); } 
			});     
			// $("#filter_search").on('input', function(e){ var ln = $(this).val().length; if(parseInt(ln) > 3){ index.ajax.reload(); } });  			
			$("#table_dashboard_stock_filter").css('display','none');  
			$("#table_dashboard_stock_length").css('display','none');
			$("#table_dashboard_stock_info").css('display','none');  
			$("#table_dashboard_stock_paginate").css('display','none');		
			*/	
			$(document).on("click",".btn_dashboard_stock",function(e) {
				e.preventDefault();
				e.stopPropagation();
				var product_id = $(this).attr('data-product-id');
				var product_name = $(this).attr('data-product-name');
				var product_unit = $(this).attr('data-product-unit');
				var title   = 'Lokasi Stok';
				$.confirm({
					title: title,
					columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
					closeIcon: true,
					closeIconClass: 'fas fa-times',    
					animation:'zoom',
					closeAnimation:'bottom',
					animateFromElement:false,      
					content: function(){
						var self = this;
						var form = new FormData();
						form.append('action', 'stock');
						form.append('id',product_id);
						form.append('tipe',1);

						return $.ajax({
							url: url_product,
							data: form,
							dataType: 'json',
							type: 'post',
							cache: 'false', contentType: false, processData: false,
						}).done(function (d) {
							var s = d.status;
							var m = d.message;
							var r = d.result;
							if(parseInt(s) == 1){
								// notif(s,m);
								// notifSuccess(m);
								/* hint zz_for or zz_each */
								var dsp = '';
								var total_data = r.length;
								dsp += 'Barang :<b>'+product_name+'</b><br>';
								dsp += 'Satuan :<b>'+product_unit+'</b><br><br>';
								dsp += '<table class="table table-bordered">';
								dsp += '  <thead>';
								dsp += '    <tr>';
								dsp += '      <th>Gudang</th>';
								dsp += '      <th>Stok</th>';
								dsp += '      <th>Action</th>';  
								dsp += '    <tr>';
								dsp += '  </thead>';
								dsp += '  <tbody>';
								for(var a=0; a<total_data; a++){  
								dsp += '<tr class="tr-price-item-id" data-id="'+d.result[a]['product_price_id']+'">';
									dsp += '<td>'+d.result[a]['location_name']+'</td>';
									dsp += '<td style="text-align:right;">'+addCommas(d.result[a]['qty_balance'])+'</td>';         
									dsp += '<td>';
									dsp += '<a class="btn btn-mini btn-small btn-primary btn-header-view-stock-card" data-url="'+d.result[a]['stock_card_url']+'">';
									dsp += '<i class="fas fa-print"></i>';
									dsp += '&nbsp;Kartu Stok Terakhir</button>';
									dsp += '</td>';
								dsp += '</tr>';
								}
								dsp += '  </tbody>';
								dsp += '</table>';

								self.setContentAppend(dsp);
							}else{
								// notif(s,m);
								// notifSuccess(m);
							}            
						}).fail(function(){
							self.setContent('Something went wrong, Please try again.');
						});
					},
					onContentReady: function(){
						var self = this;
						var content = '';
						var dsp     = '';

						var d = self.ajaxResponse.data;
						
						var s = d.status;
						var m = d.message;
						var r = d.result;

						if(parseInt(s)==1){
						}else{
							self.setContentAppend('<div>Content ready!</div>');
						}
					},
					buttons: {
					cancel:{
						btnClass: 'btn-default',
						text: 'Tutup', 
						action: function () {
						// $.alert('Canceled!');
						}
					}
					}        
				});
			});			
			//Datatable Stock End

			$(document).on("change","#header-trans-date-due-customer",function(e) {
				transaction_over_due(2);
			}); 
			$(document).on("change","#header-trans-date-due-supplier",function(e) {
				transaction_over_due(1);
			}); 			 
			$(document).on("change","#header-goods",function(e) {
				e.preventDefault();
				e.stopPropagation();
				var id = $(this).find(":selected").val();
				var data = {
					action: 'stock',
					id:id,
					tipe:1
				}
				$.ajax({
					type: "POST",     
					url: url_product,
					data: data,
					dataType:'json',
					cache: false,
					beforeSend:function(){
						$("#table-stock tbody").html('');            
						var dsp = '';
						dsp += '<tr>';
						dsp += '<td colspan="2">Sedang memproses</td>';
						dsp += '</tr>';
						$("#table-stock tbody").append(dsp);
					},
					success:function(d){
						$("#table-stock tbody").html('');            
						if(parseInt(d.status)==1){ /* Success Message */
							var dsp = '';  
							if(parseInt(d.total_data) > 0){
								for(var a=0; a < parseInt(d.total_data); a++){
									dsp += '<tr>';
										dsp += '<td>'+d.result[a]['location_name']+'</td>';
										dsp += '<td class="text-right"><b>'+addCommas(d.result[a]['qty_balance'])+' '+d.result[a]['product_unit']+'</b></td>';
										dsp += '<td><a class="btn btn-mini btn-small btn-primary btn-header-view-stock-card" data-url="'+d.result[a]['stock_card_url']+'" href="#"><i class="fas fa-print"></i> Kartu Stok Terakhir</a></td>';
									dsp += '</tr>';                 
								}
							}else{
								dsp += '<tr>';
								dsp += '<td colspan="3">Data tidak ada</td>';
								dsp += '</tr>';
							}
							// console.log(dsp);
							$("#table-stock tbody").append(dsp);
						}else{
							var dsp = '';
							dsp += '<tr>';
							dsp += '<td colspan="3">Data tidak ada</td>';
							dsp += '</tr>';
							$("#table-stock tbody").append(dsp);
						}
					},
					error:function(xhr, Status, err){
						notif(0,'Error');
					}
				});
			});
            $(document).on("change","#header-down-payment",function(e) {
                e.preventDefault();
                e.stopPropagation();
                var id = $(this).find(":selected").val();
                var data = {
                    action: 'down-payment-balance', //Harus Cari dari down-payment-history
                    contact_id:id,
                    journal_type:7
                }
                $.ajax({
                    type: "POST",     
                    url: url_finance,
                    data: data,
                    dataType:'json',
                    cache: false,
                    beforeSend:function(){
                        $("#table-down-payment tbody").html('');            
                        var dsp = '';
                        dsp += '<tr>';
                        dsp += '<td colspan="2">Sedang memproses</td>';
                        dsp += '</tr>';
                        $("#table-down-payment tbody").append(dsp);
                    },
                    success:function(d){
                        $("#table-down-payment tbody").html('');            
                        if(parseInt(d.status)==1){ /* Success Message */
                            var dsp = '';  
                            if(parseInt(d.total_data) > 0){
                                // for(var a=0; a < parseInt(d.total_data); a++){
                                    dsp += '<tr>';
                                        dsp += '<td>'+d.result['contact_name']+'</td>';
                                        dsp += '<td class="text-right"><b>'+addCommas(d.result['balance'])+'</b></td>';
                                        // dsp += '<td><a class="btn btn-mini btn-small btn-primary btn-header-view-stock-card" data-url="'+d.result[a]['stock_card_url']+'" href="#"><i class="fas fa-print"></i> Kartu Stok Terakhir</a></td>';
                                    dsp += '</tr>';                 
                                // }
                            }else{
                                dsp += '<tr>';
                                dsp += '<td colspan="3">Data tidak ada</td>';
                                dsp += '</tr>';
                            }
                            // console.log(dsp);
                            $("#table-down-payment tbody").append(dsp);
                        }else{
                            var dsp = '';
                            dsp += '<tr>';
                            dsp += '<td colspan="3">Data tidak ada</td>';
                            dsp += '</tr>';
                            $("#table-down-payment tbody").append(dsp);
                        }
                    },
                    error:function(xhr, Status, err){
                        notif(0,'Error');
                    }
                });
            });            
			$(document).on("change","#header-goods-history, #header-contact-history",function(e) {
				e.preventDefault();
				e.stopPropagation();
				var user_group = "<?php echo $session['user_data']['user_group_id']; ?>";
				var user_check_price_buy = "<?php echo $session['user_data']['user_check_price_buy']; ?>";
				var user_check_price_sell = "<?php echo $session['user_data']['user_check_price_sell']; ?>";
				var id = $("#header-goods-history").find(":selected").val();
				var contact_id = $("#header-contact-history").find(":selected").val();
				var data = {
					action: 'product-history',
					product_id:id,
					contact_id:contact_id,
					product_type:1
				}
				$.ajax({
					type: "POST",
					url: url_product,
					data: data,
					dataType:'json',
					cache: false,
					beforeSend:function(){
						$("#table-product-history tbody").html('');
						var dsp = '';
						dsp += '<tr>';
						dsp += '<td colspan="4">Sedang memproses</td>';
						dsp += '</tr>';
						$("#table-product-history tbody").append(dsp);
					},
					success:function(d){
						$("#table-product-history tbody").html('');
						if(parseInt(d.status)==1){ /* Success Message */
							var dsp = '';  
							if(parseInt(d.total_data) > 0){
								for(var a=0; a < parseInt(d.total_data); a++){
									dsp += '<tr>';
									dsp += '<td>'+d.result[a]['trans_date_format']+'</td>';
									dsp += '<td>';
										dsp += d.result[a]['type_name']+'<br>';
										dsp += d.result[a]['trans_number']+'</br>';
										dsp += d.result[a]['contact_name'];
									dsp += '</td>';

									if(d.result[a]['trans_type'] == 1){
										var lck1 = '<span class="fas fa-lock"></span>';
										if(user_check_price_buy == 1){
											lck1 = d.result[a]['trans_item_in_price']
										}
										dsp += '<td class="text-right">'+lck1+'</td>';
										dsp += '<td class="text-right">'+d.result[a]['trans_item_in_qty']+'</td>';
										dsp += '<td class="text-right">-</td>';
										dsp += '<td class="text-right">-</td>';
									}else if(d.result[a]['trans_type'] == 2){
										var lck2 = '<span class="fas fa-lock"></span>';
										if(user_check_price_sell == 1){
											lck2 = d.result[a]['trans_item_sell_price']
										}
										dsp += '<td class="text-right">-</td>';
										dsp += '<td class="text-right">-</td>';
										dsp += '<td class="text-right">'+lck2+'</td>';
										dsp += '<td class="text-right">'+d.result[a]['trans_item_out_qty']+'</td>';
									}	
									// if(d.result[])
									// dsp += '<td>'+d.result[a]['trans_date_format']+'</td>';																		
									// dsp += '<td class="text-right"><b>'+addCommas(d.result[a]['qty_balance'])+' '+d.result[a]['product_unit']+'</b></td>';
									// dsp += '<td><a class="btn btn-mini btn-small btn-primary btn-header-view-stock-card" data-url="'+d.result[a]['stock_card_url']+'" href="#"><i class="fas fa-print"></i> Kartu Stok Terakhir</a></td>';
									dsp += '</tr>';                 
								}
							}else{
								dsp += '<tr>';
								dsp += '<td colspan="4">Data tidak ada</td>';
								dsp += '</tr>';
							}
							// console.log(dsp);
							$("#table-product-history tbody").append(dsp);
						}else{
							var dsp = '';
							dsp += '<tr>';
							dsp += '<td colspan="4">Data tidak ada</td>';
							dsp += '</tr>';
							$("#table-product-history tbody").append(dsp);
						}
					},
					error:function(xhr, Status, err){
					notif(0,'Error');
					}
				});
			});
			$(document).on("click","#btn-header-notification",function(e) {
				// alert('as');
			});
			$(document).on("click","#btn-header-stock, .btn-header-stock",function(e) {
				// $("#modal-search-stock").modal({backdrop: 'static', keyboard: false});
				// $("#header-goods").val(0).trigger('change');
				// $("#table-stock tbody").html('');
				$("#modal-search-stock").modal('show');
				setTimeout(function() {
					table_stock_operation = 1;
					table_dashboard_stock.ajax.reload();
					// $('#header-goods').select2('open');
				}, 700);
			});
            $(document).on("click","#btn-header-down-payment, .btn-header-down-payment",function(e) {
                $("#modal-search-down-payment").modal('show');
                setTimeout(function() {
                }, 700);
            });            
			$(document).on("click","#btn-header-product-history, .btn-header-product-history",function(e) {
				$("#modal-search-product-history").modal('show');
				setTimeout(function() {
					// $('#header-goods-history').select2('open');
				}, 700);
			});
			$(document).on("click","#btn-header-report",function(e) {
				// window.open('<?= base_url('purchase/buy');?>','_self');
			});
			$(document).on("click","#btn-header-purchase",function(e) {
				window.open('<?= base_url('purchase/buy');?>','_self');
			});
			$(document).on("click","#btn-header-sell",function(e) {
				window.open('<?= base_url('sales/sell');?>','_self');
			});
			$(document).on("click","#btn-header-product",function(e) {
				window.open('<?= base_url('product/product');?>','_self');
			});
			$(document).on("click","#btn-header-customer",function(e) {
				window.open('<?= base_url('contact/customer');?>','_self');
			});
			$(document).on("click","#btn-header-supplier",function(e) {
				window.open('<?= base_url('contact/supplier');?>','_self');
			});
			$(document).on("click","#btn-header-cost-out",function(e) {
				window.open('<?= base_url('finance/cost_out');?>','_self');
			});
			$(document).on("click","#btn-header-account-payable",function(e) {
				window.open('<?= base_url('report/purchase/buy/account_payable');?>','_self');
			});
			$(document).on("click","#btn-header-account-receivable",function(e) {
				window.open('<?= base_url('report/sales/sell/account_receivable');?>','_self');
			});
			$(document).on("click",".btn-header-view-stock-card",function(e) {
				var surl = $(this).attr('data-url');
				window.open(surl,'_blank');
			});
			$(document).on("click","#btn-header-stock-minimal, .btn-header-stock-minimal",function(e) {
				var data = {
					action:'product-min-stock',
					method:'load-data'
				};
				$.ajax({
					type: "post",
					url: url_product,
					data: data,
					dataType: 'json',
					cache: false,
					success: function(d){
						if(parseInt(d.status)==1){
							$("#modal-product-stock-min").modal('show');
							if(parseInt(d.total_records) > 0){
								$("#table-product-stock-min tbody").html('');
							
								var dsp = '';
								for(var a=0; a < d.total_records; a++) {
							
									dsp += '<tr>';
										dsp += '<td>'+d.result[a]['product_name']+'</td>';
										dsp += '<td class="text-right">'+d.result[a]['product_min_stock_limit']+'</td>';
										dsp += '<td class="text-right">'+d.result[a]['product_stock']+'</td>';
										dsp += '<td>';
											dsp += '<button type="button" class="btn-header-product-stock-min-track btn btn-mini btn-small btn-primary" data-id="'+d.result[a]['product_id']+'" data-name="'+d.result[a]['product_name']+'">';
											dsp += '<i class="fas fa-check-double"></i>&nbsp;Lacak';
											dsp += '</button>';
										dsp += '</td>';
									dsp += '</tr>';
									
								}
								$("#table-product-stock-min tbody").html(dsp);
							}
							
						}
					}
				});
			});
			$(document).on("click",".btn-header-product-stock-min-track", function(e){
				var product_id = $(this).attr('data-id');
				var product_name = $(this).attr('data-name');
				var title   = product_name;
				$.confirm({
				    title: title,
				    columnClass: 'col-md-5 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
				    autoClose: 'button_1|20000',    
				    closeIcon: true,
				    closeIconClass: 'fas fa-times',    
				    animation:'zoom',
				    closeAnimation:'bottom',
				    animateFromElement:false,      
				    content: function(){
				        var self = this;
				        var data = {
				        	action:'stock',
				        	id:product_id,
				        	tipe:1
				        };
				        
				        var form = new FormData();
				        form.append('action','stock');
				        form.append('id',product_id);
				        form.append('tipe',1);

				        return $.ajax({
				            url: url_product,
				            data: form,
				            dataType: 'json',
				            type: 'post',
				            cache: 'false', contentType: false, processData: false,
				        }).done(function (d) {
				            var s = d.status;
				            var m = d.message;
				            var r = d.result;
				            if(parseInt(s) == 1){
				            	if(parseInt(d.total_data) > 0){

				            		var dsp = '';
				            		dsp += '<table class="table">';
				            		dsp += '<thead>';
				            			dsp += '<tr>';
				            				dsp += '<td><b>Gudang</b></td>';
				            				dsp += '<td class="text-right"><b>Stok</b></td>';
				            				dsp += '<td><b>Action</b></td>';
				            			dsp += '</tr>';
				            		dsp += '</thead>';
				            		dsp += '<tbody>';
				            		for(var a=0; a < d.total_data; a++) {
				            	
				            			dsp += '<tr>';
				            				dsp += '<td>'+d.result[a]['location_name']+'</td>';
				            				dsp += '<td class="text-right">'+d.result[a]['qty_balance']+' '+d.result[a]['product_unit']+'</td>';
				            				dsp += '<td>';
				            					dsp += '<a href="'+d.result[a]['stock_card_url']+'" target="_blank" class="btn-mini btn-small btn btn-primary">';
				            					dsp += '<span class="fas fa-print"></span>&nbsp;Kartu Stok Terakhir';
				            					dsp += '</a>';
				            				dsp += '</td>';
				            			dsp += '</tr>';
				            		}
				            		dsp += '</tbody>';            		
				            		dsp += '</table>';
				            	}
				            	
				            }else{
				                // notif(s,m);
				                // notifSuccess(m);
				            }            
				            self.setContent(dsp);
				        }).fail(function(){
				            self.setContent('Something went wrong, Please try again.');
				        });

				    },
				    onContentReady: function(){
				    },
				    buttons:{
				        button_1: {
				            text: 'Tutup',
				            btnClass: 'btn-default',
				            keys: ['Escape'],
				            action: function(){
				                //Close
				            }
				        }
				    }
				});
			});
			$(document).on("click","#btn-header-trans-over-due, .btn-header-trans-over-due",function(e) {
				$("#modal-search-trans-over-due").modal('toggle');
				transaction_over_due(1);
				transaction_over_due(2);
			});
			$(document).on("click",".btn-user-theme, .btn-user-theme", function(e) {
				// e.preventDefault(e);
				// var id = $(this);
				$.confirm({
					title: 'Ganti Warna Interface',
					columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
					icon: 'fas fa-fill-drip',
					autoClose: 'btn2|10000',
					closeIcon: true,
					closeIconClass: 'fa fa-close',      
					content: function(){
						var disp = '';
							disp += '<div class="form-group">';
								disp += '<div class="controls">';
									disp += '<select name="theme" id="theme" style="width:100%">';
                                        disp += '<optgroup label="Minimalist">';
                                            disp += '<option value="black">Black</option>';
                                            disp += '<option value="blue">Blue</option>'; 
                                            disp += '<option value="white">White</option> ';                                                                               
                                            disp += '<option value="green">Green</option>';                                      
                                            disp += '<option value="red">Red</option>';                                      
                                            disp += '<option value="purple">Purple</option>';
                                            disp += '<option value="peach">Peach</option>';
                                            disp += '<option value="orange">Orange</option>';
                                            disp += '<option value="dark">Dark Mode</option>';                                            
                                        disp += '</optgroup>';
                                        disp += '<optgroup label="Gradient Colorfull">';
                                            disp += '<option value="black-blue">Blue Sapphire</option>';
                                            disp += '<option value="blue_sea">Blue Sea</option>';
                                            disp += '<option value="blue_scooter">Blue Scooter</option>';                                             
                                            // disp += '<option value="black-white">Black White</option>';
                                            disp += '<option value="black_steel">Black Steel</option>';  
									        disp += '<option value="green_lush">Green Lush</option>';                                                                                    
                                            disp += '<option value="orange_coral">Orange Coral</option>';
										    disp += '<option value="red_celestial">Red Celestial</option>'
                                            disp += '<option value="purple_virgin_america">Purple Virgin America</option>';                                            ;  
                                            disp += '<option value="purple_aubergine">Purple Aubergine</option>';                                                                                        
                                        disp += '</optgroup>';                                       																								
									disp += '</select>';
								disp += '</div>';
							disp += '</div>';   
						return disp; 
					},
					onContentReady: function(){
						// when content is fetched & rendered in DOM
					},
					buttons: {
						btn1: {
							text: 'Terapkan',
							btnClass: 'btn-primary',
							action: function(){
								var color = this.$content.find('#theme option:selected').val();
								$.ajax({
									type: "POST",     
									url: "<?php echo base_url('user/manage');?>",
									data: {
										action: 'change-theme',
										theme: color
									},
									dataType:'json',
									success:function(d){
										if(d.status==1){ /* Success Message */
											// notifSuccess(result['message']);
											window.location.href = d.url;
										}else{ /* Error */
											// notifError(result['message']);  
										}           
									}
								});
							}
						},
						btn2: {
							text: 'Batal',
							btnClass: 'btn-default',
							action: function(){
								
							}
						}
					}
				});
			});
			$(document).on("click","#btn-user-password, btn-user-password", function(e) {
				$.confirm({
					title: 'Ganti Password Anda',
					columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
					icon: 'fas fa-key',
					autoClose: 'cancel|30000',
					closeIcon: true,
					closeIconClass: 'fa fa-close',
					content: function(){
					var disp = '';
						disp += '<div class="form-group">';
						disp += '<label for="label-password" class="label-password">Password Baru</label>';
						disp += '<div class="controls">';
						disp += '<input type="password" name="password" id="password" style="width:100%">';
						disp += '<label for="label-confirm-password" class="label-confirm-password">Confirm Password</label>';
						disp += '<input type="password" name="cpassword" id="cpassword" style="width:100%">';
						disp += '</div>';
						disp += '</div>';
					return disp;
					},
					onContentReady: function(){
					// when content is fetched & rendered in DOM
					},
					buttons: {
					formSubmit: {
						text: '<i class="fas fa-check"></i> Ganti', btnClass: 'btn-blue',
						action: function () {
						var password = this.$content.find('#password').val();
						var confirm_password = this.$content.find('#cpassword').val();
						if(password == ''){
							$.alert('Password tidak boleh kosong');
							return false;
						}
						if(confirm_password == ''){
							$.alert('Konfirmasi password harus diisi');
							return false;
						}
						if(password != confirm_password){
							$.alert('Konfirmasi password harus sama dengan password baru');
							return false;
						}
						var data = {
							action: 'change-password',
							password: password
						};
						$.ajax({
							type: "POST",
							url: "<?= base_url('user/manage');?>",
							data: data,
							dataType: 'json',
							success:function(d){
							if(d.status == 1){
								notif(1,d.message);
							}else{
								notif(0,d.message);
							}
							}
						});
						}
					},
					cancel: {
						text:'<i class="fas fa-window-close"></i> Batal', btnClass: 'btn-default',
						action: function(){
						}
					}
					// cancel: function () {
						//close
					// }
					},
					onContentReady: function () {
						// bind to events
						var jc = this;
						this.$content.find('form').on('submit', function (e) {
							// if the user submits the form by pressing enter in the field.
							e.preventDefault();
							jc.$$formSubmit.trigger('click'); // reference the button and click it
						});
					}
					/*btn1: {
						text: 'Ganti',
						btnClass: 'btn-primary',
						action: function(){
						var password = this.$content.find('#password').val();
						var confirm_password = this.$content.find('#cpassword').val();
						console.log(password,confirm_password);
						if(password == ''){
							notif(0, 'Password tidak boleh kosong');
							console.log('a');
						} else if(confirm_password == ''){
							notif(0, 'Konfirmasi password harus diisi');
							console.log('b');
						} else if(password != confirm_password){
							notif(0, 'Konfirmasi password harus sama dengan password baru');
							console.log('c');
						}else{
							var data = {
							action: 'change-password',
							password: password
							};
							$.ajax({
							type: "POST",
							url: "<?= base_url('User/manage');?>",
							data: data,
							dataType: 'json',
							success:function(d){
								if(parseInt(d.status) == 1){
								notif(1,d.message);
								}else{
								notif(0,d.message);
								}
							}
							});
						}
						}
					},
					btn2: {
						text: 'Batal',
						btnClass: 'btn-default',
						action: function(){

						}
					}*/
					//}
				});
			});
			$(document).on("click","#btn-user-switch, .btn-user-switch",function(e) {
				e.preventDefault();
				e.stopPropagation();
				let title   = 'Pindah User';
				$.confirm({
					title: title,
					columnClass: 'col-md-5 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
					autoClose: 'button_2|60000',    
					closeIcon: true, closeIconClass: 'fas fa-times',    
					animation:'zoom', closeAnimation:'bottom', animateFromElement:false,      
					content: function(){
						let self = this;
				
						let data = {
							source:'users'
						};        
						
						// let form = new FormData();
						// form.append('action','switch-user');
						// form.append('source','users');
				
						return $.ajax({
							url: url_search+'?source=users-switch',
							// data: data,
							dataType: 'json',
							type: 'get',
							cache: 'false', contentType: false, processData: false,
						}).done(function (d) {
							// let len = d.length;
							// var dsp = '';
							// for(var a=0; a<len; a++){
							// 	if(parseInt(d[a]['id']) > 0){
							// 		dsp += '<option value="'+d[a]['id']+'">'+d[a]['nama']+'</option>';
							// 	}
							// }
							// console.log(dsp);
							// self.setContentAppend('<select>'+dsp+'</select');			
						}).fail(function(){
							self.setContent('Something went wrong, Please try again.');
						});
					},
					onContentReady: function(){
						let self = this;
						let content = '';
						let dsp     = '';
				
						let d = self.ajaxResponse.data;
						let s = d.status;
						let m = d.message;
						let r = d.result;
				
						let len = d.length;

						// dsp += '<div>Content is ready after process !</div>';
						dsp += '<form id="jc_form">';
							dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
							dsp += '    <div class="form-group">';
							dsp += '    <label class="form-label">Choice User [Cabang - Group - Username]</label>';
							dsp += '        <select id="jc_select" name="jc_select" class="form-control">';
							for(var a=0; a<len; a++){
								if(parseInt(d[a]['id']) > 0){
									dsp += '<option value="'+d[a]['id']+'">'+d[a]['text']+'</option>';
								}
							}								
							dsp += '        </select>';
							dsp += '    </div>';
							dsp += '</div>';
						dsp += '</form>';
						content = dsp;
						self.setContentAppend(content);

						$('#jc_select').select2({
							dropdownParent:$(".jconfirm-box-container"), //If Select2 Inside Modal
							// placeholder: '<i class="fas fa-search"></i> Search',
							//width:'100%',
							tags:true,
							minimumInputLength: 0,
							ajax: {
								type: "get",
								url: url_search,
								dataType: 'json',
								delay: 250,
								data: function (params) {
									var query = {
										search: params.term,
										tipe: 1,
										source: 'users-switch'
									};
									return query;
								},
								processResults: function (data) {
									return {
										results: data
									};
								},
								cache: true
							},
							escapeMarkup: function(markup){ 
								return markup; 
							},
							templateResult: function(datas){ //When Select on Click
								if (!datas.id) { return datas.text; }
								if($.isNumeric(datas.id) == true){
									// return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
									return datas.text;          
								}else{
									return datas.text;          
								}  
							},
							templateSelection: function(datas) { //When Option on Click
								if (!datas.id) { return datas.text; }
								return datas.text;
							}
						}); 
						// self.buttons.button_1.disable();
						// self.buttons.button_2.disable();
			
						// this.$content.find('form').on('submit', function (e) {
						//      e.preventDefault();
						//      self.$$formSubmit.trigger('click'); // reference the button and click it
						// });
					},
					buttons: {
						button_1: {
							text:'<span class="fas fa-sign-in-alt"></span> Masuk',
							btnClass: 'btn-primary',
							keys: ['enter'],
							action: function(){
								let self      = this;
								let select    = self.$content.find('#jc_select').val();
								
								if(select == 0){
									$.alert('User dipilih dahulu');
									return false;
								} else{
									let form = new FormData();
									form.append('action', 'action');
									// form.append('input', input);
									// form.append('textarea', textarea);
									form.append('user_id', select);
									$.ajax({
										type: "post",
										url: url_login+'/authentication_switch',
										data: form, dataType: 'json',
										cache: 'false', contentType: false, processData: false,
										beforeSend: function() {},
										success: function(d) {
											let s = d.status;
											let m = d.message;
											let r = d.result;
											if(parseInt(s) == 1){
												window.location.href = d.result.return_url;												
											}else{
												notif(s,m);
											}
										},
										error: function(xhr, status, err) {}
									});
								}            
							}
						},
						button_2: {
							text: '<span class="fas fa-times"></span> Tutup',
							btnClass: 'btn-danger',
							keys: ['Escape'],
							action: function(){
								//Close
							}
						}
					}
				});
			});
			$(document).on("click",".btn-user-navigation",function(e) {
				e.preventDefault();
				e.stopPropagation();
				var user = $(this).attr('data-user');
				var is_root = $(this).attr('data-is-r');
                var is_allowed = $(this).attr('data-is-allowed');				
				// let title   = 'Hai '+user;
				let title = '';

				$.confirm({
					title: title,
					columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
					autoClose: 'button_2|20000',
					closeIcon: false, closeIconClass: 'fas fa-times', 
					animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
					content: function(){
					},
					onContentReady: function(e){
						let self    = this;
						let content = '';
						let dsp     = '';
				
						// dsp += '<div></div>';
						dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
						// dsp += '<div class="navbar-inner" class="visible-xs visible-sm">';
						// dsp += '<div class="header-seperation">';
						dsp += '<ul class="ul-user-navigation">';
							if(parseInt(is_root) == 1){
								dsp += '<li><a href="#" class="btn-user-switch"><i class="fas fa-toggle-off"></i><span style="position: relative;">&nbsp;Switch User</span></a></li>';
							}
							if(parseInt(is_allowed) == 1){
								dsp += '<li><a href="#" class="btn-branch-switch"><i class="fas fa-building"></i><span style="position: relative;">&nbsp;Switch Cabang</span></a></li>';
							} 							
							dsp += '<li><a href="#" class="btn-user-password"><i class="fas fa-key"></i><span style="position: relative;">&nbsp;Ganti Password</span></a></li>';
							dsp += '<li><a href="#" class="btn-user-theme"><i class="fas fa-fill-drip"></i><span style="position: relative;">&nbsp;Warna Interface</span></a></li>';
							dsp += '<li><a href="<?= base_url('login/logout'); ?>"><i class="fa fa-power-off"></i><span style="position: relative;">&nbsp;Keluar</span></a></li>';
						dsp += '</ul>';
						dsp += '</div>';
						// dsp += '</div>';
						content = dsp;
						self.setContentAppend(content);
					},
					buttons: {
						button_2: {
							text: 'Batal',
							btnClass: 'btn-danger',
							keys: ['Escape'],
							action: function(){
								//Close
							}
						}
					}
				});
			});
			$(document).on("click",".btn-user-menu-style",function(e) {
				e.preventDefault();
				e.stopPropagation();
				var user = $(this).attr('data-user');
				var is_root = $(this).attr('data-is-r');
				// let title   = 'Hai '+user;
				let title = 'Pengaturan posisi menu ?';

				$.confirm({
					title: title,
					columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
					// autoClose: 'button_2',
					closeIcon: true, closeIconClass: 'fas fa-times', 
					animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
					content: function(){
					},
					onContentReady: function(e){
					},
					buttons: {
						button_1: {
							text: '<i class="fas fa-arrow-left"></i> Menu di Kiri',
							btnClass: 'btn-success',
							keys: ['Escape'],
							action: function(){
								$.ajax({type: "post",url: url_login+'/manage',data: {action:'change-menu-style',val:0}, 
									dataType: 'json', cache: 'false', 
									beforeSend:function(){},
									success:function(d){window.location.href = d.return_url;
									},error:function(xhr,status,err){}
								});
							}
						},
						button_2: {
							text: '<i class="fas fa-arrow-up"></i> Menu di Atas',
							btnClass: 'btn-primary',
							keys: ['Escape'],
							action: function(){
								$.ajax({type: "post",url: url_login+'/manage',data: {action:'change-menu-style',val:1}, 
									dataType: 'json', cache: 'false', 
									beforeSend:function(){},
									success:function(d){window.location.href = d.return_url;
									},error:function(xhr,status,err){}
								});
							}
						}
					}
				});
			});
			$(document).on("click","#btn-branch-switch, .btn-branch-switch",function(e) {
				e.preventDefault();
				e.stopPropagation();
                switch_branch();
			});         			
			$(document).on("click",".btn-contact-info",function(e) {
				e.preventDefault();
				e.stopPropagation();
				// return false;
				var id = $(this).attr('data-id');
				var typ = $(this).attr('data-type');
				var trans_type = $(this).attr('data-trans-type');

				var title   = 'Info Kontak';
				$.confirm({
					title: title,
					columnClass: 'col-lg-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
					autoClose: 'button_2|40000',    
					closeIcon: true,
					closeIconClass: 'fas fa-times',    
					animation:'zoom',
					closeAnimation:'bottom',
					animateFromElement:false,      
					content: function(){
						var self = this;
						var url = "<?= base_url('kontak/manage'); ?>"; //CI

						var form = new FormData();
						form.append('action','info');
						form.append('id',id);
						form.append('trans_type',trans_type);

						return $.ajax({
							url: url,
							data: form,
							dataType: 'json',
							type: 'post',
							cache: 'false', contentType: false, processData: false,
						}).done(function (d) {
							var s = d.status;
							var m = d.message;
							var r = d.result;
							if(parseInt(s) == 1){
								// notif(s,m);
								// notifSuccess(m);
								/* hint zz_for or zz_each */
								var dsp = '';
								dsp += '<table class="table-default">';
								dsp += '<tr><td><b>Kode</b></td><td>: '+r.contact.contact_code+'</td></tr>';
								dsp += '<tr><td><b>Nama</b></td><td>: '+r.contact.contact_name+'</td></tr>';
								dsp += '<tr><td><b>Perusahaan</b></td><td>: '+r.contact.contact_company+'</td></tr>';
								dsp += '<tr><td><b>Telepon</b></td><td>: '+r.contact.contact_phone_1+'</td></tr>';			                  
								dsp += '<tr><td><b>Email</b></td><td>: '+r.contact.contact_email_1+'</td></tr>';
								dsp += '<tr><td><b>Alamat</b></td><td>: '+r.contact.contact_address+'</td></tr>';			                  
								dsp += '</table>';
								dsp += '<h5>Riwayat Terakhir</h5>';

								//Order
								if(typ=='order'){
									dsp += '<table id="table-order" class="table table-bordered">';
									dsp += '  <thead>';
									dsp += '    <th>Tgl</th>';
									dsp += '    <th>Nomor</th>';
									dsp += '    <th class="text-right">Total</th>';
									dsp += '  </thead>';
									dsp += '  <tbody>';
									if(r['order'].length > 0){
										for(var o=0; o < r['order'].length; o++){
											dsp += '    <tr>';
											dsp += '      <td>'+r['order'][o]['order_date_format']+'</td>';
											dsp += '      <td><a href="'+r['order'][o]['order_url']+'" target="_blank"><span class="fas fa-file-alt"></span> '+r['order'][o]['order_number']+'</a></td>';
											dsp += '      <td class="text-right"><a class="btn-order-item-info" data-id="'+r['order'][o]['order_id']+'" data-session="" data-order-number="'+r['order'][o]['order_number']+'" data-contact-name="'+r.contact.contact_name+'" data-type="order" style="cursor:pointer;">'+r['order'][o]['order_total_format']+'</a></td>';
											dsp += '    </tr>';                    
										}
									}else{
										dsp += '    <tr><td colspan="3">Tidak ada data</td></tr>';
									}
									dsp += '  </tbody>';
									dsp += '</table>';
								}else if(typ=='trans'){
									dsp += '<table id="table-order" class="table table-bordered">';
									dsp += '  <thead>';
									dsp += '    <th>Tgl</th>';
									dsp += '    <th>Nomor</th>';
									dsp += '    <th class="text-right">Total</th>';
									dsp += '  </thead>';
									dsp += '  <tbody>';
									if(r['trans'].length > 0){
										for(var o=0; o < r['trans'].length; o++){
											dsp += '    <tr>';
											dsp += '      <td>'+r['trans'][o]['trans_date_format']+'</td>';
											dsp += '      <td><a href="'+r['trans'][o]['trans_url']+'" target="_blank"><span class="fas fa-file-alt"></span> '+r['trans'][o]['trans_number']+'</a></td>';
											// dsp += '      <td class="text-right">'+r['trans'][o]['trans_total_format']+'</td>';
											dsp += '      <td class="text-right"><a class="btn-trans-item-info" data-id="'+r['trans'][o]['trans_id']+'" data-session="'+r['trans'][o]['trans_session']+'" data-trans-number="'+r['trans'][o]['trans_number']+'" data-trans-type="'+r['trans'][o]['trans_type']+'" data-contact-name="'+r.contact.contact_name+'" data-type="trans" style="cursor:pointer;">'+r['trans'][o]['trans_total_format']+'</a></td>';											
											dsp += '    </tr>';                    
										}
									}else{
										dsp += '    <tr><td colspan="3">Tidak ada data</td></tr>';
									}
									dsp += '  </tbody>';
									dsp += '</table>';
								}else if(typ=='journals'){

								}
							}else{
								// notif(s,m);
								// notifSuccess(m);
							}            
							self.setTitle('Info Kontak');
							self.setContentAppend(dsp);
							/*type_your_code_here*/
						}).fail(function(){
							self.setContent('Something went wrong, Please try again.');
						});
					},
					buttons: {
						button_2: {
							text: 'Tutup',
							btnClass: 'btn-danger',
							keys: ['Escape'],
							action: function(){
								//Close
							}
						}
					}
				});
			});
			$(document).on("click",".btn-order-item-info",function(e) {
				e.preventDefault();
				e.stopPropagation();
				var id = $(this).attr('data-id');
				var ses = $(this).attr('data-session');
				// var typ = $(this).attr('data-type');
				var on = $(this).attr('data-order-number');
				var cn = $(this).attr('data-contact-name');
				// $.alert('.btn-order-item-info');		
				var title   = 'Info Detail';
				$.confirm({
					title: title,
					columnClass: 'col-lg-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
					autoClose: 'button_2|40000',    
					closeIcon: true,
					closeIconClass: 'fas fa-times',    
					animation:'zoom',
					closeAnimation:'bottom',
					animateFromElement:false,      
					content: function(){
						var self = this;
						var url = "<?= base_url('order/manage'); ?>"; //CI

						var form = new FormData();
						form.append('action','load-order-items');
						form.append('order_id',id);
						return $.ajax({
							url: url,
							data: form,
							dataType: 'json',
							type: 'post',
							cache: 'false', contentType: false, processData: false,
						}).done(function (d) {
							var s = d.status;
							var m = d.message;
							var r = d.result;
							if(parseInt(s) == 1){
								var dsp = '';
								dsp += '<table class="table-default">';
									dsp += '<tr><td><b>Nomor Dokumen </b></td><td> : '+on+'</td></tr>';
									dsp += '<tr><td><b>Kontak</b></td><td> : '+cn+'</td></tr>';		                  
								dsp += '</table>';

								dsp += '<table id="table-order" class="table table-bordered">';
								dsp += '  <thead>';
								dsp += '    <th>Produk</th>';
								dsp += '    <th class="text-right">Qty</th>';
								dsp += '    <th class="text-right">Harga</th>';
								dsp += '    <th class="text-right">Total</th>';							
								dsp += '  </thead>';
								dsp += '  <tbody>';
								if(parseInt(d['total_produk']) > 0){
									for(var o=0; o < r.length; o++){
										dsp += '    <tr>';
										dsp += '      <td>'+r[o]['product_name']+'</td>';
										dsp += '      <td class="text-right">'+r[o]['order_item_qty']+'</td>';
										dsp += '      <td class="text-right">'+r[o]['order_item_price']+'</td>';
										dsp += '      <td class="text-right">'+r[o]['order_item_total']+'</td>';									
										dsp += '    </tr>';               
									}
									dsp += '<tr>';
										dsp += '<td colspan="3" class="text-right"><b>Subtotal (Rp)</b></td>';
										dsp += '<td class="text-right"><b>'+addCommas(d['subtotal'])+'</b></td>';
									dsp += '</tr>';
									dsp += '<tr>';
										dsp += '<td colspan="3" class="text-right"><b>Down Payment (Rp)</b></td>';
										dsp += '<td class="text-right"><b>'+addCommas(d['total_dp'])+'</b></td>';
									dsp += '</tr>';
									dsp += '<tr>';
										dsp += '<td colspan="3" class="text-right"><b>Total (Rp)</b></td>';
										dsp += '<td class="text-right"><b>'+addCommas(d['total'])+'</b></td>';
									dsp += '</tr>';																		
								}else{
									dsp += '    <tr><td colspan="3">Tidak ada data</td></tr>';
								}
								dsp += '  </tbody>';
								dsp += '</table>';
								
							}else{
								// notif(s,m);
								// notifSuccess(m);
							}            
							// self.setTitle('Info Kontak');
							self.setContentAppend(dsp);
							/*type_your_code_here*/
						}).fail(function(){
							self.setContent('Something went wrong, Please try again.');
						});
					},
					buttons: {
						button_2: {
							text: 'Tutup',
							btnClass: 'btn-danger',
							keys: ['Escape'],
							action: function(){
								//Close
							}
						}
					}
				});				
			});
			$(document).on("click",".btn-trans-item-info",function(e) {
				e.preventDefault();
				e.stopPropagation();
				var id = $(this).attr('data-id');
				var ses = $(this).attr('data-session');
				var typ = $(this).attr('data-type');
				var tn = $(this).attr('data-trans-number');
				var cn = $(this).attr('data-contact-name');
				var tt = $(this).attr('data-trans-type');			
				// $.alert('.btn-order-item-info');		
				var title   = 'Info Detail';
				$.confirm({
					title: title,
					columnClass: 'col-lg-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
					autoClose: 'button_2|40000',    
					closeIcon: true,
					closeIconClass: 'fas fa-times',    
					animation:'zoom',
					closeAnimation:'bottom',
					animateFromElement:false,    
					useBootstrap:false,  
					content: function(){
						var self = this;
						var url = "<?= base_url('transaksi/manage'); ?>"; //CI

						var form = new FormData();
						form.append('action','load-trans-items');
						form.append('trans_id',id);
						form.append('tipe',tt);
						return $.ajax({
							url: url,
							data: form,
							dataType: 'json',
							type: 'post',
							cache: 'false', contentType: false, processData: false,
						}).done(function (d) {
							var s = d.status;
							var m = d.message;
							var r = d.result;
							if(parseInt(s) == 1){
								var dsp = '';
								dsp += '<table class="table-default">';
									dsp += '<tr><td><b>Nomor Dokumen </b></td><td> : '+tn+'</td></tr>';
									dsp += '<tr><td><b>Kontak</b></td><td> : '+cn+'</td></tr>';		                  
								dsp += '</table>';

								if(tt==1){ //Pembelian
									dsp += '<table id="table-order" class="table table-bordered">';
									dsp += '  <thead>';
									dsp += '    <th>Produk</th>';
									dsp += '    <th class="text-left">Lokasi Penempatan</th>';
									dsp += '    <th class="text-right">Qty</th>';
									dsp += '    <th class="text-right">Harga Beli</th>';
									dsp += '    <th class="text-right">Total</th>';
									dsp += '  </thead>';
									dsp += '  <tbody>';
									if(parseInt(d['total_produk']) > 0){
										for(var o=0; o < r.length; o++){
											dsp += '    <tr>';
											dsp += '      <td>'+r[o]['product_name']+'</td>';
											dsp += '      <td>'+r[o]['location']['location_name']+'</td>';										
											dsp += '      <td class="text-right">'+r[o]['trans_item_in_qty']+' '+r[o]['trans_item_unit']+'</td>';
											dsp += '      <td class="text-right">'+r[o]['trans_item_in_price']+'</td>';
											dsp += '      <td class="text-right">'+r[o]['trans_item_total']+'</td>';									
											dsp += '    </tr>';               
										}
										dsp += '<tr>';
											dsp += '<td colspan="4"><b>Subtotal (Rp)</b></td>';
											dsp += '<td class="text-right"><b>'+d['subtotal']+'</b></td>';
										dsp += '</tr>';
										dsp += '<tr>';
											dsp += '<td colspan="4"><b>PPN (Rp)</b></td>';
											dsp += '<td class="text-right"><b>'+d['total_ppn']+'</b></td>';
										dsp += '</tr>';										
										dsp += '<tr>';
											dsp += '<td colspan="4"><b>Total (Rp)</b></td>';
											dsp += '<td class="text-right"><b>'+d['total']+'</b></td>';
										dsp += '</tr>';
									}else{
										dsp += '    <tr><td colspan="3">Tidak ada data</td></tr>';
									}
								}else if(tt==2){ //Penjualan
									dsp += '<table id="table-order" class="table table-bordered">';
									dsp += '  <thead>';
									dsp += '    <th>Produk</th>';
									dsp += '    <th class="text-left">Lokasi Pengambilan</th>';								
									dsp += '    <th class="text-right">Qty</th>';								
									dsp += '    <th class="text-right">Harga Jual</th>';
									dsp += '    <th class="text-right">Total</th>';
									dsp += '  </thead>';
									dsp += '  <tbody>';
									if(parseInt(d['total_produk']) > 0){
										for(var o=0; o < r.length; o++){
											dsp += '    <tr>';
											dsp += '      <td>'+r[o]['product_name']+'</td>';
											dsp += '      <td>'+r[o]['location']['location_name']+'</td>';
											dsp += '      <td class="text-right">'+r[o]['trans_item_out_qty']+' '+r[o]['trans_item_unit']+'</td>';
											dsp += '      <td class="text-right">'+r[o]['trans_item_sell_price']+'</td>';
											dsp += '      <td class="text-right">'+r[o]['trans_item_sell_total']+'</td>';									
											dsp += '    </tr>';               
										}
										dsp += '<tr>';
											dsp += '<td colspan="4"><b>Subtotal (Rp)</b></td>';
											dsp += '<td class="text-right"><b>'+d['subtotal']+'</b></td>';
										dsp += '</tr>';
										dsp += '<tr>';
											dsp += '<td colspan="4"><b>PPN (Rp)</b></td>';
											dsp += '<td class="text-right"><b>'+d['total_ppn']+'</b></td>';
										dsp += '</tr>';										
										dsp += '<tr>';
											dsp += '<td colspan="4"><b>Total (Rp)</b></td>';
											dsp += '<td class="text-right"><b>'+d['total']+'</b></td>';
										dsp += '</tr>';
									}else{
										dsp += '    <tr><td colspan="4">Tidak ada data</td></tr>';
									}								
								}else if(tt==3){ //Retur Beli
									dsp += '<table id="table-order" class="table table-bordered">';
									dsp += '  <thead>';
									dsp += '    <th>Produk</th>';
									dsp += '    <th class="text-left">Lokasi Pengambilan</th>';								
									dsp += '    <th class="text-right">Qty</th>';								
									dsp += '    <th class="text-right">Harga Beli</th>';
									dsp += '    <th class="text-right">Total</th>';
									dsp += '  </thead>';
									dsp += '  <tbody>';
									if(parseInt(d['total_produk']) > 0){
										for(var o=0; o < r.length; o++){
											dsp += '    <tr>';
											dsp += '      <td>'+r[o]['product_name']+'</td>';
											dsp += '      <td>'+r[o]['location']['location_name']+'</td>';
											dsp += '      <td class="text-right">'+r[o]['trans_item_out_qty']+' '+r[o]['trans_item_unit']+'</td>';
											dsp += '      <td class="text-right">'+r[o]['trans_item_out_price']+'</td>';
											dsp += '      <td class="text-right">'+r[o]['trans_item_total']+'</td>';									
											dsp += '    </tr>';               
										}
										dsp += '<tr>';
											dsp += '<td colspan="4"><b>Total (Rp)</b></td>';
											dsp += '<td class="text-right"><b>'+d['total']+'</b></td>';
										dsp += '</tr>';
									}else{
										dsp += '    <tr><td colspan="4">Tidak ada data</td></tr>';
									}								
								}else if(tt==4){ //Retur Jual
									dsp += '<table id="table-order" class="table table-bordered">';
									dsp += '  <thead>';
									dsp += '    <th>Produk</th>';
									dsp += '    <th class="text-right">Lokasi Penempatan</th>';
									dsp += '    <th class="text-right">Qty</th>';
									dsp += '    <th class="text-right">Harga Jual</th>';
									dsp += '    <th class="text-right">Total</th>';
									dsp += '  </thead>';
									dsp += '  <tbody>';
									if(parseInt(d['total_produk']) > 0){
										for(var o=0; o < r.length; o++){
											dsp += '    <tr>';
											dsp += '      <td>'+r[o]['product_name']+'</td>';
											dsp += '      <td>'+r[o]['location']['location_name']+'</td>';										
											dsp += '      <td class="text-right">'+r[o]['trans_item_in_qty']+' '+r[o]['trans_item_unit']+'</td>';
											dsp += '      <td class="text-right">'+r[o]['trans_item_in_price']+'</td>';
											dsp += '      <td class="text-right">'+r[o]['trans_item_total']+'</td>';									
											dsp += '    </tr>';               
										}
										dsp += '<tr>';
											dsp += '<td colspan="4"><b>Total (Rp)</b></td>';
											dsp += '<td class="text-right"><b>'+d['total']+'</b></td>';
										dsp += '</tr>';
									}else{
										dsp += '    <tr><td colspan="3">Tidak ada data</td></tr>';
									}
								}
								dsp += '  </tbody>';
								dsp += '</table>';
								
							}else{
								// notif(s,m);
								// notifSuccess(m);
							}            
							// self.setTitle('Info Kontak');
							self.setContentAppend(dsp);
							/*type_your_code_here*/
						}).fail(function(){
							self.setContent('Something went wrong, Please try again.');
						});
					},
					buttons: {
						button_2: {
							text: 'Tutup',
							btnClass: 'btn-danger',
							keys: ['Escape'],
							action: function(){
								//Close
							}
						}
					}
				});					
			});
			$(document).on("click",".btn-trans-payment-infoHAPUS",function(e) {
				e.preventDefault();
				e.stopPropagation();
				var id = $(this).attr('data-id');
				var ses = $(this).attr('data-session');
				var typ = $(this).attr('data-type');
				var tn = $(this).attr('data-trans-number');
				var cn = $(this).attr('data-contact-name');
				var tt = $(this).attr('data-trans-type');			
				var tto = $(this).attr('data-trans-total');

				// $.alert('.btn-order-item-info');		
				var title   = 'Riwayat Transaksi';
				$.confirm({
					title: title,
					columnClass: 'col-lg-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
					autoClose: 'button_2|40000',    
					closeIcon: true,
					closeIconClass: 'fas fa-times',    
					animation:'zoom',
					closeAnimation:'bottom',
					animateFromElement:false,      
					content: function(){
						var self = this;
						var url = "<?= base_url('transaksi/manage'); ?>"; //CI

						var form = new FormData();
						form.append('action','trans-payment-history');
						form.append('trans_id',id);
						form.append('trans_type',tt);
						return $.ajax({
							url: url,
							data: form,
							dataType: 'json',
							type: 'post',
							cache: 'false', contentType: false, processData: false,
						}).done(function (d) {
							var s = d.status;
							var m = d.message;
							var r = d.result;
							if(parseInt(s) == 1){

								if(tt==1){
									var label = 'Supplier';
									var type = 'Hutang';
									var title = 'Riwayat Pembayaran Hutang ';
								}else if(tt==2){
									var label = 'Customer';
									var type = 'Piutang';
									var title = 'Riwayat Pelunasan Piutang ';									
								}
								var dsp = '';
								dsp += '<table class="table-default">';
									dsp += '<tr><td><b>Nomor Dokumen </b></td><td> : '+tn+'</td></tr>';
									dsp += '<tr><td><b>'+label+'</b></td><td> : '+cn+'</td></tr>';		   
									dsp += '<tr><td><b>Total (Rp) </b></td><td> : '+addCommas(tto)+'</td></tr>';									               
								dsp += '</table>';


								dsp += '<table id="table-order" class="table table-bordered">';
								dsp += '  <thead>';
								dsp += '    <th>Tanggal</th>';
								dsp += '    <th class="text-left">Keterangan</th>';
								dsp += '    <th class="text-right">Debit</th>';
								dsp += '    <th class="text-right">Kredit</th>';							
								dsp += '  </thead>';
								dsp += '  <tbody>';
								if(parseInt(d['total_data']) > 0){
									for(var p=0; p < d['total_data']; p++){
										var related = r[p]['related'];
										var debt = 0;
										var crdt = 0;											
										for(var o=0; o < related.length; o++){										
											dsp += '    <tr>';
											dsp += '      <td>'+r[p]['related'][o]['journal_item_date']+'</td>';
											dsp += '      <td>';
											dsp += '<a href="'+r[p]['journal_url']+'" target="_blank"><i class="fas fa-file-alt"></i> '+r[p]['journal_number']+'</a><br>';
											dsp += r[p]['related'][o]['account_code']+' - '+r[p]['related'][o]['account_name'];											
											dsp += '      </td>';
											dsp += '      <td class="text-right">'+addCommas(r[p]['related'][o]['journal_item_debit'])+'</td>';
											dsp += '      <td class="text-right">'+addCommas(r[p]['related'][o]['journal_item_credit'])+'</td>';
											dsp += '    </tr>';               

											debt = debt + r[p]['related'][o]['journal_item_debit'];
											crdt = crdt + r[p]['related'][o]['journal_item_credit'];												
										}
										var balance = parseFloat(tto) - parseFloat(debt) - parseFloat(crdt);
									}
									dsp += '<tr>';
										dsp += '<td colspan="3"><b>Sisa '+type+' (Rp)</b></td>';
										dsp += '<td class="text-right"><b>'+addCommas(balance)+'</b></td>';
									dsp += '</tr>';
								}else{
									dsp += '    <tr><td colspan="4">Tidak ada data riwayat</td></tr>';
									dsp += '<tr>';
										dsp += '<td colspan="3"><b>Sisa '+type+' (Rp)</b></td>';
										dsp += '<td class="text-right"><b>'+addCommas(tto)+'</b></td>';
									dsp += '</tr>';									
								}
								dsp += '  </tbody>';
								dsp += '</table>';
								
							}else{
								// notif(s,m);
								// notifSuccess(m);
							}            
							self.setTitle(title);
							self.setContentAppend(dsp);
							/*type_your_code_here*/
						}).fail(function(){
							self.setContent('Something went wrong, Please try again.');
						});
					},
					buttons: {
						button_2: {
							text: 'Tutup',
							btnClass: 'btn-danger',
							keys: ['Escape'],
							action: function(){
								//Close
							}
						}
					}
				});
			});
			$(document).on("click",".btn-trans-payment-info",function(e) {
				e.preventDefault();
				e.stopPropagation();
				var id = $(this).attr('data-id');
				var ses = $(this).attr('data-session');
				var typ = $(this).attr('data-type');
				var tn = $(this).attr('data-trans-number');
				var cn = $(this).attr('data-contact-name');
				var tt = $(this).attr('data-trans-type');			
				var tto = $(this).attr('data-trans-total');

				// $.alert('.btn-order-item-info');		
				var title   = 'Riwayat Transaksi';
				$.confirm({
					title: title,
					columnClass: 'col-lg-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
					autoClose: 'button_2|40000',    
					closeIcon: true,
					closeIconClass: 'fas fa-times',    
					animation:'zoom',
					closeAnimation:'bottom',
					useBootstrap: false, // Key line
					animateFromElement:false,      
					content: function(){
						var self = this;
						var url = "<?= base_url('transaksi/manage'); ?>"; //CI

						var form = new FormData();
						form.append('action','trans-payment-history');
						form.append('trans_id',id);
						form.append('trans_type',tt);
						return $.ajax({
							url: url,
							data: form,
							dataType: 'json',
							type: 'post',
							cache: 'false', contentType: false, processData: false,
						}).done(function (d) {
							var s = d.status;
							var m = d.message;
							var r = d.result;
							if(parseInt(s) == 1){

								if(tt==1){
									var label = 'Supplier';
									var type = 'Hutang';
									var title = 'Riwayat Pembayaran Hutang ';
								}else if(tt==2){
									var label = 'Customer';
									var type = 'Piutang';
									var title = 'Riwayat Pelunasan Piutang ';									
								}
								var dsp = '';
								dsp += '<table class="table-default">';
									dsp += '<tr><td><b>Nomor Dokumen </b></td><td> : '+tn+'</td></tr>';
									dsp += '<tr><td><b>'+label+'</b></td><td> : '+cn+'</td></tr>';		   
									dsp += '<tr><td><b>Total (Rp) </b></td><td> : '+addCommas(tto)+'</td></tr>';									               
								dsp += '</table>';


								dsp += '<table id="table-order" class="table table-bordered">';
								dsp += '  <thead>';
								dsp += '    <th>Tanggal</th>';
								dsp += '    <th class="text-left">Keterangan</th>';
								dsp += '    <th class="text-right">Debit</th>';
								dsp += '    <th class="text-right">Kredit</th>';	
								dsp += '    <th class="text-right">Balance</th>';															
								dsp += '  </thead>';
								dsp += '  <tbody>';
								if(parseInt(d['total_data']) > 0){
									// var related = r[p]['related'];
									var debt = 0;
									var crdt = 0;										
									for(var p=0; p < d['total_data']; p++){												
											dsp += '    <tr>';
											dsp += '      <td>'+r[p]['temp_date']+'</td>';
											dsp += '      <td>';
											dsp += '<a href="'+r[p]['temp_url']+'" target="_blank"><i class="fas fa-file-alt"></i> '+r[p]['temp_number']+'</a><br>';
											dsp += '<b>'+r[p]['temp_note']+'</b>';	
											if(r[p]['temp_memo'] != undefined){
												dsp += '<br>'+r[p]['temp_memo'];
											}										
											dsp += '      </td>';
											dsp += '      <td class="text-right">'+addCommas(r[p]['temp_debit'])+'</td>';
											dsp += '      <td class="text-right">'+addCommas(r[p]['temp_credit'])+'</td>';
											dsp += '      <td class="text-right">'+addCommas(r[p]['temp_balance'])+'</td>';											
											dsp += '    </tr>';               

											debt = parseFloat(debt) + parseFloat(r[p]['temp_debit']);
											crdt = parseFloat(crdt) + parseFloat(r[p]['temp_credit']);
									}
									if(tt==1){
										var balance = parseFloat(crdt) - parseFloat(debt);
									}else if(tt==2){
										var balance = parseFloat(debt) - parseFloat(crdt);
									}					
									dsp += '<tr>';
										dsp += '<td colspan="4"><b>Sisa '+type+' (Rp)</b></td>';
										dsp += '<td class="text-right"><b>'+addCommas(balance)+'</b></td>';
									dsp += '</tr>';
								}else{
									dsp += '    <tr><td colspan="4">Tidak ada data riwayat</td></tr>';
									dsp += '<tr>';
										dsp += '<td colspan="3"><b>Sisa '+type+' (Rp)</b></td>';
										dsp += '<td class="text-right"><b>'+addCommas(tto)+'</b></td>';
									dsp += '</tr>';									
								}
								dsp += '  </tbody>';
								dsp += '</table>';
								
							}else{
								// notif(s,m);
								// notifSuccess(m);
							}            
							self.setTitle(title);
							self.setContentAppend(dsp);
							/*type_your_code_here*/
						}).fail(function(){
							self.setContent('Something went wrong, Please try again.');
						});
					},
					buttons: {
						button_2: {
							text: 'Tutup',
							btnClass: 'btn-danger',
							keys: ['Escape'],
							action: function(){
								//Close
							}
						}
					}
				});
			});			
			$(document).on("click",".btn-journal-info",function(e) {
				e.preventDefault();
				e.stopPropagation();
				console.log($(this));
				var id = $(this).attr('data-id');
				var ses = $(this).attr('data-session');
				var typ = $(this).attr('data-type');
				// $.alert('.btn-journal-info');			
			});
			$(document).on("click",".btn-account-info",function(e) {
				e.preventDefault();
				e.stopPropagation();
				// return false;
				var id = $(this).attr('data-id');
				// alert(id);
				// var typ = $(this).attr('data-type');
				// var trans_type = $(this).attr('data-trans-type');
				// return false;
				var title   = 'Pergerakan Terakhir';
				$.confirm({
					title: title,
					columnClass: 'col-lg-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
					autoClose: 'button_2|40000',    
					closeIcon: true,
					closeIconClass: 'fas fa-times',    
					animation:'zoom',
					closeAnimation:'bottom',
					animateFromElement:false,      
					content: function(){
						var self = this;
						var url = "<?= base_url('keuangan/manage'); ?>"; //CI

						var form = new FormData();
						form.append('action','account-info');
						form.append('id',id);

						return $.ajax({
							url: url,
							data: form,
							dataType: 'json',
							type: 'post',
							cache: 'false', contentType: false, processData: false,
						}).done(function (d) {
							var s = d.status;
							var m = d.message;
							var r = d.result;
							var u = d.url;
							if(parseInt(s) == 1){
								// notif(s,m);
								// notifSuccess(m);
								/* hint zz_for or zz_each */
								var dsp = '';
								dsp += '<table class="table-default">';
								dsp += '<tr><td><b>Kode</b></td><td>: '+r.account.account_code+'</td></tr>';
								dsp += '<tr><td><b>Nama</b></td><td>: '+r.account.account_name+'</td></tr>';			                  
								dsp += '</table>';
								dsp += '<h5>Riwayat Terakhir</h5>';

								dsp += '<table id="table-order" class="table table-bordered">';
								dsp += '  <thead>';
								dsp += '    <th>Tgl</th>';
								dsp += '    <th>Nomor</th>';
								dsp += '    <th class="text-right">Total</th>';
								dsp += '  </thead>';
								dsp += '  <tbody>';
								if(r['journals'].length > 0){
									for(var o=0; o < r['journals'].length; o++){
										dsp += '    <tr>';
										dsp += '      <td>'+r['journals'][o]['journal_item_date']+'</td>';
										dsp += '      <td><a href="'+u+r['journals'][o]['journal_session']+'" target="_blank"><span class="fas fa-file-alt"></span> '+r['journals'][o]['journal_number']+'</a><br>';
											if(r['journals'][o]['journal_item_note'] != undefined){
												dsp += r['journals'][o]['journal_item_note']+'<br>';
											}
											if(r['journals'][o]['journal_note'] != undefined){
												dsp += r['journals'][o]['journal_note']+'<br>';
											}
											/*
											if(r['journals'][o]['journal_type'] == 1){
												dsp += r['journals'][o]['contact_name']+'<br>';
											}	
											if(r['journals'][o]['journal_type'] == 2){
												dsp += r['journals'][o]['contact_name']+'<br>';
											}	
											*/											
										dsp += '	  </td>';
										var total = '';
										if(parseFloat(r['journals'][o]['journal_item_debit']) > 0){
											color = 'green';
											total = addCommas(r['journals'][o]['journal_item_debit'])+'&nbsp;<i class="fas fa-arrow-down" style="color:'+color+';"></i>';
										}else if(parseFloat(r['journals'][o]['journal_item_credit']) > 0){
											color = 'red';
											total = addCommas(r['journals'][o]['journal_item_credit'])+'&nbsp;<i class="fas fa-arrow-up" style="color:'+color+';"></i>';
										}
										dsp += '      <td class="text-right"><a href="'+u+r['journals'][o]['journal_session']+'" target="_blank" data-id="'+r['journals'][o]['journal_item_journal_id']+'" data-session="'+r['journals'][o]['journal_session']+'" data-account-name="'+r['journals'][o]['account_name']+'" style="cursor:pointer;color:'+color+'">'+total+'</a></td>';
										dsp += '    </tr>';                    
									}
								}else{
									dsp += '    <tr><td colspan="3">Tidak ada data</td></tr>';
								}
								dsp += '  </tbody>';
								dsp += '</table>';

							}else{
								// notif(s,m);
								// notifSuccess(m);
							}            
							// self.setTitle('Info Kontak');
							self.setContentAppend(dsp);
							/*type_your_code_here*/
						}).fail(function(){
							self.setContent('Something went wrong, Please try again.');
						});
					},
					buttons: {
						button_2: {
							text: 'Tutup',
							btnClass: 'btn-danger',
							keys: ['Escape'],
							action: function(){
								//Close
							}
						}
					}
				});
			});
			$(document).on("click",".btn-approval-info",function(e) {
				e.preventDefault();
				e.stopPropagation();
				var fid = $(this).attr('data-id');
				var ffrom = $(this).attr('data-from');
				var fnum = $(this).attr('data-number');
				var fcnm = $(this).attr('data-contact-name');
				var fcid = $(this).attr('data-contact-id');
				var fdt = $(this).attr('data-date');		
				var ftt = $(this).attr('data-total');						
				var ftpe = $(this).attr('data-type');	
				var ftp = $(this).attr('data-contact-type');																								

				var title   = 'Info Approval '+ffrom;
				$.confirm({
					title: title,
					columnClass: 'col-lg-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
					closeIcon: true,
					closeIconClass: 'fas fa-times',    
					animation:'zoom',
					closeAnimation:'bottom',
					animateFromElement:false,      
					content: function(){
						var self = this;
						var url = "<?= base_url('approval'); ?>"; //CI

						var form = new FormData();
						form.append('action','load_approval_history');
						form.append('approval_from_id',fid);
						form.append('approval_from_table',ffrom);

						return $.ajax({
							url: url,
							data: form,
							dataType: 'json',
							type: 'post',
							cache: 'false', contentType: false, processData: false,
						}).done(function (d) {
							var s = d.status;
							var m = d.message;
							var r = d.result;
							var tr = d.total_records;
							if(parseInt(s) == 1){
								if(parseInt(ftp) == 1){
									set_contact_label = 'Supplier';
								}else if(parseInt(ftp) == 2){
									set_contact_label = 'Customer';
								}else if(parseInt(ftp) == 3){
									set_contact_label = 'Karyawan';
								}
								var dsp = '';
								dsp += '<table class="table-default">';
								dsp += '<tr><td><b>Nomor</b></td><td>: '+fnum+'</td></tr>';
								dsp += '<tr><td><b>Tanggal</b></td><td>: '+fdt+'</td></tr>';
								dsp += '<tr><td><b>'+set_contact_label+'</b></td><td>: '+fcnm+'</td></tr>';
								dsp += '<tr><td><b>Total</b></td><td>: '+ftt+'</td></tr>';			                  	                  
								dsp += '</table>';
								dsp += '<br><b>Riwayat Approval Terakhir</b>';

								dsp += '<table id="table-order" class="table table-bordered">';
								dsp += '  <thead>';
								dsp += '    <th>Tgl</th>';
								dsp += '    <th>User</th>';
								dsp += '    <th>Status</th>';
								dsp += '    <th>Komentar</th>';								
								dsp += '  </thead>';
								dsp += '  <tbody>';
								if(parseInt(tr) > 0){
									r.forEach(async (v, i) => {
										dsp += '<tr>';
											dsp += '<td>'+ moment(v['approval_date_created']).format("DD-MMM-YYYY, HH:mm")+'</td>';
											dsp += '<td>'+v['user_to']['username']+'</td>';
											dsp += '<td>'+v['flag']['label']+'</td>';
											dsp += '<td>'+v['approval_comment']+'</td>';
											// dsp += '<td>';
											//     dsp += '<button type="button" class="btn-action btn btn-primary" data-id="'+v['approval_id']+'">';
											//     dsp += 'Action';
											//     dsp += '</button>';
											// dsp += '</td>';
										dsp += '</tr>';             
									});
								}else{
									dsp += '    <tr><td colspan="4">Tidak ada data</td></tr>';
								}
								dsp += '  </tbody>';
								dsp += '</table>';
							}else{
								// notif(s,m);
							}            
							self.setTitle('Info Approval');
							self.setContentAppend(dsp);
						}).fail(function(){
							self.setContent('Something went wrong, Please try again.');
						});
					},
					buttons: {
						button_2: {
							text: 'Tutup',
							btnClass: 'btn-danger',
							keys: ['Escape'],
							action: function(){
								//Close
							}
						}
					}
				});
			});
			$(document).on("click",".btn-attachment-info",function(e) {
				e.preventDefault();
				e.stopPropagation();
				var fid = $(this).attr('data-id');
				var ffrom = $(this).attr('data-from');
				var fnum = $(this).attr('data-number');
				var fcnm = $(this).attr('data-contact-name');
				var fcid = $(this).attr('data-contact-id');
				var fdt = $(this).attr('data-date');		
				var ftt = $(this).attr('data-total');						
				var ftpe = $(this).attr('data-type');
				var ftp = $(this).attr('data-contact-type');																								

				var title   = 'Info Attachment '+ffrom;
				$.confirm({
					title: title,
					columnClass: 'col-lg-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
					closeIcon: true,
					closeIconClass: 'fas fa-times',    
					animation:'zoom',
					closeAnimation:'bottom',
					animateFromElement:false,      
					content: function(){
						var self = this;
						var url = "<?= base_url('approval'); ?>"; //CI

						var form = new FormData();
						form.append('action','load_file_history');
						form.append('file_from_id',fid);
						form.append('file_from_table',ffrom);

						return $.ajax({
							url: url,
							data: form,
							dataType: 'json',
							type: 'post',
							cache: 'false', contentType: false, processData: false,
						}).done(function (d) {
							var s = d.status;
							var m = d.message;
							var r = d.result;
							var tr = d.total_records;
							if(parseInt(s) == 1){
								var set_contact_label = 'Supplier';
								if(parseInt(ftp) == 2){
									set_contact_label = 'Customer';
								}else if(parseInt(ftp) == 3){
									set_contact_label = 'Karyawan';
								}
								var dsp = '';
								dsp += '<table class="table-default">';
								dsp += '<tr><td><b>Nomor</b></td><td>: '+fnum+'</td></tr>';
								dsp += '<tr><td><b>Tanggal</b></td><td>: '+fdt+'</td></tr>';
								dsp += '<tr><td><b>'+set_contact_label+'</b></td><td>: '+fcnm+'</td></tr>';
								dsp += '<tr><td><b>Total</b></td><td>: '+ftt+'</td></tr>';			                  	                  
								dsp += '</table>';
								dsp += '<br><b>Attachment Terkait</b>';

								dsp += '<table id="table-order" class="table table-bordered">';
								dsp += '  <thead>';
								dsp += '    <th>Name</th>';
								dsp += '    <th>Size</th>';
								dsp += '    <th>Date Created</th>';
								dsp += '    <th>Format</th>';
								dsp += '  </thead>';
								dsp += '  <tbody>';
								if(parseInt(tr) > 0){
									r.forEach(async (v, i) => {
                                
										var siz = '';
										if(v['file_type'] == 1){
											siz = v['file']['size_unit'];
										}

										var attr = 'data-file-type="'+v['file_type']+'" data-file-id="'+v['file_id']+'" data-file-session="'+v['file_session']+'" data-file-name="'+v['file']['name']+'" data-file-format="'+v['file']['format']+'" data-file-src="'+v['file']['src']+'"';                                                                                      
										dsp += '<tr>';
										dsp += '<td><a class="btn_attachment_preview" href="#" '+attr+'>'+v['file']['name']+'</a></td>';
										dsp += '<td style="text-align:right;">'+siz+'</td>';
										dsp += '<td>'+ moment(v['date']['date_created']).format("DD-MMM-YY, HH:mm")+'</td>';
											dsp += '<td>'+v['file']['format_label']+'</td>';
											// dsp += '<td>';
											//     dsp += '<button type="button" class="btn-action btn btn-primary" data-id="'+v['approval_id']+'">';
											//     dsp += 'Action';
											//     dsp += '</button>';
											// dsp += '</td>';
									dsp += '</tr>';       
									});
								}else{
									dsp += '    <tr><td colspan="3">Tidak ada data</td></tr>';
								}
								dsp += '  </tbody>';
								dsp += '</table>';
							}else{
								// notif(s,m);
							}            
							self.setTitle('Info Attachment');
							self.setContentAppend(dsp);
						}).fail(function(){
							self.setContent('Something went wrong, Please try again.');
						});
					},
					buttons: {
						button_2: {
							text: 'Tutup',
							btnClass: 'btn-danger',
							keys: ['Escape'],
							action: function(){
								//Close
							}
						}
					}
				});
			});

			$(document).on("click",".btn-trans-print",function(e) {
				e.preventDefault();
				e.stopPropagation();
				var id 			= $(this).attr('data-id');
				var session 	= $(this).attr('data-session');			
				window.open(url_print_trans+''+session,'_blank');
			});
			$(document).on("click",".btn-journal-print",function(e) {
				e.preventDefault();
				e.stopPropagation();
				var id 			= $(this).attr('data-id');
				var session 	= $(this).attr('data-session');			
				window.open(url_print_journal+''+session,'_blank');
			});

			$(document).on("click", ".btn-product-stock", function (e) {
				e.preventDefault();
				e.stopPropagation();
				var product_id = $(this).attr('data-product-id');
				var product_name = $(this).attr('data-product-name');
				var product_unit = $(this).attr('data-product-unit');
				var title = 'Lokasi Stok';
				$.confirm({
					title: title,
					columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
					// autoClose: 'button_2|30000',
					closeIcon: true,
					closeIconClass: 'fas fa-times',
					animation: 'zoom',
					closeAnimation: 'bottom',
					animateFromElement: false,
					content: function () {
						var self = this;

						var form = new FormData();
						form.append('action', 'stock');
						form.append('id', product_id);
						form.append('tipe', 1);

						return $.ajax({
							url: url_product,
							data: form,
							dataType: 'json',
							type: 'post',
							cache: 'false', contentType: false, processData: false,
						}).done(function (d) {
							var s = d.status;
							var m = d.message;
							var r = d.result;
							if (parseInt(s) == 1) {
								// notif(s,m);
								// notifSuccess(m);
								/* hint zz_for or zz_each */
								var dsp = '';
								var total_data = r.length;
								dsp += 'Barang :<b>' + product_name + '</b><br>';
								dsp += 'Satuan :<b>' + product_unit + '</b><br><br>';
								dsp += '<table class="table table-bordered">';
								dsp += '  <thead>';
								dsp += '    <tr>';
								dsp += '      <th>Gudang</th>';
								dsp += '      <th>Stok</th>';
								dsp += '      <th>Action</th>';
								dsp += '    <tr>';
								dsp += '  </thead>';
								dsp += '  <tbody>';
								for (var a = 0; a < total_data; a++) {
									dsp += '<tr class="tr-price-item-id" data-id="' + d.result[a]['product_price_id'] + '">';
									dsp += '<td>' + d.result[a]['location_name'] + '</td>';
									dsp += '<td style="text-align:right;">' + addCommas(d.result[a]['qty_balance']) + '</td>';
									dsp += '<td>';
									dsp += '<button type="button" class="btn-product-stock-card btn btn-mini btn-primary" data-url="' + d.result[a]['stock_card_url'] + '">';
									dsp += '<span class="fas fa-file-alt"></span>';
									dsp += '&nbsp;Kartu Stok</button>';
									dsp += '</td>';
									dsp += '</tr>';
								}
								dsp += '  </tbody>';
								dsp += '</table>';

								self.setContentAppend(dsp);
							} else {
								// notif(s,m);
								// notifSuccess(m);
							}
							// self.setTitle(m);
							// self.setContentAppend('<br>Version: ' + d.short_name); //Json Return
							// self.setContentAppend('<img src="'+ d.icons[0].src+'" class="img-responsive" style="margin:0 auto;">'); // Image Return
							/*type_your_code_here*/

						}).fail(function () {
							self.setContent('Something went wrong, Please try again.');
						});
					},
					onContentReady: function () {
						var self = this;
						var content = '';
						var dsp = '';

						var d = self.ajaxResponse.data;

						var s = d.status;
						var m = d.message;
						var r = d.result;

						if (parseInt(s) == 1) {
							// dsp += '<div>Content is ready after process !</div>';
							// dsp += '<form id="jc_form">';
							//     dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
							//     dsp += '    <div class="form-group">';
							//     dsp += '    <label class="form-label">Input</label>';
							//     dsp += '        <input id="jc_input" name="jc_input" class="form-control">';
							//     dsp += '    </div>';
							//     dsp += '</div>';
							//     dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
							//     dsp += '    <div class="form-group">';
							//     dsp += '    <label class="form-label">Textarea</label>';
							//     dsp += '        <textarea id="jc_textarea" name="alamat" class="form-control" rows="4"></textarea>';
							//     dsp += '    </div>';
							//     dsp += '</div>';
							//     dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
							//     dsp += '    <div class="form-group">';
							//     dsp += '    <label class="form-label">Select</label>';
							//     dsp += '        <select id="jc_select" name="jc_select" class="form-control">';
							//     dsp += '            <option value="1">Ya</option>';
							//     dsp += '            <option value="2">Tidak</option>';
							//     dsp += '        </select>';
							//     dsp += '    </div>';
							//     dsp += '</div>';
							// dsp += '</form>';
							// content = dsp;
							// self.setContentAppend(content);
							// self.buttons.button_1.disable();
							// self.buttons.button_2.disable();

							// this.$content.find('form').on('submit', function (e) {
							//      e.preventDefault();
							//      self.$$formSubmit.trigger('click'); // reference the button and click it
							// });
						} else {
							self.setContentAppend('<div>Content ready!</div>');
						}
					},
					buttons: {
						cancel: {
							btnClass: 'btn-default',
							text: 'Tutup',
							action: function () {
								// $.alert('Canceled!');
							}
						}
					}
				});
			});
			$(document).on("click", ".btn-product-stock-card", function (e) {
				e.preventDefault();
				e.stopPropagation();
				console.log($(this));
				var print_stock = $(this).attr('data-url');

				var x = screen.width / 2 - 700 / 2;
				var y = screen.height / 2 - 450 / 2;
				var print_url = print_stock;
				var win = window.open(print_url, 'Print Kartu Stok', 'width=880,height=500,left=' + x + ',top=' + y + '');
			});			
		  	//on change #search_input => info stok barang berdasarkan gudang
		  	$("#search_input").on("change", function(){
				var id = $(this).val();
				if(id > 0){
				  	$.ajax({
						type: "GET",     
						url: "<?= base_url('Barang/barang_get_satuan/'); ?>"+id,
						beforeSend:function(){},
						success:function(result){
							// reset select value 0
							$("#search_input").val(0).trigger('change');

							// nama + satuan
							var nama_a = result.nama + ' - ' +result.satuan;
							// nama
							var nama_b = result.nama;
							// jumlah desimal  = 4 => 1,0000
							var jumlah_desimal = 2;
						  
						  	/* BEGIN JCONFIRM X TABLE */
							// documentation : https://craftpip.github.io/jquery-confirm/
							$.confirm({
							  // custom layout
							  columnClass: 'medium',
							  // content
							  content: function () {
								  var self = this;
								  return $.ajax({
									  url: "<?= base_url('Barang/barang_get_stock_on_gudang/'); ?>"+id+"/"+jumlah_desimal,
									  dataType: 'json',
									  method: 'GET'
								  }).done(function (result) {
									  // title content
									  self.setTitle(nama_a);
									  
									  if(result.total_records > 0){
										
										// table tag
										var head = '<table class="table table-bordered table-striped"><tbody><tr><td style="padding:4px 0px!important;text-align:center"><b>Stok</b>&nbsp;</td><td style="padding:4px 0px!important;text-align:center">&nbsp;<b>Gudang</b></td><td class="hide" style="padding:4px 0px!important;text-align:center">&nbsp;<b>Riwayat</b></td></tr>';
										
										// table body
										var body = '';
										
										// end tag table
										var end = '</tbody></table>';
										
										$.each(result.result, function(i,val){
										   
											// table body
											body += '<tr>'+
													  '<td style="padding:4px 0px!important;text-align:right;"><b>'+ val.qty_akhir+'</b>&nbsp;<span class="hide fa fa-arrow-right"></td>'+
													  '<td style="padding:4px 0px!important;">&nbsp;<b>'+ val.gudang_kode +' - '+ val.gudang_nama +'</b>&nbsp;</td>'+
													  '<td class="hide" style="text-align:center;">'+
														'<button class="btn btn-primary btn-mini btn-riwayat-kartu-stok"'+
														' data-id-barang='+ val.id_barang+''+
														' data-id-lokasi='+ val.id_gudang+''+
														'>'+
														'Kartu Stok</button></td>'+
													'</tr>';

											// table structure
											var table = head+body+end;
											// content        
											self.setContent(table);
										 });
									  };

								  }).fail(function(){
									  // error
									  self.setContent('Something went wrong...');
								  });
							  },
								// autoClose: 'close|30000',
								buttons: {
									close: {
										btnClass: 'btn-dark',
										action: function(){}
									}
								}      
							});
						  	/* END JCONFIRM X TABLE */
						},
						error:function(xhr, Status, err){
							notifError('Error');
						}
				  	});
				};
		  	});
			$(document).on("click","#body-condense",function(e) {
				$("body").condensMenu();
			});
			$(document).on("click","#body-showhide",function(e) {
				$("body").toggleMenu();
			});   
		  	$.fn.datepicker.dates['id'] = {
				days: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"],
				daysShort: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
				daysMin: ["Mi", "Se", "Sl", "Rb", "Km", "Ju", "Sb"],
				months: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
				monthsShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
				today: "Today",
				clear: "Clear",
				format: "mm/dd/yyyy",
				titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
				weekStart: 0
		  	};
			function total_product_min_stock(){
				var data = {
					action:'product-min-stock',
					method:'count'
				};
				$.ajax({
					type: "post",
					url: url_product,
					data: data,
					dataType: 'json',
					cache: false,
					success: function(d){
						// $("#badge-product-stok-min").html(0);
						// badge-product-stock-min
						// $("#badge-product-stok-min").hide();
						// if(parseInt(d.status)==1){
							$(".badge-product-stok-min").html('');
							$(".badge-product-stok-min").html(d.result);
							// $(".badge-product-stok-min").css('display','block');
							// console.log(d.result);							
						// }
					}
				});
			}
			function total_transaction_over_due(){
				var data = {
					action:'product-',
					method:'count'
				};
				$.ajax({
					type: "post",
					url: url_product,
					data: data,
					dataType: 'json',
					cache: false,
					success: function(d){
						// $("#badge-product-stok-min").html(0);
						// badge-product-stock-min
						// $("#badge-product-stok-min").hide();
						// if(parseInt(d.status)==1){
							$(".badge-product-stok-min").html('');
							$(".badge-product-stok-min").html(d.result);
							// $(".badge-product-stok-min").css('display','block');
							// console.log(d.result);							
						// }
					}
				});
			}	
			function transaction_over_due(trans_type){
				if(trans_type == 1){
					var form_data = {
						action:'load-report-purchase-buy-account-payable',
						contact: $("#header-trans-date-due-supplier").find(':selected').val(),
						order:[0]['trans_date']	
					}
				}
				else if(trans_type == 2){
					var form_data = {
						action:'load-report-sales-sell-account-receivable',
						contact: $("#header-trans-date-due-customer").find(':selected').val(),
						order:[0]['trans_date']	
					}
				}
				$.ajax({
					type: "post",
					url: url_report,
					data: form_data,
					dataType: 'json',
					cache: false,
					success: function(d){
						let s = d.status;
						let m = d.message;
						let r = d.result;
						// console.log(trans_type);
						if(trans_type == 1){
							let total_records = d.total_records;
							if(parseInt(total_records) > 0){
								$("#table-trans-date-due-purchase tbody").html('');
							
								let dsp = '';
								for(let a=0; a < total_records; a++) {

									var date_due_over = parseInt(r[a]['trans_date_due_over']);
									dsp += '<tr>';
										dsp += '<td>';
										dsp += r[a]['trans_date_due_format'];
											if(date_due_over > 0){
												dsp += '<br><span class="label" style="color:white;background-color:#ff6665;padding:1px 4px;">Jatuh Tempo</span>';
												dsp += '<br>'+date_due_over+' hari';
											}	
										dsp += '</td>';
										dsp += '<td>';
										dsp += '<a href="#" class="btn-trans-print" data-id="'+r[a]['trans_id']+'" data-session="'+r[a]['trans_session']+'"><span class="fas fa-file-alt"></span> '+r[a]['trans_number']+'</a><br>';
											dsp += '<a href="#" class="btn-trans-item-info" data-id="'+r[a]['trans_id']+'" data-session="'+r[a]['trans_session']+'" data-trans-type="1" data-contact-name="'+r[a]['contact_name']+'" data-trans-number="'+r[a]['trans_number']+'" data-type="trans">'+addCommas(r[a]['balance'])+'</a><br>';						
											dsp += r[a]['contact_name'];
										dsp += '</td>';
									dsp += '</tr>';
							
								}
								$("#table-trans-date-due-purchase tbody").html(dsp);
							}
						}else if(trans_type == 2){
							let total_records = d.total_records;
							if(parseInt(total_records) > 0){
								$("#table-trans-date-due-sales tbody").html('');
							
								let dsp = '';
								for(let a=0; a < total_records; a++) {
									// console.log(a);
									var date_due_over = parseInt(r[a]['trans_date_due_over']);

									dsp += '<tr>';
										dsp += '<td>';
										dsp += r[a]['trans_date_due_format'];
											if(date_due_over > 0){
												dsp += '<br><span class="label" style="color:white;background-color:#ff6665;padding:1px 4px;">Jatuh Tempo</span>';
												dsp += '<br>'+date_due_over+' hari';
											}	
										dsp += '</td>';
										dsp += '<td>';
											dsp += '<a href="#" class="btn-trans-print" data-id="'+r[a]['trans_id']+'" data-session="'+r[a]['trans_session']+'"><span class="fas fa-file-alt"></span> '+r[a]['trans_number']+'</a><br>';
											dsp += '<a href="#" class="btn-trans-item-info" data-id="'+r[a]['trans_id']+'" data-session="'+r[a]['trans_session']+'" data-trans-type="2" data-contact-name="'+r[a]['contact_name']+'" data-trans-number="'+r[a]['trans_number']+'" data-type="trans">'+addCommas(r[a]['balance'])+'</a><br>';							
											dsp += r[a]['contact_name'];
										dsp += '</td>';
									dsp += '</tr>';
							
								}
								$("#table-trans-date-due-sales tbody").html(dsp);
							}
						}
					}
				});
			}
            function switch_branch(){
                let title   = 'Ganti Cabang';
                $.confirm({
                    title: title,
                    columnClass: 'col-md-8 col-md-offset-2 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                    closeIcon: true, closeIconClass: 'fas fa-times',    
                    animation:'zoom', closeAnimation:'bottom', animateFromElement:false,      
                    content: function(){
                        let self = this;
                        return $.ajax({
                            url: url_search+'?source=branchs',
                            dataType: 'json',
                            type: 'get',
                            cache: 'false', contentType: false, processData: false,
                        }).done(function (d) {		
                        }).fail(function(){
                            self.setContent('Something went wrong, Please try again.');
                        });
                    },
                    onContentReady: function(){
                        let self = this;
                        let content = '';
                        let dsp     = '';
                
                        let d = self.ajaxResponse.data;
                        let s = d.status;
                        let m = d.message;
                        let r = d.result;
                
                        let len = d.length;
                        // dsp += '<div>Silahkan pilih cabang yg ingin diinginkan</div>';
                        dsp += '<form id="jc_form">';
                            dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                            for(var a=0; a<len; a++){
                                if(parseInt(d[a]['id']) > 0){
                                    dsp += '<div class="col-md-3 col-sm-4 col-xs-6 padding-remove-side" style="cursor:pointer;">';
                                        dsp += '<a href="#" class="btn_switch_branch col-md-12 col-sm-12 col-xs-12" data-id="'+d[a]['id']+'">';
                                            dsp += '<div class="col-md-12 col-sm-12 col-xs-12">';
                                                dsp += '<img src="'+site_url+d[a]['branch_logo']+'" class="img-responsive">';
                                            dsp += '</div>';
                                            dsp += '<div class="col-md-12 col-sm-12 col-xs-12">';                  
                                                dsp += '<p style="text-align: center;font-size: 15px;font-weight: 800;">'+d[a]['nama']+'</p>';
                                            dsp += '</div>';           
                                        dsp += '</a>';                                   
                                    dsp += '</div>';
                                }
                            }							
                            dsp += '</div>';
                            dsp += '<input id="jc_input" name="jc_input" value="0" type="hidden">';
                        dsp += '</form>';
                        content = dsp;
                        self.setContentAppend(content);

                        $(document).on("click",".btn_switch_branch",function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            var id = $(this).attr('data-id');
                            $("#jc_input").val(id);
                        });
                    },
                    buttons: {
                        button_1: {
                            text:'<span class="fas fa-sign-out-alt"></span> Masuk',
                            btnClass: 'btn-primary',
                            keys: ['enter'],
                            action: function(){
                                let self      = this;
                                let select    = self.$content.find('#jc_input').val();
                                
                                if(select == 0){
                                    $.alert('Cabang dipilih dahulu');
                                    return false;
                                } else{
                                    let form = new FormData();
                                    form.append('action', 'action');
                                    // form.append('input', input);
                                    // form.append('textarea', textarea);
                                    form.append('branch_id', select);
                                    $.ajax({
                                        type: "post",
                                        url: url_login+'/authentication_switch_branch',
                                        data: form, dataType: 'json',
                                        cache: 'false', contentType: false, processData: false,
                                        beforeSend: function() {
                                        },
                                        success: function(d) {
                                            let s = d.status;
                                            let m = d.message;
                                            let r = d.result;
                                            if(parseInt(s) == 1){
                                                window.location.href = d.result.return_url;												
                                            }else{
                                                notif(s,m);
                                            }
                                        },
                                        error: function(xhr, status, err) {}
                                    });
                                }            
                            }
                        },
                        button_2: {
                            text: '<span class="fas fa-times"></span> Batal',
                            btnClass: 'btn-default',
                            keys: ['Escape'],
                            action: function(){
                                //Close
                            }
                        }
                    }
                });
            } 
            if(switch_do > 0){
                // switch_branch();
            }			
			// total_product_min_stock();
		});

		const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 3000,
			onOpen: (toast) => {
				toast.addEventListener('mouseenter', Swal.stopTimer)
				toast.addEventListener('mouseleave', Swal.resumeTimer)
			}
		});
		function loader($stat) {
			if ($stat == 1) {
				swal({
					title: '<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>',
					html: '<span style="font-size: 14px;">Loading...</span>',
					width: '20%',
					showConfirmButton: false,
					allowOutsideClick: false
				});
			} else if ($stat == 0) {
				swal.close();
			}
		}
		function notif($type,$msg) {
			if (parseInt($type) === 1) {
				//Toastr.success($msg);
				Toast.fire({
				type: 'success',
				title: $msg
				});
			} else if (parseInt($type) === 0) {
				//Toastr.error($msg);
				Toast.fire({
				type: 'error',
				title: $msg
				});
			}
		}

		// Notif Messenger Example
		function notifSuccess(msg){
			Messenger().post({
				message: msg,
				type: 'success',
				showCloseButton: true
			});
			// toastr.success('j');
			// toastr.options = {
			//     "closeButton": false,
			//     "debug": false,
			//     "newestOnTop": true,
			//     "progressBar": true,
			//     "positionClass": "toast-top-right",
			//     "preventDuplicates": false,
			//     "showDuration": "300",
			//     "hideDuration": "1000",
			//     "timeOut": "5000",
			//     "extendedTimeOut": "1000",
			//     "showEasing": "swing",
			//     "hideEasing": "linear",
			//     "showMethod": "slideDown",
			//     "hideMethod": "fadeOut",
			//     "rtl": false
			//   }  
			//   // Command: toastr['success'](msg, msg)
			//   // toastr.options.onclick = function() { console.log('clicked'); }
			//   // toastr.options.onCloseClick = function() { console.log('close button clicked'); }    
			//   // toastr.info('Are you the 6 fingered man?')
			//   toastr.success('We do have the Kapua suite available.', 'Turtle Bay Resort', {timeOut: 5000})
		}
		function notifConfirm(message){
			msg = Messenger().post({
				message: message,
				type: 'info',
				actions: {
					ok: {
						label: "Ok",
						action: function(){
						//
						// msg.hide()
						alert('You click ok');
						}
					},
					cancel: {
						action: function(){
							msg.hide()
						}
					}
				}
			})
		}
		function notifError(msg){
			Messenger().post({
				message: msg,
				type: 'error',
				showCloseButton: true
			});
		} 
		function notifProgress(errorMessage,successMessage){
			var i = 0;
			Messenger().run({
				errorMessage: errorMessage,
				successMessage: successMessage,
				action: function(opts) {
					if (++i < 3) {
						return opts.error({
						status: 500,
						readyState: 0,
						responseText: 0
						});
					} else {
						return opts.success();
					}
				}
			});
		}        

		// JConfirm
		function alerts(title,message,buttonText){
			$.alert({
				title: title,
				content: message,
				type: 'green',
				animation: 'scale',
				closeAnimation: 'scale',
				escapeKey: true,
				backgroundDismiss: true,
				buttons:{
					ok:{
						text: buttonText,
						btnClass: 'btn-primary',
						action: function(){
							// code here..
						}
					}
				}
			});
		}
		function openAlert(title,message,buttonText,milisecond){
			$.alert({
				title: title,
				content: message,
				autoClose: 'ok|'+milisecond,                
				buttons:{
					ok:{
						text: buttonText,
						btnClass: 'btn-green',
						action: function(){

						}
					}
				}
			});
		}
		function openConfirm(){
			$.confirm({
				title: 'Confirm!',
				content: 'Simple confirm!',
				buttons: {
					info: {
						btnClass: 'btn-blue',
						action: function(){

						}
					},
					confirm: function () {
						$.alert('Confirmed!');
					},
					cancel: function () {
						$.alert('Canceled!');
					},
					somethingElse: {
						text: 'Something else',
						btnClass: 'btn-blue',
						keys: ['enter', 'shift'],
						action: function(){
							$.alert('Something else?');
						}
					}
				}
			});
		}               
		// Custom  
		function logout(page,action){
			$.ajax({
				type: "POST",     
				url : "<?php echo base_url('login/logout');?>", 
				beforeSend:function(){
				},
				success:function(msg){
					var d=JSON.parse(msg);
					if(d['status']==1){
						notifSuccess(d['message']);
						window.location.href = '<?php echo base_url();?>';
					}
					else if(d['status']==0){ 
						notifError(d['message']);
					}            
				},
				error:function(xhr, Status, err){
					alert('Gagal');
				}
			});
		}   
		function checkInternet(status){
		  	if(status=='offline'){
				var stat = 'Internet Offline';
		  	}else{
				var stat = 'Internet Online';
		  	}
	  		$.confirm({
			 	// theme: 'bootstrap',
				type: 'red',
				title: stat,
				content: 'Check your internet connections',
				autoClose: 'trying|10000',
				buttons: {
					trying: {
						text: 'Trying In',
						btnClass: 'btn-red',                          
						action: function () {
						}
					},
					close: {
						text: 'Close',
						action: function () {
						}
					}
				}
			});
		}
		function setSelect2(formid,idoption,textoption){
			var data = {
				id: idoption
			};
			// Set the value, creating a new option if necessary
			if ($(formid).find("option[value='" + data.id + "']").length) {
				$(formid).val(data.id).trigger('change');
			} 
			else { 
				// Create a DOM Option and pre-select by default
				var newOption = new Option(data.id, true, true);
				// Append it to the select
				$(formid).append(newOption).trigger('change');
			}                
		}      
		function activeTab(tab){
			$('.nav-tabs a[href="#' + tab + '"]').tab('show');
		}
		function loadNotif(){
			$.ajax({
				type: "POST",     
				url: "<?= base_url('menu/notif/'); ?>",
				data:$("#form-master").serialize(), 
				beforeSend:function(){},
				success:function(result){
					if(result['total_records'] >= 1){ /* Success Message */
						console.log(result)
						$('#my-task-list').append('<span id="notif-count" class="badge badge-important bubble-only" style="padding: 3px 1px;height: 15px; width: 15px;bottom: 4px;">'+result['total_records']+'</span>');

						var data = result['result'];
						var text = "";
						var i = 0;
						for (; i < result['total_records']; i++) {
							text += '<div class="upd" style="width: 300px" onClick="approve(&#39;'+data[i]['controller']+'&#39;,&#39;'+data[i]['idPrint']+'&#39;);"><div style="width:290px; margin-left: auto; margin-right: auto;"><div class="notification-messages info"><div class="user-profile"><img src="assets/img/profiles/d.jpg" alt="" data-src="assets/img/profiles/d.jpg" data-src-retina="assets/img/profiles/d2x.jpg" width="35" height="35"></div><div class="message-wrapper"><div class="heading">'+data[i]['user']+' Meminta Persetujuan</div><div class="description">'+data[i]['menu']+' '+data[i]['nomor']+'</div><div class="recent pull-left">'+data[i]['tgl']+'</div></div><div class="clearfix"></div></div></div><div>';
						}
						$('.upd').remove();
						$('#drop').append(text);
					}
				},
				error:function(xhr, Status, err){
					notifError('Error');
				}
			});
		}
		function approve(menu,id){
			window.open('<?= base_url();?>'+menu+'/prints/'+id)
		}
		function scrollUp(idelement){
			$([document.documentElement, document.body]).animate({
			scrollTop: $("#"+ idelement).offset().top
			}, 2000);
		}   
		// $(".sidebarz").scroll(function() { //.box is the class of the div
		//   var sidebar_from_top = $(".sidebarz").offset().top;
		//   console.log('As:'+sidebar_from_top);
		//     // $("span").css( "display", "inline" ).fadeOut( "slow" );
		// });
		function addCommas(string){
			string += '';
			var x = string.split('.');
			var x1 = x[0];
			var x2 = x.length > 1 ? '.' + x[1] : '';
			var rgx = /(\d+)(\d{3})/;
			while (rgx.test(x1)) {
				x1 = x1.replace(rgx, '$1' + ',' + '$2');
			}
			return x1 + x2;
		}
		function removeCommas(string){

		 	return string.split(',').join("");
		}
		function numberWithCommas(x) {
		    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		}	
        function get_date_diff(start_date,end_date){
            // console.log('get_date_diff('+start_date+','+end_date+')');
            let d1 = moment(start_date, 'YYYY-MM-DD H:m:s').toDate();
            let d2 = moment(end_date, 'YYYY-MM-DD H:m:s').toDate();
            // console.log(d1);
            // console.log(d2);
            // start_date  = Thu Feb 01 2024 00:00:00 GMT+0700 (Western Indonesia Time);
            // end_date = Mon Feb 05 2024 00:00:00 GMT+0700 (Western Indonesia Time);
            var dd = ((d2-d1)/1000/60/60/24) + 1;
			console.log('get_date_diff() => '+start_date+', '+end_date+' ? '+dd);
            return dd;
        }			
  	</script>   
	<!-- <script src="<?php #echo base_url();?>assets/pwa.min.js" type="text/javascript"></script> -->
	<script>
		// UpUp.start({ 'content-url' : '<?php #echo base_url();?>' });
        // var BASE_URL = '<?= base_url() ?>';
        // document.addEventListener('DOMContentLoaded', init, false);
        // function init() {
        //     if ('serviceWorker' in navigator && navigator.onLine) {
        //         navigator.serviceWorker.register( BASE_URL + 'manifest_sw.js')
        //         .then((reg) => {
        //             console.log('ServiceWorker: Registered'); 
		// 			// console.log(reg);                    
        //         }, (err) => {
        //             console.error('ServiceWorker: Failed'); 
		// 			console.log(err);
        //         });
        //     }
        // }
	</script>    
<?php if (isset($script)) { /* $this->load->view($script); */ } ?>
</body>
</html>