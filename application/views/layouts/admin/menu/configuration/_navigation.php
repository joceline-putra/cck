<ul class="nav nav-tabs" role="tablist">
    <!-- <li class="" data-name="statistic">
        <a href="<?php #echo base_url('configuration'); ?>">
            <span class="fas fa-chart-line"></span> Statistik
        </a>
    </li> -->   
    <?php 
    if($session['user_data']['user_group_id'] == 1){
    ?> 
    <li class="" data-name="configuration/menu">
        <a href="<?php echo base_url('configuration/menu'); ?>">
            <span class="fas fa-vials"></span> Menu
        </a>
    </li>
    <li class="" data-name="configuration/account">
        <a href="<?php echo base_url('configuration/account'); ?>">
            <span class="fas fa-balance-scale"></span> Akun Perkiraan
        </a>
    </li>
    <li class="" data-name="configuration/mapping">
        <a href="<?php echo base_url('configuration/account_map'); ?>">
            <span class="fas fa-swatchbook"></span> Pemetaan Akun
        </a>
    </li>
    <?php 
    }
    ?>
    <li class="" data-name="configuration/branch">
        <a href="<?php echo base_url('configuration/branch'); ?>">
            <span class="fas fa-archway"></span> Cabang
        </a>
    </li>				
    <li class="" data-name="user">
        <a href="<?php echo base_url('user'); ?>">
            <span class="fas fa-diagnoses"></span> User
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