<style>
    *,*:before,*:after{
        box-sizing:border-box;
        margin:0;
        padding:0;
        /*transition*/
        -webkit-transition:.25s ease-in-out;
        -moz-transition:.25s ease-in-out;
        -o-transition:.25s ease-in-out;
        transition:.25s ease-in-out;
        outline:none;
        /*font-family:Helvetica Neue,helvetica,arial,verdana,sans-serif;*/
    }
    /* Checkbox n Toggle */
        .toggles{
            width:48px;
        }
        .ios-toggle,.ios-toggle:active{
            position:absolute;
            top:-5000px;
            height:0;
            width:0;
            opacity:0;
            border:none;
            outline:none;
        }
        .ios-toggle:checked + .order_contact_checkbox{
            /*box-shadow*/
            -webkit-box-shadow:inset 0 0 0 20px rgba(19,191,17,1),0 0 0 2px rgba(19,191,17,1);
            -moz-box-shadow:inset 0 0 0 20px rgba(19,191,17,1),0 0 0 2px rgba(19,191,17,1);
            box-shadow:inset 0 0 0 20px rgba(19,191,17,1),0 0 0 2px rgba(19,191,17,1);
        }
        .ios-toggle:checked + .order_contact_checkbox:before{
            left:calc(100% - 24px);
            /*box-shadow*/
            -webkit-box-shadow:0 0 0 2px transparent,0 3px 3px rgba(0,0,0,.3);
            -moz-box-shadow:0 0 0 2px transparent,0 3px 3px rgba(0,0,0,.3);
            box-shadow:0 0 0 2px transparent,0 3px 3px rgba(0,0,0,.3);
        }
        .ios-toggle:checked + .order_contact_checkbox:after{
            /*content:attr(data-on);*/
            left:60px;
            width:36px;
        }  
        .ios-toggle:checked + .payment_contact_checkbox{
            /*box-shadow*/
            -webkit-box-shadow:inset 0 0 0 20px rgba(19,191,17,1),0 0 0 2px rgba(19,191,17,1);
            -moz-box-shadow:inset 0 0 0 20px rgba(19,191,17,1),0 0 0 2px rgba(19,191,17,1);
            box-shadow:inset 0 0 0 20px rgba(19,191,17,1),0 0 0 2px rgba(19,191,17,1);
        }
        .ios-toggle:checked + .payment_contact_checkbox:before{
            left:calc(100% - 24px);
            /*box-shadow*/
            -webkit-box-shadow:0 0 0 2px transparent,0 3px 3px rgba(0,0,0,.3);
            -moz-box-shadow:0 0 0 2px transparent,0 3px 3px rgba(0,0,0,.3);
            box-shadow:0 0 0 2px transparent,0 3px 3px rgba(0,0,0,.3);
        }
        .ios-toggle:checked + .payment_contact_checkbox:after{
            /*content:attr(data-on);*/
            left:60px;
            width:36px;
        }     
        .order_contact_checkbox, .payment_contact_checkbox{
            display:block;
            position:relative;
            padding:10px;
            margin-bottom:0px!important;
            font-size:12px;
            line-height:16px;
            width:100%;
            height:24px;
            /*border-radius*/
            -webkit-border-radius:18px;
            -moz-border-radius:18px;
            border-radius:18px;
            /*background:#f8f8f8;*/
            background: #f46767;
            cursor:pointer;
        }
        .order_contact_checkbox:before, .payment_contact_checkbox:before{
            content:'';
            display:block;
            position:absolute;
            z-index:1;
            line-height:34px;
            text-indent:40px;
            height:24px;
            width:24px;
            /*border-radius*/
            -webkit-border-radius:100%;
            -moz-border-radius:100%;
            border-radius:100%;
            top:0px;
            left:0px;
            right:auto;
            background:white;
            /*box-shadow*/
            -webkit-box-shadow:0 3px 3px rgba(0,0,0,.2),0 0 0 2px #dddddd;
            -moz-box-shadow:0 3px 3px rgba(0,0,0,.2),0 0 0 2px #dddddd;
            box-shadow:0 3px 3px rgba(0,0,0,.2),0 0 0 2px #dddddd;
        }
        .order_contact_checkbox:after, .payment_contact_checkbox:after{
            /*content:attr(data-off);*/
            display:block;
            position:absolute;
            z-index:0;
            top:0;
            left:-300px;
            padding:10px;
            height:100%;
            width:300px;
            text-align:right;
            color:#bfbfbf;
            white-space:nowrap;
        }
    /* Scroll */
        .scroll {
            margin-top: 4px;
            margin-bottom: 8px;
            margin-left: 4px;
            margin-right: 4px;
            padding: 4px;
            /*background-color: green; */
            width: 100%;
            height: 200px;
            overflow-x: hidden;
            overflow-y: auto;
            text-align: justify;
        }    
        .scroll-track::-webkit-scrollbar-track{
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            background-color: #F5F5F5;
        }
        .scroll-track::-webkit-scrollbar{
            width: 10px;
            background-color: #F5F5F5;
        }
        .scroll-track::-webkit-scrollbar-thumb{
            /* background-color: #f27f2e; */
            /* border: 2px solid #f27f2e; */
            background-color: var(--back-primary)!important;
            border: 2px solid var(--back-primary)!important;    
        }    
        .scroll-track { 
            margin-top:4px;
            margin-bottom: 4px;
            margin-left:4px;
            margin-right: 4px; 
            padding:4px; 
            /*background-color: green; */
            width: 100%; 
            height: 400px; 
            overflow-x: hidden; 
            overflow-y: auto; 
            text-align:justify; 
        } 
        .scroll-nav::-webkit-scrollbar-track{
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            background-color: #ecf0f2;
        }
        .scroll-nav::-webkit-scrollbar{
            width: 10px;
            background-color: #ecf0f2;
        }
        .scroll-nav::-webkit-scrollbar-thumb{
            background-color: var(--back-primary)!important;
            border: 2px solid var(--back-primary)!important;
        }
        .scroll-nav { 
            margin-top:4px;
            /* margin-bottom: 4px; */
            margin-left:4px;
            margin-right: 4px; 
            padding:4px; 
            /*background-color: green; */
            /*width: 100%; */
            height: 530px; 
            overflow-x: hidden; 
            overflow-y: auto; 
            text-align:justify; 
        }
        .scroll-order-room{
            /* height:220px!important; */
        }
        .scroll-order-product-categories{
            height:480px!important;
        }
        .scroll-order-product{
            height:480px!important;
        }
        .scroll-order-item{
            /* height:340px!important; */
            height:294px!important;        
        }
        .scroll-pembayaran-daftar{
            height:560px!important;
        }
        .scroll-pembayaran-antrian{
            height:400px!important;
        }
    /* Modal */
        .modal-header-order{
            padding:4px 14px;
        }
        .modal-body-order{
            padding:0px!important;
        }        
        .modal-header{
            background-color:white!important; 
        }
        .modal-header > h4{
            font-size: 18px;
            font-weight: 600;
        }  
        .btn-outline-danger{
            color:var(--theme-font);
            background-color:var(--form-background-color-hover);
        } 
    /* Form */
        .group-plus-minus > button:nth-child(1){
            float:left;
        }
        .group-plus-minus > button:nth-child(2){
            float:left;
            border-radius: 0px!important;
            height: 44px;
            width: 42px;    
        }
        .group-plus-minus > button:nth-child(3){
            float:left;
        }    
        .btn-save-order-item-plus-minus{
            border-radius: 0px!important;
            padding:12px 14px;
        }
        .btn-read-payment{
            background-color:#ecf0f2;
        }
        .btn-read-payment:hover, .btn-read-payment:active, .btn-read-payment:focus{
            background-color:#c7d6e9;
        }

        .btn-room-click{
            /* background-color:#ecf0f2; */
        }
        .btn-room-click:hover, .btn-room-click:active, .btn-room-click:focus{
            background-color:#444444!important;
            color:white!important;
        }  
        .btn-payment-item{
            border: 1px solid red;
        }
        .select2-container.select2-container-disabled .select2-choice {
            /* background-color: #ddd;
            border-color: #a8a8a8; */
        }
    /* Tab */
        .product-tab-detail-item{
            position: relative;
            padding-left:4px;
            padding-right: 4px;
            /* height: 242px; */
            margin-bottom:4px;
        }
        #payment-tab{
            /*  */
        }
        .product-tab-detail-item:hover div:nth-child(1){
            background-color: var(--back-shadow);
            /* border:0px; */

        } 
        .product-tab-detail-item:hover div:nth-child(2){
            background-color: var(--back-shadow);
            /* border:0px; */

        }    
        .product-tab-detail-item div:nth-child(1){
            padding:0;
            cursor:pointer;
            background-color:none!important;
            padding-top:4px;
            /*border-top:1px solid #f37f2f;*/
            /*border-left:1px solid #f37f2f;*/
            /*border-right:1px solid #f37f2f;        */
        }
        .product-tab-detail-item div:nth-child(1) > img{
            /* width:100%; */
            /* height: 84px; */
            width:162px;
            height: 162px;
            /* padding-left:15px; */
            /* padding-right: 15px; */
            margin:0 auto;
        }  
        .product-tab-detail-item div:nth-child(1) > img:hover{
            /* width:164px; */
            /* height:164px; */
            /* margin:0 auto; */
            position: relative;
            top:1px;
        }          
        .product-tab-color-blue{
            background-color:#2f7ac6;
            border-top: 1px solid #2f7ac6;
            border-left:1px solid #2f7ac6;
            border-right:1px solid #2f7ac6;
            border-bottom:1px solid #2f7ac6;    
        }
        .product-tab-color-template{
            background-color:#444444;
            border-top: 1px solid var(--back-primary);
            border-left:1px solid var(--back-primary);
            border-right:1px solid var(--back-primary);
        } 
        
        .product-tab-color-orange{
            background-color:#f37f2f;
            border-left:1px solid #f37f2f;
            border-right:1px solid #f37f2f;
            border-bottom:1px solid #f37f2f;    
        }  
        .product-tab-detail-item div:nth-child(2){
            position:relative;    
            cursor: pointer;
            padding:0;
            height: 72px;
            /*padding-bottom: 8px;*/
            font-weight:800;
            color:white;
            border-bottom:1px solid #444444;    
        }  
        .product-tab-detail-item div:nth-child(2) > p:nth-child(1){
            text-align:center;
            font-size:12px;
            font-weight:500;
            color:white;
            position:absolute;
            width:100%;
            top:8px;
        }    
        .product-tab-detail-item div:nth-child(2) > p:nth-child(2){
            text-align:center;
            font-size:12px;
            margin-top: 16px;
            font-weight: 700;
            color:white;
            position:absolute;
            width:100%;
            top:34px;    
        }      
        .room-tab{
            height:auto;
        }
        .room-tab > div{
            margin-bottom:0px;
        }    
        #room-tab-detail{
            color:#545353;
        }
        .room-tab-detail-item{
            padding-left: 0px!important;
            padding-right: 0px!important;
        }
    /* Table */
        #table-item > thead > tr > th {
            padding-top: 4px;
            padding-bottom: 4px;
        }
        #table-item > tbody > tr > td:nth-child(1){
            padding-left: 0px!important;
            padding-right: 0px!important;
            padding-top: 2px!important;
            padding-bottom: 0px!important;
        }
        #table-item > tbody > tr > td:nth-child(1) > button{
            height: 80px;
            border-radius: 0px;
        }
        #table-item > tbody > tr > td:nth-child(3){
            padding-left: 0px!important;
            padding-right: 0px!important;
            padding-top: 12px!important;
            padding-bottom: 0px!important;
        }     

    /* Large desktops and laptops */
    @media (min-width: 1200px) {
        /* .prs-0{
            padding-left: 0px!important;
            padding-right: 0px!important;    
        }
        .prs-5{
            padding-left: 5px!important;
            padding-right: 5px!important;    
        }   
        .prs-2{
            padding-left: 2.5px!important;
            padding-right: 2.5px!important;    
        }              
        .prl-2{
            padding-left: 2.5px!important;
        }
        .prr-2{
            padding-right: 2.5px!important;
        }   */
        .table-responsive{
            overflow-x: unset;
        }        
    }

    /* Landscape tablets and medium desktops  @media (min-width: 992px) and (max-width: 1199px) */
    @media (min-width: 992px) {
        .table-responsive{
            overflow-x: unset;
        }
        .modal-order{
            width:1367px!important;
        }
    }

    /* Portrait tablets and small desktops  and (max-width: 991px) */
    @media (min-width: 768px){
        /* .modal-order{
            width:1367px!important;
        }            */
        .table-responsive{
            overflow-x: unset;
        }
        .padding-remove-left, .padding-remove-right{
            padding-left:0px!important;
            padding-right:0px!important;    
        }
        .padding-remove-side{
            padding-left: 5px!important;
            padding-right: 5px!important;
        }
        .btn-room-click, .btn-payment-read-order{
            padding-left:0px!important;
            padding-right:0px!important;
        }
        .form-label{
            /*padding-left: 5px!important;*/
        }
        .prs-0{
            padding-left: 0px!important;
            padding-right: 0px!important;    
        }
        .prs-0 > label{
            padding-left: 5px!important;
            padding-right: 5px!important;    
        }
        .prs-0 > div{
            /*padding-left: 5px!important;*/
            /*padding-right: 5px!important;    */
        }
        .prs-0 > input{
            margin-left: 0px!important;
            margin-right: 0px!important;    
        }
        .prs-0 > select{
            margin-left: 5px!important;
            margin-right: 5px!important;    
        }

        .prs-5{
            padding-left: 5px!important;
            padding-right: 5px!important;    
        }
        .prs-5 > label{
            padding-left: 5px!important;
            padding-right: 5px!important;    
        }
        .prs-5 > div{
            /*padding-left: 5px!important;*/
            /*padding-right: 5px!important;    */
        }
        .prs-5 > input{
            margin-left: 5px!important;
            margin-right: 5px!important;    
        }
        .prs-5 > select{
            margin-left: 5px!important;
            margin-right: 5px!important;    
        }    

        .prl-2{
            padding-left: 2.5px!important;
        }
        .prr-2{
            padding-right: 2.5px!important;
        } 
    }

    /* Landscape phones and portrait tablets */
    @media (max-width: 767px) {
        /* .table-responsive{
            overflow-x: unset;
        } */
    }

    /* Portrait phones and smaller */
    @media (max-width: 480px) {
        .tab-content > .active{
            padding: 8px!important;
        }  
        .padding-remove-left, .padding-remove-right{
            padding-left:0px!important;
            padding-right:0px!important;    
        }
        .padding-remove-side{
            padding-left: 5px!important;
            padding-right: 5px!important;
        }
        .form-label{
            /*padding-left: 5px!important;*/
        }
        .prs-0{
            padding-left: 0px!important;
            padding-right: 0px!important;    
        }
        .prs-0 > label{
            padding-left: 5px!important;
            padding-right: 5px!important;    
        }
        .prs-0 > div{
            /*padding-left: 5px!important;*/
            /*padding-right: 5px!important;    */
        }
        .prs-0 > input{
            margin-left: 0px!important;
            margin-right: 0px!important;    
        }
        .prs-0 > select{
            margin-left: 5px!important;
            margin-right: 5px!important;    
        }

        .prs-5{
            padding-left: 5px!important;
            padding-right: 5px!important;    
        }
        .prs-5 > label{
            padding-left: 5px!important;
            padding-right: 5px!important;    
        }
        .prs-5 > div{
            /*padding-left: 5px!important;*/
            /*padding-right: 5px!important;    */
        }
        .prs-5 > input{
            margin-left: 5px!important;
            margin-right: 5px!important;    
        }
        .prs-5 > select{
            margin-left: 5px!important;
            margin-right: 5px!important;    
        }    

        .prl-2{
            padding-left: 2.5px!important;
        }
        .prr-2{
            padding-right: 2.5px!important;
        }    
    } 

</style>
<!--
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php #include '_navigation.php'; ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
-->
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <ul class="nav nav-tabs" role="tablist" style="display:inline;">
            <li class="active">
                <a href="#tab1" role="tab" class="btn-tab-1" data-toggle="tab" aria-expanded="true">
                    <span class="fas fa-clipboard"></span> <?php echo $ref_alias; ?></a> <!-- Order -->
            </li> 
            <li class="">
                <a href="#tab3" role="tab" class="btn-tab-3" data-toggle="tab" aria-expanded="false"  style="cursor:pointer;">
                    <span class="fas fa-shopping-cart"></span> <?php echo $order_alias; ?></a> <!-- Payment -->
            </li>  
            <li class="">
                <a href="#tab2" role="tab" class="btn-tab-2" data-toggle="tab" aria-expanded="false"  style="cursor:pointer;">
                    <span class="fas fa-cash-register"></span> <?php echo $payment_alias; ?></a> <!-- Payment -->
            </li>                                                             
        </ul>
        <!-- <div class="tab-content" style="border-radius:24px;"> -->
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div class="row div_mobile">                    
                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                        <div class="col-md-12 col-xs-12 col-sm-12" style="margin-bottom:4px;">
                            <div class="col-md-4 col-sm-4 col-xs-4 padding-remove-left">
                                <div class="pull-left">
                                    <div class="btn-group"> 
                                        <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"> 
                                            <i class="fas fa-bars"></i>&nbsp;&nbsp;Menu&nbsp;&nbsp;<span class="fa fa-angle-down"></span> 
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" class="btn_menu_toggle" data-id="1"><span class="fas fa-shopping-cart"></span>&nbsp;Data <?php echo $order_alias; ?></a></li>
                                            <li><a href="#" class="btn_menu_toggle" data-id="2"><span class="fas fa-cash-register"></span>&nbsp;Data <?php echo $payment_alias; ?></a></li>
                                            <li><a href="#" class="btn_menu_toggle" data-id="3"><span class="fas fa-list-alt"></span>&nbsp;Antrian Pembayaran</a></li>                                            
                                        </ul>
                                    </div>                                                       
                                </div>
                            </div> 
                            <div class="col-md-4 col-sm-4 col-xs-4 padding-remove-side">
                                <div class="col-md-12 col-xs-12 col-sm-12" style="padding-left: 0;padding-right:0;margin-top:0px;">
                                    <h5 style="text-align:center;margin-top:6px;"><b><?php echo $ref_alias; ?></b></h5>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4 padding-remove-right">
                                <div class="pull-right">
                                    <div class="btn-group"> 
                                        <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"> 
                                            <i class="fas fa-user-lock"></i>&nbsp;&nbsp;<?php echo ucfirst($session['user_data']['user_name']); ?>&nbsp;&nbsp;<span class="fa fa-angle-down"></span> 
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" class="btn_menu_toggle" data-id="4" style="padding-left:8px;"><span class="fa fa-power-off"></span>&nbsp;Keluar</a></li>                              
                                        </ul>
                                    </div>                     
                                </div>
                            </div>                             
                        </div>
                    </div>
                </div>                   
                <div id="div_order_form" style="display:inline;" class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                            <div class="grid simple">
                                <div class="hidden grid-title">
                                    <div class="tools">
                                        <a href="javascript:;" class="collapse"></a>
                                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                                        <a href="javascript:;" class="reload"></a>
                                        <a href="javascript:;" class="remove"></a>
                                    </div>
                                </div>
                                <div class="grid-body" style="padding:0px;">
                                    <!-- <div class="col-md-12 col-xs-12 col-sm-12" style="margin-top:8px!important;margin-bottom:0px!important;padding-left:10px;">                                      
                                    </div>                         -->
                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                        <div id="room-tab" class="tabbable tabs-left room-tab">
                                            <div class="tab-content">
                                                <div class="tab-pane active" style="padding-top:0px;padding-bottom:0px;">
                                                    <div class="row">
                                                        <div id="room-tab-detail" class="col-md-12 col-xs-12 col-sm-12 scroll-track scroll-order-room">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>      
                    </div>
                </div>            
                <div id="div_payment_form" style="display:none;" class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                </div>                
            </div>
            <div class="tab-pane" id="tab2">
                <div class="row div_mobile">
                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                        <div class="col-md-12 col-xs-12 col-sm-12" style="margin-bottom:4px;">
                            <div class="col-md-4 col-sm-4 col-xs-4 padding-remove-left">
                                <div class="pull-left">
                                    <button class="btn btn_back_order btn-success" type="button" style="background-color:var(--form-background-color-hover);" >
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </button>           
                                </div>
                            </div> 
                            <div class="col-md-4 col-sm-4 col-xs-4 padding-remove-side">
                                <div class="col-md-12 col-xs-12 col-sm-12" style="padding-left: 0;padding-right:0;margin-top:0px;">
                                    <h5 style="text-align:center;margin-top:0px;"><b>Data <br><?php echo $payment_alias; ?></b></h5>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4 padding-remove-right">
                                <div class="pull-right">
                                    <div class="btn-group"> 
                                        <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"> 
                                            <i class="fas fa-print"></i>&nbsp;&nbsp;Cetak&nbsp;&nbsp;<span class="fa fa-angle-down"></span> 
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" class="btn-print-all-trans" data-action="1" data-request="report_sales_sell_recap" data-format="html">A4 Rekap</a></li>
                                            <li><a href="#" class="btn-print-all-trans" data-action="1" data-request="report_sales_sell_detail" data-format="html">Struk Rinci</a></li>
                                        </ul>
                                    </div> 
                                </div>
                            </div>                             
                        </div>
                        <div class="col-md-12 col-xs-12 col-sm-12">
                        </div>
                    </div>
                </div>
                <div id="div_payment_data" class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                    <div class="grid simple">
                        <div class="grid-body">                    
                            <div class="row">
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:8px;">
                                    <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side">
                                        <div class="col-lg-3 col-md-3 col-xs-6 col-sm-6 form-group padding-remove-right prs-5">
                                            <label class="form-label">Periode Awal</label>
                                            <div class="col-md-12 col-sm-12 padding-remove-side">
                                                <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                    <input name="start_2" id="start_2" type="text" class="form-control input-sm" readonly="true"
                                                            value="<?php echo $first_date; ?>">
                                                    <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-6 col-sm-6 form-group padding-remove-right prs-5">
                                            <label class="form-label">Periode Akhir</label>
                                            <div class="col-md-12 col-sm-12 padding-remove-side">
                                                <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                    <input name="end_2" id="end_2" type="text" class="form-control input-sm" readonly="true"
                                                            value="<?php echo $end_date; ?>">
                                                    <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 form-group padding-remove-right prs-5">
                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                <label class="form-label"><?php echo $contact_1_alias; ?></label>
                                                <select id="filter_kontak_2" name="filter_kontak_2" class="form-control">
                                                    <option value="0">-- Semua --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side">
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 form-group padding-remove-right prs-5">
                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                <label class="form-label">Metode Bayar</label>
                                                <select id="filter_type_paid_2" name="filter_type_paid_2" class="form-control">
                                                    <option value="0">-- Semua --</option>
                                                    <?php 
                                                    foreach($type_paid as $v){
                                                        echo '<option value="'.$v['paid_id'].'">'.$v['paid_name'].'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>                                                        
                                        <div class="col-lg-5 col-md-5 col-xs-6 col-sm-6 form-group padding-remove-right prs-0 prs-5">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group padding-remove-side">
                                                <label class="form-label">Cari</label>
                                                <input id="filter_search_2" name="filter_search_2" type="text" value="" class="form-control" placeholder="Pencarian" />
                                            </div>
                                        </div>                                 
                                        <div class="col-lg-4 col-md-4 col-xs-6 col-sm-6 form-group prs-0 prs-0 prs-5">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group padding-remove-side">                                        
                                                <label class="form-label">Tampil</label>
                                                <select id="filter_length_2" name="filter_length_2" class="form-control">
                                                    <option value="10">10 Baris</option>
                                                    <option value="25">25 Baris</option>
                                                    <option value="50">50 Baris</option>
                                                    <option value="100">100 Baris</option>
                                                </select>
                                            </div>
                                        </div>   
                                    </div>                
                                </div>  
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 prs-5">
                                    <div class="table-responsive">
                                        <table id="table_data_trans" class="table table-bordered" data-limit-start="0" data-limit-end="10" style="width:100%;">
                                        </table>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab3">
                <div class="row div_mobile">
                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                        <div class="col-md-12 col-xs-12 col-sm-12" style="margin-bottom:4px;">
                            <div class="col-md-4 col-sm-4 col-xs-4 padding-remove-left">
                                <div class="pull-left">
                                    <button class="btn btn_back_order btn-success" type="button" style="background-color:var(--form-background-color-hover);" >
                                        <!-- <i class="fas fa-plus"></i> Buat <?php #echo $order_alias; ?> Baru -->
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </button>                
                                </div>
                            </div> 
                            <div class="col-md-4 col-sm-4 col-xs-4 padding-remove-side">
                                <div class="col-md-12 col-xs-12 col-sm-12" style="padding-left: 0;padding-right:0px;margin-top:0px;">
                                    <h5 style="text-align: center;margin-top:0px;"><b>Data <br><?php echo $order_alias; ?></b></h5>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4 padding-remove-right">
                                <div class="pull-right">
                                    <div class="btn-group"> 
                                        <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"> 
                                            <i class="fas fa-print"></i>&nbsp;&nbsp;Cetak&nbsp;&nbsp;<span class="fa fa-angle-down"></span> 
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" class="btn-print-all" data-action="1" data-request="report_point_of_sales_recap" data-format="html">A4 Rekap</a></li>
                                            <li><a href="#" class="btn-print-all" data-action="1" data-request="report_point_of_sales_detail" data-format="html">Struk Rinci</a></li>
                                        </ul>
                                    </div>   
                                </div>
                            </div>                             
                        </div>
                    </div>
                </div>
                <div id="div_order_data" class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                    <div class="grid simple">
                        <div class="grid-body">                    
                            <div class="row">
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:8px;">
                                    <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-5">
                                        <label class="form-label">Periode Awal</label>
                                        <div class="col-md-12 col-sm-12 padding-remove-side">
                                            <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                <input name="start" id="start" type="text" class="form-control input-sm" readonly="true"
                                                        value="<?php echo $first_date; ?>">
                                                <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-5">
                                        <label class="form-label">Periode Akhir</label>
                                        <div class="col-md-12 col-sm-12 padding-remove-side">
                                            <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                <input name="end" id="end" type="text" class="form-control input-sm" readonly="true"
                                                        value="<?php echo $end_date; ?>">
                                                <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 form-group padding-remove-right prs-5">
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                            <label class="form-label"><?php echo $contact_1_alias; ?></label>
                                            <select id="filter_kontak" name="filter_kontak" class="form-control">
                                                <option value="0">-- Semua --</option>
                                            </select>
                                        </div>
                                    </div>                    
                                    <div class="col-lg-3 col-md-3 col-xs-6 col-sm-6 form-group padding-remove-right prs-0 prs-5">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group padding-remove-side">
                                            <label class="form-label">Cari</label>
                                            <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                                        </div>
                                    </div>                                 
                                    <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group prs-0 prs-0 prs-5">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group padding-remove-side">                                        
                                            <label class="form-label">Tampil</label>
                                            <select id="filter_length" name="filter_length" class="form-control">
                                                <option value="10">10 Baris</option>
                                                <option value="25">25 Baris</option>
                                                <option value="50">50 Baris</option>
                                                <option value="100">100 Baris</option>
                                            </select>
                                        </div>
                                    </div>                   
                                </div>  
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 prs-5">
                                    <div class="table-responsive">
                                        <table id="table_data_order" class="table table-bordered" data-limit-start="0" data-limit-end="10" style="width:100%;">
                                        </table>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>               
            </div>
        </div>
    </div>
</div>
    <!--
                </div>
            </div>
        </div>
    </div>
    -->

<!-- Order -->
<div class="modal fade" id="modal-order" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-order modal-dialog modal-lg" style="">
        <div class="modal-content">
            <form id="form-trans" name="form-trans" method="" action="">         
                <div class="modal-header modal-header-order">
                    <h4 id="modal_booking_title" style="text-align:left;">Buat <?php #echo $order_alias; ?> Baru</h4>
                    <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal"
                        style="position:relative;top:-38px;float:right;">
                        <i class="fas fa-times"></i>                                 
                        Tutup
                    </button>
                </div>
                <div class="modal-body modal-body-order">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="col-md-7 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                    <div class="grid simple">
                                        <div class="hidden grid-title">
                                            <div class="tools">
                                                <a href="javascript:;" class="collapse"></a>
                                                <a href="#grid-config" data-toggle="modal" class="config"></a>
                                                <a href="javascript:;" class="reload"></a>
                                                <a href="javascript:;" class="remove"></a>
                                            </div>
                                        </div>
                                        <div class="grid-body" style="padding:0px;">
                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                <h5 style="margin-top:8px!important;margin-bottom:0px!important;padding-left:8px;"><b>Produk</b></h5>
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="margin-top:10px;padding-left:5px;padding-right:5px;">
                                                    <div class="col-md-12 col-xs-12 col-sm-12" style="padding-left:4px;padding-right:4px;">
                                                        <div class="form-group">    
                                                            <input id="search-produk-tab-detail" name="search-produk-tab-detail" type="text" class="form-control" placeholder="Cari produk yang terkait">
                                                        </div>
                                                    </div>   
                                                </div>                                  
                                                <div id="product-tab" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-remove-side tabbable tabs-left" style="border-top: 1px solid #dddddd;border-bottom: 1px solid #dddddd;">
                                                    <ul class="nav nav-tabs scroll-nav scroll-order-product-categories" id="style-5" role="tablist" style="background-color: #ecf0f2!important;">
                                                        <li class="active"><a href="#" class="btn-product-tab-click" data-id="0" role="" data-toggle="tab" style="padding: 8px!important;">Semua</a></li>
                                                        <?php 
                                                        foreach($product_category as $y => $yy): 
                                                            // if($y == 0){$class='active'; }else{ 
                                                                $class = ''; 
                                                            // }
                                                        ?>
                                                            <li class="<?php echo $class; ?>"><a href="#" class="btn-product-tab-click" data-id="<?php echo $yy['category_id']; ?>" role="" data-toggle="tab" style="padding: 8px!important;"><?php echo $yy['category_name']; ?></a></li>                                      
                                                        <?php endforeach; ?>
                                                    </ul>
                                                    <div class="tab-content" style="margin-bottom: 0px!important">
                                                        <div class="tab-pane active" style="padding-top:0px;padding-bottom:0px;">
                                                            <div class="row">                                                   
                                                                <div id="product-tab-detail" class="col-md-12 col-xs-12 col-sm-12 scroll-track scroll-order-product">
                                                                    <!--
                                                                        <div class="col-md-4 col-xs-6 col-sm-6 btn-save-item tab-product-detail" data-id="13" data-qty="2" data-satuan="Pcs" data-price="18">
                                                                            <div class="col-md-12 col-sm-12">
                                                                                <img src="<?php #echo site_url('upload/product/product2.png'); ?>" class="img-responsive" style="width:100%;">
                                                                            </div>
                                                                            <div class="col-md-12 col-sm-12">
                                                                                <p class="product-name">Ayam Bakar Madu Urat</p>
                                                                                <p class="product-price">Rp. 100,000</p>
                                                                            </div>
                                                                        </div>
                                                                    -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-12 col-xs-12 padding-remove-right prs-0">
                                <div class="grid simple">
                                    <div class="hidden grid-title">
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse"></a>
                                            <a href="#grid-config" data-toggle="modal" class="config"></a>
                                            <a href="javascript:;" class="reload"></a>
                                            <a href="javascript:;" class="remove"></a>
                                        </div>
                                    </div>
                                    <div class="grid-body" style="padding-left:0;padding-right:0;padding-top:0;">
                                        <!-- <form id="form-trans" name="form-trans" method="" action=""> -->
                                            <div class="col-md-12 col-xs-12 padding-remove-side prs-0" style="background-color:#ecf0f2;">
                                                <div class="col-md-12 col-sm-12 col-xs-12" style="padding-top:8px;padding-bottom:8px;">
                                                    <h5 style="margin-top:4px!important;margin-bottom:0px!important;padding-left:0px;"><b><?php echo $order_alias; ?> Detail</b></h5>                  
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                                <input id="tipe" type="hidden" value="<?php echo $identity; ?>">
                                                            </div>                                                
                                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                                <input id="id_document" name="id_document" type="hidden" value="" placeholder="id" readonly>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 padding-remove-side prs-0">
                                                                <div class="col-md-12 col-xs-12 col-sm-12 prs-0 prs-5">
                                                                    <div class="form-group">                        
                                                                        <label class="form-label"><?php echo $ref_alias;?></label>
                                                                        <select id="ref" name="ref" class="form-control" disabled readonly>
                                                                            <option value="0">-</option>
                                                                        </select>
                                                                    </div>
                                                                </div>   
                                                            </div>                                                 
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 prs-0">                                                   
                                                                <div class="col-md-5 col-xs-5 col-sm-5 padding-remove-left prs-0">
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Tanggal</label>
                                                                            <div class="input-append success date col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                                                                                <input name="tgl" id="tgl" type="text" class="form-control input-sm" readonly="true" value="<?php echo $end_date; ?>">
                                                                                <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                                            </div>
                                                                        </div>                   
                                                                    </div>   
                                                                </div>
                                                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 padding-remove-side prs-0">
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                        <div class="form-group">                        
                                                                            <label class="form-label"><?php echo $contact_2_alias; ?> *</label>
                                                                            <select id="order_sales_id" name="order_sales_id" class="form-control" disabled readonly>
                                                                                <option value="0">Pilih</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>   
                                                                </div>                                                     
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 prs-0">
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 padding-remove-side">
                                                                    <div class="form-group">
                                                                        <label class="form-label"><?php echo $contact_1_alias; ?> / Non ?</label>
                                                                        <div class="toggles">
                                                                            <input type="checkbox" name="checkbox" id="order_contact_checkbox_flag" class="ios-toggle"/>
                                                                            <label class="order_contact_checkbox" data-flag="0"></label>	
                                                                        </div>
                                                                    </div>
                                                                </div>                                                        
                                                                <div class="col-md-4 col-xs-4 col-sm-4 padding-remove-side">
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-left">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Nama non-<?php echo $contact_1_alias; ?></label>
                                                                            <input id="order_contact_name" name="order_contact_name" type="text" value="" class="form-control"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5 col-xs-5 col-sm-5 padding-remove-side">
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-left">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Telepon non-<?php echo $contact_1_alias; ?></label>
                                                                            <input id="order_contact_phone" name="order_contact_phone" type="text" value="" class="form-control"/>
                                                                        </div>
                                                                    </div>
                                                                </div>                                                                
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0 prs-5">
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                        <div class="form-group">                        
                                                                            <label class="form-label"><?php echo $contact_1_alias; ?> *</label>
                                                                            <select id="order_contact_id" name="order_contact_id" class="form-control" disabled readonly>
                                                                            </select>               
                                                                        </div>                   
                                                                    </div>   
                                                                </div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                        
                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side scroll-track scroll-order-item" style="padding-top: 4px;margin-top: 0;margin-left:0px;margin-right:0px;"> 
                                                <table id="table-item" class="table table-bordered" data-limit-start="0" data-limit-end="10">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Produk</th> 
                                                            <th style="text-align:center;">Qty</th>
                                                            <th style="text-align:right;">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr><td colspan="4" style="padding-top:8px!important;padding-bottom:8px!important;text-align:center;">Kosong</td></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-12 col-xs-12 padding-remove-side" style="padding-top:8px;background-color:#ecf0f2;">
                                                <div class="col-md-12 col-xs-12 col-sm-12">
                                                    <div class="col-md-4 col-xs-6 col-sm-12 padding-remove-left">
                                                        &nbsp;
                                                    </div>
                                                    <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="margin-bottom:4px;">
                                                            <div class="form-group">
                                                                <label class="form-label">Jumlah Produk</label>
                                                                <input id="total_produk" name="total_produk" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-right prs-0">
                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                            <div class="form-group">
                                                                <label class="form-label padding-remove-left">Total (Rp)</label>
                                                                <input id="total" name="total" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                            </div>
                                                        </div>                                        
                                                        <!--
                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="margin-bottom:4px;">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Diskon %</label>
                                                                    <div class="col-md-7">
                                                                    <input id="diskon" name="diskon" type="text" value="0" class="form-control" style="cursor:pointer;text-align:right;" readonly='true'/>
                                                                    </div>
                                                                </div>                            
                                                            </div>                                            
                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="margin-bottom:4px;">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Subtotal</label>
                                                                    <div class="col-md-7">
                                                                        <input id="subtotal" name="subtotal" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="margin-bottom:4px;">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">Total Diskon</label>
                                                                    <div class="col-md-7">
                                                                        <input id="total_diskon" name="total_diskon" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                                    </div>
                                                                </div>                            
                                                            </div>
                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="margin-bottom:4px;">
                                                                    <div class="form-group">
                                                                        <label class="col-md-5 padding-remove-side">Down Payment (Rp)</label>
                                                                        <div class="col-md-7 padding-remove-side">
                                                                            <input id="total_down_payment" name="total_down_payment" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        -->                                     
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- </form>   -->
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0" style="margin-top: 10px;">                    
                        <!--
                            <button id="btn-update-order" class="btn btn-info btn-small" type="button" style="">
                                <i class="fas fa-edit""></i> 
                                Update
                            </button> 
                            <button id="btn-delete-order" class="btn btn-danger btn-small" type="button" style="">
                                <i class="fas fa-trash"></i> 
                                Delete
                            </button>
                            <button id="btn-new-order" onClick="formTransNew();" class="btn btn-success btn-small" type="button">
                                <i class="fas fa-file-medical"></i> 
                                Buat Baru
                            </button>  
                        -->                                                         
                        <button id="btn-save-order" class="btn btn-primary" type="button" style="width:100%;margin-bottom:8px;">
                            <i class="fas fa-save"></i>                                 
                            Simpan <?php echo $order_alias; ?>
                        </button>                                                                             
                        <button id="btn-cancel-order" class="btn btn-danger" type="reset" style="width:100%;margin-left:0px;">
                            <i class="fas fa-ban"></i> 
                            Reset <?php echo $order_alias; ?>
                        </button> 
                        <!--     
                        <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal">
                            <i class="fas fa-times"></i>                                 
                            Tutup
                        </button>
                        -->           
                    </div>                  
                </div>
            </form>      
        </div>
    </div>
</div>
<div class="modal fade" id="modal-payment-list" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modal-payment-order-ref" style="text-align:left;">Daftar <?php echo $order_alias; ?> Anda</h4>
                <b id="modal-payment-order-date">-</b>
                <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal" style="position:relative;top:-38px;float:right;">
                    <i class="fas fa-times"></i>                                 
                    Tutup
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 col-xs-12">
                        <!-- <p><h5 style="text-align:center;"><b id="modal-payment-ref-name"></b></h5></p>-->
                        <p class="text-center">
                            <i class="fas fa-list-alt fa-4x"></i>
                        </p>
                        <p><h5 style="text-align:center;"><b id="modal-payment-contact-name"></b></h5></p>
                        <p><h5 style="text-align:center;"><b id="modal-payment-contact-non"></b></h5></p>
                        <p><h5 style="text-align:center;"><b id="modal-payment-employee-name"></b></h5></p>                        
                    </div>
                    <div class="col-md-9 col-xs-12">
                        <table id="modal-payment-order-list" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Qty</th>
                                    <th class="text-right">Subtotal</th>
                                    <th>#</th>                                    
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>                                         
            </div>
            <div class="modal-footer flex-center">
                <div class="row">
                    <div class="col-md-4 col-sm-12" style="margin-bottom:4px;">           
                        <a class="btn-move-room-order btn btn-primary" href="#" data-order-id="0" data-order-number="0" data-ref-id="0" data-ref-name="0" data-dismiss="modal" style="width:45%;">
                            <i class="fas fa-arrow-right"></i> Pindah <?php echo $ref_alias; ?>
                        </a>
                        <a id="btn-addon-order" href="#" class="btn btn-primary btn-small" data-order-id="0" data-order-number="0" data-ref-name="0" data-dismiss="modal" style="width:45%;">
                            <i class="fas fa-plus"></i> Tambah Produk
                        </a>
                    </div>
                    <div class="col-md-4 col-sm-12" style="margin-bottom:4px;">
                        <a id="btn-print-order" href="#" class="btn btn-print btn-info" style="width:45%;">
                            <i class="fas fa-print"></i> Print
                        </a>                             
                        <a class="btn-prepare-payment btn btn-success" href="#" data-id="0" data-grand-total="0" style="width:45%;">
                            <i class="fas fa-check white"></i> Buat <?php echo $payment_alias;?>
                        </a>  
                    </div>      
                    <div class="col-md-4 col-sm-12" style="margin-bottom:4px;">
                        <a class="btn-cancel-order btn btn-danger" href="#" data-order-id="0" data-order-number="0" data-ref-id="0" data-ref-name="0" data-dismiss="modal" style="width:45%;">
                            <i class="fas fa-ban"></i> Batal <?php echo $order_alias; ?>
                        </a>                
                        <a type="button" class="btn btn-outline-danger waves-effect" data-dismiss="modal" style="width:45%;">
                            <span class="fas fa-times"></span> Tutup
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment -->
<div class="modal fade" id="modal-payment-form" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-order modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-payment" name="form-payment" method="" action="">         
                <div class="modal-header">
                    <h4 id="modal_booking_title" style="text-align:left;">Daftar <?php echo $payment_alias;?></h4>
                    <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal"
                        style="position:relative;top:-38px;float:right;">
                        <i class="fas fa-times"></i>                                 
                        Tutup
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 prs-0">
                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                <div class="grid simple">
                                    <div class="hidden grid-title">
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse"></a>
                                            <a href="#grid-config" data-toggle="modal" class="config"></a>
                                            <a href="javascript:;" class="reload"></a>
                                            <a href="javascript:;" class="remove"></a>
                                        </div>
                                    </div>
                                    <div class="grid-body" style="padding:0px;padding-top:12px;padding-bottom:12px;">
                                        <div class="col-md-12 col-xs-12 col-sm-12">
                                            <div class="col-md-6 col-sm-12 col-xs-12 padding-remove-left prs-0">
                                                <!-- <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0"> -->
                                                    <div class="grid simple">
                                                        <div class="grid-body">
                                                            <h5 style="margin-top:0px!important;padding-left:0px;"><b>Daftar <?php echo $order_alias; ?> yg Dibayar</b></h5>
                                                            <!--
                                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                    <h5 style="margin-top:8px!important;padding-left:12px;"><b>Daftar <?php #echo $payment_alias; ?></b></h5> 
                                                                    <div id="payment-tab" class="tabbable tabs-left">
                                                                        <div class="tab-content">
                                                                            <div class="tab-pane active" style="padding-top:0px;padding-bottom:0px;">
                                                                                <div class="row">
                                                                                    <div id="payment-tab-detail" class="col-md-12 col-xs-12 col-sm-12 scroll-track scroll-pembayaran-daftar" style="margin-top:0px;padding-top:0px;">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            -->
                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0" style="padding-top:0px;border-bottom: 1px solid #dddddd;margin-top:0px;margin-left:0px;margin-right:0px;"> 
                                                                <input id="order_list_id" name="order_list_id" value="" type="hidden" class="form-controls">
                                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side scroll-track scroll-pembayaran-antrian prs-0">
                                                                    <div class="table-responsive"> 
                                                                        <table id="table-payment-item" class="table table-bordered" data-limit-start="0" data-limit-end="10">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>#</th>
                                                                                    <th><?php echo $order_alias; ?> Info</th></s>
                                                                                    <th style="text-align:right;">Total</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>                               
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                <!-- </div> -->
                                            </div>
                                            <div class="col-md-6 col-sm-12 col-xs-12 padding-remove-side">
                                                <div class="grid simple">
                                                    <div class="grid-body">
                                                        <h5 style="margin-top:0px!important;padding-left:0px;"><b>Metode Pembayaran</b></h5>  
                                                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                                            <!-- <b><i class="fas fa-table"></i> Form Payment</b><br>-->
                                                            <div class="row">
                                                                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                                                    <input id="tipe" type="hidden" value="<?php echo $identity; ?>">
                                                                    <div class="col-md-12">
                                                                        <input id="id_payment" name="id_payment" type="hidden" value="" placeholder="id" readonly>
                                                                    </div>
                                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6 padding-remove-side">
                                                                            <div class="form-group">
                                                                                <label class="form-label"><?php echo $contact_1_alias?> / Non ?</label>
                                                                                <div class="toggles">
                                                                                    <input type="checkbox" name="checkbox" id="payment_contact_checkbox_flag" class="ios-toggle"/>
                                                                                    <label class="payment_contact_checkbox" data-flag="0"></label>	
                                                                                </div>
                                                                            </div>
                                                                        </div>                                                        
                                                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-6 padding-remove-side">
                                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-left">
                                                                                <div class="form-group">
                                                                                <label class="form-label">Nama non-<?php echo $contact_1_alias?></label>
                                                                                    <input id="payment_contact_name" name="payment_contact_name" type="text" value="" class="form-control"/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-6 padding-remove-side">
                                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-left">
                                                                                <div class="form-group">
                                                                                <label class="form-label">Telepon non-<?php echo $contact_1_alias?></label>
                                                                                    <input id="payment_contact_phone" name="payment_contact_phone" type="text" value="" class="form-control"/>
                                                                                </div>
                                                                            </div>
                                                                        </div>                                                                                                                                                
                                                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-remove-side">
                                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                                <div class="form-group">                        
                                                                                    <label class="form-label"><?php echo $contact_1_alias; ?></label>
                                                                                    <select id="payment_contact_id" name="payment_contact_id" class="form-control" disabled>
                                                                                    </select>               
                                                                                </div>                   
                                                                            </div>   
                                                                        </div>                                               
                                                                        <!--
                                                                            <div class="col-md-3 col-xs-6 col-sm-12 padding-remove-left">
                                                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                                    <div class="form-group">
                                                                                    <label>Tanggal</label>
                                                                                    <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                                                        <input name="tgl" id="tgl" type="text" class="form-control input-sm" readonly="true" value="<?php echo $end_date; ?>">
                                                                                        <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                                                    </div>
                                                                                    </div>                   
                                                                                </div>   
                                                                            </div>
                                                                        -->
                                                                        <!--     
                                                                            <div class="col-md-3 col-xs-6 col-sm-12 padding-remove-side">
                                                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-left">
                                                                                    <div class="form-group">
                                                                                    <label>Nomor *</label>
                                                                                    <input id="nomor" name="nomor" type="text" value="" class="form-control" readonly='true'/>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        -->
                                                                        <!--
                                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="margin-bottom:4px;">
                                                                                <div class="form-group">
                                                                                    <label class="col-md-5">Diskon %</label>
                                                                                    <div class="col-md-7">
                                                                                    <input id="diskon_payment" name="diskon_payment" type="text" value="0" class="form-control" style="cursor:pointer;text-align:right;" readonly='true'/>
                                                                                    </div>
                                                                                </div>                            
                                                                            </div>
                                                                        -->                 
                                                                        <!--
                                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="margin-bottom:4px;">
                                                                                <div class="form-group">
                                                                                    <label class="col-md-5">Subtotal</label>
                                                                                    <div class="col-md-7">
                                                                                    <input id="subtotal_payment" name="subtotal_payment" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="margin-bottom:4px;">
                                                                                <div class="form-group">
                                                                                    <label class="col-md-5">Total Diskon</label>
                                                                                    <div class="col-md-7">
                                                                                    <input id="total_diskon_payment" name="total_diskon_payment" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                                                    </div>
                                                                                </div>                            
                                                                            </div>
                                                                        -->                                                                               
                                                                    </div>               
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                            <form id="form_modal_trans_save">
                                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Metode Pembayaran</label>
                                                                            <select id="modal_metode_pembayaran" name="modal_metode_pembayaran" class="form-control">
                                                                                <option value="0">-- Pilih --</option>
                                                                                <?php 
                                                                                foreach($type_paid as $k):
                                                                                    echo "<option value=".$k['paid_id'].">".$k['paid_name']."</option>";
                                                                                endforeach;
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div id="modal_metode_pembayaran_cash" style="display: none;" class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                        <div class="hide col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Setor ke</label>
                                                                                <select id="modal_akun_cash" name="modal_akun_cash" class="form-control" disabled readonly>
                                                                                    <!-- <option value="0">-- Pilih --</option> -->
                                                                                    <?php foreach($account_payment as $v){
                                                                                        if($v['account_map_type']==1){
                                                                                            echo '<option value="'.$v['account_id'].'" selected>'.$v['account_name'].'</option>';
                                                                                        } 
                                                                                    } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div id="modal_metode_pembayaran_transfer" style="display: none;" class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                        <div class="hide col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Transfer ke Bank</label>
                                                                                <select id="modal_akun_transfer" name="modal_akun_transfer" class="form-control" disabled readonly>
                                                                                    <?php foreach($account_payment as $v){
                                                                                        if($v['account_map_type']==2){
                                                                                            echo '<option value="'.$v['account_id'].'" selected>'.$v['account_name'].'</option>';
                                                                                        } 
                                                                                    } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 col-xs-6 col-sm-6 padding-remove-left">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Nomor Ref Bukti Transfer</label>
                                                                                <input id="modal_nomor_ref_transfer" name="modal_nomor_ref_transfer" type="text" value="" class="form-control" readonly='true'/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 col-xs-6 col-sm-6 padding-remove-side">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Nama Pengirim Transfer</label>
                                                                                <input id="modal_nama_pengirim" name="modal_nama_pengirim" type="text" value="" class="form-control" readonly='true'/>
                                                                            </div>
                                                                        </div>
                                                                    </div>                            
                                                                    <div id="modal_metode_pembayaran_edc" style="display: none;" class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                        <div class="hide col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                            <div class="form-group">
                                                                                <label class="form-label">EDC Bank</label>
                                                                                <select id="modal_akun_edc" name="modal_akun_edc" class="form-control" disabled readonly>
                                                                                    <?php foreach($account_payment as $v){
                                                                                        if($v['account_map_type']==3){
                                                                                            echo '<option value="'.$v['account_id'].'" selected>'.$v['account_name'].'</option>';
                                                                                        } 
                                                                                    } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                            <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-left">                                                                               
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Bank Penerbit Kartu</label>
                                                                                    <select id="modal_bank_penerbit" name="modal_bank_penerbit" class="form-control" disabled readonly>
                                                                                        <option value="0">-- Pilih --</option>
                                                                                        <option value="1">BCA</option>
                                                                                        <option value="2">BNI</option>
                                                                                        <option value="3">BRI</option>
                                                                                        <option value="4">Mandiri</option>
                                                                                        <option value="5">DBS</option>
                                                                                        <option value="6">Standard Chartered</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-side">                                                                              
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Valid Tahun</label>
                                                                                    <input id="modal_valid_tahun" name="modal_valid_tahun" type="text" value="" class="form-control" readonly='true'/>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-right">                                                                                   
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Valid Bulan</label>
                                                                                    <input id="modal_valid_bulan" name="modal_valid_bulan" type="text" value="" class="form-control" readonly='true'/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-side">                                                                            
                                                                            <div class="form-group">
                                                                                <label class="form-label">Jenis Kartu</label>
                                                                                <select id="modal_jenis_kartu" name="modal_jenis_kartu" class="form-control" disabled readonly>
                                                                                    <option value="0">-- Pilih --</option>
                                                                                    <option value="1">Visa</option>
                                                                                    <option value="2">MasterCard</option>
                                                                                    <option value="3">American Express</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>                                
                                                                        <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-side">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Nomor Kartu</label>
                                                                                <input id="modal_nomor_kartu" name="modal_nomor_kartu" type="text" value="" class="form-control" readonly='true'/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-right">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Nama Pemilik Kartu</label>
                                                                                <input id="modal_nama_pemilik" name="modal_nama_pemilik" type="text" value="" class="form-control" readonly='true'/>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">                                                                            
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Nomor Transaksi</label>
                                                                                    <input id="modal_kode_transaksi" name="modal_kode_transaksi" type="text" value="" class="form-control" readonly='true'/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div id="modal_metode_pembayaran_qris" style="display: none;" class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                        <div class="hide col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Akun Penampung QRIS</label>
                                                                                <select id="modal_akun_qris" name="modal_akun_qris" class="form-control" disabled readonly>
                                                                                    <?php foreach($account_payment as $v){
                                                                                        if($v['account_map_type']==4){
                                                                                            echo '<option value="'.$v['account_id'].'" selected>'.$v['account_name'].'</option>';
                                                                                        } 
                                                                                    } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div id="modal_metode_pembayaran_gratis" style="display: none;" class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                        <div class="hide col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Akun Penampung Gratis</label>
                                                                                <select id="modal_akun_gratis" name="modal_akun_gratis" class="form-control" disabled readonly>
                                                                                    <?php foreach($account_payment as $v){
                                                                                        if($v['account_map_type']==5){
                                                                                            echo '<option value="'.$v['account_id'].'" selected>'.$v['account_name'].'</option>';
                                                                                        } 
                                                                                    } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>                                                                    
                                                                    <div id="modal_metode_pembayaran_down_payment" style="display: none;" class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                        <div class="col-md-8 col-xs-8 col-sm-8 padding-remove-side">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Pengecekan Saldo <?php echo $dp_alias; ?> <?php echo $contact_1_alias; ?></label>
                                                                                <select id="modal_dp_contact_id" name="modal_dp_contact_id" class="form-control" disabled readonly>
                                                                                    <option value="0">-- Pilih --</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 col-xs-4 col-sm-4 padding-remove-right">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Sisa Saldo <?php echo $dp_alias; ?></label>
                                                                                <input id="modal_dp_balance" name="modal_dp_balance" type="text" value="" class="form-control" readonly='true'/>
                                                                            </div>
                                                                        </div>
                                                                    </div>                                
                                                                </div>
                                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Grand Total</label>
                                                                            <input id="method-payment-total-before" name="method-payment-total-before" type="text" value="" class="form-control" readonly='true'/>
                                                                        </div>
                                                                    </div>
                                                                    <!-- <div class="col-md-8 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Klaim Voucher / Promo</label>
                                                                            <input id="modal-payment-voucher" name="modal-payment-voucher" type="text" value="" class="form-control" style="border-radius:0px!important;width:60%;"/>
                                                                            <button id="btn-voucher-search" data-id="1" type="button" class="btn btn-mini btn-small" style="position:relative;top:-28px;left:246px;width:40%;height:28px;border-radius:0px;">
                                                                                <span class="fas fa-check-double"></span> Gunakan
                                                                            </button>
                                                                        </div>                   
                                                                    </div> -->
                                                                    <div class="col-md-4 col-sm-4 col-xs-12 padding-remove-side prs-0">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Klaim Voucher / Promo</label>
                                                                            <input id="modal-payment-voucher" name="modal-payment-voucher" type="text" value="" class="form-control" style=""/>
                                                                        </div>                   
                                                                    </div>
                                                                    <div class="col-md-4 col-sm-4 col-xs-12 padding-remove-side prs-0">
                                                                        <div class="form-group">
                                                                            <label class="form-label">&nbsp;</label>
                                                                            <button id="btn-voucher-search" data-id="1" type="button" class="form-control btn btn-mini btn-small" style="">
                                                                                <span class="fas fa-check-double"></span> Gunakan
                                                                            </button>
                                                                        </div>                   
                                                                    </div>                                                                                                                                        
                                                                    <div class="col-md-4 col-sm-4 col-xs-12 padding-remove-right prl-2">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Nilai Voucher</label>
                                                                            <input id="modal-payment-voucher-nominal" name="modal-payment-voucher-nominal" type="text" value="" class="form-control" readonly='true'/>
                                                                        </div>
                                                                    </div>    
                                                                    <div class="clearfix"></div>
                                                                    <div class="col-md-4 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Total Tagihan</label>
                                                                            <input id="method-payment-total" name="method-payment-total" type="text" value="" class="form-control" readonly='true'/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Masukkan Jumlah (Rp)</label>
                                                                            <input id="method-payment-received" name="method-payment-received" type="text" value="" class="form-control"/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-sm-12 col-xs-12 padding-remove-right prl-2">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Kembali</label>
                                                                            <input id="method-payment-change" name="method-payment-change" type="text" value="" class="form-control" readonly='true'/>
                                                                        </div>
                                                                    </div>              
                                                                </div>
                                                            </form>
                                                        </div>              
                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                            <div class="form-group">
                                                                <div class="pull-right">
                                                                    <!--
                                                                        <button id="btn-update" class="btn btn-info btn-small" type="button" style="">
                                                                            <i class="fas fa-edit""></i> 
                                                                            Update
                                                                        </button> 
                                                                        <button id="btn-delete" class="btn btn-danger btn-small" type="button" style="">
                                                                            <i class="fas fa-trash"></i> 
                                                                            Delete
                                                                        </button>
                                                                        <button id="btn-new-order" onClick="formTransNew();" class="btn btn-success btn-small" type="button">
                                                                            <i class="fas fa-file-medical"></i> 
                                                                            Buat Baru
                                                                        </button>                                                                 
                                                                        <button id="btn-cancel" class="btn btn-warning btn-small" type="reset" style="">
                                                                            <i class="fas fa-ban"></i> 
                                                                            Batal
                                                                        </button>  
                                                                    -->     
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button id="btn-save-payment" class="btn btn-success" data-id="" style="width:25%;">
                        <span class="fas fa-cash-register white"></span> Bayar
                    </button>
                    <!--
                    <button id="btn-payment-confirmation" class="btn btn-primary btn-small" type="button" style="">
                        <i class="fas fa-save"></i>
                        Proses ke Pembayaran
                    </button>
                    -->                
                    <button class="btn btn-outline-danger waves-effect" type="button" style="width:25%;" data-dismiss="modal">
                        <i class="fas fa-times"></i>                                 
                        Tutup
                    </button>
                </div>
            </form>      
        </div>
    </div>
</div>
<div class="modal fade" id="modal-payment-print" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f3f5f6;">
                <h4 style="color:black;text-align:left;">Pembayaran Berhasil</h4>
                <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal" style="position:relative;top:-38px;float:right;">
                    <i class="fas fa-times"></i>                                 
                    Tutup
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 col-xs-4 padding-remove-side">
                        <p class="text-center">
                            <i class="fas fa-print fa-4x"></i>
                        </p>
                        <p class="text-center"><b>LUNAS</b></p>
                    </div>
                    <div class="col-md-8 col-xs-8 padding-remove-side">
                        <table class="table">      
                            <tr>
                                <td>Nomor</td>
                                <td class="modal-print-trans-number">:</td>
                            </tr>
                            <tr>
                                <td>Tanggal</td>
                                <td class="modal-print-trans-date">:</td>
                            </tr>                            
                            <tr>
                                <td>Metode Pembayaran</td>
                                <td class="modal-print-trans-paid-type-name">:</td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td class="modal-print-trans-total">:</td>
                            </tr>
                            <tr>
                                <td>Dibayar</td>
                                <td class="modal-print-trans-total-paid">:</td>
                            </tr>
                            <tr>
                                <td>Kembalian</td>
                                <td class="modal-print-trans-total-change">:</td>
                            </tr>        
                            <tr>
                                <td>Penerima</td>
                                <td>:<input id="modal-print-contact-name" name="modal-contact-name" value="" style="border:none!important;"></td>
                            </tr>         
                            <tr>
                                <td>Telepon</td>
                                <td>:<input id="modal-print-contact-phone" name="modal-contact-phone" value="" style="border:none!important;"></td>
                            </tr>                                                              
                        </table>
                    </div> 
                    <!--
                        <div class="col-md-12 col-xs-12">
                            <h2 class="text-center">Lunas</h2>
                        </div> 
                    -->                  
                </div>
            </div>
            <div class="modal-footer flex-center">
                <!-- <a href="#" id="modal-btn-print" class="btn-print-from-modal btn btn-success" data-id="">
                    <i class="fas fa-print white"></i> Print
                </a> -->
                <!-- <a href="#" class="btn-send-whatsapp btn btn-primary" data-id="0"
                data-number="" data-date="" data-total="" data-contact-id="" data-contact-name="" data-contact-phone="" style="width:45%;">
                    <i class="fas fa-whatsapp"></i> Kirim Invoice WhatsApp
                </a> -->
                <!-- <a href="#" id="" class="btn-print-payment btn btn-success" data-id="0" data-number="0" style="width:25%;">
                    <i class="fas fa-file-invoice white"></i> Print Invoice
                </a> -->
                <!--
                <a href="#" id="modal-btn-print-whatsapp" class="btn-print-whatsapp btn btn-primary" data-id="" data-contact-id="">
                  <i class="fab fa-whatsapp white"></i> Kirim via WhatsApp
                </a> -->       
                <!-- <a type="button" class="btn btn-outline-danger waves-effect" data-dismiss="modal" style="width:25%;"><span class="fas fa-times"></span> Tutup</a> -->

                <button type="button" class="btn-send-whatsapp btn btn-primary" 
                    data-id="0" data-number="" data-date="" data-total="" data-contact-id="" data-contact-name="" data-contact-phone="" style="width:45%;">
					<span class="fab fa-whatsapp white"></span> Kirim Struk via WhatsApp
				</button>
				<button type="button" class="btn-print-payment btn btn-success" data-id="0" data-number="0" style="width:45%;">
					<span class="fas fa-file-invoice white"></span> Print Struk
				</button>
                <!--
				<button type="button" class="btn btn-outline-danger waves-effect" data-dismiss="modal">
					<span class="fas fa-times"></span> Tutup
				</button>                   
                -->
            </div>
        </div>
    </div>
</div>

<!-- Other (Form Contact n Form Product)-->
<div class="modal fade" id="modal-contact" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="form-master" name="form-master" method="" action="">         
                <div class="modal-header">
                    <h4 style="text-align:left;">Buat <?php echo $contact_1_alias; ?> Baru</h4>
                    <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal" style="position:relative;top:-38px;float:right;">
                        <i class="fas fa-times"></i>                                 
                        Tutup
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <p></p>
                            <p class="text-center">
                                <i class="fas fa-user-plus fa-5x"></i>
                            </p>
                        </div>
                        <div class="col-md-9 col-xs-12"> 
                            <div class="col-md-6 col-sm-12 col-xs-12">              
                                <div class="col-lg-5 col-md-5 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Kode</label>
                                        <input id="kode_contact" name="kode_contact" type="text" value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Nama <?php echo $contact_1_alias; ?> *</label>
                                        <input id="nama_contact" name="nama_contact" type="text" value="" class="form-control"/>
                                    </div>
                                </div>                      
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Telepon *</label>
                                        <!-- <input class="form-control" id="code" name="code" type="text" required placeholder="" value="+62" style="width:30%;float:left;" readonly> -->
                                        <input id="telepon_1_contact" name="telepon_1_contact" type="text" value="" class="form-control"/>
                                    </div>                          
                                </div>                                                          
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Alamat</label>
                                        <textarea id="alamat_contact" name="alamat_contact" type="text" value="" class="form-control"rows="8"/></textarea>
                                    </div>
                                </div>                             
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input id="email_1_contact" name="email_1_contact" type="text" value="" class="form-control"/>
                                    </div>                          
                                </div>    
                                <!--
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label>Perusahaan</label>
                                            <input id="perusahaan_contact" name="perusahaan_contact" type="text" value="" class="form-control"/>
                                        </div>
                                    </div>
                                -->                                                                              
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button id="btn-save-contact" onClick="" class="btn btn-primary" type="button" style="width:45%;">
                        <i class="fas fa-save"></i>                                 
                        Simpan
                    </button>    
                    <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal" style="width:45%;">
                        <i class="fas fa-times"></i>                                 
                        Tutup
                    </button>                   
                </div>
            </form>      
        </div>
    </div>
</div>
<div class="modal fade" id="modal-product" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="form-product" name="form-product" method="" action="">         
                <div class="modal-header">
                    <h4 style="text-align:left;">Buat Produk Baru</h4>
                    <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal" style="position:relative;top:-38px;float:right;">
                        <i class="fas fa-times"></i>                                 
                        Tutup
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <p></p>
                            <p class="text-center">
                                <i class="fas fa-boxes fa-5x"></i>
                            </p>
                        </div>
                        <div class="col-md-9 col-xs-12"> 
                            <div class="col-md-12 col-sm-12 col-xs-12">              
                                <div class="col-lg-5 col-md-5 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Kode Barang / SKU / PLU</label>
                                        <input id="kode_barang" name="kode_barang" type="text" value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input id="nama_barang" name="nama_barang" type="text" value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Satuan</label>
                                        <select id="satuan_barang" name="satuan_barang" class="form-control">
                                            <option value="0">-- Pilih / Cari --</option>
                                        </select>
                                    </div>
                                </div>                                                                                 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button id="btn-save-product" onClick="" class="btn btn-primary" type="button" style="width:45%;">
                        <i class="fas fa-save"></i>                                 
                        Simpan
                    </button>    
                    <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal" style="width:45%;">
                        <i class="fas fa-times"></i>                                 
                        Tutup
                    </button>                     
                </div>
            </form>      
        </div>
    </div>
</div>
<!-- Add On -->
<div class="modal fade" id="modal-order-addon" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="form-trans-item" name="form-trans-item" method="" action="">
                <div class="modal-header">
                    <h4 id="modal-trans-addon-title" style="text-align:left;">Tambahkan Produk</h4>
                    <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal" style="position:relative;top:-38px;float:right;">
                        <i class="fas fa-times"></i>                                 
                        Tutup
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2 col-xs-12">
                            <p></p>
                            <p class="text-center">
                                <i class="fas fa-plus-square fa-8x"></i>
                            </p>
                        </div>
                        <div class="col-md-10 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Produk *</label>
                                            <select id="produk" name="produk" class="form-control" disabled readonly>
                                                <option value="0">-- Cari Item Produk Tambahan--</option>
                                            </select>
                                        </div> 
                                    </div>
                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                        <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-left">
                                            <div class="form-group">
                                                <label class="form-label">Satuan</label>
                                                <input id="satuan" name="satuan" type="text" value="" class="form-control" readonly='true'/>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-left">
                                            <div class="form-group">
                                                <label class="form-label">Harga Jual</label>
                                                <input id="harga" name="harga" type="text" value="" class="form-control" readonly='true'/>
                                            </div>
                                        </div>                     
                                        <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-left">
                                            <div class="form-group">
                                                <label class="form-label">Qty</label>
                                                <input id="qty" name="qty" type="text" value="1" class="form-control" readonly='true'/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>                                         
                </div>
                <div class="modal-footer flex-center">
                    <button id="btn-save-order-item-addon" data-order-id="0" onClick="" class="btn btn-primary" type="button" style="width:45%;">
                        <i class="fas fa-plus"></i>
                        Tambah
                    </button>                    
                    <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal" style="width:45%;">
                        <i class="fas fa-times"></i>                                 
                        Tutup
                    </button> 
                </div>                
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-order-item-discount" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 style="text-align:left;">Pasang Diskon Produk</h4>
                <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal" style="position:relative;top:-38px;float:right;">
                    <i class="fas fa-times"></i>                                 
                    Tutup
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-xs-12">
                        <div class="col-md-2 col-xs-12">
                            <p></p>
                            <p class="text-center">
                                <i class="fas fa-percent fa-4x"></i>
                            </p>
                        </div>
                        <div class="col-md-10 col-xs-12">
                            <div class="form-group col-md-12 col-sm-12">
                                <label class="form-label">Produk</label>
                                <input id="trans-item-id" name="trans-item-product-id" type="hidden" value="" readonly="true">
                                <input id="trans-item-product-name" name="trans-item-product-name" type="text" value="" readonly="true" style="width:100%;" class="form-control">
                            </div>            
                            <div class="clearfix"></div>
                            <div class="form-group col-md-4 col-sm-12">
                                <label class="form-label">Qty</label>
                                <input id="trans-item-qty" name="trans-item-qty" type="text" value="" readonly class="form-control">
                            </div>
                            <div class="form-group col-md-4 col-sm-12">
                                <label class="form-label">Harga</label>
                                <input id="trans-item-price" name="trans-item-price" type="text" value="" readonly class="form-control">
                            </div> 
                            <div class="form-group col-md-4 col-sm-12">
                                <label class="form-label">Total</label>
                                <input id="trans-item-total" name="trans-item-total" type="text" value="" readonly class="form-control">
                            </div>                      
                            <div class="clearfix"></div>  
                            <div class="form-group col-md-4 col-sm-12">
                                <label class="form-label">Diskon (Rp)</label>
                                <input id="trans-item-discount" name="trans-item-discount" type="text" value="" class="form-control">
                            </div>
                            <div class="form-group col-md-4 col-sm-12">
                                <label class="form-label">Subtotal</label>
                                <input id="trans-item-total-after-discount" name="trans-item-total-after-discount" type="text" value="" readonly="true" class="form-control">
                            </div>            
                        </div>
                    </div>
                </div>                                         
            </div>
            <div class="modal-footer flex-center">
                <button id="btn-save-order-item-discount" class="btn btn-success" data-id="" style="width:45%;">
                    <i class="fas fa-check white"></i> Pasang Diskon
                </button>
                <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal" style="width:45%;">
                    <i class="fas fa-times"></i>                                 
                    Tutup
                </button>         
            </div>
        </div>
    </div>
</div>
<!--
<div class="modal fade" id="modal-order-item-note" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Tambahkan Catatan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 col-xs-12">
                        <p></p>
                        <p class="text-center">
                            <i class="fas fa-sticky-note fa-4x"></i>
                        </p>
                    </div>
                    <div class="col-md-10 col-xs-12">
                        <div class="form-group col-md-12 col-sm-12" style="padding-left:0px;">
                            <label class="form-label">Produk</label>
                            <input id="trans-item-note-id" name="trans-item-note-product-id" type="hidden" value="" readonly="true">
                            <input id="trans-item-note-product-name" name="trans-item-note-product-name" type="text" value="" readonly="true" style="width:100%;" class="form-control">
                        </div>
                        <div class="form-group col-md-12 col-sm-12" style="padding-left:0px;">
                            <label class="form-label">Catatan</label>
                            <input id="trans-item-note" name="trans-item-note" type="text" value="" style="width:100%;" class="form-control">
                        </div>   
                    </div>
                </div>                                         
            </div>
            <div class="modal-footer flex-center">
                <button id="btn-save-item-note" class="btn btn-success" data-id="">
                    <i class="fas fa-check white"></i> Perbarui Catatan
                </button>
                <a type="button" class="btn btn-outline-danger waves-effect" data-dismiss="modal">
                    <i class="fas fa-window-close"></i>
                    Batal
                </a>   
            </div>
        </div>
    </div>
</div>
-->