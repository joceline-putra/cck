<style>
    .li-report > ul > li > a{
        margin-left: 60px!important; 
        color:red;
    }
    #report > ul{
        padding-top: 0px!important;
    }
    #report > ul > li{
        padding: 2px 0px;
    }
    #report > ul > li > ul > li > a{
        padding: 3px 0px!important;
    }
</style>
<?php
$next = true;
?>
<div id="main-menu" class="page-sidebar col-md-2" style="padding-bottom: 30px!important;display: block!important;">
    <div class="page-sidebar-wrapper scrollbar-dynamic" id="main-menu-wrapper">
        <p class="menu-title sm" style="padding-top:0px!important;margin:0px 0px 0px!important;">
        </p>
        <ul id="sidebar" style="" class="sidebarz">
            <li class="start"> 
                <a href="<?php echo base_url('admin'); ?>">
                  <!-- <i class="fa fa-home" style=""></i> -->
                    <i class="fas fa-home"></i>
                    <span class="title">Beranda</span> <span class="selected"></span>
                </a>            
            </li>
            <?php
            //Start of Only Joe
            // if($session['user_data']['user_name'] == 'root'){
            ?>
            <!-- <li class="start"> 
                <a href="#">
                    <i class="fas fa-wallet"></i>
                    <span class="title">Booking</span> <span class="selected"></span>
                </a>
                <li class="start open">
                    <ul class="open sub-menu" style="display:block;">
                        <li><a href="<?php #echo site_url('booking/cece')?>">Cece</a></li>
                        <li><a href="<?php #echo site_url('booking/lily')?>">Lily</a></li>
                    </ul>
                </li>
            </li> -->
            <?php                
            // }
            //End of Only Joe  
            ?>          
            <?php  
            if ($next) {
                ?>
                <?php
                if ($session['user_data']['menu_access']) {
                    $menu = $session['user_data']['menu_access'];
                    foreach ($menu as $key => $value) {

                        if ($value['menu_group_id'] != 44) {
                            ?>
                            <li class="start open"> 
                                <a href="#"><i class="<?php echo $value['menu_group_icon'] ?>"></i>
                                    <span class="title"><?php echo $value['menu_group_name']; ?></span> <span class="selected"></span></a>
                                <ul class="open sub-menu" style="display:block;">

                                    <?php
                                    foreach ($value['sub_menu'] as $key => $sub_menu) {
                                        $user_menu_flag = $sub_menu['user_menu_flag'];
                                        if (intval($user_menu_flag) == 1) {
                                            ?>
                                            <li data-menu="<?php echo $sub_menu['menu_link']; ?>"> 
                                                <a id="m<?php echo $sub_menu['menu_link']; ?>" data-id="<?php echo $sub_menu['menu_id']; ?>" href="<?php echo base_url($sub_menu['menu_link']); ?>" style="">
                                                    <?php echo $sub_menu['menu_name']; ?>
                                                </a> 
                                            </li>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </li> 
                            <?php } else {
                            ?>
                            <li id="report" class="start open"> 
                                <a href="<?php echo base_url('report'); ?>">
                                    <span class="material-icons">summarize</span>
                                    <span class="title">Laporan</span> <span class="selected"></span>
                                </a>
                                <ul class="open sub-menu" style="display:block;">
                                    <?php
                                    foreach ($value['sub_menu'] as $key => $sub_menu) {
                                        $user_menu_flag = $sub_menu['user_menu_flag'];
                                        if (intval($user_menu_flag) == 1) {
                                            ?>
                                            <li class="start open li-report"> 
                                                <a id="" data-id="" href="" style="padding-left: 38px!important;"><span class="title"><?php echo $sub_menu['menu_name']; ?></span> <span class="selected"></span></a> 
                                                <ul class="sub-menu" style="display:none;">

                                                    <?php
                                                    $submenu = $sub_menu['menu_name'];
                                                    switch ($submenu) {
                                                        // case "Bisnis":
                                                        //     echo '<li class="start"><a id="" data-id="" href="' . base_url('report/finance/journal') . '" style=""><span class="title">Jurnal</span> <span class="selected"></span></a></li>';
                                                        //     echo '<li class="start"><a id="" data-id="" href="' . base_url('report/finance/ledger') . '" style=""><span class="title">Buku Besar</span> <span class="selected"></span></a></li>';
                                                        //     echo '<li class="start"><a id="" data-id="" href="' . base_url('report/finance/trial_balance') . '" style=""><span class="title">Neraca Saldo</span> <span class="selected"></span></a></li>';
                                                        //     echo '<li class="start"><a id="" data-id="" href="' . base_url('report/finance/worksheet') . '" style=""><span class="title">Kertas Kerja</span> <span class="selected"></span></a></li>';
                                                        //     echo '<li class="start"><a id="" data-id="" href="' . base_url('report/finance/profit_loss') . '" style=""><span class="title">Laba Rugi</span> <span class="selected"></span></a></li>';
                                                        //     echo '<li class="start"><a id="" data-id="" href="' . base_url('report/finance/balance') . '" style=""><span class="title">Neraca</span> <span class="selected"></span></a></li>';
                                                        //     echo '<li class="start"><a id="" data-id="" href="#"><span class="title">&nbsp;</span> <span class="selected"></span></a></li>';
                                                        //     echo '<li class="start"><a id="" data-id="" href="' . base_url('report/finance/cash_in') . '" style=""><span class="title">Pemasukan Uang</span> <span class="selected"></span></a></li>';
                                                        //     echo '<li class="start"><a id="" data-id="" href="' . base_url('report/finance/cash_out') . '" style=""><span class="title">Pengeluaran Uang</span> <span class="selected"></span></a></li>';
                                                        //     break;
                                                        // case "Pembelian":
                                                        //     echo '<li class="start"><a id="" data-id="" href="' . base_url('report/purchase/buy/recap') . '" style=""><span class="title">Pembelian Rekap</span> <span class="selected"></span></a></li>';
                                                        //     echo '<li class="start"><a id="" data-id="" href="' . base_url('report/purchase/buy/detail') . '" style=""><span class="title">Pembelian Rinci</span> <span class="selected"></span></a></li>';
                                                        //     echo '<li class="start"><a id="" data-id="" href="' . base_url('report/purchase/buy/account_payable') . '" style=""><span class="title">Hutang Supplier</span> <span class="selected"></span></a></li>';
                                                        //     echo '<li class="start"><a id="" data-id="" href="' . base_url('report/purchase/order/detail') . '" style=""><span class="title">Purchase Order Rinci</span> <span class="selected"></span></a></li>';
                                                        //     echo '<li class="start"><a id="" data-id="" href="' . base_url('report/purchase/return/detail') . '" style=""><span class="title">Retur Pembelian</span> <span class="selected"></span></a></li>';
                                                        //     echo '<li class="hide"><a id="" data-id="" href="" style=""><span class="title">Usia Hutang</span> <span class="selected"></span></a></li>';
                                                        //     break;
                                                        case "Penjualan":
                                                            // echo '<li class="start"><a id="" data-id="" href="' . base_url('report/sales/sell/recap') . '" style=""><span class="title">Penjualan Rekap</span> <span class="selected"></span></a></li>';
                                                            echo '<li class="start"><a id="" data-id="" href="' . base_url('report/sales/sell/detail') . '" style=""><span class="title">Warmindo</span> <span class="selected"></span></a></li>';
                                                            // echo '<li class="start"><a id="" data-id="" href="' . base_url('report/sales/sell/account_receivable') . '" style=""><span class="title">Piutang Customer</span> <span class="selected"></span></a></li>';
                                                            echo '<li class="start"><a id="" data-id="" href="' . base_url('report/sales/order/detail') . '" style=""><span class="title">Kamar</span> <span class="selected"></span></a></li>';
                                                            // echo '<li class="start"><a id="" data-id="" href="' . base_url('report/sales/return/detail') . '" style=""><span class="title">Retur Penjualan</span> <span class="selected"></span></a></li>';
                                                            // echo '<li class="hide"><a id="" data-id="" href="" style=""><span class="title">Usia Piutang</span> <span class="selected"></span></a></li>';
                                                            break;
                                                        // case "Produksi":
                                                        //     echo '<li class="start"><a id="" data-id="" href="' . base_url('report/production/product/detail') . '" style=""><span class="title">Produk Jadi Rinci</span> <span class="selected"></span></a></li>';
                                                        //     echo '<li class="hide"><a id="" data-id="" href="" style=""><span class="title">Usia Piutang</span> <span class="selected"></span></a></li>';
                                                        //     break;
                                                        case "Stok":
                                                            // echo '<li class="hide"><a id="" data-id="" href="" style=""><span class="title">Ringkasan Persediaan</span> <span class="selected"></span></a></li>';
                                                            // echo '<li class="hide"><a id="" data-id="" href="" style=""><span class="title">Nilai Persediaan</span> <span class="selected"></span></a></li>';
                                                            // echo '<li class="hide"><a id="" data-id="" href="" style=""><span class="title">Rincian Persediaan</span> <span class="selected"></span></a></li>';
                                                            // echo '<li class="start"><a id="" data-id="" href="' . base_url('report/inventory/product/stock_warehouse') . '" style=""><span class="title">Kuantitas Stok Gudang</span> <span class="selected"></span></a></li>';
                                                            // echo '<li class="start"><a id="" data-id="" href="' . base_url('report/inventory/product/stock_valuation') . '" style=""><span class="title">Nilai Stok Gudang</span> <span class="selected"></span></a></li>';
                                                            echo '<li class="start"><a id="" data-id="" href="' . base_url('report/inventory/product/stock_moving') . '" style=""><span class="title">Pergerakan Stok</span> <span class="selected"></span></a></li>';
                                                            break;
                                                        // case "Asset":
                                                        //     echo '<li class="start"><a id="" data-id="" href="" style=""><span class="title">Ringkasan Aset</span> <span class="selected"></span></a></li>';
                                                        //     echo '<li class="start"><a id="" data-id="" href="" style=""><span class="title">Detail Aset</span> <span class="selected"></span></a></li>';
                                                        //     break;
                                                        // case "Pajak":
                                                        //     echo '<li class="hide"><a id="" data-id="" href="" style=""><span class="title">Pajak Pemotongan</span><span class="selected"></span></a></li>';
                                                        //     echo '<li class="hide"><a id="" data-id="" href="" style=""><span class="title">Pajak Penjualan</span><span class="selected"></span></a></li>';
                                                        //     break;
                                                    }
                                                    ?>
                                                    <!-- <li class="start"> 
                                                      <a id="" data-id="" href="" style=""><span class="title">Arus Kas</span> <span class="selected"></span></a> 
                                                    </li>-->

                                                </ul>
                                            </li>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </li>
                            <?php
                        }
                    }
                }
            }
            ?>  
            <li id="report" class="start open" style="display:none;"> 
                <a href="<?php echo base_url('report'); ?>">
                  <!-- <i class="fa fa-home" style=""></i> -->
                    <span class="material-icons">summarize</span>
                    <span class="title">Laporan</span> <span class="selected"></span>
                </a>
                <ul class="open sub-menu" style="display:block;">

                    <!-- Bisnis -->          
                    <li class="start open li-report"> 
                        <a id="" data-id="" href="" style="padding-left: 38px!important;"><span class="title">Bisnis</span> <span class="selected"></span></a> 
                        <ul class="open sub-menu" style="display:block;">
                            <!-- <li class="start"> 
                              <a id="" data-id="" href="" style=""><span class="title">Arus Kas</span> <span class="selected"></span></a> 
                            </li>    -->             
                            <li class="start"> 
                                <a id="" data-id="" href="<?php echo base_url('report/finance/journal'); ?>" style=""><span class="title">Jurnal</span> <span class="selected"></span></a> 
                            </li>      
                            <li class="start"> 
                                <a id="" data-id="" href="<?php echo base_url('report/finance/ledger'); ?>" style=""><span class="title">Buku Besar</span> <span class="selected"></span></a> 
                            </li>     
                            <li class="start">
                                <a id="" data-id="" href="<?php echo base_url('report/finance/trial_balance'); ?>" style=""><span class="title">Neraca Saldo</span> <span class="selected"></span></a>
                            </li> 
                            <li class="start">
                                <a id="" data-id="" href="<?php echo base_url('report/finance/worksheet'); ?>" style=""><span class="title">Kertas Kerja</span> <span class="selected"></span></a>
                            </li>               
                            <li class="start">
                                <a id="" data-id="" href="<?php echo base_url('report/finance/profit_loss'); ?>" style=""><span class="title">Laba Rugi</span> <span class="selected"></span></a>
                            </li>
                            <li class="start">
                                <a id="" data-id="" href="<?php echo base_url('report/finance/balance'); ?>" style=""><span class="title">Neraca</span> <span class="selected"></span></a>
                            </li> 
                        </ul>                 
                    </li> 

                    <!-- Pembelian -->          
                    <li class="start open li-report"> 
                        <a id="" data-id="" href="" style="padding-left: 38px!important;"><span class="title">Pembelian</span> <span class="selected"></span></a> 
                        <ul class="open sub-menu" style="display:block;">
                            <li class="start"> 
                                <a id="" data-id="" href="<?php echo base_url('report/purchase/buy/recap'); ?>" style=""><span class="title">Pembelian Rekap</span> <span class="selected"></span></a> 
                            </li>  
                            <li class="start"> 
                                <a id="" data-id="" href="<?php echo base_url('report/purchase/buy/detail'); ?>" style=""><span class="title">Pembelian Rinci</span> <span class="selected"></span></a> 
                            </li>     
                            <li class="start"> 
                                <a id="" data-id="" href="<?php echo base_url('report/purchase/buy/account_payable'); ?>" style=""><span class="title">Hutang Supplier</span> <span class="selected"></span></a> 
                            </li> 
                            <!-- <li class="start">  -->
                              <!-- <a id="" data-id="" href="" style=""><span class="title">Usia Hutang</span> <span class="selected"></span></a>  -->
                            <!-- </li>                                             -->
                        </ul>                 
                    </li> 

                    <!-- Penjualan -->
                    <li class="start open li-report"> 
                        <a id="" data-id="" href="" style="padding-left: 38px!important;"><span class="title">Penjualan</span> <span class="selected"></span></a> 
                        <ul class="open sub-menu" style="display:block;">
                            <li class="start"> 
                                <a id="" data-id="" href="<?php echo base_url('report/sales/sell/recap'); ?>" style=""><span class="title">Penjualan Rekap</span> <span class="selected"></span></a> 
                            </li>  
                            <li class="start"> 
                                <a id="" data-id="" href="<?php echo base_url('report/sales/sell/detail'); ?>" style=""><span class="title">Penjualan Rinci</span> <span class="selected"></span></a> 
                            </li>     
                            <li class="start"> 
                                <a id="" data-id="" href="<?php echo base_url('report/sales/sell/account_receivable'); ?>" style=""><span class="title">Piutang Customer</span> <span class="selected"></span></a> 
                            </li> 
                            <!-- <li class="start">  -->
                              <!-- <a id="" data-id="" href="" style=""><span class="title">Usia Piutang</span> <span class="selected"></span></a>  -->
                            <!-- </li>                   -->
                        </ul>                 
                    </li>

                    <!-- Produksi -->
                    <li class="start open li-report"> 
                        <a id="" data-id="" href="" style="padding-left: 38px!important;"><span class="title">Produksi</span> <span class="selected"></span></a> 
                        <ul class="open sub-menu" style="display:block;">
                            <li class="start"> 
                                <a id="" data-id="" href="<?php echo base_url('report/production/product/detail'); ?>" style=""><span class="title">Produk Jadi Rinci</span> <span class="selected"></span></a> 
                            </li>
                            <!-- <li class="start">  -->
                              <!-- <a id="" data-id="" href="" style=""><span class="title">Usia Piutang</span> <span class="selected"></span></a>  -->
                            <!-- </li>-->
                        </ul>                 
                    </li>

                    <!-- Produk -->
                    <li class="start open li-report"> 
                        <a id="" data-id="" href="" style="padding-left: 38px!important;"><span class="title">Stok</span> <span class="selected"></span></a> 
                        <ul class="open sub-menu" style="display:block;">
                            <!-- <li class="start">  -->
                              <!-- <a id="" data-id="" href="" style=""><span class="title">Ringkasan Persediaan</span> <span class="selected"></span></a>  -->
                            <!-- </li>       -->
                            <!-- <li class="start">  -->
                              <!-- <a id="" data-id="" href="" style=""><span class="title">Nilai Persediaan</span> <span class="selected"></span></a>  -->
                            <!-- </li>  -->
                            <!-- <li class="start">  -->
                              <!-- <a id="" data-id="" href="" style=""><span class="title">Rincian Persediaan</span> <span class="selected"></span></a>  -->
                            <!-- </li>    -->
                            <li class="start"> 
                                <a id="" data-id="" href="<?php echo base_url('report/inventory/product/stock_warehouse'); ?>" style=""><span class="title">Kuantitas Stok Gudang</span> <span class="selected"></span></a> 
                            </li>                                            
                            <!-- <li class="start">  -->
                              <!-- <a id="" data-id="" href="" style=""><span class="title">Nilai Stok Gudang</span> <span class="selected"></span></a>  -->
                            <!-- </li>                                                           -->
                            <li class="start"> 
                                <a id="" data-id="" href="<?php echo base_url('report/inventory/product/stock_moving'); ?>" style=""><span class="title">Pergerakan Stok</span> <span class="selected"></span></a> 
                            </li>                                                                        
                        </ul>                 
                    </li>

                    <!-- Asset -->
                    <li class="hide start open li-report"> 
                        <a id="" data-id="" href="" style="padding-left: 38px!important;"><span class="title">Asset</span> <span class="selected"></span></a> 
                        <ul class="open sub-menu" style="display:block;">
                            <li class="start"> 
                                <a id="" data-id="" href="" style=""><span class="title">Ringkasan Aset</span> <span class="selected"></span></a> 
                            </li>      
                            <li class="start"> 
                                <a id="" data-id="" href="" style=""><span class="title">Detail Aset</span> <span class="selected"></span></a> 
                            </li>                
                        </ul>                 
                    </li>

                    <!-- Pajak -->
                    <li class="hide start open li-report"> 
                        <a id="" data-id="" href="" style="padding-left: 38px!important;"><span class="title">Pajak</span> <span class="selected"></span></a> 
                        <ul class="open sub-menu" style="display:block;">
                            <li class="start"> 
                                <a id="" data-id="" href="" style=""><span class="title">Pajak Pemotongan</span> <span class="selected"></span></a> 
                            </li>
                            <li class="start"> 
                                <a id="" data-id="" href="" style=""><span class="title">Pajak Penjualan</span> <span class="selected"></span></a> 
                            </li>                
                        </ul>                 
                    </li>
                </ul>        
            </li>
        </ul>
        <div class="clearfix"></div>
        <br><br>
    </div>
</div>
<!-- <a href="#" class="scrollup">Scroll</a> -->

