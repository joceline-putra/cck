<ul class="nav nav-tabs" role="tablist">
    <!-- <li class="" data-name="layouts/admin/menu/reference/statistic">
        <a href="<?php #echo base_url('reference'); ?>">
            <span class="fas fa-file"></span> Statistik
        </a>
    </li>-->
    <?php
    ?> 	      
    <!-- <li class="" data-name="layouts/admin/menu/reference/group_of_goods">
        <a href="<?php #echo base_url('reference/group_of_goods'); ?>">
            <span class="fas fa-file"></span> Golongan Barang
        </a>
    </li>
    <li class="" data-name="layouts/admin/menu/reference/diagnose">
        <a href="<?php #echo base_url('reference/diagnose'); ?>">
            <span class="fas fa-file"></span> Diagnosa
        </a>
    </li>	
    <li class="" data-name="layouts/admin/menu/reference/practice_type">
        <a href="<?php #echo base_url('reference/practice_type'); ?>">
            <span class="fas fa-file"></span> Jenis Praktik
        </a>
    </li>	 -->		
    
    <?php 
    foreach($session['user_data']['menu_access'] as $i):

        if($i['menu_group_id'] == 46){
            foreach($i['sub_menu'] as $v){
                if($v['user_menu_flag'] == 1){
                    $menu_icon = !empty($v['menu_icon']) ? $v['menu_icon'] : 'fas fa-folder-open';
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
</ul>