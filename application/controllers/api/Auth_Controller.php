<?php 

class Auth_Controller extends RestApi_Controller 
{
    function __construct() 
    {
        parent::__construct();
        $this->load->library('Api_Auth');
        $this->load->model('Api_parent_model');
    }

    
    function login() 
    {
        
        $email = $this->input->post('email');
        $password = $this->input->post('password');

       
        $this->form_validation->set_rules('email','Email','required');
        $this->form_validation->set_rules('password','Pasword','required');
        if($this->form_validation->run())
        {
            // echo "-------------------"
            //  $data = array('email'=>$email,'password'=> sha1($password));
             $login_credential = $this->Api_parent_model->login_credential($email, $password);
             if($login_credential != false) 
             {
                  $userId = $login_credential->id;
                  $bearerToken = $this->api_auth->generateToken($userId);


                  $getUser = $this->Api_parent_model->getUserNameByRoleID($login_credential->role, $login_credential->user_id);
                        $getConfig = $this->db->get_where('global_settings', array('id' => 1))->row_array();

                        if ($login_credential->role == 6) {
                            $userType = 'parent';
                        } elseif($login_credential->role == 7) {
                            $userType = 'student';
                        } else {
                            $userType = 'staff';
                        }
                        // get logger name
                        // $sessionData = array(
                        //     'name' => $getUser['name'],
                        //     'mobile_no'=> $getUser['mobileno'],
                        //     'logger_photo' => $getUser['photo'],
                        //     'loggedin_branch' => $getUser['branch_id'],
                        //     'loggedin_id' => $login_credential->id,
                        //     'loggedin_userid' => $login_credential->user_id,
                        //     'loggedin_role_id' => $login_credential->role,
                        //     'loggedin_type' => $userType,
                        //     'set_lang' => $getConfig['translation'],
                        //     'set_session_id' => $getConfig['session_id'],
                        //     'loggedin' => true,
                        // );
                  $responseData = array(
                    'status'=> true,
                    'message' => 'Successfully Logged In',
                    'token'=> $bearerToken,

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
                 return $this->response($responseData,200);
             }
             else 
             {
                $responseData = array(
                    'status'=>false,
                    'message' => 'Invalid Crendentials',
                    'data'=> []
                 );
                 return $this->response($responseData);
             }
        }
        else 
        {
            $responseData = array(
                'status'=>false,
                'message' => 'Email Id and password is required',
                'data'=> []
             );
             return $this->response($responseData);
        }
    }
    
/*
    public function login()
    {

        if ($_POST) {
            $rules = array(
                array(
                    'field' => 'email',
                    'label' => "Email",
                    'rules' => 'trim|required',
                ),
                array(
                    'field' => 'password',
                    'label' => "Password",
                    'rules' => 'trim|required',
                ),
            );
            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run() !== false) {
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                // username is okey lets check the password now
                $login_credential = $this->Api_parent_model->login_credential($email, $password);
                if ($login_credential) {
                    if ($login_credential->active) {
                        if ($login_credential->role == 6) {
                            $userType = 'parent';
                        } elseif($login_credential->role == 7) {
                            $userType = 'student';
                        } else {
                            $userType = 'staff';
                        }
                        $getUser = $this->Api_parent_model->getUserNameByRoleID($login_credential->role, $login_credential->user_id);
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
*/
}