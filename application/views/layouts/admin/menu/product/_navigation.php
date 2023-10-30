<ul class="nav nav-tabs" role="tablist">
    <!-- <li class="" data-name="layouts/admin/menu/product/statistic">
        <a href="<?php echo base_url('product/statistic'); ?>">
            <span class="fas fa-chart-bar"></span> Statistik
        </a>
    </li>                            -->
    <?php 
    foreach($session['user_data']['menu_access'] as $i):

        if($i['menu_group_id'] == 45){
            foreach($i['sub_menu'] as $v){
                if($v['user_menu_flag'] == 1){
                    $menu_icon = !empty($v['menu_icon']) ? $v['menu_icon'] : 'fas fa-file';
                    echo '
                        <li class="" data-name="'.$v['menu_link'].'">
                            <a href="'.base_url($v['menu_link']).'">
                                <span class="'.$menu_icon.'"></span> '.$v['menu_name'].'
                            </a>
                        </li>                
                    ';          
                }
            }
        }
    endforeach;
    ?>							
    <li class="" data-name="layouts/admin/menu/product/category_product">
        <a href="<?php echo base_url('category/product'); ?>">
            <span class="fas fa-filter"></span> Kategori Produk
        </a>
    </li>			
    <!--	
    <li class="" data-name="layouts/admin/menu/inventory/stock_opname">
            <a href="<?php echo base_url('inventory/stock_opname'); ?>">
            <span class="fas fa-broom"></span> Penyesuaian Stok
    </a>
    </li>
    -->						              			             			
</ul>