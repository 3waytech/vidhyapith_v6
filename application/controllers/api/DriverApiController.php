<?php
defined('BASEPATH') or exit('No direct script access allowed');


class DriverApiController extends RestApi_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Api_driver_model');
        // $this->load->model('parents_model');
        $this->load->model('profile_model');
        $this->load->model('employee_model');
        $this->load->model('student_model');
        $this->load->model('fees_model');
        // $this->load->model('parents_model');
        // $this->load->model('profile_model');
        $this->load->model('email_model');
        $this->load->model('student_fields_model');
    }

    public function index()
    {

        echo "Hi this is Driver API";
    }

    public function login()
    {

        if ($_POST) {
            $rules = array(
                array(
                    'field' => 'mobile_no',
                    'label' => "mobile_no",
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'password',
                    'label' => "Password",
                    'rules' => 'trim|required',
                ),
            );
            $this->form_validation->set_rules($rules);

            
                // print_r($rules);// "--------",$rules;
            if ($this->form_validation->run() !== false) {
                // print_r($rules);// "--------",$rules;
                $email = $this->input->post('mobile_no');
                $password = $this->input->post('password');
                // username is okey lets check the password now
                $login_credential = $this->Api_driver_model->login_credential($email, $password);
                if ($login_credential) {
                    if ($login_credential->active) {
                        if ($login_credential->role == 6) {
                            $userType = 'parent';
                        } elseif($login_credential->role == 7) {
                            $userType = 'student';
                        } else {
                            $userType = 'staff';
                        }
                        $getUser = $this->Api_driver_model->getUserNameByRoleID($login_credential->role, $login_credential->user_id);
                        $getConfig = $this->db->get_where('global_settings', array('id' => 1))->row_array();
                        // get logger name
                        $sessionData = array(
                            'name' => $getUser['name'],
                            'mobile_no'=> $getUser['mobileno'],
                            'logger_photo' => $getUser['photo'],
                            'loggedin_branch' => $getUser['branch_id'],
                            'loggedin_id' => $login_credential->id,
                            'loggedin_userid' => $login_credential->user_id,
                            'loggedin_role_id' => $login_credential->role,
                            'loggedin_type' => $userType,
                            'set_lang' => $getConfig['translation'],
                            'set_session_id' => $getConfig['session_id'],
                            'loggedin' => true,
                        );
                        $this->session->set_userdata($sessionData);
                        $this->db->update('login_credential', array('last_login' => date('Y-m-d H:i:s')), array('id' => $login_credential->id));
                        // is logged in
                        if ($this->session->has_userdata('redirect_url')) {
                            redirect($this->session->userdata('redirect_url'));
                        } else {

                            $json = array("result" => $sessionData, "status" => true, "msg" =>"Login Successfully!");
                            // redirect(base_url('dashboard'));
                        }

                    } else {
                        $json = array("status" => 2, "msg" => translate('inactive_account'));
                        // set_alert('error', translate('inactive_account'));
                        // redirect(base_url('authentication'));
                    }
                } 
                
                else {
                    $json = array("status" => false, "msg" => translate('username_password_incorrect'));
                    // set_alert('error', translate('username_password_incorrect'));
                    // redirect(base_url('authentication'));
                }
                header('Content-type: application/json');
                echo json_encode($json);
            }
        }
    }
    
    public function driverprofile()
    {
        $mobile_no = $this->input->post('mobile_no');
        if ($mobile_no) {

            $data = $this->Api_driver_model->getSingleDriver($mobile_no);

            if ($data) {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get profile list Successfully!!'
                );
            } else {
                $json =  array(
                    "result" => Null,
                    'success'  => true,
                    "msg" => 'No Data Found'
                );
            }
        } else {
            $json = array(
                "result" => null,
                'success'    => false,
                'msg' => "Mobile Number are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }
}
?>