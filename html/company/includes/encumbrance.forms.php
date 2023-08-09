<?php
$formAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $formAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>

<div class="modal fade" tabindex="-1" role="dialog" id="add-encumbrance-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">&times;</span>
                    </button>
                    Add Encumbrance Details
                </div>
            </div>
            <div class="modal-body padding">
                <form action="<?php echo $formAction ?>" method="POST">
                    <input type="hidden" name="search_id" value="<?php echo $ref_number ?>">
                    <input type="hidden" id="added_by" name="added_by" value="<?php echo $_SESSION['MM_full_names'] . "(" . $_SESSION['MM_USR_EMAIL'] . ")"; ?>" />
                    <input type="hidden" name="addencumbrance" value="addencumbrance">

                    <label class="col-sm-4">Amount Secured</label>
                    <div class="col-sm-7"><span id="sprytextfield7">
                            <input type=" text" id="data" name="amount_secured" class="form-control" />
                            <span class="textfieldRequiredMsg">*</span></span></div>
                    <br />
                    <div class="space-10"></div>
                    <label class="col-sm-4">Date</label>
                    <div class="col-sm-7"><span id="sprytextfield7">
                            <input type=" text" id="date" name="date" class="form-control" />
                            <span class="textfieldRequiredMsg">*</span></span></div>
                    <br />
                    <div class="space-10"></div>
                    <label class="col-sm-4">Description</label>
                    <div class="col-sm-7 form-group"><span id="sprytextfield7">
                            <textarea name="description" id="" data-provide="markdown" data-iconlibrary="fa" rows="10" class="form-control"></textarea>
                            <span class="textfieldRequiredMsg">*</span>
                        </span></div>
                    <br />
                    <div class="space-10"></div>

                    <div class="col-md-9">
                        <button onClick="submit" type="submit" value="submit" type="button" class="btn btn-info">
                            <i class="ace-icon fa fa-check bigger-110"></i>
                            Save
                        </button>

                           
                        <button class="btn" type="reset">
                            <i class="ace-icon fa fa-undo bigger-110"></i>
                            Reset
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer no-margin-top">
                <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
                    <i class="ace-icon fa fa-times"></i>
                    Close
                </button>


            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-addreject" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">&times;</span>
                    </button>
                    Add a Rejection Note
                </div>
            </div>
            <div class="modal-body padding">
                <form action="<?php echo $formAction ?>" method="POST">
                    <input type="hidden" name="reject" value="true">
                    <textarea name="review_notes" id="review_notes" class="form-control" rows="10"></textarea>

                    <div class="space-10"></div>


                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button onClick="submit" type="submit" value="submit" type="button" class="btn btn-info">
                                <!--<button onClick="submit" class="btn btn-info" type="button">-->
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Save
                            </button>

                               
                            <button class="btn" type="reset">
                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                Reset </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>