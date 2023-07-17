<?php 
defined('BASEPATH') or exit('No direct script access allowed');
 

class ParentsApiController extends RestApi_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('Api_Auth');
        if($this->api_auth->isNotAuthenticated())
        {
            $err = array(
                'status'=>false,
                'message'=>'unauthorised',
                'data'=>[]
            );
            $this->response($err);
        }
        $this->load->library('form_validation');
        $this->load->model('Api_parent_model');
        $this->load->model('parents_model');
        $this->load->model('profile_model');
    }

    public function index(){

        echo "Hi this is parents API";
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
    public function profile()
    {

        if ($_POST) {
            $rules = array(
                array(
                    'field' => 'loggedin_userid',
                    'label' => "loggedin_userid",
                    'rules' => 'required',
                ),
                array(
                    'field' => 'loggedin_role_id',
                    'label' => "loggedin_role_id",
                    'rules' => 'required',
                ),
                array(
                    'field' => 'loggedin_branch',
                    'label' => "loggedin_branch",
                    'rules' => 'required',
                ),
            );

            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run() !== false) {

                $userID = $this->input->post('loggedin_userid');
                $loggedinRoleID = $this->input->post('loggedin_role_id');
                $branchID = $this->input->post('loggedin_branch');

                if ($loggedinRoleID == 6) {
                    $data = $this->Api_parent_model->getSingleParent($userID,$branchID);
                } 
                $json = array("result" =>$data,"status" => true, "msg" => 'Details Get Successfully');


                header('Content-type: application/json');
                echo json_encode($json);
            }

        }
    }

    public function update()
    {
        $userID = get_loggedin_user_id();
        $loggedinRoleID = loggedin_role_id();
        $branchID = get_loggedin_branch_id();

        if ($loggedinRoleID == 6) {
            if ($_POST) {
                $this->form_validation->set_rules('name', translate('name'), 'trim|required');
                $this->form_validation->set_rules('relation', translate('relation'), 'trim|required');
                $this->form_validation->set_rules('occupation', translate('occupation'), 'trim|required');
                $this->form_validation->set_rules('income', translate('income'), 'trim|numeric');
                $this->form_validation->set_rules('mobileno', translate('mobile_no'), 'trim|required');
                $this->form_validation->set_rules('email', translate('email'), 'trim|valid_email');
                $this->form_validation->set_rules('username', translate('username'), 'trim|required');
                $this->form_validation->set_rules('user_photo', 'profile_picture', 'callback_photoHandleUpload[user_photo]');
                $this->form_validation->set_rules('facebook', 'Facebook', 'valid_url');
                $this->form_validation->set_rules('twitter', 'Twitter', 'valid_url');
                $this->form_validation->set_rules('linkedin', 'Linkedin', 'valid_url');
                if ($this->form_validation->run() == true) {
                    $data = $this->input->post();
                    $this->Api_parent_model->parentUpdate($data);
                    set_alert('success', translate('information_has_been_updated_successfully'));
                    // redirect(base_url('profile')); 
                    $array =  array(
                        "result" => $data,
                        'success'  => true,
                        "msg" => 'Update Profile Successfully!!'
                    );
                } else {
                    // echo "$this->form_validation->set_rules('user_photo', 'profile_picture', 'callback_photoHandleUpload[user_photo]')";
                    $error = $this->form_validation->error_array();
                    $array = array('status' => 'fail', 'error' => $error);
                }
            }
            header('Content-type: application/json');
            echo json_encode($array);
        } elseif ($loggedinRoleID == 7) {
            if ($_POST) {

                $this->form_validation->set_rules('student_id', translate('student'), 'trim');
                // system fields validation rules
                $validArr = array();
                $validationArr = $this->student_fields_model->getStatusProfileArr($branchID);
                foreach ($validationArr as $key => $value) {
                    if ($value->status && $value->required) {
                        $validArr[$value->prefix] = 1;
                    }
                }

                $this->form_validation->set_rules('user_photo', 'profile_picture', 'callback_photoHandleUpload[user_photo]');
                if (isset($validArr['admission_date'])) {
                    $this->form_validation->set_rules('admission_date', translate('admission_date'), 'trim|required');
                }

                if (isset($validArr['student_photo'])) {
                    if (isset($_FILES["user_photo"]) && empty($_FILES["user_photo"]['name']) && empty($_POST['old_user_photo'])) {
                        $this->form_validation->set_rules('user_photo', translate('profile_picture'), 'required');
                    }
                }

                if (isset($validArr['first_name'])) {
                    $this->form_validation->set_rules('first_name', translate('first_name'), 'trim|required');
                }
                if (isset($validArr['last_name'])) {
                    $this->form_validation->set_rules('last_name', translate('last_name'), 'trim|required');
                }
                if (isset($validArr['gender'])) {
                    $this->form_validation->set_rules('gender', translate('gender'), 'trim|required');
                }
                if (isset($validArr['birthday'])) {
                    $this->form_validation->set_rules('birthday', translate('birthday'), 'trim|required');
                }
                if (isset($validArr['category'])) {
                    $this->form_validation->set_rules('category_id', translate('category'), 'trim|required');
                }
                if (isset($validArr['religion'])) {
                    $this->form_validation->set_rules('religion', translate('religion'), 'trim|required');
                }
                if (isset($validArr['caste'])) {
                    $this->form_validation->set_rules('caste', translate('caste'), 'trim|required');
                }
                if (isset($validArr['blood_group'])) {
                    $this->form_validation->set_rules('blood_group', translate('blood_group'), 'trim|required');
                }
                if (isset($validArr['mother_tongue'])) {
                    $this->form_validation->set_rules('mother_tongue', translate('mother_tongue'), 'trim|required');
                }
                if (isset($validArr['present_address'])) {
                    $this->form_validation->set_rules('current_address', translate('present_address'), 'trim|required');
                }
                if (isset($validArr['permanent_address'])) {
                    $this->form_validation->set_rules('permanent_address', translate('permanent_address'), 'trim|required');
                }
                if (isset($validArr['city'])) {
                    $this->form_validation->set_rules('city', translate('city'), 'trim|required');
                }
                if (isset($validArr['state'])) {
                    $this->form_validation->set_rules('state', translate('state'), 'trim|required');
                }
                if (isset($validArr['student_email'])) {
                    $this->form_validation->set_rules('email', translate('email'), 'trim|required|valid_email');
                }
                if (isset($validArr['student_mobile_no'])) {
                    $this->form_validation->set_rules('mobileno', translate('mobile_no'), 'trim|required|numeric');
                }
                if (isset($validArr['previous_school_details'])) {
                    $this->form_validation->set_rules('school_name', translate('school_name'), 'trim|required');
                    $this->form_validation->set_rules('qualification', translate('qualification'), 'trim|required');
                }

                if ($this->form_validation->run() == true) {
                    $data = $this->input->post();
                    $this->profile_model->studentUpdate($data);
                    set_alert('success', translate('information_has_been_updated_successfully'));
                    $array = array('status' => 'success');
                } else {
                    $error = $this->form_validation->error_array();
                    $array = array('status' => 'fail', 'error' => $error);
                }
                echo json_encode($array);
                exit();
            }
            $this->data['student'] = $this->student_model->getSingleStudent($userID);
            $this->data['sub_page'] = 'profile/student';
        } else {
            if ($_POST) {
                $this->form_validation->set_rules('name', translate('name'), 'trim|required');
                $this->form_validation->set_rules('mobile_no', translate('mobile_no'), 'trim|required');
                $this->form_validation->set_rules('present_address', translate('present_address'), 'trim|required');
                if (is_admin_loggedin()) {
                    $this->form_validation->set_rules('designation_id', translate('designation'), 'trim|required');
                    $this->form_validation->set_rules('department_id', translate('department'), 'trim|required');
                    $this->form_validation->set_rules('joining_date', translate('joining_date'), 'trim|required');
                    $this->form_validation->set_rules('qualification', translate('qualification'), 'trim|required');
                }
                $this->form_validation->set_rules('email', translate('email'), 'trim|required|valid_email');
                $this->form_validation->set_rules('username', translate('username'), 'trim|required|callback_unique_username');
                $this->form_validation->set_rules('facebook', 'Facebook', 'trim|valid_url');
                $this->form_validation->set_rules('twitter', 'Twitter', 'trim|valid_url');
                $this->form_validation->set_rules('linkedin', 'Linkedin', 'trim|valid_url');
                $this->form_validation->set_rules('user_photo', 'profile_picture', 'callback_photoHandleUpload[user_photo]');
                if ($this->form_validation->run() == true) {
                    $data = $this->input->post();
                    $this->profile_model->staffUpdate($data);
                    set_alert('success', translate('information_has_been_updated_successfully'));
                    redirect(base_url('profile'));
                }
            }
            $this->data['staff'] = $this->employee_model->getSingleStaff($userID);
            $this->data['sub_page'] = 'profile/employee';
        }
    }
    public function photoHandleUpload($str, $fields)
    {
        $get_config = $this->db->get_where('global_settings', array('id' => 1))->row_array();
        $this->data['global_config'] = $get_config;
        $allowedExts = array_map('trim', array('strtolower'));
        $allowedSizeKB = $this->data['global_config']['image_size'];
        $allowedSize = floatval(1024 * $allowedSizeKB);
        if (isset($_FILES["$fields"]) && !empty($_FILES["$fields"]['name'])) {
            $file_size = $_FILES["$fields"]["size"];
            $file_name = $_FILES["$fields"]["name"];
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);
            if ($files = filesize($_FILES["$fields"]['tmp_name'])) {
                if (!array(strtolower($extension), $allowedExts)) {
                    $this->form_validation->set_message('photoHandleUpload', translate('this_file_type_is_not_allowed'));
                    return false;
                }
                if ($file_size > $allowedSize) {
                    $this->form_validation->set_message('photoHandleUpload', translate('file_size_shoud_be_less_than') . " $allowedSizeKB KB.");
                    return false;
                }
            } else {
                $this->form_validation->set_message('photoHandleUpload', translate('error_reading_the_file'));
                return false;
            }
            return true;
        }
    }
   
    public function my_children()
    {
        $userID = $this->input->post('loggedin_userid');
        if ($userID) {
            $data   = $this->Api_parent_model->fetchallchild($userID);

            if ($data) {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get children list Successfully!!'
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
                'msg' => "User Id are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }

    public function single_children()
    {
        $userID = $this->input->post('loggedin_userid');    
        $register_no = $this->input->post('register_no');  

        if($userID && $register_no )
        {
            $data   = $this->profile_model->fetchsinglechild($userID,$register_no);

            if ($data) {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get child list Successfully!!'
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
                'msg' => "User Id & Reister Number are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }

    public function getbranchdetails()
    {
        $mobileno = $this->input->post('mobile_no');
        $branchID = $this->input->post('loggedin_branch');

        if ($mobileno && $branchID) {
            $data = $this->Api_parent_model->fetchbranchprofile($mobileno, $branchID);

            if (empty($data)) {
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
            } else {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Child Profile Successfully!'
                );
            }
        } else {
            $json = array(
                'result' => Null,
                'error'    => false,
                'msg' => "User ID & Branch ID are Required"
            );
        }
        header('Content-type: application/json');
        echo json_encode($json);
    }


    public function getallbranch_teacherlist()
    {
        $mobileno = $this->input->post('mobile_no');
        $branchID = $this->input->post('loggedin_branch');
        // $branchID = $this->input->post('loggedin_branch'); 

        if ($mobileno && $branchID) {
            $data = $this->Api_parent_model->getallbranchteacher($mobileno, $branchID);

            if(empty($data)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Teacher list Successfully!!'
                );
            }
        }

        else{
            $json = array(
                'error'    => false,
                'msg' => "Mobile Number & Branch ID are Required");       
        }
        header('Content-type: application/json');
        echo json_encode($json);
    }

    
    public function getteacher_profile()
    {
        $userID = $this->input->post('loggedin_userid');    
        $teacherID = $this->input->post('id');  
        $branchID = $this->input->post('loggedin_branch'); 

        if($userID && $teacherID && $branchID )
        {
            $data   = $this->Api_parent_model->fetchsingleteacher($teacherID,$userID,$branchID);

            $json =  array(
                "result" =>$data,
                'success'  => true,
                "msg" => 'Get Teacher Profile Successfully!!'
            );
        }

        else{
            $json = array(
                'error'    => false,
                'msg' => "USer ID,Branch ID & teacherID Number are Required");       
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }


    public function eventlist()
    {
        $userID = $this->input->post('loggedin_userid');    
        $branchID = $this->input->post('loggedin_branch'); 

        if($userID && $branchID )
        {
            $data = $this->Api_parent_model->geteventlist($userID,$branchID);

            $json =  array(
                "result" =>$data,
                'success'  => true,
                "msg" => 'Get Event Successfully!!'
            );
        }

        else{
            $json = array(
                'error'    => false,
                'msg' => "USer ID & Branch ID are Required");       
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }


    public function attachmentslist()
    {
        $mobileno = $this->input->post('mobile_no');
        $branchID = $this->input->post('loggedin_branch');

        if ($mobileno && $branchID) {
            $data = $this->Api_parent_model->getattachmentslist($mobileno, $branchID);

            if(empty($data)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Attachments list Successfully!!'
                );
            }
        } else {
            $json = array(
                'error'    => false,
                'msg' => "Branch Id And Mobile Number are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }

    public function booklist()
    {
        // $userID = $this->input->post('loggedin_userid');
        $branchID = $this->input->post('loggedin_branch');

        if ($branchID) {
            $data = $this->Api_parent_model->getbooklist($branchID);

            if(empty($data)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Book list Successfully!'
                );
            }
        } else {
            $json = array(
                'error'    => false,
                'msg' => "Branch Id are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }

    public function book_issue_list()
    {
        $id = $this->input->post('id');  
        $branchID = $this->input->post('loggedin_branch'); 

        if($id &&  $branchID)
        {
            $data = $this->Api_parent_model->book_issues($id,$branchID);

            if(empty($data)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Book Issued list Successfully!'
                );
            }
        } else {
            $json = array(
                'error'    => false,
                'msg' => "Branch Id are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }


    public function subjectlist()
    {
        $userID = $this->input->post('loggedin_userid');    
        $register_no = $this->input->post('register_no');  
        $branchID = $this->input->post('loggedin_branch'); 

        if($userID && $branchID )
        {
            $data = $this->Api_parent_model->getbooklist($userID,$branchID);

            if ($data) {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Subject list Successfully!!'
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
                'msg' => "User Id , Branch Id and Register number are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);

    }
    public function logout()
    {
        $userID = $this->input->post('loggedin_userid');
        $branchID = $this->input->post('loggedin_branch');
        if ($userID && $branchID) {

            $this->session->unset_userdata('name');
            $this->session->unset_userdata('logger_photo');
            $this->session->unset_userdata('loggedin_id');
            $this->session->unset_userdata('loggedin_userid');
            $this->session->unset_userdata('loggedin_type');
            $this->session->unset_userdata('set_lang');
            $this->session->unset_userdata('set_session_id');
            $this->session->unset_userdata('loggedin_branch');
            $this->session->unset_userdata('loggedin');
            $this->session->sess_destroy();
            $json = array(
                'error'    => true,
                'msg' => "logout successfull"
            );
            // header('Content-type: application/json');
            // echo json_encode($json);
        } else {
            $json = array(
                'error'    => false,
                'msg' => "User ID & Branch ID are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }
    public function change_pass()
    {
        $mobile_no = $this->input->post('mobile_no');
        $branchID = $this->input->post('loggedin_branch');

        $this->form_validation->set_rules('current_password', 'Current Password', 'trim|required|min_length[4]|callback_check_validate_password');
        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[4]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[4]|matches[new_password]');

        if ($this->form_validation->run() == true) {
            $new_password = $this->input->post('new_password');
            $this->db->where('mobile_no', $mobile_no);
            $this->db->update('login_credential', array('password' => $this->app_lib->pass_hashed($new_password)));
            // password change email alert
            $emailData = array(
                'branch_id' => $branchID,
                'password' => $new_password,
            );
            // $this->Api_parent_model->changePassword($emailData);
            
            set_alert('success', translate('password_has_been_changed'));
            $array = array('status' => 'true', 'msg'=>'success change password');
        } else {
            // $error = $this->form_validation->error_array();
            // $array = array('status' => 'fail', 'error' => $error);
            $array = array('status' => 'fail', 'error' => 'Enter Correct Password & Confirm Password');
        }
        echo json_encode($array);
        exit();
      
    }
    public function check_validate_password($password)
    {

        $mobile_no = $this->input->post('mobile_no');

        if ($password) {
            $getPassword = $this->db->select('password')
                ->where('mobile_no', $mobile_no)
                ->get('login_credential')->row()->password;

                // echo $getPassword;
            $getVerify = $this->app_lib->verify_password($password, $getPassword);
            // echo "sdfnjksdjkfjksd getVerify",$getVerify;
            if ($getVerify) {
                return true;
            } else {
                $this->form_validation->set_message("check_validate_password", translate('current_password_is_invalid'));
                return false;
            }
        }
       
    }
    public function subj_list()
    {
        
        // $userID = $this->input->post('loggedin_userid');
        // $branchID = $this->input->post('loggedin_branch');
        $classID = $this->input->post('class_id');
        if ($classID) {
            $data = $this->Api_parent_model->subjectlist($classID);

           if(empty($data)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get subject list Successfully!!'
                );
            }
        } else {
            $json = array(
                'error'    => false,
                'msg' => "Class Id are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
        
    }
    public function class_schedule()
    {
        // $userID = $this->input->post('loggedin_branch');
        $class_id = $this->input->post('class_id');
        $session_id = $this->input->post('session_id');
        // $classID = $this->input->post('class_id');

        if ($class_id && $session_id) {
            $data = $this->Api_parent_model->class_schedulelist($class_id, $session_id);

            if (empty($data)) {
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
            } else {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Schedule list Successfully!!'
                );
            }
        } else {
            $json = array(
                'result' => Null,
                'error'    => false,
                'msg' => "Class Id & Branch ID are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }
    
    
      public function hostel_list()
    {
        // $userID = $this->input->post('loggedin_userid');    
        $register_no = $this->input->post('register_no');  

        if($register_no)
        {
            $data   = $this->Api_parent_model->fetchhostel($register_no);

            if ($data) {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Hostel list Successfully!!'
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
                'msg' => "Register Number are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);

    }
    
    
    
    
    public function route_list()
    {
        $branchID = $this->input->post('loggedin_branch'); 

        if($branchID)
        {
            $data   = $this->Api_parent_model->fetchroutelist($branchID);

            if ($data) {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Route list Successfully!!'
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
                'msg' => "Branch ID are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);

    }



 public function transport_assign()
    {
        $id = $this->input->post('id');  
        $branchID = $this->input->post('loggedin_branch'); 

        if($id &&  $branchID)
        {
        $data = $this->Api_parent_model->gettransport_assign($id,$branchID);

            if ($data) {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Transport assign list Successfully!'
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
                'msg' => "ID & Branch ID are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);

    }
    
     public function leave_request()
    {
        $id = $this->input->post('id');  
        $branchID = $this->input->post('loggedin_branch');
        
        
        if($id &&  $branchID)
        {

        $data = $this->Api_parent_model->getLeaveList($id,$branchID);

            if ($data) {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Leav Request Successfully!'
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
                'msg' => "ID & Branch are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);

    }
    public function exam()
    {
        $session_id = $this->input->post('session_id');
        $branch_id = $this->input->post('loggedin_branch');

        if ($branch_id && $session_id) {
            $data = $this->Api_parent_model->getExamList($session_id);
            if($data){
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Attachments list Successfully!!'
                );
            }
            else{
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
                'msg' => "Session ID & Branch ID are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }
    public function examschedule()
    {
        $session_id = $this->input->post('session_id');
        $branch_id = $this->input->post('loggedin_branch');
        $exam_id = $this->input->post('exam_id');

        if ($branch_id && $session_id && $exam_id) {
            $data = $this->Api_parent_model->examsbydetail($branch_id,$exam_id);
            if($data){
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Attachments list Successfully!!'
                );
            }
            else{
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
                'msg' => "Session ID & Branch and exam id ID are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }
    
    public function getExamTimetableM()
    {
        $examID = $this->input->post('exam_id');
        $classID = $this->input->post('class_id');
        $sectionID = $this->input->post('section_id');
        $session_id = $this->input->post('session_id');
        $branchID = $this->input->post('loggedin_branch');
        // $this->data['exam_id'] = $examID;
        // $this->data['class_id'] = $classID;
        // $this->data['section_id'] = $sectionID;
        $data = $this->Api_parent_model->getExamTimetableByModal($examID, $classID, $sectionID,$session_id, $branchID);
        // $this->load->view($data);
        $json =  array(
            "result" => $data,
            'success'  => true,
            "msg" => 'Get Attachments list Successfully!!'
        );
        header('Content-type: application/json');
        echo json_encode($json);
        // $this->data
    }
    public function viewexam()
    {
        // $examID = $this->input->post('exam_id');
        $classID = $this->input->post('exam_id');
        $sectionID = $this->input->post('section_id');
        $session_id = $this->input->post('session_id');
        $branch_id = $this->input->post('loggedin_branch');
        if ($branch_id && $session_id && $sectionID && $classID) {
            $data = $this->Api_parent_model->getExamTimetableList($classID, $sectionID, $branch_id, $session_id);
            if ($data) {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Attachments list Successfully!!'
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
                'msg' => "Session ID & Branch and exam ID are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }
    
    
     public function attendance_report()
    {
        $id = $this->input->post('id');  
        $branchID = $this->input->post('loggedin_branch');
        // $date = $this->input->post('date');
        // $d = strval($date);
        
        if($id &&  $branchID)
        {
            
        $data = $this->Api_parent_model->get_attendance_by_date($id,$branchID);
           if ($data) {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Attendance Report Successfully!'
                );
            } else {
                $json =  array(
                    "result" => Null,
                    'success'  => true,
                    "msg" => 'No Data Available'
                );
            }
        } else {
            $json = array(
                "result" => null,
                'success'    => false,
                'msg' => "Id & Branch id and Date are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }
    
    
    public function homeworklist()
    {


        // $userID = $this->input->post('loggedin_userid');    
        $studentID = $this->input->post('child_id');  
         $branchID = $this->input->post('loggedin_branch');


        if($studentID && $branchID)
        {
            // $stu = $this->userrole_model->getStudentDetails();
            $data = $this->Api_parent_model->getHomeworkList($studentID, $branchID);
            if ($data) {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Homework list Successfully!!'
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
                'msg' => "Student Id & Branch Id are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
        
        /*
        $classID = $this->input->post('class_id');    
        $sectionID = $this->input->post('section_id');  
        $subjectID = $this->input->post('subject_id');  
        $session_id = $this->input->post('session_id');  
        $branchID = $this->input->post('loggedin_branch');


        if($sectionID && $branchID)
        {
            // $stu = $this->userrole_model->getStudentDetails();
            $data = $this->Api_parent_model->getHomeworkList($classID, $sectionID, $subjectID, $branchID, $session_id);
            if ($data) {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Homework list Successfully!!'
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
                'msg' => "Class Id, Branch Id and subject id are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
        */

    }
     public function exam_report_card()
    {
        
        $session_id = $this->input->post('session_id');
        $student_id = $this->input->post('student_id');
        if ($student_id && $session_id) {
            $result = $this->Api_parent_model->getStudentReportCard($student_id, $session_id);
            if ($result) {
                $json =  array(
                    "result" => $result,
                    'success'  => true,
                    "msg" => 'Get Attachments list Successfully!!'
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
                'msg' => "Session ID & Branch and exam id ID are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }

    public function fees_invoice(){

        // $id = $this->input->post('id');  
        $userID = $this->input->post('mobileno');   
        $branchID = $this->input->post('loggedin_branch');
        $student_id = $this->input->post('student_id');
        $get_session_id = $this->input->post('get_session_id');

        if($userID && $student_id &&  $branchID && $get_session_id)
        {
        $data = $this->Api_parent_model->getInvoiceStatus();  
        $allocations = $this->Api_parent_model->getInvoiceDetails($student_id,$get_session_id);
        // $data = $this->Api_parent_model->getfee($id,$userID);
        $invoicebasic  = $this->Api_parent_model->getInvoiceBasic($student_id,$userID);
            $json =  array(
                "result" =>$data,
                // "Academic" =>$Academic,
                "invoicebasic" => $invoicebasic ,
                "Invoice To" => $allocations ,
                'success'  => true,
                "msg" => 'Fees Details Get Successfully!!'  
            );
        }

        else{
            $json = array(
                "result" => null,
                'success'    => false,
                'msg' => "Session Id & Branch Id are Required");           
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }
    public function my_kids()
    {
        // $userID = $this->input->post('loggedin_userid');    
        $mobileno = $this->input->post('mobileno');    
        if($mobileno)
        {
            $data   = $this->Api_parent_model->fetchallkids($mobileno);

            if ($data) {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Kids list Successfully!!'
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