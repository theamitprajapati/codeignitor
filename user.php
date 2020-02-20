<?php if ($act == 'list') { ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <?php echo ibox_head(['title' => 'User', 'button' => ['title' => 'Add User', 'url' => 'admin/user?act=add']]); ?>
                <div class="ibox-body">
                    <div class="row">
                        <?php echo getTable(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } else { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <?php echo ibox_head(['title' => 'New User', 'button' => ['title' => 'Back', 'url' => '']]); ?>
                <div class="ibox-body">
                    <?php echo form_open_multipart(base_url('admin/user?id=' . $id), 'class="form-horizontal" id="myform"'); ?>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-1">
                            <div class="row">
                                <div class="col-md-6" style="border-right: 1px solid #eee;">
                                    <h5 class="text-info m-b-20 m-t-10"><i class="fa fa-building-o"></i> Office details</h5>
                                    <div class="row">
                                        <?php echo _form_dropdown('Management Unit', 'unitID', $unit, '1', getValue($set, 'unitID'), 6); ?>
                                        <?php echo _form_dropdown('Group', 'groupID', $groups, '1', getValue($set, 'groupID') ? getValue($set, 'groupID') : 3, 6); ?>

                                        <?php echo _form_dropdown('Designation', 'designationID', $roles, '1', getValue($set, 'designationID'), 6); ?>
                                        <?php echo _form_input(['title' => 'Employee Code', 'name' => 'emp_code'], '1', getValue($row, 'emp_code'), 6); ?>
                                    </div>
                                </div>
                                <div class="col-md-6" style="border-right: 1px solid #eee;">
                                    <h5 class="text-info m-b-20 m-t-10"><i class="fa fa-lock"></i> Login details</h5>
                                    <div class="row">
                                        <?php echo _form_input(['title' => 'Username', 'name' => 'username'], '1', getValue($row, 'username') ? getValue($row, 'username') : time(), 6); ?>
                                        <?php echo _form_input(['title' => 'Email', 'name' => 'email'], '', getValue($row, 'email'), 6); ?>
                                        <?php echo _form_input(['title' => 'Password', 'name' => 'password'], '', getValue($row, 'password'), 6); ?>
                                        <?php echo _form_input(['title' => 'Confirm Password', 'name' => 'cpassword'], '', getValue($row, 'cpassword'), 6); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-md-6" style="border-right: 1px solid #eee;">
                                    <h5 class="text-info m-b-20 m-t-10"><i class="fa fa-user"></i> Basic details</h5>
                                    <div class="row">
                                        <?php echo _form_input(['title' => 'Full Name', 'name' => 'full_name'], '1', getValue($row, 'full_name'), 6); ?>
                                        <?php echo _form_input(['title' => 'Mobile', 'name' => 'mobile'], '1', getValue($row, 'mobile'), 6); ?>
                                        <?php echo _form_input(['title' => 'DOB', 'name' => 'dob', 'class' => 'form-control dobDatepicker', 'autocomplete' => 'off'], '1', getValue($row, 'dob') ? getValue($row, 'dob') : date('Y-m-d'), 6); ?>
                                        <?php echo _form_dropdown('Gender', 'genderID', ['1' => 'Male', '2' => 'Female'], '1', getValue($set, 'genderID'), 6); ?>
                                        <?php echo _form_input(['title' => 'Father Name', 'name' => 'father_name'], '', getValue($row, 'father_name'), 6); ?>
                                        <?php echo _form_input(['title' => 'Mother Name', 'name' => 'mother_name'], '', getValue($row, 'mother_name'), 6); ?>
                                    </div>
                                </div>
                                <div class="col-md-6" style="border-right: 1px solid #eee;">
                                    <h5 class="text-info m-b-20 m-t-10"><i class="fa fa-address-card"></i> Address details</h5>
                                    <div class="row">
                                        <?php //echo _form_dropdown('Country', 'countryID', formStatus(1), '1', getValue($set, 'countryID'), 6); 
                                            ?>
                                        <?php echo _form_dropdown('State', 'stateID', $states, '1', getValue($set, 'stateID'), 6, 'getCityRecords(this)'); ?>
                                        <?php echo _form_dropdown('City', 'cityID', ['' => '--Select one--'], '1', getValue($set, 'cityID'), 6); ?>
                                        <?php echo _form_input(['title' => 'Landmark', 'name' => 'landmark'], '', getValue($row, 'landmark')); ?>
                                        <?php echo _form_textarea(['title' => 'Full Address', 'name' => 'full_address', 'rows' => 2, 'col' => 2], '1', getValue($set, 'full_address'), 12); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-md-6" style="border-right: 1px solid #eee;">
                                    <h5 class="text-info m-b-20 m-t-10"><i class="fa fa-bank"></i> Bank details</h5>
                                    <div class="row">
                                        <?php echo _form_dropdown('Bank', 'bankID', $banks, '', getValue($set, 'bankID'), 6,'getBankBranchRecords(this)'); ?>
                                        <?php echo _form_dropdown('Bank', 'bank_branchID',['' => '--Select one--'], '', getValue($set, 'bank_branchID'), 6); ?>
                                        <?php echo _form_input(['title' => 'IFSC', 'name' => 'ifsc'], '', getValue($row, 'ifsc'), 6); ?>
                                        <?php echo _form_input(['title' => 'A/C Holder Name', 'name' => 'ac_name'], '', getValue($row, 'ac_name'), 6); ?>
                                        <?php echo _form_input(['title' => 'A/C Number', 'name' => 'ac_number'], '', getValue($row, 'ac_number'), 6); ?>
                                    </div>
                                </div>
                                <div class="col-md-6" style="border-right: 1px solid #eee;">
                                    <h5 class="text-info m-b-20 m-t-10"><i class="fa fa-clipboard"></i> Documents details</h5>
                                    <div class="row">
                                        <?php echo _form_input(['title' => 'Address Proof', 'name' => 'address_proof', 'type' => 'file'], ''); ?>
                                        <?php echo _form_input(['title' => 'Photo Proof', 'name' => 'photo_proof', 'type' => 'file'], ''); ?>
                                    </div>
                                </div>
                            </div>
                            <?php echo  submit(); ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
<?php } ?>
<!-- Tab panes -->

<script>
    i = 0;
    $(function() {
        drawTable('#example', 'datatable/get_user', [


            {
                "title": 'Full Name',
                "data": "full_name"
            },
            {
                "title": 'Emp code',
                "data": "emp_code"
            },
            {
                "title": 'Mobile',
                "data": 'mobile'
            },
            {
                "title": 'Role',
                "data": 'role'
            },
            {
                "title": 'Unit',
                "data": 'unit'
            },
            {
                "title": 'Status',
                "data": 'statusID'
            },
            {
                "title": 'Date',
                "data": "updated"
            },
            {
                "title": 'Action',
                "data": function(row) {
                    return enableAction('', 'admin/user?act=set&id=' + row._id, 'admin/user?act=del&id=' + row._id);
                }
            },
        ])
    })
</script>

<script>
    $(function() {
        var rules = {
            unitID: 'required',
            groupID: 'required',
            designationID: 'required',
            emp_code: {
                required: true,
                minlength: 4,
                maxlength: 20
            },
            username: {
                required: true,
                minlength: 4,
                maxlength: 20
            },
            full_name: {
                required: true,
                words: true,
                minlength: minNameLength,
                maxlength: maxNameLength
            },
            mobile: {
                required: true,
                digits: true,
                minlength: 6,
                maxlength: 12
            },
            full_address: {               
                minlength: 6,
                maxlength: 50
            },
            father_name: {               
                minlength: 4,
                maxlength: 25
            },
            mother_name: {               
                minlength: 4,
                maxlength: 25
            },
            ifsc: {               
                minlength: 4,
                maxlength: 25
            },ac_number: {               
                minlength: 4,
                maxlength: 25
            },
            stateID: 'required',
            cityID: 'required'
        };
        //setValidator('#myform', rules);
    })
</script>