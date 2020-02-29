<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master extends MY_Controller
{

    public  function __construct()
    {
        parent::__construct();
        $this->load->model('Master_model', 'master');
        
    }

    public function index($data = null)
    {
        if (!isset($data['page']))
            redirect('auth');
        $data['page'] = 'master/' . $data['page'];

        if (!empty($data)) {
            $this->data = array_merge($this->data, $data);
        }

        $this->load->view('index', $this->data);
    }

    public function test()
    {
        $this->data['page'] = 'unit';
        $this->master->table = TBL_MANAGEMENT_UNIT;
        $_id =  $this->get('id');
        $act =  $this->get('act');
        $this->data['id'] =  $_id;
        $data = $this->postData(['name', 'statusID' => 'status', 'code']);

        // delete process is here
        if ($act == 'del' && !empty($_id)) {
            $this->master->delete(['_id' => $_id]);
            set_msg('success', 'Section  remove successfully !');
            redirect('master/unit');
        }

        // update process is here
        if ($this->method() == 'POST' && !empty($_id)) {
            $this->master->set($_id, $data);
            set_msg('success', 'Section details has been updated successfully !');
            redirect('master/unit');
        }

        // insert or add new reoords in database
        if ($this->method() == 'POST' && empty($_id)) {
            $this->master->add($data);
            set_msg('success', 'Section details has been inserted successfully !!');
            redirect('master/unit');
        }

        // fetching or get new records from database related to $_id is id
        if (!empty($_id)) {
            $this->data['get'] = $this->master->get_row($_id);
            if ($act == 'set') {
                $this->data['set'] = $this->data['get'];
                $this->data['get'] = '';
                $this->data['activeTab'] = 2;
            }
        }
        $this->index($this->data);
    }

    public function handler($table, $page, $postData, $redirect, $message, $fx = '')
    {
        $this->data['page'] = $page;
        $this->master->table = $table;
        $_id =  $this->get('id');
        $act =  $this->get('act');
        $this->data['id'] =  $_id;
        $redirect = $redirect;
        $data = $this->postData($postData);
        
        // delete process is here
        if ($act == 'del' && !empty($_id)) {
            $this->master->delete(['_id' => $_id]);
            set_msg('success', $message['del']);
            redirect($redirect);
        }

        // update process is here
        if ($this->method() == 'POST' && !empty($_id)) {
            $this->master->set($_id, $data);
            set_msg('success', $message['set']);
            redirect($redirect);
        }

        // insert or add new reoords in database
        if ($this->method() == 'POST' && empty($_id)) {
            $this->master->add($data);
            set_msg('success', $message['add']);
            redirect($redirect);
        }
        // fetching or get new records from database related to $_id is id
        if (!empty($_id)) {
            $this->data['row'] = $this->master->get_row($_id);            
        }
        $this->index($this->data);
    }

    public function bank()
    {
        $postData =  ['name', 'statusID' => 'status', 'code'];
        $redirect = 'master/bank';
        $message = [
            'add' => 'Bank details has been inserted successfully !!',
            'set' => 'Bank details has been updated successfully !',
            'del' => 'Bank  remove successfully !'
        ];
        $this->handler(TBL_BANK, 'bank', $postData, $redirect, $message);
    }

    public function bank_branch()
    {
        $postData =  ['name', 'statusID' => 'status', 'parentID'=>'bank','ifsc','full_address','micr'];
        $redirect = 'master/bank_branch';
        $message = [
            'add' => 'Bank branch details has been inserted successfully !!',
            'set' => 'Bank branch details has been updated successfully !',
            'del' => 'Bank branch remove successfully !'
        ];
        $this->data['banks'] = $this->getBank();
        $this->handler(TBL_BANK_BRANCH, 'bank_branch', $postData, $redirect, $message);
    }

    public function unit()
    {
        $postData =  ['name', 'statusID' => 'status', 'code','officeID'];
        $redirect = 'master/unit';
        $message = [
            'add' => 'Section details has been inserted successfully !!',
            'set' => 'Section details has been updated successfully !',
            'del' => 'Section  remove successfully !'
        ];
        $this->data['offices'] = $this->getOffice();
        $this->data['title'] = 'Management Unit';
        $this->handler(TBL_MANAGEMENT_UNIT, 'unit', $postData, $redirect, $message);
    }


    public function pay_structure()
    {
        $postData =  ['name', 'statusID' => 'status', 'gross_pay'];
        $redirect = 'master/pay_structure';
        $message = [
            'add' => 'Pay structure details has been inserted successfully !!',
            'set' => 'Pay structure details has been updated successfully !',
            'del' => 'Pay structure  remove successfully !'
        ];
        $this->handler(TBL_PAYSTRUCTURE, 'pay_structure', $postData, $redirect, $message);
    }
    
    public function deduction()
    {
        $postData =  ['name', 'statusID' => 'status'];
        $redirect = 'master/deduction';
        $message = [
            'add' => 'Deduction details has been inserted successfully !!',
            'set' => 'Deduction details has been updated successfully !',
            'del' => 'Deduction  remove successfully !'
        ];
        $this->handler(TBL_DEDUCTION, 'deduction', $postData, $redirect, $message);
    }


    public function designation()
    {
        $postData =  ['name', 'statusID' => 'status'];
        $redirect = 'master/designation';
        $message = [
            'add' => 'Designation details has been inserted successfully !!',
            'set' => 'Designation details has been updated successfully !',
            'del' => 'Designation  remove successfully !'
        ];
       // $this->data['users'] = 
        $this->handler(TBL_DESIGNATION, 'designation', $postData, $redirect, $message);
    }

 public function financial_year()
    {
        $postData =  ['name', 'statusID' => 'status'];
        $redirect = 'master/financial_year';
        $message = [
            'add' => 'Financial year details has been inserted successfully !!',
            'set' => 'Financial year details has been updated successfully !',
            'del' => 'Financial year  remove successfully !'
        ];

        $this->handler(TBL_YEARS, 'financial_year', $postData, $redirect, $message);
    }

    public function user()
    {
        $this->data['page'] = 'user';
        $this->master->table = TBL_USER;
        $_id =  $this->get('id');
        $act =  $this->get('act');
        $this->data['id'] =  $_id;
        $data = $this->postData(['username','password','empID'=>'code' , 'groupID','full_name','statusID' => 'status']);

        // delete process is here
        if ($act == 'del' && !empty($_id)) {
            $this->master->delete(['_id' => $_id]);
            set_msg('success', 'User remove successfully !');
            redirect('master/user');
        }

        // update process is here
        if ($this->method() == 'POST' && !empty($_id)) {
            if($data['password'] != $data['password'])
            {
                set_msg('error', 'Password and confirm password is not matching');
                redirect('master/user');  
            }

            if(empty($data['password'])){
                unset($data['password']);
            }
            if(!empty($data['password'])){
               $data['password'] = md5($data['password']);
            }
            $this->master->set($_id, $data);
            set_msg('success', 'User details has been updated successfully !');
            redirect('master/user');
        }

        // insert or add new reoords in database
        if ($this->method() == 'POST' && empty($_id)) {
            $data['password'] = md5($data['password']);
            $data['userID'] = $this->userID;
            $data['mobile'] = $this->post('mobile');
            $data['email'] = $this->post('email');
            $data['statusID'] = 1;

            $row = $this->common->get_row(TBL_EMPLOYEE,['_id'=>$data['empID']]);
            if(count($this->common->get_row(TBL_USER,['empID'=>$data['empID']]))){
                set_msg('warning', 'This user already created login account');
                redirect('master/user');
            }            
            $this->master->add($data);
            set_msg('success', 'User details has been inserted successfully !!');
            redirect('master/user');
        }

        // fetching or get new records from database related to $_id is id
        if (!empty($_id)) {
            $this->data['row'] = $this->master->get_row($_id);
            if ($act == 'set') {
                $this->data['set'] = $this->data['get'];
                $this->data['get'] = '';
                $this->data['activeTab'] = 2;
            }
        }
        $this->data['userlist'] = $this->common->get_where(TBL_EMPLOYEE,['statusID'=>1],'_id,username,emp_code,full_name');
        $this->index($this->data);
    }

    public function office()
    {
        $postData =  ['name', 'statusID' => 'status'];
        $redirect = 'master/office';
        $message = [
            'add' => 'Office details has been inserted successfully !!',
            'set' => 'Office details has been updated successfully !',
            'del' => 'Office  remove successfully !'
        ];
       
        $this->handler(TBL_OFFICE, 'office', $postData, $redirect, $message);
    }

    public function permission()
    {
        $this->master->table = TBL_PERMISSION;
        $_id =  $this->get('id');
        $act =  $this->get('act');
        $this->data['id'] =  $_id;
        $redirect = 'master/permission';
        $data = $this->postData(['unitID', 'empID', 'data']);

        // delete process is here
        if ($act == 'del' && !empty($_id)) {
            $this->master->delete(['loginID' => $_id]);
            set_msg('success', '');
            redirect($redirect);
        }

        // update process is here
        if ($this->method() == 'POST' && !empty($_id)) {
            $this->master->delete(['loginID' => $_id]);
            $data['loginID'] = $data['empID'];
            $d['unitID'] = $data['unitID'];
            $d['loginID'] = $data['loginID'];
            if (empty($data['data'])) {
                $data['data'][0] = 0;
            }
            foreach ($data['data'] as $key => $value) {
                $d['menu'] = $key;
                $d['empID'] = $this->session->userdata['logged_in']['empID'];
                $d['childs'] = is_array($value) ? json_encode($value) : '[]';    
                $this->master->add($d);            
            }            
            set_msg('success', 'Menu permission set success');
            redirect($redirect);
        }

        // insert or add new reoords in database
        if ($this->method() == 'POST' && empty($_id)) {
            $data['loginID'] = $data['empID'];
            $d['unitID'] = $data['unitID'];
            $d['loginID'] = $data['loginID'];
            foreach ($data['data'] as $key => $value) {
                $d['menu'] = $key;
                $d['empID'] = $this->session->userdata['logged_in']['empID'];
                $d['childs'] = is_array($value) ? json_encode($value) : '0';

                $this->master->add($d);
            }
            set_msg('success', 'New employee menu permission create success');
            redirect($redirect);
        }

        // fetching or get new records from database related to $_id is id
        if (!empty($_id)) {
            $this->data['get'] = $this->master->get_user_menu($_id);
            if ($act == 'set') {
                $this->data['set'] = $this->data['get'];
                $this->data['get'] = '';
                $this->data['activeTab'] = 2;
            }
        }

        $this->data['unit'] = $this->setSection();
        $this->data['employee'] = $this->setEmployee('1');
        $this->data['page'] = 'menu';
        $this->index($this->data);
    }
  public function district()
    {
        $postData =  ['name', 'statusID' => 'status'];
        $redirect = 'master/district';
        $message = [
            'add' => 'District details has been inserted successfully !!',
            'set' => 'District details has been updated successfully !',
            'del' => 'District remove successfully !'
        ];
        $this->data['title'] = 'District';
        $this->handler(TBL_DISTRICT, 'district', $postData, $redirect, $message);
    }
}