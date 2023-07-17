<?php 
defined('BASEPATH') or exit('No direct script access allowed');
 

class ApiTeachersController extends RestApi_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Api_Teachers_Model');
     
        // $this->load->library('Api_Auth');
        // if($this->api_auth->isNotAuthenticated())
        // {
        //     $err = array(
        //         'status'=>false,
        //         'message'=>'unauthorised',
        //         'data'=>[]
        //     );
        //     $this->response($err);
        // }

        /*cache controling*/
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  
        $this->load->helper(array('form', 'url'));
        

    }


    public function index(){

        echo "Hi this is parents API";
    }
    public function test_get() {
        $response['status']     = 'success';
        $response['message']    = 'Rest API is working...';            
        $this->response($response,200);
    }
    public function profile()
    {
        $userID = $this->input->get('userID');
        $branchID = $this->input->get('branchID');

        if ($userID && $branchID) {
            $arrayprofile = $this->Api_Teachers_Model->getSingleStaff($userID, $branchID);
            if(empty($arrayprofile)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $arrayprofile,
                    "success"  => true,
                    "msg" => 'Get profile list Successfully!!'
                );
            }
        } else {
            $json = array(
                'error'    => null,
                'success' => false,
                'msg' => "user id and branch id are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
        
    }
    public function branchlist()
    {
        $arrayBranch = $this->Api_Teachers_Model->getSelectList('branch');
        if(empty($arrayBranch)){
            $json = array(
                'result' => Null,
                'success'    => false,
                'msg' => "No data Found"
            );
            
        }else{
            $json =  array(
                "result" => $arrayBranch,
                'success'  => true,
                "msg" => 'Get Branch list Successfully!'
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);

    }
    public function classlist()
    {
        $branch_id = $this->input->get('branch_id');

        if ($branch_id) {
            $arrayClass = $this->Api_Teachers_Model->getClass($branch_id);
            if(empty($arrayClass)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $arrayClass,
                    "success"  => true,
                    "msg" => 'Get Class list Successfully!!'
                );
            }
        } else {
            $json = array(
                'error'    => null,
                'success' => false,
                'msg' => "Branch id are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }
   

    public function sectionlist()
    {
        $class_id = $this->input->get('class_id');
        // $user_id = $this->input->get('user_id');
        // $get_session_id = $this->input->get('session_id');

        if ($class_id) {
            $arraySection = $this->Api_Teachers_Model->getSections($class_id);
            if(empty($arraySection)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $arraySection,
                    "success"  => true,
                    "msg" => 'Get section list Successfully!!'
                );
            }
        } else {
            $json = array(
                'error'    => null,
                'success' => false,
                'msg' => "Class id are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }
    public function student_list()
    {
        $classID = $this->input->get('class_id');
        $sectionID = $this->input->get('sectionID');
        $branchID = $this->input->get('branchID');
        $get_session_id = $this->input->get('get_session_id');

        if ($classID && $sectionID) {
            $arraySection = $this->Api_Teachers_Model->getStudentList($classID, $sectionID, $branchID, $get_session_id);
            if(empty($arraySection)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $arraySection,
                    "success"  => true,
                    "msg" => 'Get students list Successfully!!'
                );
            }
        } else {
            $json = array(
                'error'    => null,
                'success' => false,
                'msg' => "Class id , Sectioin id, Branch id And Get session id are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }
    public function subjectassignlist()
    {
        $classID = $this->input->get('class_id');
        $sectionID = $this->input->get('sectionID');

        if ($classID && $sectionID) {
            $arraySection = $this->Api_Teachers_Model->getSubjectList($classID, $sectionID);
            if(empty($arraySection)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $arraySection,
                    "success"  => true,
                    "msg" => 'Get students list Successfully!!'
                );
            }
        } else {
            $json = array(
                'error'    => null,
                'success' => false,
                'msg' => "Class id , Sectioin id are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }

    public function homework_list()
    {
        $classID = $this->input->get('class_id');
        $sectionID = $this->input->get('sectionID');
        $branchID = $this->input->get('branchID');
        $subjectID = $this->input->get('subject_id');

        if ($classID && $sectionID) {
        
            $arraySection = $this->Api_Teachers_Model->getListhomework($classID, $sectionID, $subjectID, $branchID);
            if(empty($arraySection)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $arraySection,
                    "success"  => true,
                    "msg" => 'Get students list Successfully!!'
                );
            }
        } else {
            $json = array(
                'error'    => null,
                'success' => false,
                'msg' => "Class id , Sectioin id, Branch id And Get session id are Required"
            );
        }

        header('Content-type: application/json');
        echo json_encode($json);
    }
    public function addhomework()
    {
    
       
        // $data = [
        //         'class_id' => $this->input->post('class_id'),
        //         'section_id' => $this->input->post('section_id'),
        //         'session_id' => $this->input->post('session_id'),
        //         'subject_id' => $this->input->post('subject_id'),
        //         'date_of_homework' => $this->input->post('date_of_homework'),
        //         'date_of_submission' => $this->input->post('date_of_submission'),
        //         'description' => $this->input->post('description'),
        //         'created_by' => $this->input->post('created_by'),
        //         'create_date' => $this->input->post('create_date'),
        //         'status' => $this->input->post('status'),
        //         'sms_notification' => $this->input->post('sms_notification'),
        //         'schedule_date' => $this->input->post('schedule_date'),
        //         'document' => $this->input->post('document'),
        //         'evaluation_date' => $this->input->post('evaluation_date'),
        //         'evaluated_by' => $this->input->post('evaluated_by'),
        //         'branch_id' => $this->input->post('branch_id')
        // ];
    //     $result =  $this->db->insert('homework', $data);
    //   echo json_encode($result);
    //   $this->response($data, 200);
    if ($_POST) {
            $data = array(
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'session_id' => $this->input->post('session_id'),
                'subject_id' => $this->input->post('subject_id'),
                'date_of_homework' => $this->input->post('date_of_homework'),
                'date_of_submission' => $this->input->post('date_of_submission'),
                'description' => $this->input->post('description'),
                'created_by' => $this->input->post('created_by'),
                'create_date' => $this->input->post('create_date'),
                'status' => $this->input->post('status'),
                'sms_notification' => $this->input->post('sms_notification'),
                'schedule_date' => $this->input->post('schedule_date'),
                'document' => $this->input->post('document'),
                'branch_id' => $this->input->post('branch_id')
            );
        
            $result = $this->db->insert('homework', $data);
        
            if ($result) {
                $json = array(
                    'result' => 'true',
                    'success' => true,
                    'msg' => 'Inserted Successfully!!'
                );
            } else {
                $json = array(
                    'result' => null,
                    'success' => false,
                    'msg' => 'Error occurred while inserting data'
                );
            }
        } else {
            $json = array(
                'error' => null,
                'success' => false,
                'msg' => 'Method Not Allowed'
            );
        }
        
        header('Content-type: application/json');
        echo json_encode($json);

    }

    public function updatehomework()
    {
    
        $id = $this->input->post('id');
        $data = [
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'session_id' => $this->input->post('session_id'),
                'subject_id' => $this->input->post('subject_id'),
                'date_of_homework' => $this->input->post('date_of_homework'),
                'date_of_submission' => $this->input->post('date_of_submission'),
                'description' => $this->input->post('description'),
                'created_by' => $this->input->post('created_by'),
                'create_date' => $this->input->post('create_date'),
                'status' => $this->input->post('status'),
                'sms_notification' => $this->input->post('sms_notification'),
                'schedule_date' => $this->input->post('schedule_date'),
                'document' => $this->input->post('document'),
                'evaluation_date' => $this->input->post('evaluation_date'),
                'evaluated_by' => $this->input->post('evaluated_by'),
                'branch_id' => $this->input->post('branch_id')
        ];
      
        $this->db->where('id', $id);
        $this->db->update('homework', $data);
        
        set_alert('success', translate('information_has_been_updated_successfully'));
        $array  = array('status' => 'success');
        echo"information_has_been_updated_successfully";
    }
    public function deletehomework()
    {
        $id = $this->input->post('id');

        $this->db->where('id', $id);
        $this->db->delete('homework');

        echo"information_has_been_deleted_successfully";
    }

    
    public function book_teacher_list()
    {
        
        $branch_id = $this->input->post('branch_id');
            $arraySection = $this->Api_Teachers_Model->getbook_list($branch_id);
            if(empty($arraySection)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $arraySection,
                    "success"  => true,
                    "msg" => 'Get students list Successfully!!'
                );
            }

        header('Content-type: application/json');
        echo json_encode($json);
    }

    public function event_list()
        {
            $branch_id = $this->input->get('branch_id');
            
        $arraySection = $this->Api_Teachers_Model->getevent_list($branch_id);
        if(empty($arraySection)){
            $json = array(
                'result' => Null,
                'success'    => false,
                'msg' => "No data Found"
            );
            
        }else{
            $json =  array(
                "result" => $arraySection,
                "success"  => true,
                "msg" => 'Get students list Successfully!!'
            );
        }
        header('Content-type: application/json');
        echo json_encode($json);

        }
    
        // public function addbookissue()
        // {
        //     $data =[
        //         'book_id' => $this->input->post('book_id'),
        //         'user_id' => $this->input->post('user_id'),
        //         'role_id' => $this->input->post('role_id'),
        //         'date_of_issue' => $this->input->post('date_of_issue'),
        //         'date_of_expiry' => $this->input->post('date_of_expiry'),
        //         'return_date' => $this->input->post('return_date'),
        //         'fine_amount' => $this->input->post('fine_amount'),
        //         'status' => $this->input->post('status'),
        //         'issued_by' => $this->input->post('issued_by'),
        //         'return_by' => $this->input->post('return_by'),
        //         'session_id' => $this->input->post('session_id'),
        //         'branch_id' => $this->input->post('branch_id'),
        //         'created_at' => $this->input->post('created_at')
        //     ];
        
        //     echo json_encode($data);
        //     $result = $this->db->insert('book_issues', $data);
        
        //     $response = array(
        //         'result' => $result,
        //         'status' => true,
        //         'message' => 'Book issued successfully.'
        //     );
        
        //     $this->response($response, 200);
        // }
        function getbookissue() {
            $sessionID = $this->input->get('sessionID');
            $userID = $this->input->get('userID');
            $roleID = $this->input->get('roleID');
            $this->data['result'] = $this->Api_Teachers_Model->bookissue($sessionID, $userID, $roleID);
            $this->data['status'] = true;
            $this->data['message'] = 'Book issued successfully.';
    
            // Return the API response
            echo json_encode($this->data);
        }
    
        public function addbookissue()
        {
            
            // Perform input validation using form validation library
            $this->form_validation->set_rules('book_id', translate('book_title'), 'required|callback_validation_stock');
            $this->form_validation->set_rules('user_id', translate('user_id'), 'trim|required');
            $this->form_validation->set_rules('date_of_issue', translate('date_of_issue'), 'trim|required');
            $this->form_validation->set_rules('date_of_expiry', translate('date_of_expiry'), 'trim|required|callback_validation_date');
            
            // Check if validation passes
            if ($this->form_validation->run() !== false) {
                // Prepare data for inserting into the database
                $arrayIssue = array(
                    'branch_id' => $this->input->post('branch_id'),
                    'book_id' => $this->input->post('book_id'),
                    'user_id' => $this->input->post('user_id'),
                    'role_id' => $this->input->post('role_id'),
                    'date_of_issue' => date("Y-m-d", strtotime($this->input->post('date_of_issue'))),
                    'date_of_expiry' => date("Y-m-d", strtotime($this->input->post('date_of_expiry'))),
                    'issued_by' => $this->input->post('user_id'),
                    'status' => 0,
                    'session_id' => $this->input->post('session_id'),
                );

                // Insert data into the database
                $this->db->insert('book_issues', $arrayIssue);

                $response = array('result' => 'success' , 'status' => true,  'message' => array('msg' => 'Information has been saved successfully'));
            } else {
                // Validation failed, return the validation errors
                $error = $this->form_validation->error_array();
                $response = array('result' => 'fail','status' => false, 'message' => $error);
            }

            // Send JSON response
            echo json_encode($response);
            exit();
    
            
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
        // validation date
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

            
        public function reportcardlist()
        {
            $SessionID = $this->input->get('sessionID');
            $examID = $this->input->get('examID'); 
            $classID = $this->input->get('classID');
            $sectionID = $this->input->get('sectionID');
            $branchID = $this->input->get('branchID');
            $studentID = $this->input->get('studentID');


            if ($examID && $sectionID) {
            
                if ($studentID) {
                    $arraySection = $this->Api_Teachers_Model->exam_reportcard_print($SessionID, $examID, $studentID);
                }else{
                    $arraySection = $this->Api_Teachers_Model->getreportcardlist($SessionID, $examID, $classID, $sectionID, $branchID);
                }
                // $arraySection = $this->Api_Teachers_Model->getreportcardlist($SessionID, $examID, $classID, $sectionID, $branchID);
    
                if(empty($arraySection)){
                    $json = array(
                        'result' => Null,
                        'success'    => false,
                        'msg' => "No data Found"
                    );
                    
                }else{
                    $json =  array(
                        "result" => $arraySection,
                        "success"  => true,
                        "msg" => 'Get students list Successfully!!'
                    );
                }
            } else {
                $json = array(
                    'error'    => null,
                    'success' => false,
                    'msg' => "sessionID , examID, classID And Get session id are Required"
                );
            }

            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function payroll_list()
        {
            $branch_id = $this->input->get('branch_id');
            $staffID = $this->input->get('staffID');
            $month = date("m", strtotime($this->input->get('month_year')));
            $year = date("Y", strtotime($this->input->get('month_year')));

            if ($branch_id) {
            
                $arraySection = $this->Api_Teachers_Model->getpayrolllist($branch_id, $month, $year, $staffID);
    
                if(empty($arraySection)){
                    $json = array(
                        'result' => Null,
                        'success'    => false,
                        'msg' => "No data Found"
                    );
                    
                }else{
                    $json =  array(
                        "result" => $arraySection,
                        "success"  => true,
                        "msg" => 'Get month list Successfully!!'
                    );
                }
            } else {
                $json = array(
                    'error'    => null,
                    'success' => false,
                    'msg' => "monthly_year are Required"
                );
            }

            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function classteachealo()
        {
            $branch_id = $this->input->get('branch_id');
            $sessionid = $this->input->get('sessionid');

            if ($branch_id) {
            
                $arraySection = $this->Api_Teachers_Model->classtecherallocation($branch_id, $sessionid);
                if(empty($arraySection)){
                    $json = array(
                        'result' => Null,
                        'success'    => false,
                        'msg' => "No data Found"
                    );
                    
                }else{
                    $json =  array(
                        "result" => $arraySection,
                        "success"  => true,
                        "msg" => 'Get  list Successfully!!'
                    );
                }
            } else {
                $json = array(
                    'error'    => null,
                    'success' => false,
                    'msg' => "monthly_year are Required"
                );
            }

            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function subjectlist()
        {
            $branch_id = $this->input->get('branch_id');
            $sessionid = $this->input->get('sessionid');

            if ($branch_id) {
            
                $arraySection = $this->Api_Teachers_Model->subjectlist($branch_id, $sessionid);
                if(empty($arraySection)){
                    $json = array(
                        'result' => Null,
                        'success'    => false,
                        'msg' => "No data Found"
                    );
                    
                }else{
                    $json =  array(
                        "result" => $arraySection,
                        "success"  => true,
                        "msg" => 'Get  list Successfully!!'
                    );
                }
            } else {
                $json = array(
                    'error'    => null,
                    'success' => false,
                    'msg' => "monthly_year are Required"
                );
            }

            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function classSchedulelist()
        {
            $branch_id = $this->input->get('branch_id');
            $class_id = $this->input->get('class_id');
            $section_id = $this->input->get('section_id');
            $session_id = $this->input->get('session_id');

            if ($branch_id) {
            
                $arraySection = $this->Api_Teachers_Model->classSchedulelist($branch_id, $class_id, $section_id, $session_id);
                if(empty($arraySection)){
                    $json = array(
                        'result' => Null,
                        'success'    => false,
                        'msg' => "No data Found"
                    );
                    
                }else{
                    $json =  array(
                        "result" => $arraySection,
                        "success"  => true,
                        "msg" => 'Get  list Successfully!!'
                    );
                }
            } else {
                $json = array(
                    'error'    => null,
                    'success' => false,
                    'msg' => "classSchedulelist are Required"
                );
            }

            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function teacherSchedulelist()
        {
            $branch_id = $this->input->get('branch_id');
            $teacher_id = $this->input->get('teacher_id');
            $session_id = $this->input->get('session_id');

            if ($branch_id) {
            
                $arraySection = $this->Api_Teachers_Model->teacherSchedulelist($branch_id, $teacher_id, $session_id);
                if(empty($arraySection)){
                    $json = array(
                        'result' => Null,
                        'success'    => false,
                        'msg' => "No data Found"
                    );
                    
                }else{
                    $json =  array(
                        "result" => $arraySection,
                        "success"  => true,
                        "msg" => 'Get  list Successfully!!'
                    );
                }
            } else {
                $json = array(
                    'error'    => null,
                    'success' => false,
                    'msg' => "teacher Schedulelist are Required"
                );
            }

            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function classassignlist()
        {
            $branch_id = $this->input->get('branch_id');
            $session_id = $this->input->get('session_id');
            if ($branch_id) {
            
                $arraySection = $this->Api_Teachers_Model->classassignlist($branch_id, $session_id );
                if(empty($arraySection)){
                    $json = array(
                        'result' => Null,
                        'success'    => false,
                        'msg' => "No data Found"
                    );
                    
                }else{
                    $json =  array(
                        "result" => $arraySection,
                        "success"  => true,
                        "msg" => 'Get  list Successfully!!'
                    );
                }
            } else {
                $json = array(
                    'error'    => null,
                    'success' => false,
                    'msg' => "class assign list are Required"
                );
            }

            header('Content-type: application/json');
            echo json_encode($json);
        }
        // ------------------------------------------------------------------------------------start---------------------------------------------
        public function examtermlist()
        {
            $branch_id = $this->input->get('branch_id');
            $session_id = $this->input->get('session_id');
            

            $arraySection = $this->Api_Teachers_Model->examtermlist($branch_id,$session_id);
            if(empty($arraySection)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $arraySection,
                    "success"  => true,
                    "msg" => 'Get  list Successfully!!'
                );
            }
            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function add_term_list()
        {
            $name = $this->input->post('term_name');
            $branch_id = $this->input->post('branch_id');
            $session_id = $this->input->post('session_id');
            if(!empty($name) && !empty($branch_id) && !empty($session_id)){
                $arrayTerm = array(
                    'name' => $name,
                    'branch_id' => $branch_id,
                    'session_id' => $session_id,
                );
                // echo "--arrayTerm--",json_encode($arrayTerm);
                //save exam term information in the database file
                $arraySection = $this->Api_Teachers_Model->termSave($arrayTerm);
                if($arraySection){
                    $json = array(
                        'result' => 'insert',
                        'success'    => true,
                        'msg' => "Data has been inserted"
                    );
                    
                }
                header('Content-type: application/json');
                echo json_encode($json);
                exit();
            }else{
                $json = array(
                        'result' => 'false',
                        'success'    => false,
                        'msg' => "All Field are required"
                    );
                header('Content-type: application/json');
                echo json_encode($json);
                exit();
            }
        }

        public function term_delete()
        {
            $id = $this->input->post('id');

            $this->db->where('id', $id);
            if($this->db->delete('exam_term')){
                $json = array(
                    'result' => 'Deleted',
                    'success'    => true,
                    'msg' => "Deleted successfully"
                );
                
            }
            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function examtype()
        {
            $arraySection = array(
                '1' => 'Mark',
                '2' => 'Grade(gpa)',
                '3' => "Mark And Grade"
            );
            $result = array();
            foreach ($arraySection as $key => $value) {
                $result[] = array(
                    'id' => $key,
                    'name' => $value,
                );
            }
            if(empty($arraySection)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $result,
                    "success"  => true,
                    "msg" => 'Get  list Successfully!!'
                );
            }
            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function mark_distribution()
        {
            if (isset($_POST)) {
                
                $this->form_validation->set_rules('name', translate('name'), 'trim|required');
                if ($this->form_validation->run() !== false) {
                    // save mark distribution information in the database file
                    $arrayDistribution = array(
                        'name' => $this->input->post('name'),
                        'branch_id' => $this->input->post('branchId'),
                    );
                    $this->db->insert('exam_mark_distribution', $arrayDistribution);
                    $array = array(
                        'result' => 'insert',
                        'success'    => true,
                        'msg' => "Data has been inserted"
                    );
                    echo json_encode($array);
                    exit();
                }
            }
            $branchId = $this->input->get('branch_id');
            $sessionId = $this->input->get('session_id');
            if ($branchId && $sessionId) {
               
            
                $examlist = $this->Api_Teachers_Model->exam_mark_distribution($branchId,$sessionId);
                if(empty($examlist)){
                    $json = array(
                        'result' => Null,
                        'success'    => false,
                        'msg' => "No data Found"
                    );
                    
                }else{
                    $json =  array(
                        "result" => $examlist,
                        "success"  => true,
                        "msg" => 'Get  list Successfully!!'
                    );
                } 
            }else {
                $json = array(
                    'error'    => null,
                    'success' => false,
                    'msg' => "Branch Id and session Id  are Required"
                );
            }
            header('Content-type: application/json');
            echo json_encode($json);
         
        }
        public function mark_distribution_edit()
        {
            if (isset($_POST)) {
                
                $this->form_validation->set_rules('name', translate('name'), 'trim|required');
                $this->form_validation->set_rules('distribution_id', translate('id'), 'trim|required');
                if ($this->form_validation->run() !== false) {
                    // save mark distribution information in the database file
                    $arrayDistribution = array(
                        'name' => $this->input->post('name'),
                        'branch_id' => $this->input->post('branch_id'),
                    );
                    $this->db->where('id', $this->input->post('distribution_id'));
                    $this->db->update('exam_mark_distribution', $arrayDistribution);
                    
                    $array = array(
                        'result' => 'update',
                        'success'    => true,
                        'msg' => "Data has been updated"
                    );
                } else {
                    $error = $this->form_validation->error_array();
                    $array = array('error'    => null,
                    'success' => false,
                    'msg' => $error);
                }
                echo json_encode($array);
            }
         
        }
        public function examsetup()
        {
           
            if ($_POST) {
                
                $post = $this->input->post();
                $this->Api_Teachers_Model->exam_save($post);
                exit();
            }
            $branchId = $this->input->get('branch_id');
            $sessionId = $this->input->get('session_id');
            if ($branchId && $sessionId) {
               
                $examlist = $this->Api_Teachers_Model->getExamList($branchId,$sessionId);
                if(empty($examlist)){
                    $json = array(
                        'result' => Null,
                        'success'    => false,
                        'msg' => "No data Found"
                    );
                    
                }else{
                    $json =  array(
                        "result" => $examlist,
                        "success"  => true,
                        "msg" => 'Get  list Successfully!!'
                    );
                } 
            }else {
                $json = array(
                    'error'    => null,
                    'success' => false,
                    'msg' => "Branch Id and session Id  are Required"
                );
            }
            header('Content-type: application/json');
            echo json_encode($json);
            
        }

        public function viewexamschedule()
        {
        
            if (isset($_POST)) {
                $examID = $this->input->post('exam_id');
                $classID = $this->input->post('class_id');
                $sectionID = $this->input->post('section_id');
                $result = $this->Api_Teachers_Model->getExamTimetableByModal($examID, $classID, $sectionID);
                if(empty($result)){
                    $json = array(
                        'result' => Null,
                        'success'    => false,
                        'msg' => "No data Found"
                    );
                    
                }else{
                    $json =  array(
                        "result" => $result,
                        "success"  => true,
                        "msg" => 'Get  list Successfully!!'
                    );
                } 
                header('Content-type: application/json');
                echo json_encode($json);
                exit();
            }
            $classID = $this->input->get('class_id');
            $branchID =  $this->input->get('branch_id');
            $sectionID = $this->input->get('section_id');
            if ($branchID && $classID) {
               
                $examlist = $this->Api_Teachers_Model->getExamTimetableList($classID, $sectionID, $branchID);
                if(empty($examlist)){
                    $json = array(
                        'result' => Null,
                        'success'    => false,
                        'msg' => "No data Found"
                    );
                    
                }else{
                    $json =  array(
                        "result" => $examlist,
                        "success"  => true,
                        "msg" => 'Get  list Successfully!!'
                    );
                } 
            }else {
                $json = array(
                    'error'    => null,
                    'success' => false,
                    'msg' => "Branch Id and Class Id  are Required"
                );
            }
            header('Content-type: application/json');
            echo json_encode($json);
        }

        public function mark_entry()
        {
            $branch_id = $this->input->get('branch_id');
            $classID = $this->input->get('class_id');
            $sectionID = $this->input->get('section_id');
            $subjectID = $this->input->get('subject_id');
            $examID = $this->input->get('exam_id');
            $sessionID = $this->input->get('session_id');
            // $arraySection = $this->Api_Teachers_Model->mark_entry($branch_id, $classID, $sectionID, $subjectID, $examID );
            // $timetable_detail = $this->Api_Teachers_Model->getTimetableDetail($classID, $sectionID, $examID, $subjectID,$sessionID);
            $student = $this->Api_Teachers_Model->getMarkAndStudent($branchID, $classID, $sectionID, $examID, $subjectID,$sessionID);
            
            if(empty($timetable_detail)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "timetable_detail" => $timetable_detail,
                    "student" => $student,
                    "success"  => true,
                    "msg" => 'mark list Successfully!!'
                );
            }
            header('Content-type: application/json');
            echo json_encode($json);
    
            
        }
        function exam_hall() {
            
            $branchID =  $this->input->get('branch_id');
            if ($branchID) {
               
                $examlist = $this->Api_Teachers_Model->exam_hall_list($branchID);
                if(empty($examlist)){
                    $json = array(
                        'result' => Null,
                        'success'    => false,
                        'msg' => "No data Found"
                    );
                    
                }else{
                    $json =  array(
                        "result" => $examlist,
                        "success"  => true,
                        "msg" => 'Get  list Successfully!!'
                    );
                } 
            }else {
                $json = array(
                    'error'    => null,
                    'success' => false,
                    'msg' => "Branch Id Required"
                );
            }
            header('Content-type: application/json');
            echo json_encode($json);
        }
        function exam_hall_add() {
            $hall_no = $this->input->post('hall_no');
            $seats = $this->input->post('seats');
            $branch_id = $this->input->post('branch_id');
            if (!empty($hall_no) && !empty($seats) && !empty($branch_id)) {
                $data = array(
                    'hall_no' => $hall_no,
                    'seats' => $seats,
                    'branch_id' => $branch_id
                    
                );
                $result = $this->db->insert('exam_hall', $data);

                if(empty($result)){
                    $json = array(
                        'result' => Null,
                        'success'    => false,
                        'msg' => "No data Found"
                    );
                    
                }else{
                    $json =  array(
                        "result" => $result,
                        "success"  => true,
                        "msg" => 'insert Successfully!!'
                    );
                } 
                header('Content-type: application/json');
                echo json_encode($json);
                exit();
            }else{
                $json =  array(
                    "result" => $result,
                    "success"  => true,
                    "msg" => 'Fill all data'
                );
            } 
            header('Content-type: application/json');
            echo json_encode($json);
            exit();
            
        }
        function exam_hall_edit() {
            
            $id          = $this->input->post('id');
            if ($id) {
                $hall_no     = $this->input->post('hall_no');
                $seats       = $this->input->post('seats');
                $branch_id   = $this->input->post('branch_id');
                if (!empty($hall_no) && !empty($seats) && !empty($branch_id)) {

                    $data = array(
                        'hall_no' => $hall_no,
                        'seats' => $seats,
                        'branch_id' => $branch_id
                    );
                    // $result = $this->db->insert('exam_hall', $data);
                    $this->db->where('id', $id);
                    $result = $this->db->update('exam_hall', $data);

                    if(empty($result)){
                        $json = array(
                            'result' => Null,
                            'success'    => false,
                            'msg' => "No data Found"
                        );
                        
                    }else{
                        $json =  array(
                            "result" => $result,
                            "success"  => true,
                            "msg" => 'Updated Successfully!!'
                        );
                    } 
                    header('Content-type: application/json');
                    echo json_encode($json);
                    exit();
                }else{
                    $json =  array(
                        "result" => $result,
                        "success"  => true,
                        "msg" => 'Fill all data'
                    );
                } 
                header('Content-type: application/json');
                echo json_encode($json);
                exit();
            }else{
                $json =  array(
                    "result" => $result,
                    "success"  => true,
                    "msg" => 'Invalid Id'
                );
            } 
            header('Content-type: application/json');
            echo json_encode($json);
            exit();
        }
        public function exam_hall_delete()
        {
            $id = $this->input->post('id');
            $branch_id = $this->input->post('branch_id');
            
            if (!empty($id)) {
                $this->db->where('branch_id', $branch_id);
                $this->db->where('id', $id);
                
                $result = $this->db->delete('exam_hall');
                
                if ($this->db->affected_rows() > 0) {
                    // Deletion successful
                    $json = array(
                        'result' => $result,
                        'success' => true,
                        'msg' => 'Deleted Successfully!'
                    );
                } else {
                    // No matching data found for deletion
                    $json = array(
                        'result' => null,
                        'success' => false,
                        'msg' => 'No data found for deletion'
                    );
                }
            } else {
                // Invalid or missing ID
                $json = array(
                    'result' => null,
                    'success' => false,
                    'msg' => 'Invalid ID'
                );
            }
            
            header('Content-type: application/json');
            echo json_encode($json);
        }
        // ---------------------------------------------------------------------------end-------------------------------------------
        public function addexamterm()
        {
            $data =[
                'name' => $this->input->post('name'),
                'branch_id' => $this->input->post('branch_id'),
                'session_id' => $this->input->post('session_id'),
                
            ];
        
            echo json_encode($data);
            $result = $this->db->insert('exam_term', $data);
        
            $response = array(
                'result' => true,
                'status' => true,
                'message' => 'Examterm Add successfully.'
            );
        
            $this->response($response, 200);
        }
        public function examschedulelist()
        {
            $class_id = $this->input->get('class_id');
            $section_id = $this->input->get('section_id');
            $branch_id = $this->input->get('branch_id');
            if ($branch_id) {
            
                $arraySection = $this->Api_Teachers_Model->examschedulelist($class_id, $section_id,$branch_id);
                if(empty($arraySection)){
                    $json = array(
                        'result' => Null,
                        'success'    => false,
                        'msg' => "No data Found"
                    );
                    
                }else{
                    $json =  array(
                        "result" => $arraySection,
                        "success"  => true,
                        "msg" => 'Get  list Successfully!!'
                    );
                }
            } else {
                $json = array(
                    'error'    => null,
                    'success' => false,
                    'msg' => "Exam Schedual list are Required"
                );
            }

            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function mailinboxlist()
        {
            $arraySection = $this->Api_Teachers_Model->mailinboxlist();
            if(empty($arraySection)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $arraySection,
                    "success"  => true,
                    "msg" => 'Get  list Successfully!!'
                );
            }
            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function certificates_templetelist()
        {
            $arraySection = $this->Api_Teachers_Model->certificates_templetelist();
            if(empty($arraySection)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $arraySection,
                    "success"  => true,
                    "msg" => 'Get  list Successfully!!'
                );
            }
            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function advancesalarylist()
        {
            $arraySection = $this->Api_Teachers_Model->advancesalarylist();
            if(empty($arraySection)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $arraySection,
                    "success"  => true,
                    "msg" => 'Get  list Successfully!!'
                );
            }
            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function leavecategorylist()
        {
            $arraySection = $this->Api_Teachers_Model->leavecategorylist();
            if(empty($arraySection)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $arraySection,
                    "success"  => true,
                    "msg" => 'Get  list Successfully!!'
                );
            }
            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function leavelistteacher()
        {
            $user_id = $this->input->get('user_id');
            $role_id = $this->input->get('role_id');
            $session_id=$this->input->get('session_id');
            if ($user_id) {
            
                $arraySection = $this->Api_Teachers_Model->getLeaveList($user_id, $role_id,$session_id);
                if(empty($arraySection)){
                    $json = array(
                        'result' => Null,
                        'success'    => false,
                        'msg' => "No data Found"
                    );
                    
                }else{
                    $json =  array(
                        "result" => $arraySection,
                        "success"  => true,
                        "msg" => 'Get  list Successfully!!'
                    );
                }
            } else {
                $json = array(
                    'error'    => null,
                    'success' => false,
                    'msg' => "leave list are Required"
                );
            }

            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function getawardlist()
        {
            $arraySection = $this->Api_Teachers_Model->getawardlist();
            if(empty($arraySection)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $arraySection,
                    "success"  => true,
                    "msg" => 'Get award list Successfully!!'
                );
            }
            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function addleaverequest()
        {
            $data =[
                'name' => $this->input->post('name'),
                'role_id' => $this->input->post('role_id'),
                'days' => $this->input->post('days'),
                'branch_id' => $this->input->post('branch_id'),
            ];
            
            $result = $this->db->insert('leave_category', $data);
            if($result){
                $response = array(
                    'result' => 'success',
                    'status' => true,
                    'message' => 'Leave request add successfully.'
                );
            }else{
                $response = array(
                    'result' => 'fail',
                    'status' => false,
                    'message' => 'Leave Request Failed.'
                );
            }
        
            $this->response($response, 200);
        }
        public function liveclassroomlist()
        {
            $arraySection = $this->Api_Teachers_Model->liveclassroomlist();
            if(empty($arraySection)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $arraySection,
                    "success"  => true,
                    "msg" => 'liveclassroomlist Successfully!!'
                );
            }
            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function addliveclass()
        {
            $data = array(
                'live_class_method' => $this->input->post('live_class_method'),
                'title' => $this->input->post('title'),
                'meeting_id' => $this->input->post('meeting_id'),
                'meeting_password' => $this->input->post('meeting_password'),
                'own_api_key' => $this->input->post('own_api_key'), 
                'duration' => $this->input->post('duration'),
                'bbb' => $this->input->post('bbb'),
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'remarks' => $this->input->post('remarks'),
                'date' => $this->input->post('date'),
                'start_time' => $this->input->post('start_time'),
                'end_time' => $this->input->post('end_time'),
                'created_by' => $this->input->post('created_by'),
                'status' => $this->input->post('status'),
                'created_at' => $this->input->post('created_at'),
                'branch_id' => $this->input->post('branch_id'),           
            );
        
            $result = $this->db->insert('live_class', $data);
        
            if ($result) {
                $response = array(
                    'status' => true,
                    'message' => 'Live class added successfully.'
                );
                $this->response($response, 200);
            } else {
                $response = array(
                    'status' => false,
                    'message' => 'Failed to add live class.'
                );
                $this->response($response, 500);
            }
        }
        public function liveclassreportlist()
        {
            $branchID=$this->input->get('branchID');
                $classID = $this->input->get('class_id');
                $sectionID = $this->input->get('section_id');
                $method = $this->input->get('live_class_method');
                $daterange = explode(' - ', $this->input->get('daterange'));
                $start = date("Y-m-d", strtotime($daterange[0]));
                $end = date("Y-m-d", strtotime($daterange[1]));
            if ($classID) {
            
                $arraySection = $this->Api_Teachers_Model->getLeaveList($classID, $sectionID, $method, $start, $end, $branchID);
                if(empty($arraySection)){
                    $json = array(
                        'result' => Null,
                        'success'    => false,
                        'msg' => "No data Found"
                    );
                    
                }else{
                    $json =  array(
                        "result" => $arraySection,
                        "success"  => true,
                        "msg" => 'Get  list Successfully!!'
                    );
                }
            } else {
                $json = array(
                    'error'    => null,
                    'success' => false,
                    'msg' => "leave list are Required"
                );
            }

            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function attachmentbooklist()
        {
            $branch_id = $this->input->get('branch_id');
            $session_id = $this->input->get('session_id');
            if ($session_id) {
                $arraySection = $this->Api_Teachers_Model->attachmentbooklist($branch_id, $session_id);
                if(empty($arraySection)){
                    $json = array(
                        'result' => Null,
                        'success'    => false,
                        'msg' => "No data Found"
                    );
                    
                }else{
                    $json =  array(
                        "result" => $arraySection,
                        "success"  => true,
                        "msg" => 'Attachment book list Successfully!!'
                    );
                }
            }else {
                    $json = array(
                        'error'    => null,
                        'success' => false,
                        'msg' => "branch id and session id  are Required"
                    );
            }
            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function addAttachmentBook()
        {
            $data = array(
                'title' => $this->input->post('title'),
                'remarks' => $this->input->post('remarks'),
                'type_id' => $this->input->post('type_id'),
                'uploader_id' => $this->input->post('uploader_id'),
                'class_id' => $this->input->post('class_id'), 
                'file_name' => $this->input->post('file_name'),
                'enc_name' => $this->input->post('enc_name'),
                'subject_id' => $this->input->post('subject_id'),
                'session_id' => $this->input->post('session_id'),
                'date' => $this->input->post('date'),
                'branch_id' => $this->input->post('branch_id'),
                'updated_at' => $this->input->post('updated_at')
            );
        
            $result = $this->db->insert('attachments', $data);
        
            if ($result) {
                $response = array(
                    'status' => true,
                    'message' => 'Attachment book list added successfully.'
                );
                $this->response($response, 200);
            } else {
                $response = array(
                    'status' => false,
                    'message' => 'Failed to add attachment book list.'
                );
                $this->response($response, 500);
            }
        }
        public function attachmenttypelist()
        {
            $branch_id = $this->input->get('branch_id');
            $arraySection = $this->Api_Teachers_Model->attachmenttypelist($branch_id);
            if(empty($arraySection)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $arraySection,
                    "success"  => true,
                    "msg" => 'Attachment list Successfully!!'
                );
            }
            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function evaluationreportlist() 

        {
            $class_id = $this->input->get('class_id');
            $section_id = $this->input->get('section_id');
            $subject_id = $this->input->get('subject_id');
            $branch_id = $this->input->get('branch_id');
            $arraySection = $this->Api_Teachers_Model->evaluationreportlist($class_id,$section_id,$subject_id,$branch_id);
            if(empty($arraySection)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $arraySection,
                    "success"  => true,
                    "msg" => 'Attachment book list Successfully!!'
                );
            }
            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function sentmessage()
        {
            $data = array(
                'body' => $this->input->post('body'),
                'subject' => $this->input->post('subject'),
                'file_name' => $this->input->post('file_name'),
                'enc_name' => $this->input->post('enc_name'),
                'trash_sent' => $this->input->post('trash_sent'), 
                'trash_inbox' => $this->input->post('trash_inbox'),
                'fav_inbox' => $this->input->post('fav_inbox'),
                'fav_sent' => $this->input->post('fav_sent'),
                'reciever' => $this->input->post('reciever'),
                'sender' => $this->input->post('sender'),
                'read_status' => $this->input->post('read_status'),
                'reply_status' => $this->input->post('reply_status'),
                'created_at' => $this->input->post('created_at'),
                'updated_at' => $this->input->post('updated_at')

            );
        
            $result = $this->db->insert('message', $data);
        
            if ($result) {
                $response = array(
                    'status' => true,
                    'message' => 'Message Sent successfully.'
                );
                $this->response($response, 200);
            } else {
                $response = array(
                    'status' => false,
                    'message' => 'Failed Message Sent.'
                );
                $this->response($response, 500);
            }
        }
        public function attendanceReportStudent() 
    {
        $classId = $this->input->get('class_id');
        $sectionId = $this->input->get('section_id');
        $date = $this->input->get('date');
        $branchId = $this->input->get('branch_id');
        $attendanceData = $this->Api_Teachers_Model->attendanceReportStudent($classId, $sectionId, $date, $branchId);
        
        if(empty($attendanceData)){
            $response = array(
                'success' => false,
                'message' => "No attendance data found"
            );
        }else{
            $response = array(
                'success' => true,
                'data' => $attendanceData,
                'message' => "Attendance report generated successfully"
            );
        }
        
        header('Content-type: application/json');
        echo json_encode($response);
    }
    public function examsetuplist()
        {
            
            $branch_id = $this->input->get('branch_id');
            $arraySection = $this->Api_Teachers_Model->examsetuplist($branch_id);
            if(empty($arraySection)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $arraySection,
                    "success"  => true,
                    "msg" => 'Attachment book list Successfully!!'
                );
            }
            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function create_exam()
        {
            $data = array(
                'name' => $this->input->post('name'),
                'term_id' => $this->input->post('term_id'),
                'type_id' => $this->input->post('type_id'),
                'session_id' => $this->input->post('session_id'),
                'branch_id' => $this->input->post('branch_id'), 
                'remark' => $this->input->post('remark'),
                'mark_distribution' => $this->input->post('mark_distribution'),
                'created_at' => $this->input->post('created_at'),
                'updated_at' => $this->input->post('updated_at')

            );
        echo json_encode($data);
            $result = $this->db->insert('exam', $data);
        
            if ($result) {
                $response = array(
                    'status' => true,
                    'message' => 'Message Sent successfully.'
                );
                $this->response($response, 200);
            } else {
                $response = array(
                    'status' => false,
                    'message' => 'Failed Message Sent.'
                );
                $this->response($response, 500);
            }
        }
        
    
        public function graderangemark()
        {
            $arraySection = $this->Api_Teachers_Model->graderangemark();
            if(empty($arraySection)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $arraySection,
                    "success"  => true,
                    "msg" => 'graderangemarklist Successfully!!'
                );
            }
            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function onlinemarklist()
        {
            $arraySection = $this->Api_Teachers_Model->onlinemarklist();
            if(empty($arraySection)){
                $json = array(
                    'result' => Null,
                    'success'    => false,
                    'msg' => "No data Found"
                );
                
            }else{
                $json =  array(
                    "result" => $arraySection,
                    "success"  => true,
                    "msg" => 'onlinemarklist Successfully!!'
                );
            }
            header('Content-type: application/json');
            echo json_encode($json);
        }
        public function addonlineexam()
        {
            $data = array(
                'title' => $this->input->post('title'),
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'subject_id' => $this->input->post('subject_id'),
                'limits_participation' => $this->input->post('limits_participation'), 
                'exam_start' => $this->input->post('exam_start'),
                'exam_end' => $this->input->post('exam_end'),
                'duration' => $this->input->post('duration'),
                'mark_type' => $this->input->post('mark_type'),
                'passing_mark' => $this->input->post('passing_mark'),
                'instruction' => $this->input->post('instruction'),
                'session_id ' => $this->input->post('session_id '),
                'publish_result ' => $this->input->post('publish_result'),
                'marks_display' => $this->input->post('marks_display'),
                'neg_mark' => $this->input->post('neg_mark'),
                'question_type' => $this->input->post('question_type'),
                'publish_status' => $this->input->post('publish_status'),
                'exam_type' => $this->input->post('exam_type'),
                'fee' => $this->input->post('fee'),
                'created_by' => $this->input->post('created_by'),
                'position_generated' => $this->input->post('position_generated'),
                'branch_id' => $this->input->post('fee'),
                'created_at' => $this->input->post('created_at'),
                'updated_at' => $this->input->post('updated_at')

            );
        echo json_encode($data);
            $result = $this->db->insert('online_exam', $data);
        
            if ($result) {
                $response = array(
                    'status' => true,
                    'message' => 'Message Sent successfully.'
                );
                $this->response($response, 200);
            } else {
                $response = array(
                    'status' => false,
                    'message' => 'Failed Message Sent.'
                );
                $this->response($response, 500);
            }
        }
        
    public function questionlist()
    {
        
        $branch_id = $this->input->get('branch_id');
        $arraySection = $this->Api_Teachers_Model->questionlist($branch_id);
        if(empty($arraySection)){
            $json = array(
                'result' => Null,
                'success'    => false,
                'msg' => "No data Found"
            );
            
        }else{
            $json =  array(
                "result" => $arraySection,
                "success"  => true,
                "msg" => 'questionlist Successfully!!'
            );
        }
        header('Content-type: application/json');
        echo json_encode($json);
    }
    public function questionbankadd()
    {
        $data = array(
            'type' => $this->input->post('type'),
            'level' => $this->input->post('level'),
            'class_id' => $this->input->post('class_id'),
            'section_id' => $this->input->post('section_id'),
            'subject_id' => $this->input->post('subject_id'), 
            'group_id' => $this->input->post('group_id'),
            'question' => $this->input->post('question'),
            'opt_1' => $this->input->post('opt_1'),
            'opt_2' => $this->input->post('opt_2'),
            'opt_3' => $this->input->post('opt_3'),
            'opt_4' => $this->input->post('opt_4'),
            'answer ' => $this->input->post('answer '),
            'mark ' => $this->input->post('mark'),
            'branch_id' => $this->input->post('branch_id'),
            'created_by' => $this->input->post('created_by'),
            'created_at' => $this->input->post('created_at'),
            'updated_at' => $this->input->post('updated_at')

        );
    echo json_encode($data);
        $result = $this->db->insert('questions', $data);

        if ($result) {
            $response = array(
                'status' => true,
                'message' => 'Message Sent successfully.'
            );
            $this->response($response, 200);
        } else {
            $response = array(
                'status' => false,
                'message' => 'Failed Message Sent.'
            );
            $this->response($response, 500);
        }
    }
    public function questiongrouplist()
    {
        
        $branch_id = $this->input->get('branch_id');
        $arraySection = $this->Api_Teachers_Model->questiongrouplist($branch_id);
        if(empty($arraySection)){
            $json = array(
                'result' => Null,
                'success'    => false,
                'msg' => "No data Found"
            );
            
        }else{
            $json =  array(
                "result" => $arraySection,
                "success"  => true,
                "msg" => 'questionlist Successfully!!'
            );
        }
        header('Content-type: application/json');
        echo json_encode($json);
    }
    public function getExamAttendence()
    {
        
        $classID = $this->input->get('class_id');
        $sectionID = $this->input->get('section_id');
        $examID = $this->input->get('exam_id');
        $subjectID = $this->input->get('subject_id');
        $branchID = $this->input->get('branch_id');
        $arraySection = $this->Api_Teachers_Model->getExamAttendence($classID, $sectionID, $examID, $subjectID, $branchID);
        if(empty($arraySection)){
            $json = array(
                'result' => Null,
                'success'    => false,
                'msg' => "No data Found"
            );
            
        }else{
            $json =  array(
                "result" => $arraySection,
                "success"  => true,
                "msg" => 'questionlist Successfully!!'
            );
        }
        header('Content-type: application/json');
        echo json_encode($json);
    }
    
    public function add_exam_setup()
    {
        $branchId = $this->input->post('branchId');
        $sessionId = $this->input->post('sessionId');
        $term_id = $this->input->post('term_id');
        $type_id = $this->input->post('type_id');
        $name = $this->input->post('name');
        // $mark_distribution[] = $this->input->post('mark_distribution');
        $remark = $this->input->post('remark');

        $mark_distribution = $this->input->post('mark_distribution');
        $mark_distribution_array = explode(',', $mark_distribution);
        $mark_distribution_json = json_encode($mark_distribution_array);

        if (!empty($name) && !empty($branchId) && !empty($sessionId) && !empty($term_id) && !empty($type_id) && !empty($mark_distribution)) {
            $arrayExam = array(
                'name' => $name,
                'branch_id' => $branchId,
                'term_id' => $term_id,
                'type_id' => $type_id,
                'mark_distribution' => $mark_distribution_json,
                'remark' => $remark,
                'session_id' => $sessionId,
            );
            $this->db->insert('exam', $arrayExam);
            // $examsetup = $this->Api_Teachers_Model->exam_save($arrayExam);
            $array = array(
                'result' => 'insert',
                'success'    => true,
                'msg' => "Data has been inserted"
            );
        }else{
            $array = array(
                'result' => false,
                'success'    => false,
                'msg' => "All field are required"
            );
        }
        
        header('Content-type: application/json');
        echo json_encode($array);            
    }
    
    public function student_attendance()
    {
        // echo "----------------------------";
        if (isset($_POST['search'])) {
            $branchID = $this->input->post('branch_id');
            $this->form_validation->set_rules('class_id', translate('class'), 'required');
            $this->form_validation->set_rules('section_id', translate('section'), 'required');
            $this->form_validation->set_rules('date', translate('date'), 'trim|required|callback_get_valid_date');
            if ($this->form_validation->run() == true) {
                $classID = $this->input->post('class_id');
                $sectionID = $this->input->post('section_id');
                $session_id = $this->input->post('session_id');
                $date = $this->input->post('date');
                // $this->data['date'] = $date;
                $data = $this->Api_Teachers_Model->getStudentAttendence($classID, $sectionID, $date, $branchID, $session_id);
                if(!empty($data)){
                    $json =  array(
                        "result" => $data,
                        "success"  => true,
                        "msg" => 'questionlist Successfully!!'
                    );
                }else{
                    $json =  array(
                        "result" => Null,
                        "success"  => true,
                        "msg" => 'Empty data!'
                    );
                }
            } else {
                $errors = validation_errors(); // Retrieve all form validation errors
                $errors = str_replace("\n", ", ", strip_tags($errors));
                $json = array(
                    'result' => false,
                    'success'    => false,
                    'msg' => $errors
                );
            }
            header('Content-type: application/json');
            echo json_encode($json);
        }
        if (isset($_POST['save'])) {
            $attendance = $this->input->post('attendance');
            $date = $this->input->post('date');
            $branchID = $this->input->post('branch_id');

            if (!empty($attendance) && !empty($date) && !empty($branchID)) {
                          
                foreach ($attendance as $key => $value) {
                    $attStatus = (isset($value['status']) ? $value['status'] : "");
                    
                    $arrayAttendance = array(
                        'student_id' => $value['enroll_id'],
                        'status' => $attStatus,
                        'remark' => $value['remark'],
                        'date' => $date,
                        'branch_id' => $branchID,
                    );
                    if (empty($value['attendance_id'])) {
                        $this->db->insert('student_attendance', $arrayAttendance);
                    } else {
                        // print_r($value);
                        $this->db->where('id', $value['attendance_id']);
                        $this->db->update('student_attendance', array('status' => $attStatus, 'remark' => $value['remark']));
                        // echo $this->db->last_query();
                    }
                }           
                $json =  array(
                    "result" => 'success',
                    "success"  => true,
                    "msg" => 'information has been updated successfully'
                );
            } else {
                $json = array(
                    'result' => false,
                    'success'    => false,
                    'msg' => 'branch id, date and attendance field required'
                );
            }
            header('Content-type: application/json');
            echo json_encode($json);
        }
        
    }
    public function get_valid_date($date)
    {
        date_default_timezone_set("Asia/Kolkata");
        $present_date = date('Y-m-d');
        $date = date("Y-m-d", strtotime($date));
        if ($date > $present_date) {
            $this->form_validation->set_message("get_valid_date", "Please Enter Correct Date");
            return false;
        } else {
            return true;
        }
    }
    public function addlogbook()
    {
        $this->form_validation->set_rules('date', translate('date'), 'trim|required');
        $this->form_validation->set_rules('lec_no', translate('lec_no'), 'trim|required');
        $this->form_validation->set_rules('std', translate('std'), 'trim|required');
        $this->form_validation->set_rules('sub_name', translate('sub_name'), 'trim|required');
        $this->form_validation->set_rules('start_time', translate('start_time'), 'trim|required');
        $this->form_validation->set_rules('end_time', translate('end_time'), 'trim|required');
        $this->form_validation->set_rules('cource_planning', translate('cource_planning'), 'required');
        $this->form_validation->set_rules('homework', translate('homework'), 'trim|required');
        $this->form_validation->set_rules('branch_id', translate('branch_id'), 'trim|required');
        $this->form_validation->set_rules('teacher_id', translate('teacher_id'), 'trim|required');
        $this->form_validation->set_rules('session_id', translate('session_id'), 'trim|required');
    
        if ($this->form_validation->run() !== false) {
            $date = $this->input->post('date');
            $lec_no = $this->input->post('lec_no');
            $std = $this->input->post('std');
            $sub_name = $this->input->post('sub_name');
            $start_time = $this->input->post('start_time');
            $end_time = $this->input->post('end_time');
            $cource_planning = $this->input->post('cource_planning');
            $homework = $this->input->post('homework');
            $branch_id = $this->input->post('branch_id');
            $teacher_id = $this->input->post('teacher_id');
            $session_id = $this->input->post('session_id');
    
            $arrayData = array(
                'date' => $date,
                'lec_no' => $lec_no,
                'std' => $std,
                'sub_name' => $sub_name,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'cource_planning' => $cource_planning,
                'homework' => $homework,
                'branch_id' => $branch_id,
                'teacher_id' => $teacher_id,
                'session_id' => $session_id,
            );
    
            $this->db->insert('logbook', $arrayData);
            set_alert('success', translate('information_has_been_saved_successfully'));
    
            $response = array(
                // 'result' => $result,
                'status' => true,
                'message' => 'Logbook added successfully.'
                // 'result' => $insert_id
    
            );
    
            $this->response($response, 200);
        } else {
            $response = array(
                'status' => false,
                'message' => 'Validation error',
                'errors' => $this->form_validation->error_array()
            );
    
            $this->response($response, 400);
        }
    }
    public function logbook_list()
    {
        $branch_id = $this->input->get('branch_id');
        $session_id = $this->input->get('session_id');
        $teacher_id = $this->input->get('teacher_id');
        
        if ($branch_id && $session_id && $teacher_id) {
            
            
            // Call the model function to fetch logbook data
            $logbookData = $this->Api_Teachers_Model->logbook_list($branch_id, $session_id, $teacher_id);
            
            // Prepare the JSON response
            if (empty($logbookData)) {
                $json = array(
                    'result' => Null,
                    'success' => false,
                    'msg' => "No data Found"
                );
            } else {
                $json = array(
                    "result" => $logbookData,
                    "success" => true,
                    "msg" => 'Get list Successfully!!'
                );
            }
        }else{
            $json = array(
                "result" => $logbookData,
                "success" => true,
                "msg" => 'branch id, session id and teacher id are required'
            );
        }
        
        header('Content-type: application/json');
        echo json_encode($json);
    }
    public function updatelogbook()
    {
        $id = $this->input->post('id');
        $data = [
                'date' => $this->input->post('date'),
                'lec_no' => $this->input->post('lec_no'),
                'std' => $this->input->post('std'),
                'sub_name' => $this->input->post('sub_name'),
                'start_time' => $this->input->post('start_time'),
                'end_time' => $this->input->post('end_time'),
                'cource_planning' => $this->input->post('cource_planning'),
                'homework' => $this->input->post('homework'),
                'branch_id' => $this->input->post('branch_id'),
                'teacher_id' => $this->input->post('teacher_id'),
                'session_id' => $this->input->post('session_id')
        ];
      
        $this->db->where('id', $id);
        $this->db->update('logbook', $data);
        
        set_alert('success', translate('information_has_been_updated_successfully'));
        $array  = array('status' => 'success');
        $json =  array(
            // "result" => $arraySection,
            "success"  => true,
            "msg" => 'information_has_been_updated_successfully'
        );
        echo json_encode($json);
    }
    public function deletelogbook()
    {
        $id = $this->input->post('id');
    
        $this->db->where('id', $id);
        $this->db->delete('logbook');
    
        $json =  array(
            // "result" => $arraySection,
            "success"  => true,
            "msg" => 'information_has_been_deleted_successfully'
        );
        echo json_encode($json);
        
    }

}