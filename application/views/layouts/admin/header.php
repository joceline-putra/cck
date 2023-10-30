<?php
$sidebar_logo = !empty($session['user_data']['branch']['branch_logo_sidebar']) ? site_url() . $session['user_data']['branch']['branch_logo_sidebar'] : site_url() . 'upload/branch/default_sidebar.png';
// var_dump($session['user_data']['branch']['branch_logo_sidebar']);die;
?>
<style>
    #logo-system{
        margin-top: 4px;
        margin-left: 4px;
        /*height: 28px;
        width: 132px;*/
        /*height: 34px;*/
        /*width: 134px;      */
        width: 200px;
    }
    #logo-system-2{
        margin-top: 1px;
        margin-left: 4px;
        /*height: 28px;
        width: 132px;*/
        /*height: 34px;*/
        /*width: 134px;*/
        width: 200px;
    }
    #horizontal-logo{
        position:relative;
        top:-8px;
        width: 200px;
    }    
    .notifcation-center{
        margin-top: 1px!important;
        margin-right: 1px!important;
    }
    .badge{
        padding-left: 4px!important;
        padding-right: 4px!important;
        bottom:5px!important;
        right: 70px!important;
        font-size: 12px!important;
    }
    @media (max-width: 767px){
        #logo-system {
            margin-top: 14px!important;
        }
    }

    .header .nav > li.quicklinks > a i{
        font-size: 14px!important;
    }
    .header .nav > li.quicklinks > a > span{
        font-size: 12px!important;
    }
    .dropdown-menu li{
        background-color: white;
        padding-left:8px;
        height: 30px;
    }
    .dropdown-menu li a{
        font-size: 14px!important;
    }
    .quicklinks a{
        padding:0px!important;
        /*font-size: 20px!important;*/
    }
    .ul-user-navigation{
        list-style:none;
        padding-left:0px;
    }
    .ul-user-navigation > li{
        padding-bottom:15px;
    }
    .ul-user-navigation > li > a:hover{
        cursor:pointer;
    }        
</style>

<div class="header navbar navbar-inverse" class="visible-xs visible-sm">
    <div class="navbar-inner" class="visible-xs visible-sm">
        <div class="header-seperation">
            <ul class="nav pull-left notifcation-center visible-xs visible-sm" style="">
                <li class="dropdown">
                    <a href="<?php echo base_url(); ?>" data-webarch="toggle-left-side">
                            <!-- <i class="material-icons">menu</i> -->
                        <i class="fa fa-bars"></i>
                    </a>
                </li>
            </ul>
            <a href="<?php echo base_url('admin'); ?>" class="">
                <img src="<?php echo $sidebar_logo; ?>"
                     class="logo" id="logo-system"/>
            </a>
            <ul style="height:40px!important;" class="nav pull-right notifcation-center">
                <li class="dropdown hidden hidden-xs hidden-sm">
                    <a href="<?php echo base_url(); ?>" class="dropdown-toggle active" data-toggle="">
                        <i class="material-icons">home</i>
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li class="dropdown visible-xs visible-sm">
                    <!-- <a href="<?php echo base_url('login/logout'); ?>"><i class="fa fa-power-off"></i></a> -->
                    <a href="#" class="btn-user-navigation" data-user="<?php echo ucfirst($session['user_data']['user_name']); ?>" data-is-r="<?php echo !empty($session['root']) ? $session['root'] : 0; ?>" data-is-allowed="<?php echo !empty($session['user_data']['user_group_id']) ? $session['user_data']['user_group_id'] : 5; ?>"><i class="fas fa-user-lock"></i></a>
                </li>
                <!--
                    <li class="quicklinks hidden-xs hidden-sm">
                        <a href="#" class="" id="my-task-list" data-placement="bottom" data-content='' data-toggle="dropdown" data-original-title="Notifications">
                            <i class="fa fa-bell-o"></i><span class="badge badge-important bubble-only"></span>
                        </a>
                    </li>
                -->
            </ul>
        </div>
        <div class="header-quick-nav visible-lg visible-md">
            <div class="col-md-7 pull-left padding-remove-left">
                <div class="col-md-12 col-xs-12">
                    <a href="#" class="">
                        <img src="<?php echo $sidebar_logo; ?>" class="hide logo" id="logo-system-2" data-src="<?php echo $sidebar_logo; ?>" data-src-retina="<?php echo $sidebar_logo; ?>"/>
                    </a>
                </div>
                <ul class="nav quick-section pull-left">
                    <li class="hide quicklinks m-l-10 m-r-10">
                        <a href="#" class="" id="btn-header-notification">
                            <i class="fas fa-bell"></i>
                            <span style="position: relative;">Notifikasi</span>
                        </a>
                    </li>
                    <li class="quicklinks m-l-10 m-r-10">
                        <img src="<?php echo $sidebar_logo; ?>" class="logo" id="horizontal-logo" style="<?php echo $horizontal_logo_style;?>" data-src="<?php echo $sidebar_logo; ?>" data-src-retina="<?php echo $sidebar_logo; ?>"/>
                    </li>
                    <li class="hide quicklinks m-l-10 m-r-10">
                        <a href="#" class="" id="btn-header-stock">
                            <i class="fas fa-cubes"></i>
                            <span style="position: relative;">Cari Stok</span>
                        </a>
                    </li>
                    <li class="hide quicklinks m-l-10 m-r-10">
                        <a href="#" class="" id="btn-header-product-history">
                            <i class="fas fa-search-dollar"></i>
                            <span style="position: relative;">Riwayat Harga</span>
                        </a>
                    </li>
                    <li class="hide quicklinks m-l-10 m-r-10">
                        <a href="#" class="" id="btn-header-stock-minimal">
                            <span id="badge-product-stock-min" style="display:none;" class="badge badge-important">0</span>
                            <i class="fas fa-file-upload"></i>
                            <span style="position: relative;">Stok Habis</span>
                        </a>
                    </li>
                    <li class="hide quicklinks m-l-10 m-r-10">
                        <a href="#" class="" id="btn-header-trans-over-due">
                            <span id="badge-trans-due-date" class="badge badge-important"></span>
                            <i class="fas fa-calendar-week"></i>
                            <span style="position: relative;">Jatuh Tempo</span>
                        </a>
                    </li>     
                    <li class="hide quicklinks m-l-10 m-r-10">
                        <div class="col-lg-4 col-md-12 col-xs-12 padding-remove-side">
                            <div class="form-group">
                                <select id="referensi" name="referensi" class="form-control">
                                    <option value="0">Pilih Transaksi</option>
                                    <option value="1">Pembelian</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-xs-12 padding-remove-side">
                            <div class="form-group">
                                <input id="harga_promo" name="harga_promo" type="text" value="" class="form-control"/>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-md-2 pull-right padding-remove-right">
                <ul class="nav quick-section pull-right">
                    <li class="quicklinks m-l-10 m-r-10">
                        <a data-toggle="dropdown" class="dropdown-toggle  pull-right " href="#" id="user-options" style="background-color: transparent!important;">
                            <i class="fa fa-user-lock"></i>
                            <span style="position: relative;">
                                &nbsp;
                                <?php echo ucfirst($session['user_data']['user_name']); ?>
                            </span>
                        </a>
                        <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="user-options">
                            <?php if (intval($session['user_data']['user_id']) && $session['root'] == true) { ?>
                                <li>
                                    <a href="#" class="" id="btn-user-switch">
                                        <i class="fas fa-toggle-off"></i><span style="position: relative;">&nbsp;Switch User</span>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (intval($session['user_data']['user_group_id']) == 1) { ?>                            
                            <li>
                                <a href="#" class="" id="btn-branch-switch">
                                    <i class="fas fa-building"></i><span style="position: relative;">&nbsp;Switch Cabang</span>
                                </a>
                            </li>
                            <?php } ?>     
                            <li class="divider"></li>                                                         
                            <li>
                                <a href="#" class="" id="btn-user-password">
                                    <i class="fas fa-key"></i><span style="position: relative;">&nbsp;Ganti Password</span>
                                </a>
                            </li>					
                            <li>
                                <a href="#" class="btn-user-theme" id="">
                                    <i class="fas fa-fill-drip"></i><span style="position: relative;">&nbsp;Warna Interface</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="btn-user-menu-style">
                                    <i class="fas fa-list"></i><span style="position: relative;">&nbsp;Tampilan Menu</span>
                                </a>
                            </li>       
                            <li class="divider"></li>                                   
                            <li>
                                <a href="<?= base_url('login/logout'); ?>" class="" onclick="<?= base_url('login/logout'); ?>">
                                    <i class="fa fa-power-off"></i><span style="position: relative;">&nbsp;Keluar</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!--
                        <li class="quicklinks m-l-10 m-r-10">
                            <a href="#" class="" id="body-condense">
                                <i class="fas fa-calendar-week"></i><span style="position: relative;">Condense</span>
                            </a>
                        </li>
                        <li class="quicklinks m-l-10 m-r-10">
                            <a href="#" class="" id="body-showhide">
                                <i class="fas fa-calendar-week"></i><span style="position: relative;">Show Hide</span>
                            </a>
                        </li>
                        <li class="quicklinks m-l-10 m-r-10">
                            <a href="#" id="my-password">
                                <i class="fas fa-key"></i><span style="position: relative;">Password</span>
                            </a>
                        </li>
                        <li class="quicklinks m-l-10 m-r-10">
                            <a href="<?= base_url('login/logout'); ?>" onclick="<?= base_url('login/logout'); ?>">
                                <i class="fa fa-power-off"></i><span style="position: relative;">Logout</span>
                            </a>
                        </li>
                    -->
                </ul>
            </div>
        </div>
    </div>
    <div id="notification-list" style="display:none">
        <div style="width:300px">
        </div>
    </div>
</div>