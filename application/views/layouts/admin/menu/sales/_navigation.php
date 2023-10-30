<ul class="nav nav-tabs" role="tablist">
	<!--
	<li class="" data-name="statistic">
		<a href="<?php #echo base_url('sales'); ?>">
			<span class="fas fa-file"></span> Statistik
		</a>
	</li> -->
    <?php 
    foreach($session['user_data']['menu_access'] as $i):

        if($i['menu_group_id'] == 39){
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
	<li class="hide" data-name="finance/down_payment">
		<a href="<?php echo base_url('finance/down_payment'); ?>">
			<span class="fas fa-folder-open"></span> Down Payment
		</a>
	</li>    																                             
</ul>