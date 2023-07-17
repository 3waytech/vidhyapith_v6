<?php 
defined('BASEPATH') or exit('No direct script access allowed');
 

class ApiStudentsController extends RestApi_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Api_Students_Model');
     
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

        /*cache controling*/
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  
        $this->load->helper(array('form', 'url'));
        

    }


    public function index(){

        echo "Hi this is parents API";
    }

    /* getting all teachers list */
    public function teachers()
    {
        $branch_id = $this->input->get('branch_id');
        if ($branch_id) {
            $data   = $this->Api_Students_Model->getTeachersList($branch_id);

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
        // $branch_id = get_loggedin_branch_id();
		// $employees = $this->userrole_model->getTeachersList($branch_id);
    }


    // parents information api 
    public function parent_info(){

         $loggedin_userid = $this->input->get('loggedin_userid');

          if ($loggedin_userid) {
            $data   = $this->Api_Students_Model->getparentinfo($loggedin_userid);

            if ($data) {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Parents Info Successfully!!'
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
                'msg' => "Loggedin Id are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);

    }

    // profile info 

    public function profile(){

        $loggedin_userid = $this->input->get('loggedin_userid');

         if ($loggedin_userid) {
           $data   = $this->Api_Students_Model->getprofileinfo($loggedin_userid);

           if ($data) {
               $json =  array(
                   "result" => $data,
                   'success'  => true,
                   "msg" => 'Get Profile Info Successfully!!'
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
               'msg' => "Loggedin User Id are Required"
           );
       }

       header('Content-type: application/json');
       echo json_encode($json);

   }


   public function attachments(){
            $branch_id = $this->input->get('loggedin_branch');

            if ($branch_id) {
            $data   = $this->Api_Students_Model->getattachments($branch_id);

            if ($data) {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get attachments Successfully!!'
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
                'msg' => "Branch Id are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
   }

   public function event()
   {
        $branch_id = $this->input->get('loggedin_branch');

            if ($branch_id) {
                $data   = $this->Api_Students_Model->getevent($branch_id);

                if ($data) {
                    $json =  array(
                        "result" => $data,
                        'success'  => true,
                        "msg" => 'Get Events Successfully!!'
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
                    'msg' => "Branch Id are Required"
                );
            }

            header('Content-type: application/json');
            echo json_encode($json);
    }

    public function booklist()
    {
        $branch_id = $this->input->get('loggedin_branch');

            if ($branch_id) {
                $data   = $this->Api_Students_Model->getbook($branch_id);

                if ($data) {
                    $json =  array(
                        "result" => $data,
                        'success'  => true,
                        "msg" => 'Get Book List Successfully!!'
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
                    'msg' => "Branch Id are Required"
                );
            }

            header('Content-type: application/json');
            echo json_encode($json);
    }



    public function book_issue()
    {
        $id = $this->input->get('loggedin_userid');  
        $branchID = $this->input->get('loggedin_branch'); 


        if($id &&  $branchID)
        {
            $data = $this->Api_Students_Model->book_issues($id,$branchID);

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


    public function route_list()
    {
        $branchID = $this->input->get('loggedin_branch'); 

        if($branchID)
        {
            $data   = $this->Api_Students_Model->fetchroutelist($branchID);

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


    public function hostel_list()
    {
        $id = $this->input->get('loggedin_userid');  

        if($id)
        {
            $data   = $this->Api_Students_Model->fetchhostel($id);

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
                'msg' => "Loggeding User Id are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);

    }
    
    public function subjects()
    {
        $classID = $this->input->get('classID');
        if ($classID) {
            $data   = $this->Api_Students_Model->subjectlist($classID);

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
                'msg' => "User Id are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }
    public function homeworklist()
    {

        // $userID = $this->input->post('loggedin_userid');    
        $studentID = $this->input->get('student_id');  
        $branchID = $this->input->get('loggedin_branch');


        if($studentID && $branchID)
        {
            // $stu = $this->userrole_model->getStudentDetails();
            $data = $this->Api_Students_Model->getHomeworkList($studentID, $branchID);
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


    }
    
    
    
     public function attendance()
    {
        $id = $this->input->get('loggedin_userid');  
        $branchID = $this->input->get('loggedin_branch'); 
        
        if($id &&  $branchID)
        {
            
        $data = $this->Api_Students_Model->get_attendance_by_date($id,$branchID);
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
    
    public function leave_list()
    {
        $id = $this->input->get('loggedin_userid');  
        $branchID = $this->input->get('loggedin_branch'); 
        
        if($id &&  $branchID)
        {

        $data = $this->Api_Students_Model->getLeaveList($id,$branchID);

            if ($data) {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Leave List Successfully!'
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
    
    
    
    
    public function live_class_list()
    {
        $class_id = $this->input->get('class_id');  
        $branchID = $this->input->get('loggedin_branch'); 
        
        if($class_id &&  $branchID)
        {

        $data = $this->Api_Students_Model->getliveclassList($class_id,$branchID);

            if ($data) {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Live Class List Successfully!'
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
                'msg' => "Class ID & Branch are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);

    }



    
    public function online_exam()
    {
        $class_id = $this->input->get('class_id');  
        $branchID = $this->input->get('loggedin_branch'); 
        
        if($class_id &&  $branchID)
        {

        $data = $this->Api_Students_Model->getonlineexamList($class_id,$branchID);

            if ($data) {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Online Exam List Successfully!'
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
                'msg' => "Class ID & Branch are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);

    }
    
    
    
    public function class_schedule_stud()
    {
        $class_id = $this->input->get('class_id');  
        $session_id = $this->input->get('set_session_id');

        if ($class_id && $session_id) {
            $data = $this->Api_Students_Model->class_schedulelist($class_id, $session_id);

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
                'msg' => "Fields are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }
    
    
    
    public function exam_stud()
    {
        $session_id = $this->input->get('set_session_id');

        if ($session_id) {
            $data = $this->Api_Students_Model->getExamList($session_id);
            if($data){
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Exam list Successfully!!'
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
                'msg' => "Session ID are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }

    public function examschedule_stud()
    {
        // $session_id = $this->input->get('set_session_id');
        $branch_id = $this->input->get('loggedin_branch');
        $exam_id = $this->input->get('exam_id');

        if ($branch_id && $exam_id) {
            $data = $this->Api_Students_Model->examsbydetail($branch_id,$exam_id);
            if($data){
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Exam schedules list Successfully!!'
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
                'msg' => "Branch and exam id ID are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }    


  public function viewexam_stud()
    {
        $classID = $this->input->get('exam_id');
        $sectionID = $this->input->get('section_id');
        $session_id = $this->input->get('session_id');
        $branch_id = $this->input->get('loggedin_branch');
        if ($branch_id && $session_id && $sectionID && $classID) {
            $data = $this->Api_Students_Model->getExamTimetableList($classID, $sectionID, $branch_id, $session_id);
            if ($data) {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get View Exam list Successfully!!'
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
    
    
    
    

    public function reportcard_stud()
    {
        
        $session_id = $this->input->get('session_id');
        $student_id = $this->input->get('loggedin_id');
        if ($student_id && $session_id) {
            $result = $this->Api_Students_Model->getStudentReportCard($student_id, $session_id);
            if ($result) {
                $json =  array(
                    "result" => $result,
                    'success'  => true,
                    "msg" => 'Get Report Card Successfully!!'
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
                'msg' => "Session ID & loggedin id are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }
    
    
    
   public function change_pass_stud()
    {
        $loggedin_userid = $this->input->post('loggedin_userid');
        $branchID = $this->input->post('loggedin_branch');

        $this->form_validation->set_rules('current_password', 'Current Password', 'trim|required|min_length[4]|callback_check_validate_password');
        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[4]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[4]|matches[new_password]');

        if ($this->form_validation->run() == true) {
            $new_password = $this->input->post('new_password');
            $this->db->where('user_id', $loggedin_userid);
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

        $loggedin_userid = $this->input->post('loggedin_userid');

        if ($password) {
            $getPassword = $this->db->select('password')
                ->where('user_id', $loggedin_userid)
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
    
    
     
    public function fees_history()
    {
 
        $branchID = $this->input->get('loggedin_branch');
        $student_id = $this->input->get('loggedin_userid');
        $get_session_id = $this->input->get('get_session_id');

        if($student_id &&  $branchID && $get_session_id)
        {
        $data = $this->Api_Students_Model->getInvoiceStatus();  
        $allocations = $this->Api_Students_Model->getInvoiceDetails($student_id,$get_session_id);
        // $data = $this->Api_parent_model->getfee($id,$userID);
        $invoicebasic  = $this->Api_Students_Model->getInvoiceBasic($student_id);
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

    public function leave_request()
    {
        $stu = $this->Api_Students_Model->getStudentDetails();
        if ($stu) {
            $this->form_validation->set_rules('leave_category', translate('leave_category'), 'required|callback_leave_check');
            $this->form_validation->set_rules('daterange', translate('leave_date'), 'trim|required|callback_date_check');
            // $this->form_validation->set_rules('attachment_file', translate('attachment'), 'callback_handle_upload');

            if ($this->form_validation->run() !== false) {
                $leave_type_id = $this->input->post('leave_category');
                $branch_id = $this->application_model->get_branch_id();
                $daterange = explode(' - ', $this->input->post('daterange'));
                $start_date = date("Y-m-d", strtotime($daterange[0]));
                $end_date = date("Y-m-d", strtotime($daterange[1]));
                $reason = $this->input->post('reason');
                $apply_date = date("Y-m-d H:i:s");
                $datetime1 = new DateTime($start_date);
                $datetime2 = new DateTime($end_date);
                $leave_days = $datetime2->diff($datetime1)->format("%a") + 1;
                $orig_file_name = '';
                $enc_file_name = '';
                // upload attachment file
                if (isset($_FILES["attachment_file"]) && !empty($_FILES['attachment_file']['name'])) {
                    $config['upload_path'] = './uploads/attachments/leave/';
                    $config['allowed_types'] = "*";
                    $config['max_size'] = '2024';
                    $config['encrypt_name'] = true;
                    $this->upload->initialize($config);
                    $this->upload->do_upload("attachment_file");
                    $orig_file_name = $this->upload->data('orig_name');
                    $enc_file_name = $this->upload->data('file_name');
                }
                $arrayData = array(
                    'user_id' => $stu['student_id'],
                    'role_id' => 7,
                    'session_id' => $this->input->post('session_id'),
                    'category_id' => $leave_type_id,
                    'reason' => $reason,
                    'branch_id' => $branch_id,
                    'start_date' => date("Y-m-d", strtotime($start_date)),
                    'end_date' => date("Y-m-d", strtotime($end_date)),
                    'leave_days' => $leave_days,
                    'status' => 1,
                    'orig_file_name' => $orig_file_name,
                    'enc_file_name' => $enc_file_name,
                    'apply_date' => $apply_date,
                );
                $this->db->insert('leave_application', $arrayData);
                $array = array('result'=>array('key'=>'success'),'status' => 'true', 'msg'=>'information_has_been_saved_successfully');
                // echo json_encode($array);
                // echo 'success ', translate('information_has_been_saved_successfully');
                // redirect(base_url('userrole/leave_request'));
            }
        }
        // $where = array('la.user_id' => $stu['student_id'], 'la.role_id' => 7);
        // $leavedata = $this->Api_Students_Model->getLeaveListstudent($where);
        
        echo json_encode($array);
        exit();
    }

    // date check for leave request
    public function date_check($daterange)
    {
        $daterange = explode(' - ', $daterange);
        $start_date = date("Y-m-d", strtotime($daterange[0]));
        $end_date = date("Y-m-d", strtotime($daterange[1]));
        $today = date('Y-m-d');
        if ($today == $start_date) {
            $array = array('result'=>array('key'=>'date check'),'status' => 'true', 'msg'=>'You can not leave the current day.');
            echo json_encode($array);
            exit();
            // echo translate('date_check'), "You can not leave the current day.";
            return false;
        }
        if ($this->input->post('student_id')) {
            $applicant_id = $this->input->post('student_id');
            $role_id = '7';
        } else {
            $applicant_id = get_loggedin_user_id();
            $role_id = loggedin_role_id();
        }
        $getUserLeaves = $this->db->get_where('leave_application', array('user_id' => $applicant_id, 'role_id' => $role_id))->result();
        if (!empty($getUserLeaves)) {
            foreach ($getUserLeaves as $user_leave) {
                $get_dates = $this->user_leave_days($user_leave->start_date, $user_leave->end_date);
                $result_start = in_array($start_date, $get_dates);
                $result_end = in_array($end_date, $get_dates);
                if (!empty($result_start) || !empty($result_end)) {
                    $array = array('result'=>array('key'=>'date check'),'status' => 'true', 'msg'=>'Already have leave in the selected time.');
                    // echo translate('date_check'), 'Already have leave in the selected time.';
                    echo json_encode($array);
                    exit();
                    return false;
                }
            }
        }
        return true;
    }

    public function leave_check($type_id)
    {
        if (!empty($type_id)) {
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start_date = date("Y-m-d", strtotime($daterange[0]));
            $end_date = date("Y-m-d", strtotime($daterange[1]));

            if ($this->input->post('student_id')) {
                $applicant_id = $this->input->post('student_id');
                $role_id = '7';
            } else {
                $applicant_id = get_loggedin_user_id();
                $role_id = loggedin_role_id();
            }
            if (!empty($start_date) && !empty($end_date)) {
                $leave_total = get_type_name_by_id('leave_category', $type_id, 'days');
                $total_spent = $this->db->select('IFNULL(SUM(leave_days), 0) as total_days')
                    ->where(array('user_id' => $applicant_id, 'role_id' => $role_id, 'category_id' => $type_id, 'status' => '2'))
                    ->get('leave_application')->row()->total_days;

                $datetime1 = new DateTime($start_date);
                $datetime2 = new DateTime($end_date);
                $leave_days = $datetime2->diff($datetime1)->format("%a") + 1;
                $left_leave = ($leave_total - $total_spent);
                if ($left_leave < $leave_days) {
                    $array = array('result'=>array('key'=>'leave check'),'status' => 'true', 'msg'=>'Applyed for '.$leave_days .'days, get maximum '.$left_leave.' Days days.');
                    echo json_encode($array);
                    exit();
                    // echo translate('leave_check'), "Applyed for $leave_days days, get maximum $left_leave Days days.";
                    return false;
                } else {
                    return true;
                }
            } else {
                $array = array('result'=>array('key'=>'leave check'),'status' => 'true', 'msg'=>'Select all required field.');
                echo json_encode($array);
                exit();
                // echo translate('leave_check'), "Select all required field.";
                return false;
            }
        }
    }

    public function user_leave_days($start_date, $end_date)
    {
        $dates = array();
        $current = strtotime($start_date);
        $end_date = strtotime($end_date);
        while ($current <= $end_date) {
            $dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }
        return $dates;
    }
    public function leave_check_today()
    {
        $branch_id = $this->input->get('branch_id');
        if ($branch_id) {
            $data   = $this->Api_Students_Model->student_leave_type($branch_id);

            if ($data) {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Students Leave Type List Successfully!!'
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
                'msg' => "Branch Id are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }
    
    public function book_title()
    {
        $branch_id = $this->input->get('branch_id');
        if ($branch_id) {
            $data   = $this->Api_Students_Model->book_issue_request($branch_id);

            if ($data) {
                $json =  array(
                    "result" => $data,
                    'success'  => true,
                    "msg" => 'Get Book issued request Successfully!'
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
                'msg' => "Branch Id are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }


    public function book_request()
    {
        $stu = $this->Api_Students_Model->getStudentDetails();
        if ($_POST) {
            $this->form_validation->set_rules('book_id', translate('book_title'), 'required|callback_validation_stock');
            $this->form_validation->set_rules('date_of_issue', translate('date_of_issue'), 'trim|required');
            $this->form_validation->set_rules('date_of_expiry', translate('date_of_expiry'), 'trim|required|callback_validation_date');
            if ($this->form_validation->run() !== false) {
                $arrayIssue = array(
                    'branch_id' => $stu['branch_id'],
                    'book_id' => $this->input->post('book_id'),
                    'user_id' => $stu['student_id'],
                    'role_id' => 7,
                    'date_of_issue' => date("Y-m-d", strtotime($this->input->post('date_of_issue'))),
                    'date_of_expiry' => date("Y-m-d", strtotime($this->input->post('date_of_expiry'))),
                    'issued_by' => $stu['student_id'],
                    'status' => 0,
                    'session_id' => $stu['session_id'],
                );
                $this->db->insert('book_issues', $arrayIssue);
                $json =  array(
                    "result" => array('key'=>'success'),
                    'success'  => 'true',
                    "msg" => 'Get Book request Successfully!'
                );
            } else {
                $json =  array(
                    "result" => null,
                    'success'  => 'true',
                    "msg" => 'book id, date_of_issue and date_of_expiry are required'
                );
            }
            echo json_encode($json);
            exit();
        }
    }

    // book date validation
    public function validation_date($date)
    {
        if ($date) {
            $date = strtotime($date);
            $today = strtotime(date('Y-m-d'));
            if ($today >= $date) {
                $this->form_validation->set_message("validation_date", translate('today_or_the_previous_day_can_not_be_issued'));
                return false;
            } else {
                return true;
            }
        }
    }

    // validation book stock
    public function validation_stock($book_id)
    {
        $query = $this->db->select('total_stock,issued_copies')->where('id', $book_id)->get('book')->row_array();
        $stock = $query['total_stock'];
        $issued = $query['issued_copies'];
        if ($stock == 0 || $issued >= $stock) {
            $this->form_validation->set_message("validation_stock", translate('the_book_is_not_available_in_stock'));
            return false;
        } else {
            return true;
        }
    }


}