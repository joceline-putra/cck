<ul class="nav nav-tabs" role="tablist">
    <!-- <li class="" data-name="statistic">
        <a href="<?php #echo base_url('configuration'); ?>">
            <span class="fas fa-chart-line"></span> Statistik
        </a>
    </li> -->   
    <?php 
    foreach($session['user_data']['menu_access'] as $i):

        if($i['menu_group_id'] == 49){
            foreach($i['sub_menu'] as $v){
                $menu_icon = !empty($v['menu_icon']) ? $v['menu_icon'] : 'fas fa-file';

                //Root Only has MENU
                if(($v['menu_id'] == 21) and ($session['user_data']['user_group_id'] == 1)){
                    // echo '
                    //     <li class="" data-name="'.$v['menu_link'].'">
                    //         <a href="'.base_url($v['menu_link']).'">
                    //             <span class="'.$menu_icon.'"></span> '.$v['menu_name'].'
                    //         </a>
                    //     </li>                
                    // ';
                }else{
                    if($v['user_menu_flag'] == 1){
                        echo '
                            <li class="" data-name="'.$v['menu_link'].'">
                                <a href="'.base_url($v['menu_link']).'">
                                    <span class="'.$menu_icon.'"></span> '.$v['menu_name'].'
                                </a>
                            </li>                
                        ';                    
                        if($v['menu_id'] == 30){ // Akun Perkiraan -> Pemetaan Akun
                            echo '
                                <li class="" data-name="configuration/mapping">
                                    <a href="'.base_url('configuration/account_map').'">
                                        <span class="fas fa-swatchbook"></span> Pemetaan Akun
                                    </a>
                                </li>                
                            ';
                        }
                    }
                }
            }
        }
    endforeach;
    ?>
	<!-- <li class="" data-name="user/group">
		<a href="<?php echo base_url('user/group');?>">
			<span class="fas fa-filter"></span> Group User
		</a>
	</li>		     -->
</ul>