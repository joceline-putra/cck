<style>
.horizontal-menu .bar-inner > ul > li{
    font-family:var(--font-family);
}    
.horizontal-menu .bar {
    margin-top:50px!important;
}
.classic > ul{
    border-right: 1px solid #e5e9ec;
    border-left: 1px solid #e5e9ec;
    border-bottom: 1px solid #e5e9ec;
}
</style>
<?php 
$branch_logo = !empty($session['user_data']['branch']['branch_logo']) ? site_url() . $session['user_data']['branch']['branch_logo'] : site_url() . 'upload/branch/default_sidebar.png';
$menu = $session['user_data']['menu_access'];

if(intval($session['menu_display']) == 1){
?>
<div id="horizontal-menu-div" class="bar visible-lg visible-md" style="<?php echo $horizontal_menu_div_style; ?>">
    <div class="bar-inner">
        <ul>
            <li>
                <a href="<?php echo base_url('admin'); ?>">
                    <i class="fas fa-home"></i>&nbsp;
                    <span class="semi-bold">Beranda </span>
                </a>
            </li>
            <?php 
            if ($session['user_data']['menu_access']) {
                foreach ($menu as $key => $value): //Foreach Group Menu
                    if ($value['menu_group_id'] != 44) { //Except LAPORAN
                    ?>
                        <li class="classic">
                            <a href="javascript:;">
                                <i class="<?php echo $value['menu_group_icon'] ?>"></i>&nbsp;<span class="semi-bold"><?php echo $value['menu_group_name']; ?></span> <span class="fas fa-caret-down"></span>
                            </a>
                            <ul class="classic">
                                <?php 
                                    foreach ($value['sub_menu'] as $key => $sub_menu):
                                        if (intval($sub_menu['user_menu_flag']) == 1) {
                                            ?> 
                                            <li data-menu="<?php echo $sub_menu['menu_link']; ?>">
                                                <a id="m<?php echo $sub_menu['menu_link']; ?>" data-id="<?php echo $sub_menu['menu_id']; ?>" href="<?php echo base_url($sub_menu['menu_link']); ?>">
                                                    <?php echo $sub_menu['menu_name']; ?>
                                                    <!-- <span class="description">Menu Description</spam> -->
                                                </a>
                                            </li>
                                            <?php 
                                        }
                                endforeach; //End foreach Menu
                                ?> 
                            </ul>
                        </li>
                    <?php 
                    }else{ ?>
                        <li class="mega">
                            <a href="javascript:;">
                                <i class="<?php echo $value['menu_group_icon'] ?>"></i>&nbsp;<span class="semi-bold"><?php echo $value['menu_group_name']; ?></span> <span class="fas fa-caret-down"></span>
                            </a>
                            <ul class="mega">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="sub-menu-heading bold">Your Company</div>
                                            <img src="<?php echo $branch_logo; ?>" alt="" data-src="<?php echo $branch_logo; ?>" data-src-retina="<?php echo $branch_logo; ?>" width="150px">
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="sub-menu-heading bold">Pembelian</div>
                                            <ul class="sub-menu">
                                                <?php
                                                foreach ($value['sub_menu'] as $key => $sub_menu) {
                                                    if (intval($sub_menu['user_menu_flag']) == 1) {
                                                        if($sub_menu['menu_name'] == "Pembelian"){
                                                            echo '<li><a id="" data-id="" href="' . base_url('report/purchase/buy/recap') . '">Pembelian Rekap</a></li>';
                                                            echo '<li><a id="" data-id="" href="' . base_url('report/purchase/buy/detail') . '">Pembelian Rinci</a></li>';
                                                            echo '<li><a id="" data-id="" href="' . base_url('report/purchase/buy/account_payable') . '">Hutang Supplier</a></li>';
                                                            echo '<li><a id="" data-id="" href="' . base_url('report/purchase/order/detail') . '">Purchase Order Rinci</a></li>';
                                                            echo '<li><a id="" data-id="" href="' . base_url('report/purchase/return/detail') . '">Retur Pembelian</li>';
                                                            echo '<li class="hide"><a id="" data-id="" href="#">Usia Hutang</a></li>';
                                                        ?>
                                                        <?php 
                                                        }
                                                    }
                                                } ?>
                                            </ul>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="sub-menu-heading bold">Penjualan</div>
                                            <ul class="sub-menu">
                                                <?php
                                                foreach ($value['sub_menu'] as $key => $sub_menu) {
                                                    if (intval($sub_menu['user_menu_flag']) == 1) {
                                                        if($sub_menu['menu_name'] == "Penjualan"){
                                                            echo '<li><a id="" data-id="" href="' . base_url('report/sales/sell/recap') . '">Penjualan Rekap</a></li>';
                                                            echo '<li><a id="" data-id="" href="' . base_url('report/sales/sell/detail') . '">Penjualan Rinci</a></li>';
                                                            echo '<li><a id="" data-id="" href="' . base_url('report/sales/sell/account_receivable') . '">Piutang Customer</a></li>';
                                                            echo '<li><a id="" data-id="" href="' . base_url('report/sales/order/detail') . '">Sales Order Rinci</a></li>';
                                                            echo '<li><a id="" data-id="" href="' . base_url('report/sales/prepare/detail') . '">Prepare Rinci</li>';
                                                            echo '<li><a id="" data-id="" href="' . base_url('report/sales/return/detail') . '">Retur Penjualan</li>';                                                            
                                                            echo '<li class="hide"><a id="" data-id="" href="#">Usia Piutang</a></li>';
                                                            
                                                        ?>
                                                        <!-- <li><a href="typography.html"> Typography </a> </li> -->
                                                        <?php 
                                                        }
                                                    }
                                                } ?>
                                            </ul>
                                        </div>                                        
                                        <div class="col-sm-2">
                                            <div class="sub-menu-heading bold">Stok</div>
                                            <ul class="sub-menu">
                                                <?php
                                                foreach ($value['sub_menu'] as $key => $sub_menu) {
                                                    if (intval($sub_menu['user_menu_flag']) == 1) {
                                                        if($sub_menu['menu_name'] == "Stok"){
                                                            echo '<li class="hide"><a id="" data-id="" href="">Ringkasan Persediaan</a></li>';
                                                            echo '<li class="hide"><a id="" data-id="" href="">Nilai Persediaan</a></li>';
                                                            echo '<li class="hide"><a id="" data-id="" href="">Rincian Persediaan</a></li>';
                                                            echo '<li><a id="" data-id="" href="' . base_url('report/inventory/product/stock_warehouse') . '">Kuantitas Stok Gudang</a></li>';
                                                            echo '<li><a id="" data-id="" href="' . base_url('report/inventory/product/stock_valuation') . '">Nilai Stok Gudang</a></li>';
                                                            echo '<li class="start"><a id="" data-id="" href="' . base_url('report/inventory/product/stock_moving') . '">Pergerakan Stok</a></li>';
                                                        ?>
                                                        <?php 
                                                        }
                                                    }
                                                } ?>
                                            </ul>
                                            <div class="sub-menu-heading bold">Produksi</div>
                                            <ul class="sub-menu">
                                                <?php
                                                foreach ($value['sub_menu'] as $key => $sub_menu) {
                                                    if (intval($sub_menu['user_menu_flag']) == 1) {
                                                        if($sub_menu['menu_name'] == "Produksi"){
                                                            echo '<li class="start"><a id="" data-id="" href="' . base_url('report/production/product/detail') . '">Produk Jadi Rinci</a></li>';
                                                        ?>
                                                        <?php 
                                                        }
                                                    }
                                                } ?>
                                            </ul>                                            
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="sub-menu-heading bold">Keuangan</div>
                                            <ul class="sub-menu">
                                                <?php
                                                echo '<li><a id="" data-id="" href="' . base_url('report/finance/cash_in') . '">Pemasukan Uang</a></li>';
                                                echo '<li><a id="" data-id="" href="' . base_url('report/finance/cash_out') . '">Pengeluaran Uang</a></li>';
                                                ?>
                                            </ul>  
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="sub-menu-heading bold">Bisnis</div>
                                            <ul class="sub-menu">
                                                <?php
                                                foreach ($value['sub_menu'] as $key => $sub_menu) {
                                                    if (intval($sub_menu['user_menu_flag']) == 1) {
                                                        if($sub_menu['menu_name'] == "Bisnis"){
                                                            echo '<li><a id="" data-id="" href="' . base_url('report/finance/journal') . '">Jurnal</a></li>';
                                                            echo '<li><a id="" data-id="" href="' . base_url('report/finance/ledger') . '">Buku Besar</a></li>';
                                                            echo '<li><a id="" data-id="" href="' . base_url('report/finance/trial_balance') . '">Neraca Saldo</a></li>';
                                                            echo '<li><a id="" data-id="" href="' . base_url('report/finance/worksheet') . '">Kertas Kerja</a></li>';
                                                            echo '<li><a id="" data-id="" href="' . base_url('report/finance/profit_loss') . '">Laba Rugi</a></li>';
                                                            echo '<li><a id="" data-id="" href="' . base_url('report/finance/balance') . '">Neraca</a></li>';                                                            
                                                        ?>
                                                        <?php 
                                                        }
                                                    }
                                                } ?>
                                            </ul>
                                        </div>                                        
                                    </div>
                                </div>
                            </ul>
                        </li>
                    <?php
                    }
                endforeach; //End foreach Group Menu
            }
            ?>
        </ul>
    </div>
</div>
<?php 
}
?>
<!-- 
<li>
    <a href="javascript:;">
        Whats <span class="semi-bold">new </span>
    </a>
</li>
<li class="classic">
    <a href="javascript:;">
        Classic <span class="semi-bold">Menu</span> <span class="fas fa-caret-down"></span>
    </a>
    <ul class="classic">
        <li>
            <a href="index.html">UI Elements
                <span class="description">Find which UI elements suits you the most.</spam>
            </a>
        </li>
        <li>
            <a href="index.html">Message & Notifications
                <span class="description">Alerts help to gain user attention and give...</spam>
            </a>
        </li>
        <li>
            <a href="index.html">Buttons
                <span class="description">Basic buttons are traditional buttons ...</spam>
            </a>
        </li>
        <li>
            <a href="index.html">Sliders
                <span class="description">Basic slider with different options use of ...</spam>
            </a>
        </li>
    </ul>
</li>
<li class="horizontal">
    <a href="javascript:;">
        Horizontal <span class="semi-bold">Menu</span> <span class="fas fa-caret-down"></span>
    </a>
    <ul class="horizontal">
        <li><a href="index.html">UI Elements</a></li>
        <li><a href="index.html">Message & Notifications</a></li>
        <li><a href="index.html">Buttons</a></li>
        <li><a href="index.html">Sliders</a></li>
    </ul>
</li>
<li class="mega">
    <a href="javascript:;">
        Mega <span class="semi-bold">Menu </span><span class="fas fa-caret-down"></span>
    </a>
    <ul class="mega">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 ">
                    <div class="sub-menu-heading bold">Features UI</div>
                    <img src="assets/img/Webarch_thumb.jpg" alt="" data-src="assets/img/Webarch_thumb.jpg" data-src-retina="assets/img/Webarch_thumb_retina.jpg" height="100" width="100">
                </div>
                <div class="col-sm-3">
                    <div class="sub-menu-heading bold"> UI Elements</div>
                    <ul class="sub-menu">
                        <li> <a href="typography.html"> Typography </a> </li>
                        <li> <a href="messages_notifications.html"> Messages & Notifications </a> </li>
                        <li> <a href="notifications.html"> Notifications </a> </li>
                        <li> <a href="icons.html">Icons</a> </li>
                        <li> <a href="buttons.html">Buttons</a> </li>
                        <li> <a href="tabs_accordian.html"> Tabs & Accordions </a> </li>
                        <li> <a href="sliders.html">Sliders</a> </li>
                        <li class="active"> <a href="group_list.html">Group list </a> </li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <div class="sub-menu-heading bold">Layout Options</div>
                    <ul class="sub-menu">
                        <li> <a href="layout_options.html"> Layout Options </a> </li>
                        <li> <a href="boxed_layout.html">Boxed Layout </a> </li>
                        <li> <a href="boxed_layout_v2.html">Inner Boxed Layout </a> </li>
                        <li> <a href="extended_layout.html">Extended Layout</a> </li>
                        <li> <a href="RTL.html">RTL Layout</a> </li>
                    </ul>
                    <div class="sub-menu-heading bold"> Forms</div>
                    <ul class="sub-menu">
                        <li> <a href="form_elements.html">Form Elements </a> </li>
                        <li> <a href="form_validations.html">Form Validations</a> </li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <div class="sub-menu-heading bold">Extra</div>
                    <ul class="sub-menu">
                        <li> <a href="layout_options.html"> Layout Options </a> </li>
                        <li> <a href="boxed_layout.html">Boxed Layout </a> </li>
                        <li> <a href="boxed_layout_v2.html">Inner Boxed Layout </a> </li>
                        <li> <a href="extended_layout.html">Extended Layout</a> </li>
                        <li> <a href="RTL.html">RTL Layout</a> </li>
                    </ul>
                </div>
            </div>
        </div>
    </ul>
</li>
-->