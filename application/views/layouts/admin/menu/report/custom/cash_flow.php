<style>
	.scroll { 
		margin-top:4px;
		margin-bottom: 8px;
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
	.select2-selection--single{
		background-color: #ecf0f2!important;
		height: 30px!important;
		border:0px!important;
	}
	.select2-selection__placeholder{
		color:#5e5e5e!important;
	}
	.select2-dropdown--below{
		background-color: #ecf0f2!important;
		border:0px!important;
	}  
	.monthselect, .yearselect{
		height: 22px!important;
	}
	#filter_date{
		color:#5e5e5e!important;
	}
    /* Large desktops and laptops */
    /* @media (min-width: 1200px) {
        .table-responsive{
            overflow-x: unset;
        }
    }

    /* Landscape tablets and medium desktops */
    @media (min-width: 992px) and (max-width: 1199px) {
        .table-responsive{
            overflow-x: unset;
        }
    }

    /* Portrait tablets and small desktops */
    @media (min-width: 768px) and (max-width: 991px) {
        .table-responsive{
            overflow-x: unset;
        }
    }

    /* Landscape phones and portrait tablets */
    @media (max-width: 767px) {
        .table-responsive{
            overflow-x: unset;
        }
    }

    /* Portrait phones and smaller */
    @media (max-width: 480px) {
        .table-responsive{
            overflow-x: unset;
        }
        .div-datatable{
            margin-top:36px;
        }        
    }          */
</style>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<?php include '_navigation.php';?>
		<div class="tab-content">
			<div class="tab-pane active" id="tab1">
				<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
					<div class="grid simple">
						<div class="grid-body">
							<div class="row">    
								<div class="col-md-12 col-xs-12 col-sm-12">
									<div class="col-md-12 col-xs-12 col-sm-12"">
										<h5><b><?php echo $title;?></b></h5>
									</div>
									<div class="col-md-12 col-xs-12 col-sm-12">																			
										<div class="col-lg-4 col-md-12 col-xs-12 col-sm-12 padding-remove-left" style="margin-top:4px;">
											<label class="form-label">Periode</label>
											<div id="filter_date" data-start="<?php echo $first_date;?>" data-end="<?php echo $end_date;?>" class="filter-daterangepicker" style="background: #ecf0f2;padding-top:6px;padding-bottom:6px;border:0px;">
												<i class="fas fa-calendar-alt"></i>&nbsp;
												<span></span> 
												&nbsp;&nbsp;&nbsp;<i class="fas fa-caret-down" style="position: absolute;right: 24px;top: 7px;"></i>
											</div>
										</div>
										<div class="col-md-3 col-xs-12 col-sm-12 padding-remove-left" style="margin-top:4px;">
											<label class="form-label">Cabang</label>
											<select id="filter_branch" name="filter_branch" class="form-control" style="height:31px!important;border:0px!important;background-color:#ecf0f2;">
												<option value="0">Pilih</option>
											</select>
										</div>		
										<div class="col-md-3 col-xs-12 col-sm-12 padding-remove-left" style="margin-top:4px;">
											<label class="form-label">User</label>
											<select id="filter_user" name="filter_user" class="form-control" style="height:31px!important;border:0px!important;background-color:#ecf0f2;">
												<option value="0">Pilih</option>
											</select>
										</div>	
										<div class="col-lg-2 col-md-12 col-xs-12 col-sm-12 padding-remove-right" style="margin-top:4px;">	
											<div class="pull-right">                     
												<label class="form-label">Action</label> 
                                                <button id="btn-print-all" data-action="1" data-format="html" data-request="report_finance_cash_flow" class="btn btn-print-all btn-success btn-small" type="button" style="display: inline;width:100%;">
                                                    <i class="fas fa-print"></i>
                                                    Print
                                                </button>
                                            </div>
 
										</div>                 
									</div>
								</div>
								<div class="col-md-12 col-xs-12 col-sm-12" style="padding-top:24px;">
                                    <div class="table-responsive">
                                        <table id="table-data" class="table table-bordered" style="width:100%;">     
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
