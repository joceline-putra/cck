<ul class="nav nav-tabs" role="tablist">
        <li class="hide" data-name="contact/statistic" style="cursor:pointer">
            <a href="<?php echo base_url('contact/statistic'); ?>" style="cursor:pointer">
                <span class="fas fa-industry"></span> Statistic
            </a>
        </li>
        <?php
			$ng = $session['user_data']['menu_access'];
			foreach($ng as $b){
				if($b['menu_group_id'] == 47){
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
        <li class="" data-name="contact/category_contact">
			<a href="<?php echo base_url('category/contact'); ?>">
				<span class="fas fa-filter"></span> Group Kontak
			</a>
		</li>  
		<!--
		<li class="" data-name="contact/supplier" style="cursor:pointer">
			<a href="<?php echo base_url('contact/supplier'); ?>" style="cursor:pointer">
				<span class="fas fa-user-tie"></span> Supplier
			</a>
		</li>
		<li class="" data-name="contact/customer" style="cursor:pointer">
			<a href="<?php echo base_url('contact/customer'); ?>" style="cursor:pointer">
				<span class="fas fa-user-tag"></span> Customer
			</a>
		</li>
		<li class="" data-name="contact/employee" style="cursor:pointer">
			<a href="<?php echo base_url('contact/employee'); ?>" style="cursor:pointer">
				<span class="fas fa-id-card-alt"></span> Karyawan
			</a>
		</li>  	    
		
		<li class="" data-name="layouts/admin/menu/contact/patient" style="cursor:pointer">
			<a href="<?php echo base_url('contact/patient'); ?>" style="cursor:pointer">
				<span class="fas fa-id-card"></span> Pasien
			</a>
		</li>
		<li class="" data-name="layouts/admin/menu/contact/insurance" style="cursor:pointer">
			<a href="<?php echo base_url('contact/insurance'); ?>" style="cursor:pointer">
				<span class="fas fa-file-signature"></span> Asuransi
			</a>
		</li>								
		<li class="hide" data-name="layouts/admin/menu/contact/contact" style="cursor:pointer">
			<a href="<?php echo base_url('contact'); ?>" style="cursor:pointer">
				<span class="fas fa-file-alt"></span> Kontak
			</a>
		</li>		
		 -->                           
</ul>