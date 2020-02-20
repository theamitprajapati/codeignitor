<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Datatable extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Master_model', 'master');
        $this->load->model('Common_model', 'common');
        $this->cnd = '';
    }

    public function getFilters()
    {
        $f = [];
        if (!empty($this->get('xfilter'))) {
            $filters = array_filters($this->get('xfilter'));
            if (!empty($filters)) {
                foreach ($filters as $key => $fl) {
                    if ($key == 'status' && !empty($fl)) {
                        $fl = $fl == 'Pending' ? '0' : '1';
                    }
                    if (!empty($fl)) {
                        $column = explode('-', $key);
                        $ks = $column[0] . '.' . $column[1];
                        $f[$ks] = $fl;
                    }
                }
            }
            return array_filters($f);
        }
        return '';
    }

    public function jsonEncode($array)
    {

        return json_encode($array);
    }

    public function getInDatatable($data, $recordsFiltered, $recordsTotal)
    {
        return $this->jsonEncode([
            "draw" => $this->get('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ]);
    }

    public function get_unit()
    {
        $select = 't1._id,t1.name,t1.code,t1.statusID,t1.created,t1.updated';
        $table = [
            'from' => TBL_MANAGEMENT_UNIT,
        ];
        echo $this->pagination($table, $select);
    }

    public function get_designation()
    {
        $select = 't1._id,t1.name,t1.order,t1.code,t1.statusID,t1.updated';
        $table = [
            'from' => TBL_DESIGNATION,
        ];
        echo $this->pagination($table, $select);
    }


    public function get_employee()
    {
        $this->db->select('t1._id,t4.name as designation,t3.name as unit,t1.emp_code,t1.full_name,t1.mobile,t1.email,t1.dob,t1.adhar,t1.updated,t2.name as status,t2.code as sCode');
        $this->db->order_by('t1._id', 'desc');
        $this->db->from(TBL_EMPLOYEE . ' as t1');
        $this->db->join(TBL_STATUS . ' as t2', 't1.statusID = t2._id', 'left');
        $this->db->join(TBL_MANAGEMENT_UNIT . ' as t3', 't1.unitID = t3._id', 'left');
        $this->db->join(TBL_DESIGNATION . ' as t4', 't1.designationID = t4._id', 'left');
        $query =  $this->db->where('t1.groupID',GROUP_ID)->get();
        $data = $query->result_array();
        $total = $this->db->count_all_results(TBL_EMPLOYEE);
        $displayRecordTotal = $total;
        $recordTotal = $total;
        echo $this->getInDatatable($data, $displayRecordTotal, $recordTotal);
    }


    public function pagination($table, $select = '*')
    {

        /* Example how to use
       @param  $select = 't1.name,t1.code,t2.name as status,t1.updated';
       @param  $table = [
                          'from' => TBL_BANK,
                          'where' =>['t1.statusID'=>1],
                          'join' => [
                              't2' => ['table' => TBL_STATUS, 'on' => 't1.statusID = t2._id']
                           ]
                        ];
        @param $select  can optional defuatl *               
        */

        $this->db->limit($this->get('length'), $this->get('start'));
        $this->db->select($select);
        if (!isset($table['from'])) return false;

        if (isset($table['where']))
            $this->db->where($table['where']);

        if (isset($table['group']))
            $this->db->group_by($table['group']);

        $filters = $this->getFilters();

        if (!empty($filters)) {
            //$this->cnd = $filters;
            $this->db->like($filters);
        }

        $this->db->order_by('t1._id', 'desc');
        $this->db->from($table['from'] . ' as t1 ');
        if (isset($table['join'])) {
            foreach ($table['join'] as $key => $join) {
                if (!empty($join)) {
                    if (isset($join['on']))
                        $this->db->join($join['table'] . " as $key ", $join['on'], 'left');
                }
            }
        }

        $query =  $this->db->get();
        $data = $query->result_array();
        //echo $this->db->last_query(); die;
        $total = $this->db->count_all_results($table['from']);
        if (isset($table['where']))
          $this->db->where($table['where']);

        $displayRecordTotal = $this->db->count_all_results($table['from']." as t1");;
        $recordTotal = $total;
        return $this->getInDatatable($data, $displayRecordTotal, $recordTotal);
    }
    public function get_bank()
    {
        $select = 't1._id,t1.name,t1.code,t1.statusID,t1.updated';

        $table = [
            'from' => TBL_BANK
        ];
        echo $this->pagination($table, $select);
    }
    public function get_office()
    {
        $select = 't1._id,t1.name,t1.statusID,t1.updated';

        $table = [
            'from' => TBL_OFFICE
        ];
        echo $this->pagination($table, $select);
    }

    public function get_pay_structue()
    {
        $select = 't1._id,t1.name,t1.gross_pay,t1.statusID,t1.updated';

        $table = [
            'from' => TBL_PAYSTRUCTURE
        ];
        echo $this->pagination($table, $select);
    }
    public function get_deduction()
    {
        $select = 't1._id,t1.name,t1.statusID,t1.updated';

        $table = [
            'from' => TBL_DEDUCTION
        ];
        echo $this->pagination($table, $select);
    }

    public function get_bank_branch()
    {
        $select = 't1._id,t1.name,t1.micr,t1.ifsc,t1.statusID,t1.updated,t2.name as bank';

        $table = [
            'from' => TBL_BANK_BRANCH,
            'join' => ['t2' => ['table' => TBL_BANK, 'on' => 't1.parentID = t2._id']]
        ];
        echo $this->pagination($table, $select);
    }
    
    public function get_financial_year()
    {
        $select = 't1._id,t1.name,t1.created,t1.statusID';

        $table = [
            'from' => TBL_YEARS            
        ];
        echo $this->pagination($table, $select);
    }



    public function get_user()
    {

        $select = 't1._id,t1.emp_code,t1.full_name,t1.username,t1.updated,t1.statusID,t1.mobile,t1.email,t3.name as unit,t5.name as role';

        $table = [
            'from' => TBL_EMPLOYEE,
            'where'=>['t1.groupID' => GROUP_ID],
            'join' => [
                't2' => ['table' => TBL_EMPLOYEE_DETAILS, 'on' => 't1._id = t2.empID'],
                't3' => ['table' => TBL_MANAGEMENT_UNIT, 'on' => 't1.unitID = t3._id'],
                't4' => ['table' => TBL_GROUPS, 'on' => 't1.groupID = t4._id'],
                't5' => ['table' => TBL_DESIGNATION, 'on' => 't1.designationID = t5._id'],
            ]
        ];
        echo $this->pagination($table, $select);
    }


    public function get_login_user()
    {
        $select = '
        t1._id,
        t1.full_name,
        t1.username,
        t1.groupID,
        t1.updated,
        t1.statusID,
        t3.name as unit
        ';

        $table = [
            'from' => TBL_USER,
            'where'=>['t1.groupID !='=>1],
            'join' => [
                't3' => ['table' => TBL_MANAGEMENT_UNIT, 'on' => 't1.unitID = t3._id'],
                't4' => ['table' => TBL_GROUPS, 'on' => 't1.groupID = t4._id'],
            ]
        ];
        echo $this->pagination($table, $select);
    }


    public function get_menu_user()
    {
        $this->db->limit($this->get('length'), $this->get('start'));
        $this->db->select('t1.loginID as _id,t1.updated,t2.emp_code,t2.full_name,t2.mobile,t3.name as unit');
        $this->db->from(TBL_MENU . ' as t1');
        $this->db->join(TBL_EMPLOYEE . ' as t2', 't1.loginID = t2._id', 'left');
        $this->db->join(TBL_MANAGEMENT_UNIT . ' as t3', 't1.unitID = t3._id', 'left');
        $this->db->group_by('loginID');
        $query =  $this->db->get();
        $data = $query->result_array();
        $total = $this->db->group_by('loginID')->count_all_results(TBL_MENU);
        $displayRecordTotal = $total;
        $recordTotal = $total;
        echo $this->getInDatatable($data, $displayRecordTotal, $recordTotal);
        //return ['data'=>$data,'display'=>$displayRecordTotal,'total'=>$recordTotal];
    }


    public function get_employee_for_attendance()
    {
        $select = 't1._id,t1.emp_code,t1.full_name,t1.mobile,t3.name as unit,t5.name as role';

        $table = [
            'from' => TBL_EMPLOYEE,
            'join' => [
                't2' => ['table' => TBL_EMPLOYEE_DETAILS, 'on' => 't1._id = t2.empID'],
                't3' => ['table' => TBL_MANAGEMENT_UNIT, 'on' => 't1.unitID = t3._id'],
                't5' => ['table' => TBL_DESIGNATION, 'on' => 't1.designationID = t5._id'],
            ]
        ];
        echo $this->pagination($table, $select);
    }

    public function get_employee_pending_attendance()
    {
        $select = 't1.total_days,
                   t1.present_days,
                   t1.absent_days,
                   t1.leave_days,
                   t1.statusID,
                   t1._id,
                   t6.emp_code,
                   t6.full_name,
                   t6.mobile,
                   t3.name as unit,
                   t5.name as role,
                   t7.name as year,
                   DATE_FORMAT(t1.created,"%Y-%m-%d") as created,
                   t8.name as month';
                    


        $table = [
            'from' => TBL_ATTENDANCE,
            'join' => [
                't6' => ['table' => TBL_EMPLOYEE, 'on' => 't1.empID = t6._id'],
                't2' => ['table' => TBL_EMPLOYEE_DETAILS, 'on' => 't1._id = t2.empID'],
                't3' => ['table' => TBL_MANAGEMENT_UNIT, 'on' => 't6.unitID = t3._id'],
                't5' => ['table' => TBL_DESIGNATION, 'on' => 't6.designationID = t5._id'],
                't7' => ['table' => TBL_YEARS, 'on' => 't1.yearID = t7._id'],
                't8' => ['table' => TBL_MONTHS, 'on' => 't1.monthID = t8._id'],
            ]
        ];
        echo $this->pagination($table, $select);
    }

    public function get_employee_pending_salary()
    {
        $select = 't1.total_days,
                   t1.present_days,
                   t1.absent_days,
                   t1.leave_days,
                   t1.statusID,
                   t1._id,
                   t6.emp_code,
                   t6.full_name,
                   t6.mobile,
                   t3.name as unit,
                   t5.name as role,
                   t7.name as year,
                   t8.name as month';

        $table = [
            'from' => TBL_ATTENDANCE,
            'join' => [
                't6' => ['table' => TBL_EMPLOYEE, 'on' => 't1.empID = t6._id'],
                't2' => ['table' => TBL_EMPLOYEE_DETAILS, 'on' => 't1._id = t2.empID'],
                't3' => ['table' => TBL_MANAGEMENT_UNIT, 'on' => 't6.unitID = t3._id'],
                't5' => ['table' => TBL_DESIGNATION, 'on' => 't6.designationID = t5._id'],
                't7' => ['table' => TBL_YEARS, 'on' => 't1.yearID = t7._id'],
                't8' => ['table' => TBL_MONTHS, 'on' => 't1.monthID = t8._id'],
            ]
        ];
        echo $this->pagination($table, $select);
    }


    public function get_salary_structure()
    {
        $select = 't1._id,
                   t1.empID,
                   t1.gross_pay_amount,
                   t1.statusID,
                   t1.epf_amount,
                   t1.professional_tax_amount,
                   t2.emp_code,
                   t2.full_name,
                   t4.name as unit,
                   t3.name as designation';

        $table = [
            'from' => TBL_SALARY_STRUCTURE,
            'join' => [
                't2' => ['table' => TBL_EMPLOYEE, 'on' => 't1.empID = t2._id'],
                't3' => ['table' => TBL_DESIGNATION, 'on' => 't2.designationID = t3._id'],
                't4' => ['table' => TBL_MANAGEMENT_UNIT, 'on' => 't2.unitID = t4._id'],
            ]
        ];
        echo $this->pagination($table, $select);
    }


    public function get_paybill_report()
    {
        $select = '                   
        t1._id,
        t1.empID,
        t1.gross_pay_amount,
        t1.epf_amount,
        t1.professional_amount,
        t1.net_amount,
        t2.emp_code,
        t2.full_name,                  
        t4.name as role,
        t3.name as unit,
        t5.total_days,
        t5.present_days,
        t5.absent_days,
        t1.created,
        t6.name as year,
        t1.created,
        t7.name as month,
        
        ';
        $table = [
            'from' => TBL_GENERATED_SALARY,
            //'group' => ['t1.empID','t5.yearID'],
            'join' => [
                't2' => ['table' => TBL_EMPLOYEE, 'on' => 't1.empID = t2._id'],
                't3' => ['table' => TBL_MANAGEMENT_UNIT, 'on' => 't2.unitID = t3._id'],
                't4' => ['table' => TBL_DESIGNATION, 'on' => 't2.designationID = t4._id'],
                't5' => ['table' => TBL_ATTENDANCE, 'on' => 't1.attendanceID = t5._id'],
                't6' => ['table' => TBL_YEARS, 'on' => 't5.yearID = t6._id'],
                't7' => ['table' => TBL_MONTHS, 'on' => 't5.monthID = t7._id'],
            ]
        ];


        echo $this->pagination($table, $select);
    }

    public function get_payslip_report()
    {
        $select = '                   
        t1._id,
        t1.empID,
        t1.gross_pay_amount,
        t1.epf_amount,
        t1.professional_amount,
        t1.net_amount,
        t2.emp_code,
        t2.full_name,                  
        t4.name as role,
        t3.name as unit,
        t5.total_days,
        t5.present_days,
        t5.absent_days,
        t1.created,
        t6.name as year,        
        t7.name as month,        
        ';
        $table = [
            'from' => TBL_GENERATED_SALARY,
            'join' => [
                't2' => ['table' => TBL_EMPLOYEE, 'on' => 't1.empID = t2._id'],
                't3' => ['table' => TBL_MANAGEMENT_UNIT, 'on' => 't2.unitID = t3._id'],
                't4' => ['table' => TBL_DESIGNATION, 'on' => 't2.designationID = t4._id'],
                't5' => ['table' => TBL_ATTENDANCE, 'on' => 't1.attendanceID = t5._id'],
                't6' => ['table' => TBL_YEARS, 'on' => 't5.yearID = t6._id'],
                't7' => ['table' => TBL_MONTHS, 'on' => 't5.monthID = t7._id'],
            ]
        ];


        echo $this->pagination($table, $select);
    }


    public function get_tax_deduction()
    {
        $select = '                   
        t1._id,
        t1.empID,
        t1.professional_amount,
        t2.emp_code,
        t2.full_name,   
        t4.name as role,   
        t6.name as year,        
        t7.name as month,             
        ';
        $table = [
            'from' => TBL_GENERATED_SALARY,
            'join' => [
                't2' => ['table' => TBL_EMPLOYEE, 'on' => 't1.empID = t2._id']   ,
                't4' => ['table' => TBL_DESIGNATION, 'on' => 't2.designationID = t4._id'],
                't5' => ['table' => TBL_ATTENDANCE, 'on' => 't1.attendanceID = t5._id'],
                't6' => ['table' => TBL_YEARS, 'on' => 't5.yearID = t6._id'],
                't7' => ['table' => TBL_MONTHS, 'on' => 't5.monthID = t7._id'],            
            ]
        ];
        echo $this->pagination($table, $select);
    }

    
    public function get_epf_deduction()
    {
        $select = '                   
        t1._id,
        t1.empID,
        t1.epf_amount,
        t2.emp_code,
        t2.full_name,
        t4.name as role,  
        t6.name as year,        
        t7.name as month,                 
        ';
        $table = [
            'from' => TBL_GENERATED_SALARY,
            'join' => [
                't2' => ['table' => TBL_EMPLOYEE, 'on' => 't1.empID = t2._id'],
                't4' => ['table' => TBL_DESIGNATION, 'on' => 't2.designationID = t4._id'],
                't5' => ['table' => TBL_ATTENDANCE, 'on' => 't1.attendanceID = t5._id'],
                't6' => ['table' => TBL_YEARS, 'on' => 't5.yearID = t6._id'],
                't7' => ['table' => TBL_MONTHS, 'on' => 't5.monthID = t7._id'],                      
            ]
        ];
        echo $this->pagination($table, $select);
    }

 public function get_distric()
    {
        $select = 't1._id,t1.name,t1.statusID,t1.created,t1.updated';
        $table = [
            'from' => TBL_DISTRICT,
        ];
        echo $this->pagination($table, $select);
    }

}