<ul class="nav nav-tabs" role="tablist">
    <!-- <li class="" data-name="layouts/admin/menu/product/statistic">
        <a href="<?php echo base_url('product/statistic'); ?>">
            <span class="fas fa-chart-bar"></span> Statistik
        </a>
    </li>                            -->
    <?php
        $ng = $session['user_data']['menu_access'];
        foreach($ng as $b){
            if($b['menu_group_id'] == 45){
                foreach($b['sub_menu'] as $n){
                    $navigation_url = base_url() . $n['menu_link'];
                    if($n['user_menu_flag'] == 1){
                        echo '<li class="" data-name="' . $n['menu_link'] . '">';
                            echo '<a href="' . $navigation_url . '">';
                                echo '<span class="'.$n['menu_icon'].'"></span>&nbsp;' . $n['menu_name'];
                            echo '</a>';
                        echo '</li>';            
                    }
                }            
            }
        }
    ?> 
    <!--
    <li class="" data-name="layouts/admin/menu/product/product">
        <a href="<?php echo base_url('product/product'); ?>">
            <span class="fas fa-boxes"></span> Produk
        </a>
    </li>
    <li class="" data-name="layouts/admin/menu/product/asset">
        <a href="<?php echo base_url('product/asset'); ?>">
            <span class="fas fa-hands"></span> Inventaris
        </a>
    </li>
    
    <li class="" data-name="layouts/admin/menu/product/service">
            <a href="<?php echo base_url('product/service'); ?>">
            <span class="fas fa-hands"></span> Jasa
    </a>
    </li>
    <li class="" data-name="layouts/admin/menu/product/laboratory">
            <a href="<?php echo base_url('product/laboratory'); ?>">
            <span class="fas fa-flask"></span> Laboratorium
    </a>
    </li>	 					              
    <li class="" data-name="layouts/admin/menu/product/warehouse">
        <a href="<?php echo base_url('product/warehouse'); ?>">
            <span class="fas fa-warehouse"></span> Gudang
        </a>
    </li>	-->									
    <li class="" data-name="layouts/admin/menu/product/category_product">
        <a href="<?php echo base_url('category/product'); ?>">
            <span class="fas fa-filter"></span> Kategori Produk
        </a>
    </li>			
    <!-- <li class="" data-name="layouts/admin/menu/product/voucher">
        <a href="<?php #echo base_url('voucher'); ?>">
            <span class="fas fa-filter"></span> Voucher
        </a>
    </li>    			 -->
    <!--	
    <li class="" data-name="layouts/admin/menu/inventory/stock_opname">
            <a href="<?php echo base_url('inventory/stock_opname'); ?>">
            <span class="fas fa-broom"></span> Penyesuaian Stok
    </a>
    </li>
    -->						              			             			
</ul>