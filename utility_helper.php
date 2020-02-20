<?php
// remove all blank value in array
function array_filters($data = array())
{
    return array_filter($data);
}

// remove all dublicacy
function array_uniques($data = array())
{
    return array_unique($data);
}

function home_page()
{

    redirect(base_url());
}

function login_page()
{
    redirect('Auth/login');
}


function b_encode($value)
{
    return base64_encode($value);
}

function b_decode($value)
{
    return base64_decode($value);
}


//set session data here
function set_session($key, $value)
{
    $CI = &get_instance();
    $CI->session->set_userdata($key, $value);
}

//set session data here
function get_session($key)
{
    $CI = &get_instance();
    return $CI->session->userdata($key);
}


function view($page, $data = null)
{

    $CI = &get_instance();
    $CI->load->view($page, $data);
}

function uri($segment)
{
    $CI = &get_instance();
    return $CI->uri->segment($segment);
}

function baseurl($url = null)
{
    echo base_url($url);
}


function post($name)
{
    $CI = &get_instance();
    return ($CI->input->post($name));
}

function strpad($number)
{
    return str_pad($number, 2, 0, STR_PAD_LEFT);
}


function getToken($length)
{
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet .= "0123456789";
    $max = strlen($codeAlphabet); // edited
    for ($i = 0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max - 1)];
    }
    echo $token;
}

function crypto_rand_secure($min, $max)
{
    $range = $max - $min;
    if ($range < 1) return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd > $range);
    return $min + $rnd;
}

function getValue($data, $name)
{
    if (isset($data[$name])) {
        return $data[$name];
    }
}


function page_title($data = '')
{
    $CI = &get_instance();
    $title = $CI->uri->segment(2);
    if (isset($data['title'])) {
        if (!empty($data['title']))
            return $data['title'];
    } else {
        return str_replace('_', ' ', $title);
    }
}

function is_set($data = [], $key)
{
    return isset($data[$key]) ? $data[$key] : 'N/A';
}

function setDefaultValue($data, $key)
{
    echo isset($data[$key]) ? $data[$key] : '-';
}

function get_date()
{
    return  date('Y-m-d H:i:s');
}

function getNetSalary($data)
{
    $grossPay = get_days_of_payble($data);
    $deduction = 0;
    $ttSalary =  0;
    // stored all amount in single variable
    if (isset($grossPay)) {
        $ttSalary = $grossPay;
    }

    // debit epf amount 
    if (isset($data['gross_pay_amount'])) {
        $ttSalary = $ttSalary -  0;//$data['epf_amount'];
    }

    // debit tax
    if (isset($data['professional_tax_amount'])) {
        $ttSalary = $ttSalary -  $data['professional_tax_amount'];
    }
     // or debit tax
    if (isset($data['professional_amount'])) {
        $ttSalary = $ttSalary -  $data['professional_amount'];
    }

    return ($ttSalary);
}


/**
 * Format Number
 *
 * Returns the supplied number with commas and a decimal point.
 *
 * @param	float
 * @return	string
 */
function format_number($n = '')
{
    return ($n === '') ? '0' : number_format((float) $n, 2, '.', ',');
}


function get_finencial_year($month,$year){
    $CI = &get_instance();
    $month = $CI->common->get_row(TBL_MONTHS,['name'=>$month],'_id');
    if(count($month)){
        $month = $month['_id'];
     }

     //print_r($month );     var_dump($month);die;

    if($month >= 1 && $month <= 3){
        $year = ($year-1)."-".$year;
    }
    else {
        $year =$year."-".($year +1);
    }
    return $year;
}


function get_days_of_payble($data){

    $gross =  $data['gross_pay_amount'];
    $oneDaySalary =  $gross/$data['total_days'];
    $present =  $data['present_days'];
    return round($present*$oneDaySalary);

}

function get_captcha($config=''){
    $CI = &get_instance();
    $CI->load->helper('captcha');

    $width = isset($config['width'])?$config['width']:200;
    $height = isset($config['height'])?$config['height']:200;
    $vals = array(
        'is_show_image'      => '',
        'img_path'      => 'assets/captcha/',
        'img_url'       =>  base_url('assets/captcha/'),
        'img_width'     => $width,
        'img_height'    => $height,
        'word_length'   => 5,
        'font_size'     => 20,
        'colors'        => array(
            'background'     => array(255, 255, 255),
            'border'         => array(255, 255, 255),
            'text'           => array(0, 0, 0),
            'grid'           => array(255, 75, 100)
        )
    );
    $captcha = create_captcha($vals);
    $CI->session->set_userdata(['captcha_word'=>$captcha['word']]); 
    return  $captcha;
}

function get_current_url(){
   return uri(1).'/'.uri(2);
}