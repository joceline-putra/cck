
<div id="div-form-trans" style="display:none;" class="main-card mb-3 card">
    <div class="card-header-tab card-header">
        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="header-icon pe-7s-note2 mr-3 text-muted opacity-6"> </i>
            Form
        </div>   
        <div class="btn-actions-pane-right actions-icon-btn">
            <button class="btn-close mt-2 btn btn-warning" type="button" style="color:white;"><span class="fas fa-times"></span> Close</button>            
        </div>
    </div>    
    <div class="card-body">
        <!-- <h5 class="card-title">Grid Rows</h5> -->
        <form id="form-master" class="">
            <div class="form-row">
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label class="">Link</label>
                        <input name="link_name" id="link_name" placeholder="Paste URL here" type="text" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label class="">Label (optional)</label>
                        <input name="link_label" id="link_label" placeholder="Label" type="text" class="form-control">
                    </div>
                </div>                
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label class="">Result</label>
                        <input name="link_url" id="link_url" placeholder="Result Short URL" type="text" class="form-control" readonly>
                    </div>
                </div>
            </div>
            <!-- <button id="btn-new2" class="mt-2 btn btn-primary" type="button"><span class="fas fa-plus"></span> Add New</button> -->
            <button id="btn-save" class="mt-2 btn btn-success" type="button"><span class="fas fa-save"></span> Save</button>
            <!-- <button id="btn-update" class="mt-2 btn btn-info" type="button"><span class="fas fa-edit"></span> Update</button>             -->
            <!-- <button class="btn-cancel mt-2 btn btn-warning" type="button" style="color:white;"><span class="fas fa-check"></span> Cancel</button> -->
            <button class="btn-close mt-2 btn btn-warning" type="button" style="color:white;"><span class="fas fa-ban"></span> Close</button>
            <!-- <button id="btn-delete" class="mt-2 btn btn-danger" type="button"><span class="fas fa-trash"></span> Delete</button>                                     -->
        </form>
    </div>
</div>
<!--
<div class="main-card mb-3 card">
    <div class="card-body">
        <h5 class="card-title">Grid</h5>
        <form class="">
            <div class="position-relative row form-group">
                <label for="exampleEmail" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input name="email" id="exampleEmail" placeholder="with a placeholder" type="email" class="form-control">
                </div>
            </div>
            <div class="position-relative row form-group">
                <label for="examplePassword" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                    <input name="password" id="examplePassword" placeholder="password placeholder" type="password" class="form-control">
                </div>
            </div>
            <div class="position-relative row form-group">
                <label for="exampleSelect" class="col-sm-2 col-form-label">Select</label>
                <div class="col-sm-10">
                    <select name="select" id="exampleSelect" class="form-control"></select>
                </div>
            </div>
            <div class="position-relative row form-group">
                <label for="exampleSelectMulti" class="col-sm-2 col-form-label">Select Multiple</label>
                <div class="col-sm-10">
                    <select multiple="" name="selectMulti" id="exampleSelectMulti" class="form-control"></select>
                </div>
            </div>
            <div class="position-relative row form-group">
                <label for="exampleText" class="col-sm-2 col-form-label">Text Area</label>
                <div class="col-sm-10">
                    <textarea name="text" id="exampleText" class="form-control"></textarea>
                </div>
            </div>
            <div class="position-relative row form-group">
                <label for="exampleFile" class="col-sm-2 col-form-label">File</label>
                <div class="col-sm-10">
                    <input name="file" id="exampleFile" type="file" class="form-control-file">
                    <small class="form-text text-muted">This is some placeholder block-level help text
                        for the above input. It's a bit lighter and easily wraps to a new line.
                    </small>
                </div>
            </div>
            <fieldset class="position-relative row form-group">
                <legend class="col-form-label col-sm-2">Radio Buttons</legend>
                <div class="col-sm-10">
                    <div class="position-relative form-check">
                        <label class="form-check-label">
                            <input name="radio2" type="radio" class="form-check-input">
                            Option one is this and that—be sure to include why it's great
                        </label>
                    </div>
                    <div class="position-relative form-check">
                        <label class="form-check-label">
                            <input name="radio2" type="radio" class="form-check-input"> 
                            Option two can be something else and selecting it will deselect option one
                        </label>
                    </div>
                    <div class="position-relative form-check disabled">
                        <label class="form-check-label">
                            <input name="radio2" disabled="" type="radio" class="form-check-input"> Option three is disabled
                        </label>
                    </div>
                </div>
            </fieldset>
            <div class="position-relative row form-group">
                <label for="checkbox2" class="col-sm-2 col-form-label">Checkbox</label>
                <div class="col-sm-10">
                    <div class="position-relative form-check">
                        <label class="form-check-label">
                            <input id="checkbox2" type="checkbox" class="form-check-input"> Check me out
                        </label>
                    </div>
                </div>
            </div>
            <div class="position-relative row form-check">
                <div class="col-sm-10 offset-sm-2">
                    <button class="btn btn-secondary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
-->
<div class="card mb-3">
    <div class="card-header-tab card-header">
        <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="header-icon lnr-laptop-phone mr-3 text-muted opacity-6"> </i>
            Data Link
        </div>        
        <div class="btn-actions-pane-right actions-icon-btn">
            <div class="btn-actions-pane-right actions-icon-btn">
                <button id="btn-new" type="button" class="mt-2 btn btn-primary"><span class="fas fa-plus"></span> Add New</button>
            </div>
        </div>
    </div>
    <div class="card-body"> 
        <div class="form-row col-md-12" style="padding-left:0px;padding-right:0px;">
            <div class="col-md-3">
                <div class="position-relative form-group">
                    <label for="exampleZip" class="">Filter Label</label>
                    <select name="filter_link_label" id="filter_link_label" class="form-control">
                        <option value="0">Pilih</option>
                    </select>
                </div>
            </div>
            <div class="col-md-7">
                <div class="position-relative form-group">                
                    <label for="exampleCity" class="">Search</label>
                    <input name="filter_search" id="filter_search" type="text" class="form-control" placeholder="Type anything here...">
                </div>
            </div>   
            <div class="col-md-2">
                <div class="position-relative form-group">                
                    <label for="exampleZip" class="">Rows</label>
                    <select name="filter_length" id="filter_length" class="form-control">
                        <option value="10">10 Baris</option>
                        <option value="25">25 Baris</option>
                        <option value="50">50 Baris</option>
                        <option value="100">100 Baris</option>
                    </select>
                </div>
            </div>             
        </div>
        <div class="col-md-12 table-responsive" style="padding-left:0px;padding-right:0px;">                
            <table style="width: 100%;" id="table-data" class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Short</th>
                        <th>Label</th>                        
                        <th>Created On</th>                        
                        <th>Hits</th>
                        <th>Redirect To</th>                        
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <!-- <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Office</th>
                        <th>Age</th>
                        <th>Start date</th>
                        <th>Salary</th>
                    </tr>
                </tfoot> -->
            </table>
        </div>
    </div>
</div>