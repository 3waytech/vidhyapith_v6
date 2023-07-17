<?php

class Api_Teachers_Model extends CI_Model{

  
    public function login_credential($username, $password)
    {
        $this->db->select('*');
        $this->db->from('login_credential');
        $this->db->where('username', $username);
        $this->db->where('role', '3');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            $verify_password = $this->app_lib->verify_password($password, $query->row()->password);
            if ($verify_password) {
                return $query->row();
            }
        }
        return false;
    }

    public function getUserNameByRoleID($roleID, $userID = '')
    {
        if ($roleID == 6) {
            $sql = "SELECT `name`,`email`,`photo`,`branch_id`,`mobileno` FROM `parent` WHERE `id` = " . $this->db->escape($userID);
            return $this->db->query($sql)->row_array();
        } elseif ($roleID == 7) {
            $sql = "SELECT `student`.`id`, CONCAT_WS(' ',`student`.`first_name`, `student`.`last_name`) as `name`, `student`.`email`, `student`.`photo`, `enroll`.`branch_id`,enroll.class_id,enroll.section_id FROM `student` INNER JOIN `enroll` ON `enroll`.`student_id` = `student`.`id` WHERE `student`.`id` = " . $this->db->escape($userID);
            return $this->db->query($sql)->row_array();
        } else {
            $sql = "SELECT `name`,`email`,`photo`,`branch_id` FROM `staff` WHERE `id` = " . $this->db->escape($userID);
            return $this->db->query($sql)->row_array();
        }
    }
    public function getSelectList($table, $all = '')
    {
        $arrayData = array("" => translate('select'));
        if ($all == 'all') {
            $arrayData['all'] = translate('all_select');
        }
        $result = $this->CI->db->get($table)->result();
        foreach ($result as $row) {
            $arrayData[$row->id] = $row->name;
        }
        return $arrayData;
    }
    public function getClass($branch_id)
    {
        $this->db->select('id, name');
        $this->db->from('class');
        $this->db->where('branch_id', $branch_id);
        $result = $this->db->get()->result_array();
        
        return $result;
    }
    public function getSections($class_id = '')
    {
        $result = $this->db->select('sections_allocation.section_id,section.name as section_name')
                    ->from('sections_allocation')
                    ->join('section', 'section.id = sections_allocation.section_id', 'left')
                    ->where('sections_allocation.class_id', $class_id)
                    ->get()->result_array();
                
        return $result;
    }
    public function getStudentList($classID = '', $sectionID = '', $branchID = '', $get_session_id)
    {
        $this->db->select('e.*,s.photo, CONCAT_WS(" ", s.first_name, s.last_name) as fullname,s.register_no,s.parent_id,s.email,s.blood_group,s.birthday,l.active,c.name as class_name,se.name as section_name');
        $this->db->from('enroll as e');
        $this->db->join('student as s', 'e.student_id = s.id', 'inner');
        $this->db->join('login_credential as l', 'l.user_id = s.id and l.role = 7', 'inner');
        $this->db->join('class as c', 'e.class_id = c.id', 'left');
        $this->db->join('section as se', 'e.section_id=se.id', 'left');
        $this->db->where('e.class_id', $classID);
        $this->db->where('e.branch_id', $branchID);
        $this->db->where('e.session_id', $get_session_id);
        $this->db->where('e.section_id', $sectionID);
        $this->db->order_by('s.id', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }
    
    // GET SINGLE EMPLOYEE DETAILS
    public function getSingleStaff($userID, $branchID)
    {
        // echo "-------------------------------";

        $this->db->select('staff.*,staff_designation.name as designation_name,staff_department.name as department_name,login_credential.role as role_id,login_credential.active,login_credential.username, roles.name as role, branch.name as branch_name');
        $this->db->from('staff');
        $this->db->join('login_credential', 'login_credential.user_id = staff.id and login_credential.role != "6" and login_credential.role != "7"', 'inner');
        $this->db->join('branch', 'branch.id = staff.branch_id', 'left');
        $this->db->join('roles', 'roles.id = login_credential.role', 'left');
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');
        $this->db->where('staff.id', $userID);
        $this->db->where('staff.branch_id', $branchID);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            show_404();
        }
        return $query->row_array();
    }
    
    public function getSubjectList($classID = '', $sectionID = '')
    {
        $sessionId = $this->input->get('sessionId');
        $this->db->select('subject_assign.subject_id, subject.name as subjectname');
        $this->db->from('subject_assign');
        $this->db->join('subject', 'subject.id = subject_assign.subject_id', 'left');
        $this->db->where('class_id', $classID);
        $this->db->where('section_id', $sectionID);
        $this->db->where('session_id', $sessionId);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getListhomework($classID, $sectionID, $subjectID, $branchID)
    {
        $this->db->select('homework.*,subject.name as subject_name,class.name as class_name,section.name as section_name,staff.name as creator_name');
        $this->db->from('homework');
        $this->db->join('subject', 'subject.id = homework.subject_id', 'left');
        $this->db->join('class', 'class.id = homework.class_id', 'left');
        $this->db->join('section', 'section.id = homework.section_id', 'left');
        $this->db->join('staff', 'staff.id = homework.created_by', 'left');
        $this->db->where('homework.class_id', $classID);
        $this->db->where('homework.section_id', $sectionID);
        $this->db->where('homework.subject_id', $subjectID);
        $this->db->where('homework.branch_id', $branchID);
        $this->db->order_by('homework.id', 'desc');
        return $this->db->get()->result_array();
    }
    
    public function getbook_list($branch_id)
    {
        try {
            $this->db->select("t.*,b.name as branch_name");
            $this->db->from("book as t");
            $this->db->join("branch as b", "b.id = t.branch_id", "left");
            $this->db->where("branch_id", $branch_id);
            $query = $this->db->get();
            if ($query) {
                return $query->result_array();
            } else {
                throw new Exception('Database query error: '.$this->db->error()['message']);
            }
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
        
    public Function getevent_list($branch_id)
    {
        $this->db->select("*");
        $this->db->from("event");
        $this->db->join("event_types as et", "et.id = event.type", "left");
        $this->db->where("event.branch_id", $branch_id);
        $query = $this->db->get();
        
        return $query->result_array();
    
    }
    public function getreportcardlist($sessionID, $examID, $classID, $sectionID, $branchID)
    {
    
        $this->db->select('e.roll, s.*, c.name as category');
        $this->db->from('enroll as e');
        $this->db->join('student as s', 'e.student_id = s.id', 'inner');
        $this->db->join('mark as m', 'm.student_id = s.id', 'inner');
        $this->db->join('student_category as c', 'c.id = s.category_id', 'left');
        $this->db->where('e.session_id', $sessionID);
        $this->db->where('e.class_id', $classID);
        $this->db->where('e.section_id', $sectionID);
        $this->db->where('e.branch_id', $branchID);
        $this->db->where('m.exam_id', $examID);
        $this->db->group_by('m.student_id');

    
        $query = $this->db->get()->result_array();
    
        return $query;
    }
    public function getpayrolllist($branch_id = '', $month = '', $year = '', $staffID)
    {
        $this->db->select('payslip.*,staff.name as staff_name,staff.mobileno,IFNULL(staff_designation.name, "N/A") as designation_name,IFNULL(staff_department.name, "N/A") as department_name,payment_types.name as payvia');
        $this->db->from('payslip');
        $this->db->join('staff', 'staff.id = payslip.staff_id', 'left');
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');
        $this->db->join('payment_types', 'payment_types.id = payslip.pay_via', 'left');
       
        $this->db->where('payslip.staff_id', $staffID);
        
        $this->db->where('payslip.branch_id', $branch_id);
        $this->db->where('payslip.month', $month);
        $this->db->where('payslip.year', $year);
    
        $query = $this->db->get()->result_array();
    
        return $query;
    }
    public function getclassteacherlist($sessionID, $examID, $classID, $sectionID, $branchID)
    {
    
        $this->db->select('e.roll, s.*, c.name as category');
        $this->db->from('enroll as e');
        $this->db->join('student as s', 'e.student_id = s.id', 'inner');
        $this->db->join('mark as m', 'm.student_id = s.id', 'inner');
        $this->db->join('student_category as c', 'c.id = s.category_id', 'left');
        $this->db->where('e.session_id', $sessionID);
        $this->db->where('e.class_id', $classID);
        $this->db->where('e.section_id', $sectionID);
        $this->db->where('e.branch_id', $branchID);
        $this->db->where('m.exam_id', $examID);
    
        $query = $this->db->get()->result_array();
    
        return $query;
    }
        
       public function classtecherallocation($branch_id, $sessionid)
       {
            $this->db->select('ta.*,st.name as teacher_name,st.staff_id as teacher_id,c.name as class_name,c.branch_id,s.name as section_name');
            $this->db->from('teacher_allocation as ta');
            $this->db->join('staff as st', 'st.id = ta.teacher_id', 'left');
            $this->db->join('class as c', 'c.id = ta.class_id', 'left');
            $this->db->join('section as s', 's.id = ta.section_id', 'left');
            $this->db->order_by('ta.id', 'ASC');
            $this->db->where('ta.session_id', $sessionid);
            $this->db->where('c.branch_id', $branch_id);
            return $this->db->get()->result_array();
       } 
       public function subjectlist($branch_id, $sessionid)
       {
            $this->db->select('*');
            $this->db->from('subject');
            // $this->db->where('ta.session_id', $sessionid);
                $this->db->where('branch_id', $branch_id);
            return $this->db->get()->result_array();
       } 
       public Function classSchedulelist($branch_id, $class_id, $section_id, $session_id)
    {
        // $this->db->select("id,class_id,section_id,break AS lunch_break,subject_id,teacher_id,class_room,time_start,time_end,day,session_id,branch_id");
        // $this->db->from("timetable_class");
        // $this->db->where("branch_id", $branch_id);
        // $this->db->where('class_id', $class_id);
        // $this->db->where('section_id', $section_id);
        // $this->db->where('session_id', $session_id);
        // $query = $this->db->get();
        // return $query->result_array();
        $this->db->select('t.id,t.class_id,t.section_id,t.break AS lunch_break,t.subject_id,t.teacher_id,t.class_room,t.time_start,t.time_end,t.day,t.session_id,t.branch_id, s.name as subject_name, st.name as teacher_name');
        $this->db->from('timetable_class as t'); 
        $this->db->join('subject as s', 's.id = t.subject_id', 'left');
        $this->db->join('staff as st', 'st.id = t.teacher_id', 'left');
        $this->db->where('class_id', $class_id);
        $this->db->where('section_id', $section_id);
        $this->db->where('session_id', $session_id);
        $query = $this->db->get();
        return $query->result_array();
    
    }
    public Function teacherSchedulelist($branch_id, $teacher_id, $session_id)
    {
        // $this->db->select("id,class_id,section_id,break AS lunch_break,subject_id,teacher_id,class_room,time_start,time_end,day,session_id,branch_id");
        // $this->db->from("timetable_class");
        // $this->db->where("branch_id", $branch_id);
        // $this->db->where('teacher_id', $teacher_id);
        // $this->db->where('session_id', $session_id);
      
        // $query = $this->db->get();
        // return $query->result_array();
         $this->db->select('t.id,t.class_id,t.section_id,t.break AS lunch_break,t.subject_id,t.teacher_id,t.class_room,t.time_start,t.time_end,t.day,t.session_id,t.branch_id, s.name as subject_name');
        $this->db->from('timetable_class as t'); 
        $this->db->join('subject as s', 's.id = t.subject_id', 'left');
        $this->db->join('staff as st', 'st.id = t.teacher_id', 'left');
        // $this->db->where('class_id', $class_id);
        $this->db->where('t.branch_id', $branch_id);
        $this->db->where('t.teacher_id', $teacher_id);
        $this->db->where('t.session_id', $session_id);
        $query = $this->db->get();
        return $query->result_array();
    
    }
    public Function classassignlist($branch_id, $session_id)
    {
        
        $this->db->select('sa.class_id,sa.section_id,sa.branch_id,b.name as branch_name,c.name as class_name,s.name as section_name');
        $this->db->from('subject_assign as sa');
        $this->db->join('branch as b', 'b.id = sa.branch_id', 'left');
        $this->db->join('class as c', 'c.id = sa.class_id', 'left');
        $this->db->join('section as s', 's.id = sa.section_id', 'left');
        $this->db->group_by(array('sa.class_id', 'sa.section_id', 'sa.branch_id'));
        $this->db->where('sa.session_id', $session_id);
        $this->db->where('sa.branch_id', $branch_id);
           
        
        $result = $this->db->get()->result_array();
        return $result;
      
        
    
    }
    // ----------------------------------------start-------------------------------
    public Function examtermlist($branch_id,$session_id)
    {
        $this->db->select("*");
        $this->db->from("exam_term");
        $this->db->where("branch_id", $branch_id);
        $this->db->where("session_id", $session_id);
        $query = $this->db->get();
        return $query->result_array();

    }
    public function termSave($arrayTerm)
    {
        if (!($this->input->post('term_id'))) {
            $this->db->insert('exam_term', $arrayTerm);
        } else {
            $this->db->where('id', $this->input->post('term_id'));
            $this->db->update('exam_term', $arrayTerm);
            
        }
        return true;
        
    }

    public function exam_mark_distribution($branchId,$sessionId)
    {
        
        $this->db->select("t.*,b.name as branch_name");
        $this->db->from("exam_mark_distribution as t");
        $this->db->join("branch as b", "b.id = t.branch_id", "left");
        $this->db->where("branch_id", $branchId);
        $this->db->order_by("id", "asc");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getExamList($branchId,$sessionId)
    {

        $this->db->select('e.*,b.name as branch_name');
        $this->db->from('exam as e');
        $this->db->join('branch as b', 'b.id = e.branch_id', 'left');
        $this->db->where('e.branch_id', $branchId);
        $this->db->where('e.session_id', $sessionId);
        $this->db->order_by('e.id', 'asc');
        return $this->db->get()->result_array();
    }
    public function exam_save($data)
    {
        $arrayExam = array(
            'name' => $data['name'],
            'branch_id' => $data['branchId'],
            'term_id' => $data['term_id'],
            'type_id' => $data['type_id'],
            'mark_distribution' => json_encode($data['mark_distribution']),
            'remark' => $data['remark'],
            'session_id' => $data['sessionId'],
        );
        if (!isset($data['exam_id'])) {
            $this->db->insert('exam', $arrayExam);
            $array = array(
                'result' => 'insert',
                'success'    => true,
                'msg' => "Data has been inserted"
            );
           
            echo json_encode($array);
        } else {
            $this->db->where('id', $data['exam_id']);
            $this->db->update('exam', $arrayExam);
            $array = array(
                'result' => 'update',
                'success'    => true,
                'msg' => "Data has been updated"
            );
           
            echo json_encode($array);
        }
    }
    public function getExamTimetableList($classID, $sectionID, $branchID)
    {
        $sessionID = $this->input->get('session_id');
        $this->db->select('t.*,b.name as branch_name, e.name AS exam_name');
        $this->db->from('timetable_exam as t');
        $this->db->join('branch as b', 'b.id = t.branch_id', 'left');
        $this->db->join('exam as e', 'e.branch_id = t.branch_id AND e.id = t.exam_id', 'left');
        $this->db->where('t.branch_id', $branchID);
        $this->db->where('t.class_id', $classID);
        $this->db->where('t.section_id', $sectionID);
        $this->db->where('t.session_id', $sessionID);
        $this->db->order_by('t.id', 'asc');
        $this->db->group_by('t.exam_id');
        return $this->db->get()->result_array();
    }

    public function getExamTimetableByModal($examID, $classID, $sectionID, $branchID = '')
    {
        $sessionID = $this->input->post('session_id');
        $branchID = $this->input->post('branch_id');
        $this->db->select('t.*,s.name as subject_name,eh.hall_no');
        $this->db->from('timetable_exam as t');
        $this->db->join('subject as s', 's.id = t.subject_id', 'left');;
        $this->db->join('exam_hall as eh', 'eh.id = t.hall_id', 'left');
        $this->db->where('t.branch_id', $branchID);
        $this->db->where('t.exam_id', $examID);
        $this->db->where('t.class_id', $classID);
        $this->db->where('t.section_id', $sectionID);
        $this->db->where('t.session_id', $sessionID);
        // $this->db->where('t.subject_status !=', 0); // add this condition

        $this->db->order_by("t.exam_date", "asc");
        return $this->db->get()->result_array();
    }

    public function getTimetableDetail($classID, $sectionID, $examID, $subjectID,$sessionID)
    {
        // echo "---------------------",json_encode($result);
        $this->db->select('timetable_exam.mark_distribution');
        $this->db->from('timetable_exam');
        $this->db->where('class_id', $classID);
        $this->db->where('section_id', $sectionID);
        $this->db->where('exam_id', $examID);
        $this->db->where('subject_id', $subjectID);
        $this->db->where('session_id', $sessionID);
        $result = $this->db->get()->row_array();
        return $result;
    }
    public function getMarkAndStudent($branchID, $classID, $sectionID, $examID, $subjectID,$sessionID)
    {
        $this->db->select('en.*,st.first_name,st.last_name,st.register_no,st.category_id,m.mark as get_mark,IFNULL(m.absent, 0) as get_abs,subject.name as subject_name');
        $this->db->from('enroll as en');
        $this->db->join('student as st', 'st.id = en.student_id', 'inner');
        $this->db->join('mark as m', 'm.student_id = en.student_id and m.class_id = en.class_id and m.section_id = en.section_id and m.exam_id = ' . $this->db->escape($examID) . ' and m.subject_id = ' . $this->db->escape($subjectID), 'left');
        $this->db->join('subject', 'subject.id = m.subject_id', 'left');
        $this->db->where('en.class_id', $classID);
        $this->db->where('en.section_id', $sectionID);
        $this->db->where('en.branch_id', $branchID);
        $this->db->where('en.session_id', $sessionID);
        $this->db->order_by('en.roll', 'ASC');
        return $this->db->get()->result_array();
    }
    // -----------------------------------------------------------------end---------------------
    public Function examschedulelist($class_id, $section_id,$branch_id)
    {
        
        // $sessionID = $this->input->get('sessionID');
        //     $this->db->select('t.*,b.name as branch_name');
        //     $this->db->from('timetable_exam as t');
        //     $this->db->join('branch as b', 'b.id = t.branch_id', 'left');
        //     $this->db->where('t.branch_id', $branch_id);
        //     $this->db->where('t.class_id', $class_id);
        //     $this->db->where('t.section_id', $section_id);
        //     $this->db->where('t.session_id', $sessionID);
        //     $this->db->order_by('t.id', 'asc');
        //     $this->db->group_by('t.exam_id');
        //     return $this->db->get()->result_array();
           
        
        // $result = $this->db->get()->result_array();
        // return $result;
        $sessionID = $this->input->get('sessionID');
        $this->db->select('t.*,e.name as exam_name, s.name as subject_name, c.name as class_name');
        $this->db->from('timetable_exam as t'); 
        $this->db->join('branch as b', 'b.id = t.branch_id', 'left');
        $this->db->join('subject as s', 's.id = t.subject_id', 'left');
        $this->db->join('class as c', 'c.id = t.class_id', 'left');
        $this->db->join('exam as e', 'e.id = t.exam_id', 'left');

        $this->db->where('t.branch_id', $branch_id);
        $this->db->where('t.class_id', $class_id);
        $this->db->where('t.section_id', $section_id);
        $this->db->where('t.session_id', $sessionID);
        $this->db->order_by('t.id', 'asc');
        $this->db->group_by('t.exam_id');
        return $this->db->get()->result_array();
     
    }
    public Function mailinboxlist()
    {
        $this->db->select("*");
        $this->db->from("message");
        $query = $this->db->get();
        return $query->result_array();
    
    }
    public Function certificates_templetelist()
    {
        $this->db->select("*");
        $this->db->from("certificates_templete");
        $query = $this->db->get();
        return $query->result_array();
    
    }
    public Function advancesalarylist()
    {
        $this->db->select("*");
        $this->db->from("advance_salary");
        $query = $this->db->get();
        return $query->result_array();
    }
    public Function leavecategorylist()
    {
        $this->db->select("*");
        $this->db->from("leave_category");
        $query = $this->db->get();
        return $query->result_array();
    
    }
    public function getLeaveList($user_id, $role_id, $session_id)
    {
        $this->db->select('la.*, c.name AS category_name, r.name AS role');
    $this->db->from('leave_application AS la');
    $this->db->join('leave_category AS c', 'c.id = la.category_id', 'left');
    $this->db->join('roles AS r', 'r.id = la.role_id', 'left');
    $this->db->where('la.session_id', $session_id);
    $this->db->where('la.user_id', $user_id);
    $this->db->where('la.role_id', $role_id);
    $result = $this->db->get()->result_array();
    return $result;
    
           
    }
    public Function getawardlist()
    {
        $this->db->select("*");
        $this->db->from("award");
        $query = $this->db->get();
        return $query->result_array();
    
    }
    public Function liveclassroomlist()
    {
        $this->db->select("*");
        $this->db->from("live_class");
        $query = $this->db->get();
        return $query->result_array();
    
    }
    public Function liveclassreportlist($class_id = '', $section_id = '', $method = '', $start = '', $end = '', $branch_id = '')
    {
        $this->db->select('live_class.*,class.name as class_name,staff.name as staffname,branch.name as branchname');
        $this->db->from('live_class');
        $this->db->join('branch', 'branch.id = live_class.branch_id', 'left');
        $this->db->join('class', 'class.id = live_class.class_id', 'left');
        $this->db->join('staff', 'staff.id = live_class.created_by', 'left');
        $this->db->where('live_class.branch_id', $branch_id);
        if ($method !== '') {
            $this->db->where('live_class.live_class_method', $method);
        }
        $this->db->where('live_class.date >=', $start);
        $this->db->where('live_class.date <=', $end);
        $this->db->order_by('live_class.id', 'ASC');
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $value) {
            if (!empty($section_id)) {
                $array = json_decode($value['section_id'], true);
                if (!in_array($section_id, $array)) {
                    unset($result[$key]);
                    continue;
                }
            }
            $result[$key]['section_details'] = $this->getSectionDetails($value['section_id']);
        }
        return $result;
    
    }
    public Function attachmentbooklist($branch_id, $session_id)
    {
        $this->db->select("*");
        $this->db->from("attachments");
        $this->db->where('branch_id', $branch_id);
        $this->db->where('session_id', $session_id);
        $query = $this->db->get();
        return $query->result_array();

    }
    public Function attachmenttypelist($branch_id)
    {
        $this->db->select("*");
        $this->db->from("attachments_type");
        $this->db->where('branch_id', $branch_id);  
        $query = $this->db->get();
        return $query->result_array();
    
    }
    public Function evaluationreportlist($class_id,$section_id,$subject_id,$branch_id)
    {
        $this->db->select("*");
        $this->db->from("homework");
        $this->db->where('class_id', $class_id);
        $this->db->where('section_id', $section_id);
        $this->db->where('subject_id', $subject_id);  
        $this->db->where('branch_id', $branch_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function attendanceReportStudent($classId, $sectionId, $date, $branchId)
    {
        $sessionid = $this->input->get('sessionid');
        $sql = "SELECT enroll.student_id,enroll.roll,student.first_name,student.last_name,student.register_no,student_attendance.id as `att_id`,
            student_attendance.status as `att_status`,student_attendance.remark as `att_remark`, `student_attendance`.`homework`, `student_attendance`.`uniform` FROM enroll LEFT JOIN student ON
            student.id = enroll.student_id LEFT JOIN student_attendance ON student_attendance.student_id = student.id AND
            student_attendance.date = '" . $date . "' WHERE enroll.class_id = " . $classId .
            " AND enroll.section_id = " . $sectionId . " AND enroll.branch_id = " .
         $branchId . " AND enroll.session_id = " . $sessionid;
        //  echo "-sdssssss",json_encode($sql);
            return $this->db->query($sql)->result_array();
        
    }
    public Function examsetuplist($branch_id)
    {
        $this->db->select('e.*, t.name as term_name');
        $this->db->from('exam as e');
        $this->db->join('exam_term as t', 't.id = e.term_id', 'left');
    
        $this->db->where('e.branch_id', $branch_id);
        $query = $this->db->get();
        $exam_data = $query->result_array();
    
        $exam_mark_distribution = [];
    
            foreach ($exam_data as &$row) {
                $marks = json_decode($row['mark_distribution']);
    
                if (is_array($marks)) {
                    $row['mark_distribution'] = $marks;
                    $this->db->select('name');
                    $this->db->from('exam_mark_distribution');
                    $this->db->where_in('id', $marks);
                    $exam_mark_distribution[$row['id']] = $this->db->get()->result_array();
                }
            }
            
            // Merge the benefit titles into the toolsData array
            foreach ($exam_data as &$row) {
                if (isset($exam_mark_distribution[$row['id']])) {
                    $row['mark_distribution'] = $exam_mark_distribution[$row['id']];
                }
            }
            
        return $exam_data;
    
    }
    public Function mark_entry($branch_id, $classID, $sectionID, $subjectID, $examID )
    {
        $this->db->select("*");
        $this->db->from("mark");
        $this->db->where('branch_id', $branch_id);
        $this->db->where('class_id', $classID);
        $this->db->where('section_id', $sectionID);
        $this->db->where('subject_id', $subjectID);  
        $this->db->where('exam_id', $examID);
        $query = $this->db->get();
        return $query->result_array();
    
    }
    public Function graderangemark()
    {
        $this->db->select("*");
        $this->db->from("grade");
        $query = $this->db->get();
        return $query->result_array();
    
    }
    
    public Function onlinemarklist()
    {
        $this->db->select("*");
        $this->db->from("online_exam");
        $query = $this->db->get();
        return $query->result_array();
    
    }
    public Function  questionlist($branch_id)
    {
        $this->db->select("*");
        $this->db->from("questions");
        $this->db->where('branch_id', $branch_id);  
        $query = $this->db->get();
        return $query->result_array();
    
    }
    public Function  questiongrouplist($branch_id)
    {
        $this->db->select("*");
        $this->db->from("question_group");
        $this->db->where('branch_id', $branch_id);  
        $query = $this->db->get();
        return $query->result_array();
    
    }
    public function getExamAttendence($classID, $sectionID, $examID, $subjectID, $branchID)
    {
        $sql = "SELECT enroll.student_id,enroll.roll,student.first_name,student.last_name,student.register_no,exam_attendance.id as `atten_id`,
        exam_attendance.status as `att_status`,exam_attendance.remark as `att_remark` FROM `enroll` LEFT JOIN student ON
        student.id = enroll.student_id LEFT JOIN exam_attendance ON exam_attendance.student_id = student.id AND exam_attendance.exam_id = " .
       $examID . " AND exam_attendance.subject_id = " . $subjectID .
        " WHERE enroll.class_id = " . $classID . " AND enroll.section_id = " .$sectionID .
        " AND enroll.branch_id = " . $branchID . " AND enroll.session_id = " .get_session_id();
        return $this->db->query($sql)->result_array();
    }
    function bookissue($sessionID, $userID, $roleID) {
        $this->db->select('bi.*,b.title,b.isbn_no,b.edition,b.author,b.publisher,c.name as category_name');
        $this->db->from('book_issues as bi');
        $this->db->join('book as b', 'b.id = bi.book_id', 'left');
        $this->db->join('book_category as c', 'c.id = b.category_id', 'left');
        $this->db->where('bi.session_id', $sessionID);
        $this->db->where('bi.user_id', $userID);
        $this->db->where('bi.role_id', $roleID);
        $this->db->order_by('bi.id', 'desc');
        return $this->db->get()->result_array();
    }
    function exam_reportcard_print($SessionID, $examID, $studentID) {
        $result = array();
        $this->db->select('enroll.roll,student.*,c.name as class_name,se.name as section_name,IFNULL(parent.father_name,"N/A") as father_name,IFNULL(parent.mother_name,"N/A") as mother_name');
        $this->db->from('enroll');
        $this->db->join('student', 'student.id = enroll.student_id', 'inner');
        $this->db->join('class as c', 'c.id = enroll.class_id', 'left');
        $this->db->join('section as se', 'se.id = enroll.section_id', 'left');
        $this->db->join('parent', 'parent.id = student.parent_id', 'left');
        $this->db->where('enroll.student_id', $studentID);
        $this->db->where('enroll.session_id', $SessionID);
        $result['student'] = $this->db->get()->row_array();

        $this->db->select('m.mark as get_mark,IFNULL(m.absent, 0) as get_abs,subject.name as subject_name, te.mark_distribution');
        $this->db->from('mark as m');
        $this->db->join('subject', 'subject.id = m.subject_id', 'left');
        $this->db->join('timetable_exam as te', 'te.exam_id = m.exam_id and te.class_id = m.class_id and te.section_id = m.section_id and te.subject_id = m.subject_id', 'left');
        $this->db->where('m.exam_id', $examID);
        $this->db->where('m.student_id', $studentID);
        $this->db->where('m.session_id', $SessionID);
        $result['exam'] = $this->db->get()->result_array();
        return $result;
    }
    public function getStudentAttendence($classID, $sectionID, $date, $branchID, $session_id)
    {
        $sql = "SELECT enroll.student_id,enroll.roll,student.first_name,student.last_name,student.register_no,student_attendance.id as `att_id`,
        student_attendance.status as `att_status`,student_attendance.remark as `att_remark` FROM enroll LEFT JOIN student ON
        student.id = enroll.student_id LEFT JOIN student_attendance ON student_attendance.student_id = student.id AND
        student_attendance.date = " . $this->db->escape($date) . " WHERE enroll.class_id = " . $this->db->escape($classID) .
        " AND enroll.section_id = " . $this->db->escape($sectionID) . " AND enroll.branch_id = " .
        $this->db->escape($branchID) . " AND enroll.session_id = " . $this->db->escape($session_id);
        return $this->db->query($sql)->result_array();
    }
    
    public Function logbook_list($branch_id, $session_id, $teacher_id)
    {
        
        $date = $this->input->get('date');

        $this->db->select("*");
        $this->db->from("logbook");
        $this->db->where('branch_id', $branch_id);
        $this->db->where('session_id', $session_id);
        $this->db->where('teacher_id', $teacher_id);
        if ($date) {
            $this->db->where('date', $date);
        }
        $query = $this->db->get();
        return $query->result_array();

    }

}
?>