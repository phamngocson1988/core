{use class='yii\widgets\ActiveForm' type='block'}
{$this->registerJsFile('vendor/assets/global/plugins/jquery-repeater/jquery.repeater.js', ['depends' => '\backend\assets\AppAsset'])}
{$this->registerJsFile('vendor/assets/pages/scripts/form-repeater.min.js', ['depends' => '\backend\assets\AppAsset'])}
<div class="portlet box red">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>Repeating Forms </div>
        <div class="tools">
            <a href="javascript:;" class="collapse"> </a>
            <a href="#portlet-config" data-toggle="modal" class="config"> </a>
            <a href="javascript:;" class="reload"> </a>
            <a href="javascript:;" class="remove"> </a>
        </div>
    </div>
    <div class="portlet-body form">
        <div class="form-body">
            <div class="form-group">
                <form action="#" class="mt-repeater form-horizontal">
                    <h3 class="mt-repeater-title">Human Resource Management</h3>
                    <div data-repeater-list="group-a">
                        <div data-repeater-item class="mt-repeater-item">
                            <!-- jQuery Repeater Container -->
                            <div class="mt-repeater-input">
                                <label class="control-label">Name</label>
                                <br/>
                                <input type="text" name="text-input" class="form-control" value="John Smith" /> </div>
                            <div class="mt-repeater-input">
                                <label class="control-label">Joined Date</label>
                                <br/>
                                <input class="input-group form-control form-control-inline date date-picker" size="16" type="text" value="01/08/2016" name="date-input" data-date-format="dd/mm/yyyy" /> </div>
                            <div class="mt-repeater-input mt-repeater-textarea">
                                <label class="control-label">Job Description</label>
                                <br/>
                                <textarea name="textarea-input" class="form-control" rows="3">This role is to follow up with all meetings and ensure that each operational process flow moves accordingly in a timely manner.</textarea>
                            </div>
                            <div class="mt-repeater-input mt-radio-inline">
                                <label class="control-label">Tier</label>
                                <br/>
                                <label class="mt-radio">
                                    <input type="radio" name="optionsRadios" id="optionsRadios25" value="junior" checked=""> Junior
                                    <span></span>
                                </label>
                                <label class="mt-radio">
                                    <input type="radio" name="optionsRadios" id="optionsRadios26" value="senior" checked=""> Senior
                                    <span></span>
                                </label>
                            </div>
                            <div class="mt-repeater-input mt-checkbox-inline">
                                <label class="control-label">Language</label>
                                <br/>
                                <label class="mt-checkbox">
                                    <input type="checkbox" id="inlineCheckbox21" value="option1"> English
                                    <span></span>
                                </label>
                                <label class="mt-checkbox">
                                    <input type="checkbox" id="inlineCheckbox22" value="option2"> French
                                    <span></span>
                                </label>
                            </div>
                            <div class="mt-repeater-input">
                                <label class="control-label">Department</label>
                                <br/>
                                <select name="select-input" class="form-control">
                                    <option value="A" selected>Marketing</option>
                                    <option value="B">Creative</option>
                                    <option value="C">Development</option>
                                </select>
                            </div>
                            <div class="mt-repeater-input">
                                <a href="javascript:;" data-repeater-delete class="btn btn-danger mt-repeater-delete">
                                    <i class="fa fa-close"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:;" data-repeater-create class="btn btn-success mt-repeater-add">
                        <i class="fa fa-plus"></i> Add</a>
                </form>
            </div>
        </div>
    </div>
</div>