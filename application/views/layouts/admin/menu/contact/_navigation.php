<ul class="nav nav-tabs" role="tablist">
        <li class="hide" data-name="contact/statistic" style="cursor:pointer">
            <a href="<?php echo base_url('contact/statistic'); ?>" style="cursor:pointer">
                <span class="fas fa-industry"></span> Statistic
            </a>
        </li>
		<?php 
    foreach($session['user_data']['menu_access'] as $i):

        if($i['menu_group_id'] == 47){
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
        <li class="" data-name="contact/category_contact">
			<a href="<?php echo base_url('category/contact'); ?>">
				<span class="fas fa-filter"></span> Group Kontak
			</a>
		</li>                         
</ul>