<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Root extends CI_Controller
{

    /*
     * Render page from controller
     * it loads header and footer auto
     */

    public function render($data)
    {
        if (!isset($data['page'])) {
            return 'Page not set';
        }
        if (empty($data['page'])) {
            return "Pgae not found";
        }
        $this->load->view('index', $data);
    }
}


class MY_Controller extends Root
{

    public function __construct()
    {
        parent::__construct();
        $this->data = [];
        $this->data['get'] = [];
        $this->data['set'] = [];
        $this->data['del'] = [];
        $this->data['id'] = '';
        $this->id = empty($this->get('id')) ? '' : $this->get('id');;
        $this->data['act'] = empty($this->get('act')) ? 'list' : $this->get('act');
        $this->act = $this->data['act'];
        $this->data['activeTab'] = 1;
        $this->data['row'] = [];
        $this->load->model('Master_model', 'master');
        $this->load->model('Common_model', 'common');
        $this->isLoggedOn();
        $this->userdata = $this->session->userdata['logged_in'];
        $this->userID = $this->userdata['_id'];
        $this->unitID = $this->userdata['unitID'];
        $this->groupID = $this->userdata['groupID'];
        $this->getStatus();
    }

    public function isLoggedOn()
    {

        if (!isset($this->session->userdata['logged_in'])) {
            set_msg('error', 'Unauthorized access');
            redirect('auth/login');
        }

        $user = $this->session->userdata['logged_in'];

        if (empty($user)) {
            set_msg('error', 'Unauthorized access');
            redirect('auth/login');
        }
    }

    public function method()
    {
        return $this->input->method(true);
    }

    public function get($name)
    {
        return $this->input->get($name);
    }

    public function post($name)
    {
        return $this->input->post($name);
    }

    public function pre($data = '')
    {
        if (empty($data)) $data = $_REQUEST;
        echo "<pre>";
        print_r($data);
        echo "<pre>";
        die;
    }

    public function getUri($segment)
    {
        return $this->uri->segment($segment);
    }

    public function postData($data)
    {
        foreach ($data as $key => $name) {
            if (is_numeric($key))
                $cData[$name] = $this->post($name);
            else
                $cData[$key] = $this->post($name);
        }
        return $cData;
    }


    public function uploadMultipleImages($file_name, $height = null, $width = null, $path = null, $i = null)
    {
        $files = [];
        $uFiles = [];

        // if (empty($_FILES[$file_name]['name'][0])) {
        //     return false;
        // }

        if (!empty($_FILES[$file_name]['name']))
            $files = $_FILES[$file_name];

        //  upload an image options
        if (empty($upload_path)) {
            $upload_path = 'assets/uploads/';
        }
        foreach ($_FILES[$file_name]['name'] as $key => $image) {
            $_FILES['images']['name'] = $files['name'][$key];
            $_FILES['images']['type'] = $files['type'][$key];
            $_FILES['images']['tmp_name'] = $files['tmp_name'][$key];
            $_FILES['images']['error'] = $files['error'][$key];
            $_FILES['images']['size'] = $files['size'][$key];
            $name = $file = $this->uploadImage('images');
            $uFiles[$key] = $name;
        }
        return $uFiles;
    }

    public function uploadDobubleMultipleImages2($file_name, $height = null, $width = null, $path = null, $i = null)
    {
        $files = [];
        $uFiles = [];

        if (!empty($_FILES[$file_name]['name']))
            $files = $_FILES[$file_name];

        //  upload an image options
        if (empty($upload_path)) {
            $upload_path = 'assets/uploads/';
        }

        foreach ($_FILES[$file_name]['name'] as $pkey => $imageList) {


            foreach ($imageList as $key => $image) {
                if ($_FILES[$file_name]['name'][$pkey][$key]) {
                    $_FILES['images']['name'] = $_FILES[$file_name]['name'][$pkey][$key];
                    $_FILES['images']['type'] = $_FILES[$file_name]['type'][$pkey][$key];
                    $_FILES['images']['tmp_name'] = $_FILES[$file_name]['tmp_name'][$pkey][$key];
                    $_FILES['images']['error'] = $_FILES[$file_name]['error'][$pkey][$key];
                    $_FILES['images']['size'] = $_FILES[$file_name]['size'][$pkey][$key];
                    $name = $file = $this->uploadImage('images');
                    $uFiles[$pkey][$key] = $name;
                }

                $name = '';
            }
        }
        return $uFiles;
    }


    public function uploadImage($file_name, $height = null, $width = null, $path = null, $i = null)
    {
        //  upload an image options
        if (empty($upload_path)) {
            $upload_path = 'assets/uploads/';
        }

        if (empty($_FILES[$file_name]['name'])) {
            return false;
        }

        //print_r($_FILES);
        $file = $_FILES[$file_name]['name'];
        $exe = @end(explode('.', $file));
        $config = array();
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = '*';
        //$config['max_size'] = "5000";
        $config['file_name'] = rand(1000, 9999) . time() . '.' . $exe;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($file_name)) {
            $error = $this->upload->display_errors();
            return $error;
        } else {
            $file = $this->upload->data();
            $this->resize_image($file['file_name'], $height, $width, $path, $i);

            if ($i == 55) {
                $this->resize_image($file['file_name'], 95, 95, $path . 'thumbs/', 66);
            }
            //unlink($config['upload_path'].$file['file_name']);
            return $file['file_name'];
        }
    }

    public function resize_image($file, $height = 300, $width = 300, $path, $i = null)
    {
        $config1['image_library'] = 'gd2';
        $config1['source_image'] = 'assets/uploaded/' . $file;
        $config1['new_image'] = 'assets/uploaded/' . $path;
        $config['create_thumb'] = TRUE;
        $config1['width'] = $width;
        $config1['height'] = $height;
        $this->load->library('image_lib', $config1);
        $this->image_lib->initialize($config1);
        $this->image_lib->resize();
        $this->image_lib->clear();
    }

    public function getStatus()
    {
        if ($this->session->has_userdata('statusRows') == false) {
            $rows = $this->common->get_where(TBL_STATUS, ['statusID' => 1, 'is_dropbox' => 1]);
            foreach ($rows as $row) {
                $d[$row['_id']] = $row['name'];
            }
            $this->session->set_userdata('statusRows', $d);
            return $d;
        }
    }
    public function getData($data)
    {
        foreach ($data as $name) {
            $cData[$name] = $this->get($name);
        }
        return $cData;
    }

    public function getDropDownData($table, $cnd = [])
    {
        $d = [];
        $c = ['statusID' => 1];
        if (!empty($cnd)) {
            $c = array_merge($c, $cnd);
        }
        $data = $this->common->get_where($table, $c, '_id,name');
        foreach ($data as $key => $value) {
            $d[$value['_id']] = $value['name'];
        }
        return $d;
    }

    public function getDesignation()
    {
        return $this->getDropDownData(TBL_DESIGNATION);
    }

    public function getUnit()
    {
        return $this->getDropDownData(TBL_MANAGEMENT_UNIT);
    }

    public function getGroup()
    {
        return $this->getDropDownData(TBL_GROUPS);
    }

    public function getBank()
    {
        return $this->getDropDownData(TBL_BANK);
    }
    public function getCountry()
    {
        return $this->getDropDownData(TBL_COUNTRY);
    }
    public function getState()
    {
        return $this->getDropDownData(TBL_STATE);
    }
    public function getCity($cnd)
    {
        return $this->getDropDownData(TBL_CITY, $cnd);
    }
    public function getOffice()
    {
        return $this->getDropDownData(TBL_OFFICE);
    }

    public function getYears()
    {
        return $this->getDropDownData(TBL_YEARS);
    }

    public function getMonths()
    {
        return $this->getDropDownData(TBL_MONTHS);
    }

     public function getDistrict()
    {
        return $this->getDropDownData(TBL_DISTRICT);
    }

    public function getUserDetails($cnd = [])
    {
        $d = [];
        $cnd['statusID'] = 1;
        $data = $this->common->get_where(TBL_EMPLOYEE_DETAILS, $cnd, '_id,full_name,emp_code');
        foreach ($data as $key => $value) {
            $d[$value['_id']] = $value['full_name'] . ' - ' . $value['emp_code'];
        }
        return $d;
    }

    public  function handler($table, $page, $postData, $redirect, $message, $fx = '')
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

    public function notify($email = 0, $sms = 0)
    {
        if ($email == 1)
            $this->notify->email(['email' => $res['email'], 'template' => 'mark_letter', 'subject' => '🔔Letter forwarded✉️']);
        if ($sms == 1)
            $this->notify->sms(['mobile' => $res['mobile'], 'template' => 'mark_letter']);
            
    }
}