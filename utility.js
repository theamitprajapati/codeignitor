const fStatus = [
    'Pending',
    'Active',
    'Inactive'
];

const vStatus = [
    'Active',
    'Pending',
    'Inactive',
    'Remove',
];

function formatDate(date) {
    var date = new Date(date);
    return moment(date).format('D MMMM  Y');
}
function viewStatus(id) {
    var x = ' - ';
    if (typeof vStatus[id] != 'undefined') {
        x = vStatus[id];
    }
    return x;
}

function addLoader(id) {
    $('#' + id).addClass('ajax-loading');
}
function delLoader(id) {
    $('#' + id).removeClass('ajax-loading');
}


function unitCategory(stID) {

    CateogryUnits = [
        'Facility',
        'Interior/Exterior',
        'General',
        'Direction',
    ];
    stID = stID - 1;
    xCateogryUnits = '';
    if (typeof CateogryUnits[stID] != 'undefined') {
        xCateogryUnits = CateogryUnits[stID];
    }
    return xCateogryUnits;
}

function getStatus(type) {

    //console.log(type);
    $str = '';
    if (type == '1') {
        $str = '<span class="badge badge-success">Active</span>';
    }
    if (type == '9') {
        $str = '<span class="badge badge-danger">Inactive</span>';
    }
    if (type == 'New') {
        $str = '<span class="badge badge-danger">New</span>';
    }
    if (type == 'Forwarded') {
        $str = '<span class="badge badge-success">Forwarded</span>';
    }
    if (type == 'Despatch') {
        $str = '<span class="badge badge-light-warning">Despatch</span>';
    }
    if (type == '2') {
        $str = '<span class="badge badge-warning">Pending</span>';
    }
    if (type == 'Received') {
        $str = '<span class="badge badge-info">Received</span>';
    }
    if (type == 'Close') {
        $str = '<span class="badge badge-info">Close</span>';
    }
    return $str;
}


function enableAction(getUrl = '', setUrl = '', delUrl = '') {

    var action = '';
    var space = ' | '

    if (setUrl != '') {

        action += '<a href="' + base_url(setUrl) + '"><button data-placement="top" data-toggle="tooltip" title="Click for edit"><i class="ti-pencil"></i></button>  </a> &nbsp;';
    }

    if (getUrl != '') {
        if (action == '') {
            space = '';
        }
        action += ' <a href="' + base_url(getUrl) + '"><button data-placement="top" data-toggle="tooltip" title="Click for details"><i class="fa fa-eye"></i></button></a> &nbsp;';
    }

    if (delUrl != '') {
        var evt = "return confirm('Are you sure want to delete?')";
        action += '<a href="' + base_url(delUrl) + '" onclick="' + evt + '"><button data-placement="top" data-toggle="tooltip" title="Click for delete"> <i class="fa fa-trash"></i></button></a>';
    }
    return action;

}

function ajaxRequest(url, data, element) {
    $.get(base_url(url), data, function (result) {
        if (result == 1) {
            $('#div' + element.name).html('This name is already exists');
            $('#' + element.id).val("");
        } else
            $('#div' + element.name).html('');
    });
}

function getCityRecords(e, tbl,selected='') {
    addLoader('cityID');
    $.get(base_url('ajax/getCityRecords'), { stateID: e.value, t: tbl }, function (result) {
delLoader('cityID');
 
        if (result != 0)
            result = JSON.parse(result);

        $('#cityID').empty();
        $('#cityID').append(`<option value=''>--Select One--</option>`);
        if (result.length == 0) {
            $('#divcityID').html('City records is not found');
            delLoader('cityID');
        } else {
            result.forEach(function (row) {
              if(selected == row._id)
                $('#cityID').append(`<option selected="selected" value='${row._id}'>${row.name}</option>`);
                else
                  $('#cityID').append(`<option  value='${row._id}'>${row.name}</option>`);
                
           })
            
            $('#divcityID').html('');
        }

    });
}

function getBankBranchRecords(e,selected) {
    addLoader('bank_branchID');
    $.get(base_url('ajax/getBankBranchRecords'), { bankID: e.value }, function (result) {
       delLoader('bank_branchID');
         
        if (result != 0)
            result = JSON.parse(result);

        $('#bank_branchID').empty();

        $('#bank_branchID').append(`<option value=''>--Select One--</option>`);

        if (result == 0) {
            $('#divbank_branchID').html('Branch records not found !');
            
        } else {
            result.forEach(function (row) {
              if(selected == row._id) 
				  $('#bank_branchID').append(`<option  selected="selected" value='${row._id}'>${row.name}</option>`);
			  else
                $('#bank_branchID').append(`<option value='${row._id}'>${row.name}</option>`);
            })
            $('#divbank_branchID').html('');
            delLoader('bank_branchID');
        }


    });
}




function getAjax(e, tbl) {
    return ajaxRequest('ajax/getAjax', { name: e.value, t: tbl }, e);
}



function get_employee(e) {
    addLoader('emp_code');
    $.get(base_url('ajax/get_employee'), { emp_code: e.value }, function (result) {
        if (result)
            result = JSON.parse(result);

        $('#full_name').empty();
        $('#designationID').empty();
        $('#sectionID').empty();
        $('#divemp_code').html('');

        if (result == 2) {
            $('#divemp_code').html('Salary structur already created!');
        }

        if (result != 0) {
            $('#full_name').val(result.full_name);
            $('#designationID').val(result.designation);
            $('#sectionID').val(result.section);
        }

        if (result == 0) {
            $('#divemp_code').html('Invalid employee code');
        }

        delLoader('emp_code');
    });
}

function getEmployeeDetails(e) {
    $.get(base_url('ajax/getEmployeeDetails'), { empID: e.value }, function (result) {
        result = JSON.parse(result);
        if (result.length == 0) {
            $('#name').empty();
            $('#emp_code').empty();
            $('#divname').html('Employee name is empty');
            $('#name').val("");
            $('#emp_code').val("");
        } else {
            $('#name').empty();
            $('#name').val(result.full_name).attr('readonly', true);
            $('#emp_code').val(result.emp_code).attr('readonly', true);
            $('#divname').html('');
        }
    });
}

function getUsername(e) {
    $.get(base_url('ajax/getUsername'), { username: e.value }, function (result) {
        result = JSON.parse(result);
        if (result != 0) {
            $('#username').val('');
            $('#divusername').html('This username is already exists.');
        } else {
            $('#divusername').html('');
        }
    });
}


function letterStar(id, is) {
    $.get(base_url('ajax/letter_star'), { letterID: id, is: is });
}

function validate() {
    // check if input is bigger than 3
    var value = document.getElementById('titleeee').value;
    if (value.length < 50) {
        return false; // keep form from submitting
    }
    return true;
}


function delete_images(e, id) {
    if (confirm('Are you sure want to delete?')) {
        $(e).parent().parent().remove()
        $.get(base_url('ajax/delete_images'), { id: id });
    }
}


function check_menu_assign(e) {
    $.get(base_url('ajax/check_menu_assign'), { u: e.value }, function (result) {
        if (result != 0) {
            $('#divempID').html('Menu already assigned.');
        } else {
            $('#divempID').html('');
        }
    });
}


$(document).ajaxComplete(function () {
    $('[data-toggle="tooltip"]').tooltip();
});