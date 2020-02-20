<?php

function formStatus()
{
    $CI = &get_instance();
    return $CI->session->userdata('statusRows');
}


function userType($stID = '')
{
    $userType = [
        //'5' => 'Other',
        '1' => 'Admin',
        '2' => 'Section',
        //'3' => 'Section',
        '4' => 'Employee',
    ];

    if ($stID != '') {
        if (isset($userType[$stID])) {
            $userType =  $userType[$stID];
        } else if ($d = array_search($stID, $userType)) {
            return $d;
        } else {
            $userType = '-';
        }
    }

    return $userType;
}




function setValue($data = [], $key)
{
    if (isset($data[$key]))
        return $data[$key];
    return 'N/A';
}

function getMenu($groupID)
{

    $menu = [
        //super admin menu
        '1' => [
            ['name' => 'Dashboard', 'link' => 'super/dashboard', 'icon' => 'fa-dashboard'],
            ['name' => 'Employee', 'link' => 'admin/employee', 'icon' => 'fa-users'],
            [
                'name' => 'Master',
                'icon' => 'fa-database',
                'submenu' =>
                [
                    ['name' => 'Office', 'link' => 'master/office'],
                    ['name' => 'Designation', 'link' => 'master/designation'],
                    ['name' => 'Bank', 'link' => 'master/bank'],
                    ['name' => 'Bank Branch ', 'link' => 'master/bank_branch'],
                    ['name' => 'User ', 'link' => 'master/user'],
                    //  ['name' => 'Menu', 'link' => 'master/menu'],
                    //['name' => 'Deduction', 'link' => 'master/deduction'],
                    ['name' => 'district', 'link' => 'master/district'],
                ]
            ],
        ],
        //admin menu
        '2' => [
            ['name' => 'Dashboard', 'link' => 'admin/dashboard', 'icon' => 'fa-dashboard'],           
            [
                'name' => 'Reports',
                'icon' => 'fa-table',
                'submenu' =>
                [
                   
                    ['name' => 'Salary list', 'link' => 'report/salary'],
                    ['name' => 'Paybill', 'link' => 'report/paybill', 'icon' => 'fa-bar-chart'],
                    ['name' => 'Payslip', 'link' => 'report/payslip', 'icon' => 'fa-calculator'],
                    ['name' => 'Bank List', 'link' => 'report/bank_list', 'icon' => 'fa-building'],
                    ['name' => 'Tax Deduction', 'link' => 'report/tax_deduction'],
                ]
            ],

        ],
        '3' => [],
        '5' => [
            ['name' => 'Dashboard', 'link' => 'user/dashboard', 'icon' => 'fa-dashboard'],  
            [
                'name' => 'Attendance',
                'icon' => 'fa-calendar',
                'submenu' =>
                [
                    ['name' => 'Generate Attendance', 'link' => 'account/attendance', 'icon' => 'fa-calendar-o'],
                    ['name' => 'Update Attendance', 'link' => 'account/generated_attendance_list', 'icon' => 'fa-calendar'],
                ]
            ],         
            [
                'name' => 'Salary',
                'icon' => 'fa-bank',
                'submenu' =>
                [
                    ['name' => 'All Employee', 'link' => 'account/employee', 'icon' => 'fa-users'],
                    ['name' => 'Salary Structure', 'link' => 'account/salary_structure', 'icon' => 'fa-address-book'],
                    ['name' => 'Generate Salary', 'link' => 'account/generate_salary', 'icon' => 'fa-bank'],
                    ['name' => 'Delete Salary', 'link' => 'account/delete_salary', 'icon' => 'fa-trash'],
                              
                ]
            ],            
            [
                'name' => 'Reports',
                'icon' => 'fa-table',
                'submenu' =>
                [
                   
                    ['name' => 'Salary list', 'link' => 'report/salary'],
                    ['name' => 'Paybill', 'link' => 'report/paybill', 'icon' => 'fa-bar-chart'],
                    ['name' => 'Payslip', 'link' => 'report/payslip', 'icon' => 'fa-calculator'],
                    ['name' => 'Bank List', 'link' => 'report/bank_list', 'icon' => 'fa-building'],
                    ['name' => 'Tax Deduction', 'link' => 'report/tax_deduction'],
                    // ['name' => 'EPF Deduction', 'link' => 'report/epf_deduction'],
                   // ['name' => 'All Paybill', 'link' => 'report/all_paybill'],
                    // ['name' => 'Deductions', 'link' => 'report/deduction_list'],
                ]
            ],
        ],
        '6' => [],
        '7' => [],
        //user/account admin menu
        '8' =>[],
        //super user menu
        '9' => [
            ['name' => 'Dashboard', 'link' => 'user/dashboard', 'icon' => 'fa-dashboard'],
            [
                'name' => 'Reports',
                'icon' => 'fa-bank',
                'submenu' =>
                [
                    ['name' => 'Salary', 'link' => 'account/salary'],
                ]
            ],
        ],
    ];
    $m = [];
    if (isset($menu[$groupID])) {
        $m = $menu[$groupID];
    }
    return $m;
}


function getMenuList()
{
    $CI = &get_instance();
    $groupID = $CI->session->userdata['logged_in']['groupID'];
    $menu = getMenu($groupID);
    return $menu;
}


function tell_exe($file)
{
    if (empty($file)) {
        return false;
    }
    $exe = explode('.', $file);
    if (empty($exe)) {
        return false;
    }
    return end($exe);
}


function getUserSection()
{
    $CI = &get_instance();
    $groupID = $CI->session->userdata['logged_in']['groupID'];
    $menu = $CI->common->get_row(TBL_GROUPS, ['_id' => $groupID]);
    if (isset($menu['name']))
        return $menu['name'];
}