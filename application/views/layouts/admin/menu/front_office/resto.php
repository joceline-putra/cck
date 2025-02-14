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
    .table-responsive{
        overflow-x: unset;
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
        .ios-toggle:checked + .trans_contact_checkbox{
            /*box-shadow*/
            -webkit-box-shadow:inset 0 0 0 20px rgba(19,191,17,1),0 0 0 2px rgba(19,191,17,1);
            -moz-box-shadow:inset 0 0 0 20px rgba(19,191,17,1),0 0 0 2px rgba(19,191,17,1);
            box-shadow:inset 0 0 0 20px rgba(19,191,17,1),0 0 0 2px rgba(19,191,17,1);
        }
        .ios-toggle:checked + .trans_contact_checkbox:before{
            left:calc(100% - 24px);
            /*box-shadow*/
            -webkit-box-shadow:0 0 0 2px transparent,0 3px 3px rgba(0,0,0,.3);
            -moz-box-shadow:0 0 0 2px transparent,0 3px 3px rgba(0,0,0,.3);
            box-shadow:0 0 0 2px transparent,0 3px 3px rgba(0,0,0,.3);
        }
        .ios-toggle:checked + .trans_contact_checkbox:after{
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
        .ios-toggle:checked + .barcode_checkbox{
            /*box-shadow*/
            -webkit-box-shadow:inset 0 0 0 20px rgba(19,191,17,1),0 0 0 2px rgba(19,191,17,1);
            -moz-box-shadow:inset 0 0 0 20px rgba(19,191,17,1),0 0 0 2px rgba(19,191,17,1);
            box-shadow:inset 0 0 0 20px rgba(19,191,17,1),0 0 0 2px rgba(19,191,17,1);
        }
        .ios-toggle:checked + .barcode_checkbox:before{
            left:calc(100% - 24px);
            /*box-shadow*/
            -webkit-box-shadow:0 0 0 2px transparent,0 3px 3px rgba(0,0,0,.3);
            -moz-box-shadow:0 0 0 2px transparent,0 3px 3px rgba(0,0,0,.3);
            box-shadow:0 0 0 2px transparent,0 3px 3px rgba(0,0,0,.3);
        }
        .ios-toggle:checked + .barcode_checkbox:after{
            /*content:attr(data-on);*/
            left:60px;
            width:36px;
        }             
        .trans_contact_checkbox, .payment_contact_checkbox, .barcode_checkbox{
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
        .trans_contact_checkbox:before, .payment_contact_checkbox:before, .barcode_checkbox:before{
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
        .trans_contact_checkbox:after, .payment_contact_checkbox:after, .barcode_checkbox:after{
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
            background-color: var(--form-background-color);
        }
        .scroll-track::-webkit-scrollbar-thumb{
            background-color: var(--form-background-color)!important;
            border: 2px solid var(--form-background-color)!important;    
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
            background-color: var(--form-background-color)!important;
            border: 2px solid var(--form-background-color)!important;
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
        .scroll_product_tab{
            height:480px!important;
            background-color: var(--form-background-color)!important;
        }
        .scroll_product_tab > li > a{    
            color:var(--form-font-color)!important;      
        } 
        .scroll_product_tab > li.active > a{    
            color:var(--form-font-color-2)!important;      
        }                
        .scroll-order-product{
            height:480px!important;
        }
        .scroll-order-item{
            /* height:340px!important; */
            /* height:294px!important;         */
            height:270px!important;   
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
        .barcode_label{
            position: relative;
            top:-22px;
            left:54px;
        }
        /* .div_trans > div:nth-child(1){
            border-top:8px solid var(--form-background-color);
            border-left:8px solid var(--form-background-color);
            border-right:8px solid var(--form-background-color);
            border-bottom:8px solid var(--form-background-color);                                    
        }
        .div_trans > div:nth-child(2){
            border-top:8px solid var(--form-background-color);
            border-left:8px solid var(--form-background-color);
            border-right:8px solid var(--form-background-color);
            border-bottom:8px solid var(--form-background-color);                                    
        } */
        .div_order > div:nth-child(2) form > div:nth-child(1), .div_order > div:nth-child(2) form > div:nth-child(1) .form-label, .div_order > div:nth-child(2) form > div:nth-child(1) b{
            /* background-color: var(--form-background-color);        */
            color:var(--form-font-color);
            font-weight: 700;
        }        
        .div_order > div:nth-child(2) form > div:nth-child(3), .div_order > div:nth-child(2) form > div:nth-child(3) .form-label, .div_order > div:nth-child(2) form > div:nth-child(3) b{
            /* background-color: var(--form-background-color);             */
            color:var(--form-font-color);
        }        
        .div_order > div:nth-child(2) form > div:nth-child(3) button{
            border:1px solid var(--form-font-color);
        }

        .div_trans > div:nth-child(1) > div:nth-child(1) .grid-body, .div_trans > div:nth-child(1) > div:nth-child(1) .form-label, .div_trans > div:nth-child(1) > div:nth-child(1) b{
            /* background-color: var(--form-background-color)!important;        */
            color:var(--form-font-color);
            font-weight: 700;
        }        
        .div_trans > div:nth-child(1) > div:nth-child(3), .div_trans > div:nth-child(1) > div:nth-child(3) .form-label, .div_trans > div:nth-child(1) > div:nth-child(3) b{
            background-color: var(--form-background-color);            
            color:var(--form-font-color);
            font-weight: 700;
        }        
        .div_trans > div:nth-child(1) > div:nth-child(3) button{
            border:1px solid var(--form-font-color);
        }      

        #payment_choice_cash, #payment_choice_transfer, #payment_choice_edc, #payment_choice_qris, #payment_choice_free, #payment_choice_down_payment{
            background-color: var(--form-background-color)!important;       
            color:var(--form-font-color);
        } 
        #payment_choice_cash, #payment_choice_transfer, #payment_choice_transfer .form-label, #payment_choice_edc, #payment_choice_edc .form-label, #payment_choice_qris, #payment_choice_free, #payment_choice_down_payment{
            color:var(--form-font-color);
        }                               
        /* .group-plus-minus > button:nth-child(1){
            float:left;
        }
        .group-plus-minus > button:nth-child(2){
            float:left;
            border-radius: 0px;
            height: 44px;
            width: 42px;    
        }
        .group-plus-minus > button:nth-child(3){
            float:left;
        }    
        .btn_save_trans_item_plus_minus{
            border-radius: 0px;
            padding:12px 14px;
        } */
        .btn-read-payment{
            background-color:#ecf0f2;
        }
        .btn-read-payment:hover, .btn-read-payment:active, .btn-read-payment:focus{
            background-color:#c7d6e9;
        }
        .btn-room-click:hover, .btn-room-click:active, .btn-room-click:focus{
            background-color:#444444!important;
            color:white!important;
        }  
        .btn-payment-item{
            border: 1px solid red;
        }
        .btn_payment_method{
            margin-top:10px;
            margin-bottom:10px;
            padding-top:10px;
            padding-bottom:10px;
        }
        .btn_payment_method:hover, .btn_payment_method.active{
            background-color:var(--form-background-color);
        }        
        .btn_payment_method:hover b, .btn_payment_method.active b{
            color:var(--form-font-color);
        }
        /* .div_number_choice > {

        } */
        .btn_number_choice{
            margin-bottom:2px;
        }
        .btn_number_choice > div{
            background-color:var(--form-background-color);
            padding:10px 2px;
            cursor:pointer;
            height: 60px;
        }
        .btn_number_choice > div:hover{
            background-color:red;
        }        
        .btn_number_choice > div > p{
            color:var(--form-font-color);
            text-align: center;
        }        
    /* Tab */
        .tab-content > .active{
            padding:14px;   
        }
        .nav > li > a:hover, .nav > li > a:focus {
            color: var(--form-font-color-hover)!important;
        }
        #product-tab{
            padding-left:0px;
            padding-right:0px;
        }
        .product_tab_detail_item{
            position: relative;
            padding-left:4px;
            padding-right: 4px;
            margin-bottom:4px;
        }
        .product-tab-color-template{
            background-color:var(--form-background-color-2);
            border-top: 1px solid var(--form-background-color-2);
            border-left:1px solid var(--form-background-color-2);
            border-right:1px solid var(--form-background-color-2);
        }
        .product_tab_detail_item:hover div:nth-child(1){
            background-color:var(--form-background-color);
            border-top: 1px solid var(--form-background-color);
            border-left:1px solid var(--form-background-color);
            border-right:1px solid var(--form-background-color);
            border-radius: 20px 20px 0px 0px;            
        } 
        .product_tab_detail_item:hover div:nth-child(2){
            background-color: var(--form-background-color);
            border-top: 1px solid var(--form-background-color)!important;     
            border-left:1px solid var(--form-background-color);
            border-right:1px solid var(--form-background-color); 
            border-bottom:1px solid var(--form-background-color);        
            border-radius: 0px 0px 20px 20px;                                            
        } 
        .product_tab_detail_item:hover div:nth-child(2) > p{
            color:var(--form-font-color)!important;
        }     
        .product_tab_detail_item div:nth-child(1){
            padding:0;
            cursor:pointer;
            padding-top:4px;
            border-radius: 20px 20px 0px 0px;               
        }
            .product_tab_detail_item div:nth-child(1) > img{
                width:162px;
                height: 162px;
                margin:0 auto;
            }  
            .product_tab_detail_item div:nth-child(1) > img:hover{
                position: relative;
                top:1px;
            }        
        .product_tab_detail_item div:nth-child(2){
            position:relative;    
            cursor: pointer;
            padding:0;
            height: 72px;
            /*padding-bottom: 8px;*/
            font-weight:800;
            color:var(--form-font-color);
            border-bottom:1px var(--form-background-color);  
            border-radius: 0px 0px 20px 20px;                 
        }  
            .product_tab_detail_item div:nth-child(2) > p:nth-child(1){
                text-align:center;
                font-size:12px;
                font-weight:500;
                color:var(--form-font-color);
                position:absolute;
                width:100%;
                top:8px;
            }    
            .product_tab_detail_item div:nth-child(2) > p:nth-child(2){
                text-align:center;
                font-size:12px;
                margin-top: 16px;
                font-weight: 700;
                color:var(--form-font-color);
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

        /* Room */            
        #div_room{
            color:#545353;
        }
        #div_room > div > div:nth-child(1) > h5{
            color:var(--form-font-color);
            margin-top:5px!important;
            margin-bottom: 5px!important;
        }        
        .div_room_detail{
            padding-left: 0px;
            padding-right: 0px;
        }
        .div_room_detail > div > div{
            padding-left: 0px;
            padding-right: 0px;
        } 
        .div_room_detail:hover, .div_room_detail:focus, .div_room_detail:active, .div_room_detail:visited{
            background-color: var(--form-background-color-hover)!important;
            color: var(--form-font-color)!important;
        }                      
        .div_room_detail:hover > div, .div_room_detail:focus > div, .div_room_detail:active > div, .div_room_detail:visited > div{
            color: var(--form-font-color)!important;
        }         
        /* .btn_product_tab:active, .btn_product_tab:focus,.btn_product_tab:hover{
            color:var(--form-background-color)!important;
        } */
        /* #tab3 .grid-body{
            background-color: var(--form-background-color);
        }
        #tab3 label, #tab3 .dataTables_info, #tab3 .paginate_button, #tab3 th, #tab3 h5 > b{
            color: var(--form-font-color);
        }
        #tab3 th{
            background-color: var(--form-background-color-2);
        }         */
    /* Table */
        #table_trans_item > thead > tr > th {
            padding-top: 4px;
            padding-bottom: 4px;
            background-color:var(--form-background-color-hover);
            color:var(--form-font-color);
        }
        #table_trans_item > tbody > tr > td:nth-child(1){
            padding-left: 0px!important;
            padding-right: 0px!important;
            padding-top: 2px!important;
            padding-bottom: 0px!important;
        }
        #table_trans_item > tbody > tr > td:nth-child(1) > button{
            height: 80px;
            border-radius: 0px;
        }
        #table_trans_item > tbody > tr > td:nth-child(3){
            padding-left: 0px!important;
            padding-right: 0px!important;
            padding-top: 12px!important;
            padding-bottom: 0px!important;
        }     
        .dataTables_info, .dataTables_paginate, .paginate_button{
            color:var(--form-font-color)!important;
        }
    /* Button */
        .btn-mini{
            font-size:12px;
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
        .product_tab_detail_item div:nth-child(1) > img{
            width:auto;
            height: auto;
            margin:0 auto;
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
        .product_tab_detail_item div:nth-child(1) > img{
            width:auto;
            height: auto;
            margin:0 auto;
        }      
        .btn_payment_method img{
            width:auto!important;
            height:auto!important;
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
        .product_tab_detail_item div:nth-child(1) > img{
            width:auto;
            height: auto;
            margin:0 auto;
        }       
    }

    /* Landscape phones and portrait tablets */
    @media (max-width: 767px) {
        /* .table-responsive{
            overflow-x: unset;
        } */
        .product_tab_detail_item div:nth-child(1) > img{
            width:auto;
            height: auto;
            margin:0 auto;
        }          
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
        .prs-15{
            padding-left: 15px!important;
            padding-right: 15px!important;    
        }
        .prl-2{
            padding-left: 2.5px!important;
        }
        .prr-2{
            padding-right: 2.5px!important;
        }    

        .product_tab_detail_item div:nth-child(1) > img{
            width:auto;
            height: auto;
            margin:0 auto;
        }     
        .btn_save_trans_item_plus_minus {
            padding: 3px 8px;
        }               
        .group-plus-minus > button{
            float: left;
            border-radius: 3px;
            height: 27px;
            width: 32px;
        }            
        .btn_payment_method img{
            width:auto!important;
            height:auto!important;
        }              
    } 

</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <ul class="nav nav-tabs" role="tablist" style="display:inline;">            
            <li class="active" style="">
                <a href="#tab2" role="tab" class="btn-tab-2" data-toggle="tab" aria-expanded="true">
                <span class="fas fa-plus-square"></span> Form <?php echo $order_alias; ?></a>
            </li> 
            <li class="" style="">
                <a href="#tab1" role="tab" class="btn-tab-1" data-toggle="tab" aria-expanded="false"  style="cursor:pointer;">
                <span class="fas fa-calendar-alt"></span> Data</a>
            </li>     
            <li class="" style="display:none;">
                <a href="#tab3" role="tab" class="btn-tab-3" data-toggle="tab" aria-expanded="true">
                <span class="fas fa-plus-square"></span> Checkout <?php echo $trans_alias; ?></a>
            </li>                                                                                             
        </ul>
        <div class="tab-content" style="border: 8px solid var(--form-background-color);">
            <div class="tab-pane" id="tab1"> <!-- Data -->
                <div class="row div_mobile">
                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                        <div class="col-md-12 col-xs-12 col-sm-12" style="margin-bottom:4px;">
                            <div class="col-md-4 col-sm-4 col-xs-4 padding-remove-left">
                                <div class="pull-left">
                                    <div class="btn-group"> 
                                        <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"> 
                                            <i class="fas fa-print"></i>&nbsp;&nbsp;Cetak&nbsp;&nbsp;<span class="fa fa-angle-down"></span> 
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" class="btn_print_all_trans" data-request="trans_recap" data-format="html">A4 Rekap</a></li>
                                            <!-- <li><a href="#" class="btn_print_all_payment" data-request="trans_detail" data-format="html">A4 Rinci</a></li> -->
                                            <!-- <li><a href="#" class="btn_print_all_payment" data-request="trans_recap" data-format="receipt">Struk Rekap</a></li> -->
                                            <li><a href="#" class="btn_print_all_trans" data-request="trans_detail" data-format="receipt">Struk Rinci</a></li>                                            
                                        </ul>
                                    </div>                                    
                                </div>
                            </div>  
                            <div class="col-md-4 col-sm-4 col-xs-4 padding-remove-side">
                                <div class="col-md-12 col-xs-12 col-sm-12" style="padding-left: 0;padding-right:0;margin-top:0px;">
                                    <h5 style="text-align:center;margin-top:0px;font-size:13px;"><b style="color:#444444;">Riwayat<br><?php echo $trans_alias; ?></b></h5>
                                </div>
                            </div>    
                            <div class="col-md-4 col-sm-4 col-xs-4 padding-remove-right">
                                <div class="pull-right">
                                    <button style="background-color:var(--form-background-color-hover);" class="btn btn_new_trans btn-primary" type="button">
                                        <i class="fas fa-plus"></i>
                                        New <?php echo $order_alias; ?>
                                    </button>                                    
                                </div>
                            </div>                                                 
                        </div>
                    </div>
                </div>                
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                            <div class="grid simple">
                                <div class="grid-body" style="background-color:var(--form-background-color);">
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12 col-sm-12" style="padding-top:8px;">
                                            <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side prs-5">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 padding-remove-side">
                                                    <label class="form-label" style="margin-bottom:0px;color:var(--form-font-color);">Periode</label>
                                                    <div id="filter_trans_date" data-start="<?php echo $first_date;?>" data-end="<?php echo $end_date;?>" class="filter-daterangepicker" style="padding-top:5px;padding-bottom:4px;height:27px;">
                                                        <span></span> 
                                                        &nbsp;&nbsp;&nbsp;<i class="fas fa-caret-down" style="position: absolute;right: 14px;top: 28px;"></i>
                                                    </div>
                                                </div>                                                    
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 form-group padding-remove-right prs-0">
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                        <label class="form-label" style="color:var(--form-font-color);">Cabang</label>
                                                        <select id="filter_branch" name="filter_branch" class="form-control">
                                                            <option value="0">Semua</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side">
                                                <!--
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 form-group padding-remove-right prs-0">
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                        <label class="form-label" style="color:var(--form-font-color);">Metode Bayar</label>
                                                        <select id="filter_trans_type_paid" name="filter_trans_type_paid" class="form-control">
                                                            <option value="0">Semua</option>
                                                            <?php 
                                                            // foreach($type_paid as $v){
                                                            //     echo '<option value="'.$v['paid_id'].'">'.$v['paid_name'].'</option>';
                                                            // }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>    
                                                -->                                                    
                                                <div class="col-lg-9 col-md-9 col-xs-6 col-sm-6 form-group padding-remove-right prs-0">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group padding-remove-side">
                                                        <label class="form-label" style="color:var(--form-font-color);">Cari</label>
                                                        <input id="filter_trans_search" name="filter_trans_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                                                    </div>
                                                </div>                                 
                                                <div class="col-lg-3 col-md-3 col-xs-6 col-sm-6 form-group prs-0 prs-0 prs-5">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group padding-remove-side">                                        
                                                        <label class="form-label" style="color:var(--form-font-color);">Tampil</label>
                                                        <select id="filter_trans_length" name="filter_trans_length" class="form-control">
                                                            <option value="10">10 Baris</option>
                                                            <option value="25">25 Baris</option>
                                                            <option value="50">50 Baris</option>
                                                            <option value="100">100 Baris</option>
                                                        </select>
                                                    </div>
                                                </div>   
                                            </div>                
                                        </div>  
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table id="table_trans" class="table table-bordered" style="width:100%;">
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
            <div class="tab-pane active" id="tab2"> <!-- Form -->
                <div class="row div_mobile">
                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                        <div class="col-md-12 col-xs-12 col-sm-12" style="margin-bottom:4px;">
                            <div class="col-md-4 col-sm-4 col-xs-4 padding-remove-right">
                                <div class="pull-left">
                                    <button style="background-color:var(--form-background-color-hover);" class="btn btn_back_order btn-primary" type="button">
                                        <i class="fas fa-arrow-left"></i>
                                        Kembali
                                    </button>
                                </div>
                            </div>   
                            <div class="col-md-4 col-sm-4 col-xs-4 padding-remove-side">
                                <div class="col-md-12 col-xs-12 col-sm-12" style="padding-left: 0;padding-right:0;margin-top:0px;">
                                    <h5 style="text-align:center;font-size:large;"><b style="color:#444444;" id="trans_ref_label"><?php echo $order_alias;?></b></h5>
                                </div>
                            </div>                       
                            <div class="col-md-4 col-sm-4 col-xs-4 padding-remove-left">
                                <div class="div_btn_cart col-md-12 col-sm-12 col-xs-12 padding-remove-side">                            
                                    <div class="pull-right">
                                        <button style="background-color:var(--form-background-color-hover);" class="btn btn_cart_order btn-primary" type="button">
                                            <i class="fas fa-cart"></i>
                                            <span class="trans_product_count_span hide">0</span> 
                                            <i class="fas fa-shopping-basket"></i> Rp. <span class="trans_product_total_span">0</span> 
                                        </button>
                                    </div>
                                </div>                                
                            </div>                           
                        </div>           
                    </div>
                </div>                   
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 div_order">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                            <div class="grid simple">
                                <div class="grid-body" style="padding:0px;">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="background-color: var(--form-background-color);">
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="margin-top:5px;">
                                            <div class="col-md-6 col-sm-6 col-xs-6 padding-remove-side">
                                                <h5 style="margin-top:8px!important;margin-bottom:0px!important;color:var(--form-font-color);">
                                                    <b>
                                                        <i class="fas fa-list"></i> Daftar <?php echo $product_alias; ?>
                                                    </b>
                                                </h5>
                                            </div>
                                        </div>
                                        <div id="product-search" class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="margin-top:5px;padding-left:5px;padding-right:5px;">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 padding-remove-side">
                                                <div class="form-group">
                                                    <label class="form-label" style="color:#ffffff;">Cabang</label>
                                                    <div class="radio radio-success">
                                                            <?php 
                                                            foreach($branch as $i => $v){
                                                                $c = '';
                                                                if($i==0){
                                                                    $c = 'checked';
                                                                }
                                                            ?>
                                                            <input id="branch_<?php echo $v['branch_id']; ?>" type="radio" name="trans_branch_id" value="<?php echo $v['branch_id']; ?>" <?php echo $c; ?>><label style="color:#ffffff;" for="branch_<?php echo $v['branch_id']; ?>"><?php echo $v['branch_name']; ?></label>
                                                        <?php 
                                                        } 
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-7" style="padding-left:4px;padding-right:4px;">
                                                <div class="form-group">    
                                                    <input id="search_product_tab" name="search_product_tab" type="text" class="form-control" placeholder="Cari [Kode <?php echo $product_alias; ?> / Nama / Barcode]">
                                                </div>
                                            </div>   
                                            <!--
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 padding-remove-side">
                                                <div class="form-group">
                                                    <div class="toggles">
                                                        <input type="checkbox" name="checkbox" id="barcode_checkbox_flag" class="ios-toggle"/>
                                                        <label class="barcode_checkbox" data-flag="1"></label>	
                                                    </div>
                                                    <label class="form-label barcode_label" style="color:var(--form-font-color);">Barcode Mode</label>
                                                </div>
                                            </div> 
                                            -->                                                                                             
                                        </div>                                  
                                        <div id="product-tab" class="col-md-12 col-sm-12 col-sm-12 col-xs-12 tabbable tabs-left" style="border-bottom: 8px solid var(--form-background-color);">
                                            <ul class="nav nav-tabs scroll-nav scroll_product_tab" id="style-5" role="tablist" style="border-right:0!important;padding:0px 4px;margin:0px;">
                                                <li class="active"><a href="#" class="btn_product_tab" data-id="0" role="" data-toggle="tab" style="padding: 8px!important;">Semua</a></li>
                                                <?php 
                                                foreach($product_category as $y => $yy): 
                                                    $class = ''; 
                                                ?>
                                                <li class="<?php echo $class; ?>"><a href="#" class="btn_product_tab" data-id="<?php echo $yy['category_id']; ?>" role="" data-toggle="tab" style="padding: 8px!important;"><?php echo $yy['category_name']; ?></a></li>                                      
                                                <?php endforeach; ?>
                                            </ul>
                                            <div class="tab-content" style="margin-bottom: 0px!important">
                                                <div class="tab-pane active" style="padding-top:0px!important;padding-bottom:0px!important;">
                                                    <div class="row">                                                   
                                                        <div id="product_tab_detail" class="col-md-12 col-xs-12 col-sm-12 scroll-track scroll-order-product">
                                                            <?php 
                                                            foreach($products as $p){
                                                                $product_price = '-'; 
                                                                if($p['product_price_sell'] > 0){
                                                                    $product_price = 'Rp. '.number_format($p['product_price_sell']);
                                                                }
                                                                if(empty($p['product_image'])){
                                                                    $pimg = site_url('upload/noimage.png');
                                                                }else{
                                                                    $pimg = site_url($p['product_image']);    
                                                                }
                                                            ?>
                                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6 btn_save_trans_item product_tab_detail_item" 
                                                                data-product-id="<?php echo $p['product_id'];?>" data-product-code="<?php echo $p['product_code'];?>" data-product-name="<?php echo $p['product_name'];?>" data-product-type="<?php echo $p['product_type'];?>" 
                                                                data-product-qty="1" data-product-unit="<?php echo $p['product_unit'];?>" data-product-price="<?php echo $p['product_price_sell'];?>">
                                                                    <div class="col-md-12 col-sm-12 product-tab-color-template" style="">
                                                                        <img src="<?php echo $pimg;?>" class="img-responsive" style="margin-top:20px;">
                                                                    </div>
                                                                    <div class="col-md-12 col-sm-12 product-tab-color-template">
                                                                        <p class="product-name" style=""><?php echo $p['product_name'];?></p>
                                                                        <p class="product-price" style=""><?php echo $product_price;?></p>
                                                                    </div>
                                                                </div>                 
                                                            <?php } ?>                                                                
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0" style="margin-top: 10px;"> 
                                        <button class="btn btn_cart_order_2 btn-primary" type="button" style="width: 100%;">
                                            <i class="fas fa-shopping-basket"></i>                                 
                                            <?php echo $payment_alias; ?>
                                        </button>   
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
            <div class="tab-pane" id="tab3"> <!-- Form -->
                <div class="row div_mobile">
                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                        <div class="col-md-12 col-xs-12 col-sm-12" style="margin-bottom:4px;">
                            <div class="col-md-4 col-sm-4 col-xs-4 padding-remove-right">
                                <div class="pull-left">
                                    <button style="background-color:var(--form-background-color-hover);" class="btn btn_back_trans btn-primary" type="button">
                                        <i class="fas fa-arrow-left"></i>
                                        Kembali
                                    </button>
                                </div>
                            </div>   
                            <div class="col-md-4 col-sm-4 col-xs-4 padding-remove-side">
                                <div class="col-md-12 col-xs-12 col-sm-12" style="padding-left: 0;padding-right:0;margin-top:0px;">
                                    <h5 style="text-align:center;font-size:large;"><b style="color:#444444;" id="trans_ref_label"><?php echo $payment_alias;?></b></h5>
                                </div>
                            </div>                       
                            <div class="col-md-4 col-sm-4 col-xs-4 padding-remove-left">
                                <div class="pull-right">                                  
                                </div>                             
                            </div>                           
                        </div>
                    </div>
                </div>                   
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 div_trans">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-remove-right prs-0">
                            <div class="grid simple">
                                <div class="grid-body" style="padding-left:0;padding-right:0;padding-top:0;">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="background-color: var(--form-background-color);">
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="margin-top:5px;">
                                            <div class="col-md-6 col-sm-6 col-xs-6 padding-remove-side">
                                                <h5 style="margin-top:8px!important;margin-bottom:0px!important;padding-left:0px;color:var(--form-font-color);">
                                                    <b>
                                                        <i class="fas fa-shopping-basket"></i> <?php echo $trans_alias; ?> Detail
                                                    </b>
                                                </h5>                                                
                                            </div>
                                        </div>                                
                                        <form id="form_trans" name="form_trans" method="" action="">
                                            <div class="col-md-12 col-xs-12 padding-remove-side prs-0">
                                                <div class="col-md-12 col-sm-12 col-xs-12" style="padding-top:8px;padding-bottom:8px;">
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                                            <div class="col-md-3 col-sm-3 col-xs-12 padding-remove-left prs-0">
                                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Tanggal</label>
                                                                        <div class="input-append success date col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                                                                            <input name="trans_date" id="trans_date" type="text" class="form-control input-sm" readonly="true" value="">
                                                                        </div>
                                                                    </div>                   
                                                                </div>   
                                                            </div>                                        
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 prs-0">                                                   
                                                                <div class="col-md-4 col-sm-6 col-xs-6 padding-remove-side prs-0">
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 prs-0 prs-5">
                                                                        <div class="form-group">                        
                                                                            <label class="form-label"><?php echo $ref_alias;?></label>
                                                                            <select id="trans_ref_id" name="trans_ref_id" class="form-control">
                                                                                <option value="0">-</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>   
                                                                </div>
                                                                <div class="col-md-4 col-xs-6 col-sm-6 padding-remove-side">
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-left">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Nama <?php echo $contact_1_alias; ?></label>
                                                                            <input id="trans_contact_name" name="trans_contact_name" type="text" value="" class="form-control" style="cursor:pointer;"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 col-xs-6 col-sm-6 padding-remove-side">
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-left">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Telepon <?php echo $contact_1_alias; ?></label>
                                                                            <input id="trans_contact_phone" name="trans_contact_phone" type="text" value="" class="form-control" style="cursor:pointer;"/>
                                                                        </div>
                                                                    </div>
                                                                </div>                                                                
                                                                <!--         
                                                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 padding-remove-side prs-0">
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                        <div class="form-group">                        
                                                                            <label class="form-label"><?php echo $contact_2_alias; ?> *(trans_sales_id)</label>
                                                                            <select id="trans_sales_id" name="trans_sales_id" class="form-control">
                                                                                <option value="0">Pilih</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>   
                                                                </div>
                                                                -->                                                     
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 prs-0">
                                                                <!--
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 padding-remove-side">
                                                                    <div class="form-group">
                                                                        <label class="form-label"><?php #echo $contact_1_alias; ?>?</label>
                                                                        <div class="toggles">
                                                                            <input type="checkbox" name="checkbox" id="trans_contact_checkbox_flag" class="ios-toggle"/>
                                                                            <label class="trans_contact_checkbox" data-flag="0"></label>	
                                                                        </div>
                                                                    </div>
                                                                </div>                                                                
                                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-7 padding-remove-side prs-0 prs-5">
                                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side prs-0">
                                                                        <div class="form-group">                        
                                                                            <label class="form-label">Pilih <?php echo $contact_1_alias; ?> *</label>
                                                                            <select id="trans_contact_id" name="trans_contact_id" class="form-control" disabled readonly>
                                                                            </select>               
                                                                        </div>                   
                                                                    </div>   
                                                                </div>  
                                                                -->
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-xs-12 col-sm-12 scroll-track scroll-order-item" style="padding: 0px;margin-top: 0;margin-left:0px;margin-right:0px;"> 
                                                <table id="table_trans_item" class="table table-bordered" style="background-color: var(--form-font-color);">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th><?php echo $product_alias; ?></th> 
                                                            <th style="text-align:center;">Qty</th>
                                                            <th style="text-align:right;">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-12 col-xs-12 padding-remove-side" style="padding-top:8px;padding-bottom:8px;">
                                                <div class="col-md-12 col-xs-12 col-sm-12">
                                                    <div class="col-md-4 col-xs-6 col-sm-12 padding-remove-left hidden-xs hidden-sm">
                                                        &nbsp;
                                                    </div>
                                                    <div class="col-md-4 col-xs-6 col-sm-6 padding-remove-side prs-0">
                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="margin-bottom:4px;">
                                                            <div class="form-group">
                                                                <label class="form-label">Jumlah <?php echo $product_alias; ?></label>
                                                                <input id="trans_product_count" name="trans_product_count" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-6 col-sm-6 padding-remove-right prs-0">
                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                            <div class="form-group">
                                                                <label class="form-label padding-remove-left">Total (Rp)</label>
                                                                <input id="trans_total" name="trans_total" type="text" value="0" class="form-control" style="text-align:right;" readonly='true'/>
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
                                        </form>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0" style="margin-top: 10px;"> 
                                        <button id="btn_save_trans" class="btn btn-primary" type="button" style="width: 100%;margin-bottom:8px;">
                                            <i class="fas fa-save"></i>
                                            Simpan
                                        </button> 
                                        <button id="btn_reset_trans" class="btn btn-danger" type="reset" style="width: 100%;">
                                            <i class="fas fa-ban"></i>
                                            Kosongkan
                                        </button>
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

<!-- Modal -->
<div class="modal fade" id="modal-contact" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="form-master" name="form-master" method="" action="">         
                <div class="modal-header">
                    <h4 style="text-align:left;"><i class="fas fa-user-plus"></i> <?php echo $contact_1_alias; ?> Baru</h4>
                    <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal"
                        style="position:relative;top:-38px;float:right;">
                        <i class="fas fa-times"></i>                                 
                        Tutup
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-xs-12"> 
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
                    <button id="btn_save_contact" onClick="" class="btn btn-primary" type="button" style="width:45%;">
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
<div class="modal fade" id="modal-scanner" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="scanner-title" style="text-align:left;">Scanner Input</h4>
                <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal"
                    style="position:relative;top:-38px;float:right;">
                    <i class="fas fa-times"></i>                                 
                    Tutup
                </button>                
			</div>
			<div class="modal-body" style="background: white!important;">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="div_btn_cart col-md-6 col-sm-6 col-xs-6 padding-remove-side">                            
                            <div class="pull-left">
                                <button style="background-color:var(--form-background-color-hover);" class="btn btn_cart_order btn-primary" type="button">
                                    <i class="fas fa-cart"></i>
                                    <span class="trans_product_count_span">0</span> <i class="fas fa-shopping-basket"></i> Rp. <span class="trans_product_total_span">0</span> 
                                </button>
                            </div>
                        </div>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<center><div id="scanner-div" style="width: 100%;" class="text-center"></div></center>
					</div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="col-md-12 col-xs-12 col-sm-12 scroll-track scroll-order-item" style="padding: 0px;margin-top: 0;margin-left:0px;margin-right:0px;"> 
                            <table id="table_trans_item_modal" class="table table-bordered" data-limit-start="0" data-limit-end="10">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo $product_alias; ?></th> 
                                        <th style="text-align:center;">Qty</th>
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
	</div>
</div>
<div class="modal fade" id="modal-trans-print" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f3f5f6;">
                <h4 style="color:black;text-align:left;"><i class="fas fa-check-double"></i><b id="modal-print-title">Berhasil Tersimpan</b></h4>
                <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal" style="position:relative;top:-38px;float:right;">
                    <i class="fas fa-times"></i>                                 
                    Tutup
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- <div class="col-md-12 col-xs-12 padding-remove-side">
                        <p class="text-center">
                            <i class="fas fa-print fa-4x"></i>
                        </p>
                        <p class="text-center"><b>LUNAS</b></p>
                    </div> -->
                    <div class="col-md-12 col-xs-12 padding-remove-side">
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
                                <td>Total</td>
                                <td class="modal-print-trans-total">:</td>
                            </tr>
                            <tr>
                                <td>Metode Pembayaran</td>
                                <td class="modal-print-payment-method">:</td>
                            </tr>                                             
                            <tr>
                                <td><?php echo $contact_1_alias?></td>
                                <td>:<input id="modal-print-contact-name" name="modal-contact-name" value="" style="border:none!important;"></td>
                            </tr>         
                            <tr>
                                <td>Telepon</td>
                                <td>:<input id="modal-print-contact-phone" name="modal-contact-phone" value="" style="border:none!important;"></td>
                            </tr>                                                              
                        </table>
                    </div>           
                </div>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" class="btn_send_whatsapp btn btn-primary" 
                    data-id="0" data-number="" data-date="" data-total="" data-contact-id="" data-contact-name="" data-contact-phone="" style="width:45%;">
					<span class="fab fa-whatsapp white"></span> Kirim WhatsApp
				</button>
				<button type="button" class="btn_print_trans btn btn-success" 
                    data-id="0" data-number="0" style="width:45%;">
					<span class="fas fa-file-invoice white"></span> Cetak Struk
				</button>                  
            </div>
        </div>
    </div>
</div>