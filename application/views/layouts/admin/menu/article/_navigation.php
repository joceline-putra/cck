<ul class="nav nav-tabs" role="tablist">
		
	<?php 
        foreach($session['user_data']['menu_access'] as $i):

            if($i['menu_group_id'] == 50){
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
	<li class="" data-name="category/article">
		<a href="<?php echo base_url('category/article');?>">
			<span class="fas fa-filter"></span> Kategori Artikel
		</a>
	</li>		                             
</ul>