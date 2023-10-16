<?php
$next = true;
?>
<div id="main-menu" class="page-sidebar col-md-2" style="padding-bottom: 60px!important;display: block!important;">
    <div class="page-sidebar-wrapper scrollbar-dynamic" id="main-menu-wrapper">
        <p class="menu-title sm" style="padding-top:0px!important;">
        </p>
        <ul id="sidebar" style="" class="sidebarz">
            <li class="start"> 
                <a href="<?php echo base_url('admin'); ?>">
                  <!-- <i class="fa fa-home" style=""></i> -->
                    <span class="material-icons">dashboard</span>
                    <span class="title">Beranda</span> <span class="selected"></span>
                </a>
            </li>
            <?php
            if ($next == false) {
                ?>
                <li class="separate"></li>
                <li class="start"> 
                    <a href="<?php echo base_url('purchase'); ?>">
                      <!-- <i class="fas fa-shopping-basket" style=""></i> -->
                        <span class="material-icons">shopping_cart</span>          
                        <span class="title">Beli</span> <span class="selected"></span>
                    </a>
                </li>      
                <li class="start"> 
                    <a href="<?php echo base_url('sales'); ?>">
                      <!-- <i class="fas fa-cash-register" style=""></i> -->
                        <span class="material-icons">store</span>
                        <span class="title">Jual</span> <span class="selected"></span>
                    </a>
                </li>    
                <li class="start"> 
                    <a href="<?php echo base_url('checkup'); ?>">
                        <i class="fas fa-cogs" style=""></i>
                        <!-- <span class="material-icons">store</span> -->
                        <span class="title">Periksa</span> <span class="selected"></span>
                    </a>
                </li>          
                <li class="separate"></li>
                <li class="start"> 
                    <a href="<?php echo base_url('stock'); ?>">
                        <i class="fas fa-archive" style=""></i>
                        <!-- <span class="material-icons">store</span> -->
                        <span class="title">Stok</span> <span class="selected"></span>
                    </a>
                </li>          
                <li class="separate"></li>      
                <li class="start"> 
                    <a href="<?php echo base_url('finance/cash_in'); ?>">
                      <!-- <i class="fa fa-money-check-alt" style=""></i> -->
                        <span class="material-icons">payments</span>
                        <span class="title">Kas</span> <span class="selected"></span>
                    </a>
                </li>      
                <li class="start"> 
                    <a href="<?php echo base_url('finance/cost_out'); ?>">
                      <!-- <i class="fa fa-file-invoice-dollar" style=""></i> -->
                        <span class="material-icons">request_page</span>          
                        <span class="title">Biaya</span> <span class="selected"></span>
                    </a>
                </li>    
                <li class="separate"></li>
                <li class="start"> 
                    <a href="<?php echo base_url('product/statistic'); ?>">
                        <i class="fa fa-boxes" style=""></i>
                        <span class="title">Produk</span> <span class="selected"></span>
                    </a>
                </li>        
                <li class="start"> 
                    <a href="<?php echo base_url('contact/statistic'); ?>">
                        <i class="fa fa-address-book" style=""></i>
                        <span class="title">Kontak</span> <span class="selected"></span>
                    </a>
                </li>  

                <!--  
                <li class="start"> 
                  <a href="<?php echo base_url('asset/statistic'); ?>">
                    <i class="fa fa-building" style=""></i>
                    <span class="title">Inventaris</span> <span class="selected"></span>
                  </a>
                </li>
          
                <li class="start"> 
                  <a href="<?php echo base_url('configuration/account'); ?>">
                    <i class="fa fa-balance-scale" style=""></i>
                    <span class="title">Rekening</span> <span class="selected"></span>
                  </a>
                </li> 
                --> 
                <li class="separate"></li>
                <li class="start"> 
                    <a href="<?php echo base_url('report'); ?>">
                      <!-- <i class="fas fa-shopping-basket" style=""></i> -->
                        <span class="material-icons">receipt_long</span>                
                        <span class="title">Laporan</span> <span class="selected"></span>
                    </a>
                </li>                          
                <li class="separate"></li>
                <li class="start"> 
                    <a href="<?php echo base_url('reference'); ?>">
                        <i class="fas fa-grip-horizontal" style=""></i>
                        <span class="title">Referensi</span> <span class="selected"></span>
                    </a>
                </li>              
                <li class="start"> 
                    <a href="<?php echo base_url('user'); ?>">
                        <i class="fas fa-user-lock" style=""></i>
                        <span class="title">User</span> <span class="selected"></span>
                    </a>
                </li>     
                <li class="start"> 
                    <a href="<?php echo base_url('branch'); ?>">
                        <i class="fas fa-hotel" style=""></i>
                        <span class="title">Cabang</span> <span class="selected"></span>
                    </a>
                </li>   
                <!--     
                <li class="start"> 
                  <a href="<?php echo base_url('configuration/company'); ?>">
                    <i class="fas fa-cogs" style=""></i>
                    <span class="title">Pengaturan</span> <span class="selected"></span>
                  </a>
                </li>            
                <li class="separate"></li>
                <li class="start"> 
                  <a href="<?php echo base_url('news'); ?>">
                    <i class="fas fa-newspaper" style=""></i>
                    <span class="title">Blog</span> <span class="selected"></span>
                  </a>
                </li>     
                -->                                  
                <?php
            }

            if ($next) {

                if ($session['user_data']['menu_access']) {
                    $menu = $session['user_data']['menu_access'];
                    foreach ($menu as $key => $value) {
                        ?>
                        <li class="start open"> 
                            <a href="#"><i class="<?php echo $value['menu_group_icon'] ?>"></i>
                                <span class="title"><?php echo $value['menu_group_name']; ?></span> <span class="selected"></span></a>
                            <ul class="open sub-menu" style="display:block;">

                                <?php
                                foreach ($value['sub_menu'] as $key => $sub_menu) {
                                    ?>
                                    <li> 
                                        <a id="m<?php echo $sub_menu['menu_link']; ?>" data-id="<?php echo $sub_menu['menu_id']; ?>" href="<?php echo base_url($sub_menu['menu_link']); ?>" style="">
                                            <?php echo $sub_menu['menu_name']; ?>
                                        </a> 
                                    </li>                                    
                                    <?php
                                }
                                ?>
                            </ul>
                        </li> 
                        <?php
                    }
                }
            }
            ?>    
        </ul>
        <div class="clearfix"></div>
    </div>
</div>
<!-- <a href="#" class="scrollup">Scroll</a> -->

