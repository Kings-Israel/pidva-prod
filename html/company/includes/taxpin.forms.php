<div class="modal fade" id="add-taxpayer-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">&times;</span>
                    </button>
                    Add TaxPayer Details
                </div>
            </div>
            <div class="modal-body padding">
                <form action="<?php echo $formAction ?>" method="POST">
                    <input type="text" value="add_taxpayer" name="add_taxpayer" hidden>

                    <label class="col-sm-4">PIN</label>
                    <div class="col-sm-7"><span id="sprytextfield1">
                            <input type="text" id="pin" name="pin" class="form-control" />
                            <span class="textfieldRequiredMsg">*</span></span></div>
                    <br />

                    <div class="space-10"></div>
                    <label class="col-sm-4">TaxPayer Name</label>
                    <div class="col-sm-7"><span id="sprytextfield2">
                            <input type="text" id="taxpayer_name" name="taxpayer_name" class="form-control" />
                            <span class="textfieldRequiredMsg">*</span></span></div>
                    <br />

                    <div class="space-10"></div>

                    <label class="col-sm-4">PIN Status</label>
                    <div class="col-sm-7"><span id="sprytextfield3">
                            <select class="form-control" name="pin_status" id="pin_status" required>
                                <option disabled selected value="">Select PIN Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <span class="textfieldRequiredMsg">*</span></span></div>
                    <br />

                    <div class="space-10"></div>

                    <label class="col-sm-4">iTax Status</label>
                    <div class="col-sm-7"><span id="sprytextfield4">
                            <select class="form-control" name="itax_status" id="itax_status" required>
                                <option disabled selected value="">Select iTax Status</option>
                                <option value="registered">Registered</option>
                                <option value="not registered">Not Registered</option>
                            </select>
                            <span class="textfieldRequiredMsg">*</span></span></div>
                    <br />

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


<div class="modal fade" id="add-obligation-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">&times;</span>
                    </button>
                    Add Obligation Details
                </div>
            </div>
            <div class="modal-body padding">
                <form action="<?php echo $formAction ?>" method="POST">
                    <input type="text" value="add_obligation" name="add_obligation" hidden>

                    <label class="col-sm-4">PIN</label>
                    <div class="col-sm-7"><span id="sprytextfield1">
                            <input type="text" disabled readonly class="form-control" value="<?php echo $taxpin_object['pin'] ?>" />
                            <span class="textfieldRequiredMsg">*</span></span></div>
                    <br />

                    <div class="space-10"></div>

                    <label class="col-sm-4">Taxpayer</label>
                    <div class="col-sm-7"><span id="sprytextfield-1">
                            <input type="text" disabled readonly class="form-control" value="<?php echo $taxpin_object['taxpayer_name'] ?>" />
                            <span class="textfieldRequiredMsg">*</span></span></div>
                    <br />

                    <div class="space-10"></div>
                    <label class="col-sm-4">Obligation Name</label>
                    <div class="col-sm-7"><span id="sprytextfield2">
                            <input type="text" id="obligation_name" name="obligation_name" class="form-control" />
                            <span class="textfieldRequiredMsg">*</span></span></div>
                    <br />

                    <div class="space-10"></div>

                    <label class="col-sm-4">Current Status</label>
                    <div class="col-sm-7"><span id="sprytextfield3">
                            <select class="form-control" name="current_status" id="current_status" required>
                                <option disabled selected value="">Select Status</option>
                                <option value="registered">Registered</option>
                                <option value="not registered">Not Registered</option>
                            </select>
                            <span class="textfieldRequiredMsg">*</span></span></div>
                    <br />

                    <div class="space-10"></div>

                    <label class="col-sm-4">Effective From</label>
                    <div class="col-sm-7"><span id="sprytextfield4">
                            <input type="text" id="effective_from" name="effective_from" class="form-control" />
                            <span class="textfieldRequiredMsg">*</span></span></div>
                    <br />

                    <div class="space-10"></div>

                    <label class="col-sm-4">Effective To</label>
                    <div class="col-sm-7"><span id="sprytextfield4">
                            <input type="text" id="effective_to" name="effective_to" class="form-control" />
                            <span class="textfieldRequiredMsg">*</span></span></div>
                    <br />
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


<div id="modal-addreject" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">&times;</span></button>
                    Add Reject Reason
                </div>
            </div>

            <div class="modal-body padding">
                <form method="POST" action="<?php echo $formAction; ?>" class="form-horizontal" name="addreject">
                    <input type="hidden" id="verified_date" name="verified_date" value="<?php echo date('d-m-Y H:m:s'); ?>" />

                    <div class="space-10"></div>
                    <label class="col-sm-4">Reject Comments</label>
                    <div class="col-sm-12">
                        <div class="widget-box widget-color-green">
                            <div class="widget-header widget-header-small"></div>

                            <div class="widget-body">
                                <div class="widget-main no-padding">
                                    <textarea name="review_notes" data-provide="markdown" data-iconlibrary="fa" rows="10" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />
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
                                Reset
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="addreject" value="addreject">

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