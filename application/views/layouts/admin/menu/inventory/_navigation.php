		<ul class="nav nav-tabs" role="tablist">
			<!-- 
            <li class="" data-name="statistic">
			  	<a href="<?php echo base_url('purchase');?>">
          			<span class="fas fa-file"></span> Statistik
          		</a>
			</li>
            -->
			<?php 
			foreach($session['user_data']['menu_access'] as $i):

				if($i['menu_group_id'] == 41){
					foreach($i['sub_menu'] as $v){
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
			endforeach;
			?>																	                            
		</ul>