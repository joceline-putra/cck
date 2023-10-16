<?php
$icon = 'none;';
// $icon = 'inline;';
?>
<div id="main-menu" class="page-sidebar col-md-2" style="padding-bottom: 30px!important;display: block!important;">
    <div class="page-sidebar-wrapper scrollbar-dynamic" id="main-menu-wrapper">
        <p class="menu-title sm" style="padding-top:0px!important;margin:0px 0px 0px!important;">
        </p>
        <ul id="sidebar" class="sidebarz">
            <li class="start"> 
                <a href="<?php echo base_url('admin'); ?>">
                    <i class="fas fa-home"></i>
                    <span class="title">WhatsUp</span> <span class="selected"></span>
                </a>
                <!-- <li class="start open visible-xs visible-sm hidden-md hidden-lg">  -->
                <li class="start open">
                    <ul class="open sub-menu" style="display:block;">
                        <li><a href="<?php echo site_url('message/device')?>"><i class="fas fa-hdd" style="display:<?php echo $icon; ?>"></i> Device</a></li>
                        <li><a href="<?php echo site_url('message/template')?>"><i class="fas fa-archive" style="display:<?php echo $icon; ?>"></i> Template</a></li>
                        <li><a href="<?php echo site_url('message/recipient')?>"><i class="fas fa-address-card" style="display:<?php echo $icon; ?>"></i> Kontak</a></li>
                        <li><a href="<?php echo site_url('message')?>"><i class="fas fa-envelope-open" style="display:<?php echo $icon; ?>"></i> Pesan</a></li>                
                    </ul>
                </li>
            </li>
            <li class="start"> 
                <a href="<?php echo base_url('admin'); ?>">
                    <i class="fas fa-home"></i>
                    <span class="title">Notify</span> <span class="selected"></span>
                </a> 
                <li class="start open">
                    <ul class="open sub-menu" style="display:block;">
                        <li><a href="<?php echo site_url('notify/bank')?>"><i class="fas fa-hdd" style="display:<?php echo $icon; ?>"></i> Bank</a></li>
                        <li><a href="<?php echo site_url('notify/mutation')?>"><i class="fas fa-archive" style="display:<?php echo $icon; ?>"></i> Mutasi</a></li>
                        <li><a href="<?php echo site_url('notify/balance')?>"><i class="fas fa-address-card" style="display:<?php echo $icon; ?>"></i> Saldo</a></li>
                        <li><a href="<?php echo site_url('notify/deposit')?>"><i class="fas fa-address-card" style="display:<?php echo $icon; ?>"></i> Deposit</a></li>                        
                    </ul>
                </li>                       
            </li>
            <li class="start"> 
                <a href="<?php echo base_url('admin'); ?>">
                    <i class="fas fa-home"></i>
                    <span class="title">Minio</span> <span class="selected"></span>
                </a> 
                <li class="start open">
                    <ul class="open sub-menu" style="display:block;">
                        <li><a href="<?php echo site_url('minio/shortlink')?>"><i class="fas fa-hdd" style="display:<?php echo $icon; ?>"></i> Short Link</a></li>
                    </ul>
                </li>                       
            </li>            
            <li class="start"> 
                <a href="<?php echo base_url('admin'); ?>">
                    <i class="fas fa-home"></i>
                    <span class="title">Website</span> <span class="selected"></span>
                </a> 
                <li class="start open">
                    <ul class="open sub-menu" style="display:block;">
                        <li><a href="<?php echo site_url('article/article')?>"><i class="fas fa-hdd" style="display:<?php echo $icon; ?>"></i> Artikel</a></li>
                        <li><a href="<?php echo site_url('category/article')?>"><i class="fas fa-archive" style="display:<?php echo $icon; ?>"></i> Kategori Artikel</a></li>
                        <li><a href="<?php echo site_url('product/product')?>"><i class="fas fa-hdd" style="display:<?php echo $icon; ?>"></i> Produk</a></li>
                        <li><a href="<?php echo site_url('category/product')?>"><i class="fas fa-archive" style="display:<?php echo $icon; ?>"></i> Kategori Produk</a></li>                                              
                    </ul>
                </li>                       
            </li>            
            <li class="start"> 
                <a href="<?php echo base_url('admin'); ?>">
                    <i class="fas fa-cogs"></i>
                    <span class="title">Pengaturan</span> <span class="selected"></span>
                </a> 
                <li class="start open">
                    <ul class="open sub-menu" style="display:block;">
                        <li><a href="<?php echo site_url('configuration/menu')?>"><i class="fas fa-hdd" style="display:<?php echo $icon; ?>"></i> Menu</a></li>
                    </ul>
                </li>                       
            </li>              
        </ul>
        <div class="clearfix"></div>
        <br><br>
    </div>
</div>
<!-- <a href="#" class="scrollup">Scroll</a> -->

