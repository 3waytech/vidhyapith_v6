<?php

class Auth_Controller_Teachers extends RestApi_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('Api_Auth');
        $this->load->model('Api_Teachers_Model');
    }


    public function index()
    {
        echo "this is teachers api";
    }
    function login()
    {

        $username = $this->input->post('username');
        $password = $this->input->post('password');


        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Pasword', 'required');
        if ($this->form_validation->run()) {
            $login_credential = $this->Api_Teachers_Model->login_credential($username, $password);
            if ($login_credential != false) {
                $userId = $login_credential->id;
                $bearerToken = $this->api_auth->generateToken($userId);
                
                

                $getUser = $this->Api_Teachers_Model->getUserNameByRoleID($login_credential->role, $login_credential->user_id);
                $getConfig = $this->db->get_where('global_settings', array('id' => 1))->row_array();    

                if ($login_credential->role == 6) {
                    $userType = 'parent';
                } elseif ($login_credential->role == 7) {
                    $userType = 'student';
                } else {
                    $userType = 'staff';
                }
                $responseData = array(

                    // 'result' => array(
                        'token' => $bearerToken,
                        'name' => $getUser['name'],
                        'mobile_no' => $getUser['mobileno'],
                        'logger_photo' => $getUser['photo'],
                        'loggedin_branch' => $getUser['branch_id'],
                        'loggedin_id' => $login_credential->id,
                        'loggedin_userid' => $login_credential->user_id,
                        'loggedin_role_id' => $login_credential->role,
                        'loggedin_type' => $userType,
                        'set_lang' => $getConfig['translation'],
                        'set_session_id' => $getConfig['session_id'],
                        'section_id' => $getUser['section_id'],
                        'loggedin' => true,
                    // ),

                    // 'status' => true,
                    // 'message' => 'Successfully Logged In',
                );
                if ($responseData) {
                    $json =  array(
                        "result" => $responseData,
                        'success'  => true,
                        "msg" => 'Login Successfully!'
                    );
                }
            } else {
                $json = array(
                    "result" => null,
                    'success'    => false,
                    'msg' => "Invalid Crendentials"
                );
            }
        }else{
            $json = array(
                "result" => null,
                'success'    => false,
                'msg' => "Username and password is required"
            );
        }
        header('Content-type: application/json');
        echo json_encode($json);
    }
}
