		<ul class="nav nav-tabs" role="tablist">			
			<li class="" data-name="layouts/admin/menu/message/device">
			  	<a href="<?php echo base_url('message/device');?>">
          			<span class="fas fa-hdd"></span> Device
          		</a>
			</li>										
			<!--
            <li class="" data-name="layouts/admin/menu/message/survey">
			  	<a href="<?php #echo base_url('message/survey');?>">
          			<span class="fas fa-poll"></span> Survey Kepuasan
          		</a>
			</li> -->
			<li class="" data-name="layouts/admin/menu/message/template">
			  	<a href="<?php echo base_url('message/template');?>">
          			<span class="fas fa-archive"></span> Template Pesan
          		</a>
			</li>	
			<li class="" data-name="layouts/admin/menu/message/recipient">
			  	<a href="<?php echo base_url('message/recipient');?>">
          			<span class="fas fa-address-card"></span> Kontak
          		</a>
			</li>            
			<li class="" data-name="layouts/admin/menu/message/message">
			  	<a href="<?php echo base_url('message');?>">
          			<span class="fas fa-envelope-open"></span> Pesan
          		</a>
			</li>
			<?php 
			/*
				foreach($navigation as $n):
					$navigation_url = base_url().$n['menu_link']; 
					echo '<li class="" data-name="'.$n['menu_link'].'">';
					  	echo '<a href="'.$navigation_url.'">';
		          			echo '<span class="fas fa-file-alt"></span>&nbsp;'.$n['menu_name'];
		        		echo '</a>';
					echo '</li>';
				endforeach;
			*/
			?>                             
		</ul>