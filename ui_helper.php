<?php
function get_msg()
{
    $CI = &get_instance();

    if ($CI->session->flashdata('danger')) {
        echo $CI->session->flashdata('danger');
    }
    if ($CI->session->flashdata('success')) {
        echo $CI->session->flashdata('success');
    }
    if ($CI->session->flashdata('info')) {
        echo $CI->session->flashdata('info');
    }
    if ($CI->session->flashdata('warning')) {
        echo $CI->session->flashdata('warning');
    }
}


//set flashdata message
function set_msg($key, $value)
{
    $CI = &get_instance();
    $msg = 'Plz set message';
    $title = '';

    if ($key == 'danger') {
        $title = 'Error!';
    }

    if ($key == 'success') {
        $title = 'Success!';
    }


    if ($key == 'warning') {
        $title = 'Warning!';
    }

    if ($key == 'info') {
        $title = 'Info!';
    }

    $msg = '';
    $msg .= '<div class="bg-light alert alert-' . $key . ' border-left-4 border-' . $key . '" style="border-left: 5px solid">';
    $msg .=   '<button data-dismiss="alert" class="close">';
    $msg .=   '×';
    $msg .=   '</button>';
    $msg .=   '<strong>' . $title . '</strong> ' . $value . '<a class="alert-link" href="#"></a>';
    $msg .= '</div>';

    $CI->session->set_flashdata($key, $msg);
}


function _form_searchDropdown($title, $name, $options, $required = '')
{
    $required = $required ? "*" : " ";
    $str = '<div class="form-group">';
    $str .= '<label class="control-label">';
    $str .= $title . '<span style="color:red" class="symbol">' . $required . '</span>';
    $str .= '</label>';
    $str .= form_dropdown($name, $options, 'large', 'class="form-control search-select"');
    $str .= '</div>';
    echo $str;
}

function _form_dropdown($title, $name, $options, $required = '', $selected = '', $md = 12, $js = '')
{
    $required = $required ? "*" : " ";
    $str  = '<div class="col-md-' . $md . '">';
    $str .= '<div class="form-group">';
    $str .= '<label class="control-label">';
    $str .= $title . '<span style="color:red" class="symbol">' . $required . '</span>';
    $str .= '</label>';
    $str .= form_dropdown($name, $options, $selected, ' id="' . $name . '" class="form-control" onchange="' . $js . '"');
    $str .= '<span style="color:red" id="div' . $name . '"></span>';
    $str .= '</div>';
    $str .= '</div>';
    echo $str;
}

function drop_down($name, $id = '', $options = [], $js = '', $selected = '')
{
    if (empty($id)) $id = $name;
    $str = '<select name="' . $name . '" id="' . $id . '" class="form-control search-select">';
    $str .= '<option value="">--Selecte One---</option>';
    foreach ($options as $key => $option)
        $str .= '<option value="' . $key . '" ' . ($selected == $key ? "selected" : " ") . '>' . $option . '</option>';
    $str .= '</select>';
    echo $str;
}

function inputBox($inputData, $required, $md, $title, $name, $value, $js)
{

    $required = $required ? "*" : " ";
    $str  = '<div class="col-md-' . $md . '">';
    $str .= '<div class="form-group">';
    $str .= '<label for="form-field-select-1">';
    $str .= $title . '<span style="color:red" class="symbol">' . $required . '</span>';
    $str .= '</label>';
    $str .= form_input($inputData, $value, $js);
    $str .= '<span style="color:red" id="div' . $name . '"></span>';
    $str .= '</div>';
    $str .= '</div>';
    echo $str;
}

function formTextarea($inputData, $required, $md, $title, $name, $value, $js)
{

    $required = $required ? "*" : " ";
    $str  = '<div class="col-md-' . $md . '">';
    $str .= '<div class="form-group">';
    $str .= '<label for="form-field-select-1">';
    $str .= $title . '<span style="color:red" class="symbol">' . $required . '</span>';
    $str .= '</label>';
    $str .= form_textarea($inputData, $value, $js);
    $str .= '<span style="color:red" id="div' . $name . '"></span>';
    $str .= '</div>';
    $str .= '</div>';
    echo $str;
}

function _form_textarea($attr, $required = '', $value = '', $md = 12, $js = [])
{
    if (!isset($attr['class']))
        $attr['class'] = 'form-control';

    if (!isset($attr['title']))
        $title = 'title';
    else $title = $attr['title'];

    if (!isset($attr['name']))
        $name = 'name';
    else $name = $attr['name'];
    unset($attr['title']);
    return formTextarea($attr, $required, $md, $title, $name, $value, $js);
}

function _form_input($attr, $required = '', $value = '', $md = 12, $js = [])
{
    if (!isset($attr['class']))
        $attr['class'] = 'form-control';

    if (!isset($attr['title']))
        $title = 'title';
    else $title = $attr['title'];

    if (!isset($attr['name'])) {
        $name = 'name';
        $attr['id'] = 'name';
    } else {
        $name = $attr['name'];
        $attr['id'] = $attr['name'];
    }


    unset($attr['title']);
    return inputBox($attr, $required, $md, $title, $name, $value, $js);
}


function _form_checkbox($title, $name, $required = '', $value = '', $md = 12, $js = [])
{
    return  '<div class="col-md-6">
    <div class="form-check form-check-inline">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="' . $name . '">
                    <label class="custom-control-label" for="' . $name . '">' . $title . '</label>
                </div>
                </div>
                </div>';
}

function example_hint($str)
{
    return '<i class="help-block"><i class="fa fa-info-circle"></i>' . $str . ' </i>';
}

function in_currency()
{
    return '¥';
}




function menuCreate($menus, $isSS = '', $isParent = '')
{
    $str = '';
    $CI = &get_instance();

    $selected = $CI->uri->segment(1) . '/' . $CI->uri->segment(2);
    $session = $CI->session->userdata['logged_in'];
    //$userMenu = $session['menu'];
    //print_r($menus);die;
    foreach ($menus as $key => $value) {

        // if (!isset($userMenu[$key]) && $session['_id'] != '1' &&  $isSS != 1 && $value['name'] != 'Logout' && $value['name'] != 'Dashboard') {
        //     continue;
        // } else if ($isSS ==  1 && $session['_id'] != '1' && $value['name'] != 'Logout' && $value['name'] != 'Dashboard') {
        //     $parentMenu = json_decode($userMenu[$isParent], true);
        //     if (!isset($parentMenu[$key])) {
        //         continue;
        //     }
        // }
        $tooltip = isset($value['tooltip']) ? getTooltip($value['tooltip'],'right') : '';
        if (isset($value['submenu'])) {

            $icon = isset($value['icon']) ? $value['icon'] : 'fa-database';
            $str .= '<li '.$tooltip.'>';
            $str .= '<a href="javascript:void(0)">
            <i class="sidebar-item-icon fa ' . $icon . '"></i>
            <span class="nav-label">';
            $sname = isset($value['name']) ? $value['name'] : '--';
            $str .= ucfirst($sname) . '';
            $str .= '<i class="fa fa-angle-right arrow"></i></span></a>';
            $str .= ' <ul class="nav-2-level collapse">';
            $str .= menuCreate($value['submenu'], 1, $key);
            $str .= '</ul>';
            $str .= '</li>';
        } else {
            $link = isset($value['link']) ? ($value['link']) : 'admin/welcome';
            $name = isset($value['name']) ? $value['name'] : '#';

            $icon = isset($value['icon']) ? $value['icon'] : 'fa-cube';
            

            $i = $isSS ? '<i class="sidebar-item-icon fa fa-circle-o"></i>' : '<i class="sidebar-item-icon fa ' . $icon . '"></i>';
            $slct = $selected ==  $link ? 'class="active"' : '';
            $str .= '<li  ' . $slct . '><a  href="' . base_url($link) . '">' . $i . '<span class="nav-label" '.$tooltip.'>' . ucfirst($name) . '</span></a></li>';
        }
    }
    return $str;
}


function formTitle($first, $last = '')
{
    $str = '';
    $str .= '<div class="ibox-title">' . $first . ' <span class="text-bold">' . $last . '</span></div>';
    return $str;
}


function submit($title = 'Submit', $md = 3, $offset = 0)
{
    return '<div class="form-actions">                  
                        <div class="text-right">
                            <button type="submit" class="btn btn-info">'.$title.'</button>
                            <button type="button" onclick="window.history.back();" class="btn btn-dark">Cancel</button>
                        </div>                 
                </div>';
}

function pre($data = '')
{
    if (empty($data)) $data = $_REQUEST;
    echo "<pre>";
    print_r($data);
    echo "<pre>";
    die;
}

function setFieldTitle($text)
{
    return ucfirst($text);
}

function setFieldValue($text)
{
    return ucfirst($text);
}

function viewFormDetails($title, $value, $data = '')
{
    $str = '';
    $str = '<div class="row">';
    $str .= '<div class="form-group">';
    $str .= '<label class="col-sm-3">';
    $str .= setFieldTitle($title);
    $str .= '</label>';
    $str .= '<div class="col-sm-9">';
    if (!empty($data))
        $str .= setFieldValue(getValue($data, $value));
    else
        $str .= setFieldValue($value);

    $str .= '</div>';
    $str .= '</div>';
    $str .= '</div><br>';
    return $str;
}
function viewTextValue($name)
{
    return '<p class="form-control-static display-value" data-display="' . $name . '">-</p>';
}

function onlyNumber()
{
    return '<span class="error" style="color: Red; display: none">* Input digits (0 - 9)</span>';
}

function navTab($tabs = [], $tatActive = '')
{

    $i = 1;
    $html =  '<ul class="nav nav-tabs" role="tablist">';
    foreach ($tabs as $tab) {
        if ($i == 1 && empty($tatActive)) {
            $html .=    '<li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#' . ($tab['id']) . '" role="tab"><span class="hidden-sm-up"><i class="' . $tab['icon'] . '"></i></span> <span class="hidden-xs-down">' . ucfirst($tab['name']) . '</span></a> </li>';
        } else {
            if ($i == $tatActive) {
                $html .=    '<li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#' . ($tab['id']) . '" role="tab"><span class="hidden-sm-up"><i class="' . $tab['icon'] . '"></i></span> <span class="hidden-xs-down">' . ucfirst($tab['name']) . '</span></a> </li>';
            } else {

                $html .=    '<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#' . ($tab['id']) . '" role="tab"><span class="hidden-sm-up"><i class="' . $tab['icon'] . '"></i></span> <span class="hidden-xs-down">' . ucfirst($tab['name']) . '</span></a> </li>';
            }
        }
        $i++;
    }

    $html .= '</ul><span id="only_for_required" class="only_for_required position-absolute text-left" style="display:none"> (*) Denotes Required Field</span>';
    echo $html;
}

function getTable($id = 'example')
{
    return '<table id="' . $id . '" class="table table-striped table-bordered table-hover"></table>';
}

function activateTab($tabNumber, $tab = 1)
{

    if ($tabNumber == $tab) {
        return 'active';
    }
}


function getFilters($data, $activeTab)
{
    $str = '';
    $str .=                 _form_input(['From Date' => 'from_date', 'name' => 'to_date', 'class' => 'form-control form-control-sm'], '1', '', 2);
    $str .=                 _form_input(['To Date' => 'to_date', 'name' => 'to_date', 'class' => 'form-control form-control-sm'], '1', '', 2);
    if (isset($data['letters']))
        $str .=                 _form_dropdown('Category', 'letter_categoryID', $data['letters'], '', '', 2, 'getLetterCategory(this)');
    if (isset($data['subLetters']))
        $str .=                 _form_dropdown('Sub Category', 'sub_categoryID', $data['subLetters'], '', '', 2);
    if (isset($data['unit']))
        $str .=                 _form_dropdown('Section', 'unitID', $data['unit'], '', '', 2, 'get_employee(this)');
    if (isset($data['employee']))
        $str .=                 _form_dropdown('Employee', 'empID', $data['employee'], '', '', 2);

    if (isset($data['source']))
        $str .=                 _form_dropdown('Source', 'sourceID', $data['source'], '', '', 2);

    if (isset($data['letterType']))
        $str .=                 _form_dropdown('Letter Type', 'letterType', $data['letterType'], '', '', 2);

    $str .=            '<div class="col-md-12 text-right mb-2"><div class="btn-group"><button class="btn btn-info waves-effect waves-light border-radius-0" type="button" onclick=$("#group-filters").toggle()><span class="btn-label"><i class="ti-filter"></i></span> Filter</button><button class=" border-radius-0 btn btn-info waves-effect waves-light" type="button" onclick=$("select").val(""),$("input").val("");><span class="btn-label"><i class="ti-reload"></i></span> Reset</button></div></div>';
    $str .=     '';

    return $str;
}

function get_file_upload($name, $type = '', $md = 12)
{
    $id = time();
    return '<div class="custom-file">
    <input type="file" ' . $type . ' name="' . $name . '" class="custom-file-input" id="customFile' . $id . '" onchange="readURL(this)">
    <label class="custom-file-label" for="customFile' . $id . '"></label>
</div>';
}

function ibox_head($data)
{
    $url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'javascript: history.back()';
    $title = 'Button';
    if (isset($data['button'])) {
        if ($data['button']['url']) {
            $url = base_url($data['button']['url']);
        }
        if ($data['button']['title']) {
            $title = $data['button']['title'];
        }
    }
    $headTitle =  isset($data['title']) ? $data['title'] : "Title";
    $icon =  isset($data['icon']) ? $data['icon'] : "fa-circle-a";

    $i = ' <i class="fa fa-plus"></i> ';
    if ($title == 'Back')
        $i = ' <i class="fa fa-arrow-left"></i> ';

    $str =  '<div class="ibox-head "><div class="ibox-title"><i class="fa ' . $icon . '"></i> ' . $headTitle . '</div>';

    $str .= '<div class="ibox-tools">';

    if (isset($data['table_right_title'])) {
        $str .=   $data['table_right_title'];
    }

    if (isset($data['button'])) {
        $str .= '<a class="btn btn-info text-light" href="' . $url . '">  ' . $i . $title . ' </i></a>';
    }
    
    $str .=  '</div>';

    $str .= '</div>';
    return $str;
}



function getTooltip($title = 'Title', $align = 'top')
{
    return 'data-toggle="tooltip" data-placement="' . $align . '" title="' . $title . '"';
}

function getPopover($title = 'Title')
{
    return 'data-toggle="popover" data-trigger="hover" data-content="' . $title . '"';
}


function top_header($set){
   echo '<table class="table  mb-2 payslip-head">

    <thead>
        <tr class=" logo-sec">
            <th class="border-0 text-left w-25"><img class="img-fluid"
                    src="'.base_url(SITE_LOGO).'"></th>
            <th class="border-0 text-center " colspan="2">
                <p class="h6 text-dark top-header-heading">Government of Nagaland<br>Department of Health and Family
                    Welfare<Br><span class="text-primary">Nagaland Health Project
                        <br>Nagaland: Kohima</span>
                </p>
            </th>
           
        </tr>
        <tr id="second-header">

            <th colspan="5" class="text-center"><span class="dtitle h6 text-uppercase">Pay slip for the month of
                    '.setValue($set, 'month').' '.setValue($set, 'year').' </span>
            </th>
          
        </tr>

<tr>

            <th colspan="3" class="text-left" style="border-bottom:0px"><br>File No.____________________________ </th>
            <th colspan="3" class="text-right" style="border-bottom:0px"><span>Kohima the
                  '.date('d').'<sup>th</sup> '.date('F').' 
                    '.date('Y').'</span></th>
               </tr>
           
    </thead>
</table>';
}