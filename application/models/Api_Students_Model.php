<?php

class Api_Students_Model extends CI_Model{

  
    public function login_credential($email, $password)
    {
        $this->db->select('*');
        $this->db->from('login_credential');
        $this->db->where('username', $email);
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

    public function getTeachersList($branchID)
    {
        $this->db->select('staff.*,staff_designation.name as designation_name,staff_department.name as department_name,login_credential.role as role_id, roles.name as role');
        $this->db->from('staff');
        $this->db->join('login_credential', 'login_credential.user_id = staff.id and login_credential.role != "6" and login_credential.role != "7"', 'inner');
        $this->db->join('roles', 'roles.id = login_credential.role', 'left');
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');
        if ($branchID != "") {
            $this->db->where('staff.branch_id', $branchID);
        }
        $this->db->where('login_credential.role', 3);
        $this->db->where('login_credential.active', 1);
        $this->db->order_by('staff.id', 'ASC');
        return $this->db->get()->result();
    }


    public function getparentinfo($loggedin_userid)
    {
        $this->db->select('parent.*');
        $this->db->from('parent');
        $this->db->join('student','student.parent_id = parent.id');
    
        $this->db->where('student.id', $loggedin_userid);
    
        return $this->db->get()->row_array();
    }


    public function getprofileinfo($loggedin_userid)
    {
        $this->db->select('student.*');
        $this->db->from('student');    
        $this->db->where('student.id', $loggedin_userid);
        return $this->db->get()->row_array();
    }


    public function getattachments($branch_id)
    {
        $this->db->select('attachments.*');
        $this->db->from('attachments');    
        // $this->db->join('student','student.branch_id = attachments.branch_id');
        $this->db->where('attachments.branch_id', $branch_id);
        return $this->db->get()->result_array();
    }


    public function getevent($branch_id)
    {
        $this->db->select('event.*');
        $this->db->from('event');    
        $this->db->where('event.branch_id', $branch_id);
        return $this->db->get()->result_array();
    }


    public function getbook($branch_id)
    {
        $this->db->select('book.*');
        $this->db->from('book');    
        $this->db->where('book.branch_id', $branch_id);
        return $this->db->get()->result_array();
    }


    public function book_issues($id,$branchID)
    {
        $this->db->select('bi.date_of_issue,bi.date_of_expiry,bi.return_date,bi.fine_amount,bi.status,b.title,b.isbn_no,b.edition,b.author,b.publisher,c.name as category_name');
        $this->db->from('book_issues as bi');
        $this->db->join('book as b', 'b.id = bi.book_id', 'left');
        $this->db->join('book_category as c', 'c.id = b.category_id', 'left');
        $this->db->join('branch', 'branch.id = bi.branch_id');
        $array = array('bi.user_id' => $id, 'branch.id' => $branchID);
        $this->db->where($array);
        $this->db->order_by('bi.id', 'desc');
        return $this->db->get()->result_array();
    }
    

        
    public function fetchroutelist($branchID)
    {
            $this->db->select("transport_route.*");
            $this->db->from('transport_route');
            $array = array('branch.id' =>$branchID);
            $this->db->where($array);
            $this->db->join('branch', 'branch.id = transport_route.branch_id');
            $query = $this->db->get();
            if($query->num_rows() != 0)
            {
                return $query->result();
            }
            else
            {
                return false;
            }      
    }


    public function fetchhostel($id)
    {
            $this->db->select("hostel.name,hostel_category.name,hostel_category.description,hostel_category.type,hostel.address,hostel.watchman,hostel.remarks,");
            $this->db->from('hostel');
            $array = array('student.id' => $id);
            $this->db->where($array);
            $this->db->join('student', 'hostel.id = student.hostel_id');
            $this->db->join('hostel_category', 'hostel.category_id = hostel_category.id');          
            $query = $this->db->get();
            if($query->num_rows() != 0)
            {
                return $query->row_array();
            }
            else
            {
                return false;
            }      
    }
    public function subjectlist($classID){
        $this->db->select('sa.subject_id,sa.class_id,sa.teacher_id,s.name as subject_name,s.subject_code,s.subject_type,s.subject_author,t.name as teacher_name');
        $this->db->from('subject_assign as sa');
        $this->db->join('subject as s','s.id = sa.subject_id', 'left');
        $this->db->join('staff as t','t.id = sa.teacher_id', 'left');
        $this->db->where('sa.class_id', $classID);
        return $this->db->get()->result();
        // $subjectlist = $this->db->get()->result_array();


    }
    
    public function getHomeworkList($studentID, $branchID)
    {
        
        $this->db->select('homework.*,CONCAT_WS(" ",s.first_name, s.last_name) as fullname,s.register_no,e.student_id, e.roll,subject.name as subject_name,class.name as class_name,section.name as section_name,he.id as ev_id,he.status as ev_status,he.remark as ev_remarks,he.rank,hs.message,hs.enc_name,hs.file_name');
        $this->db->from('homework');
        $this->db->join('enroll as e', 'e.class_id=homework.class_id and e.section_id = homework.section_id and e.session_id = homework.session_id', 'inner');
        $this->db->join('student as s', 'e.student_id = s.id', 'inner');
        $this->db->join('homework_evaluation as he', 'he.homework_id = homework.id and he.student_id = e.student_id', 'left');
        $this->db->join('subject', 'subject.id = homework.subject_id', 'left');
        $this->db->join('homework_submit as hs', 'hs.homework_id = homework.id and hs.student_id = e.student_id', 'left');
        $this->db->join('class', 'class.id = homework.class_id', 'left');
        $this->db->join('section', 'section.id = homework.section_id', 'left');
        $this->db->where('e.student_id', $studentID);
        $this->db->where('homework.status', 0);
         $this->db->where('homework.branch_id', $branchID);
        // $this->db->where('homework.session_id', $userID);
        $this->db->group_by('homework.id');
        $this->db->order_by('homework.id', 'desc');

    

        return $this->db->get()->result_array();
      
    }
    
    
    
    
        public function get_attendance_by_date($id, $branchID)
        {
            $sql = "SELECT * FROM `student_attendance` as s WHERE s.student_id=".$id." and s.branch_id=".$branchID."";
            return $this->db->query($sql)->result_array();
        }


        public function getLeaveList($id,$branchID)
        {
            $this->db->select('la.reason,la.start_date,la.end_date,la.leave_days,la.apply_date,la.orig_file_name,la.status,la.comments,c.name as category_name,');
            $this->db->from('leave_application as la');
            $this->db->join('leave_category as c', 'c.id = la.category_id', 'left');
            $this->db->join('roles as r', 'r.id = la.role_id', 'left');
            $this->db->join('student','student.id = la.user_id');
            $this->db->join('branch', 'branch.id = la.branch_id');
    
            $array = array('student.id' => $id, 'branch.id' => $branchID);
            $this->db->where($array);
    
                $this->db->order_by('la.id', 'DESC');
                return $this->db->get()->result_array();
         
        }



    public function getliveclassList($class_id,$branchID)
    {
        $this->db->select('live_class.*');
        $this->db->from('live_class');

        $array = array('live_class.class_id' => $class_id, 'live_class.branch_id' => $branchID);
        $this->db->where($array);

            $this->db->order_by('live_class.id', 'DESC');
            return $this->db->get()->result_array();

    }
    
    
    
    public function getonlineexamList($class_id,$branchID)
    {
        $this->db->select('online_exam.*');
        $this->db->from('online_exam');

        $array = array('online_exam.class_id' => $class_id, 'online_exam.branch_id' => $branchID);
        $this->db->where($array);

            $this->db->order_by('online_exam.id', 'DESC');
            return $this->db->get()->result_array();

    }
    
    
        public function class_schedulelist($class_id, $session_id)
    {
        $sql='select t.*,t.break as lunch_break,s.name,subject.name as subject_name from timetable_class as t join staff as s on s.id = t.teacher_id JOIN subject on subject.id = t.subject_id where t.class_id='.$class_id.' AND t.session_id='.$session_id.'';
        $query = $this->db->query($sql);
        if($query)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }      
    }
    


    public function getExamList($session_id)
    {
        $sql='select e.*,b.name as branch_name
        from exam as e
        join branch as b on b.id = e.branch_id
        where e.session_id='.$session_id.'
        order by e.id ASC';
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    

    public function examsbydetail($branch_id,$exam_id)
    {
        $getExam = $this->db->get_where('exam', array('id' => $exam_id))->row_array();
        if (!empty($getExam['term_id'])) {
            $getTerm = $this->db->get_where('exam_term', array('id' => $getExam['term_id']))->row_array();
            return $getExam['name'] . ' (' . $getTerm['name'] . ')';
        } else {
            return $getExam['name'];
        }
    }
    
    
    


    public function getExamTimetableList($classID, $sectionID, $branch_id, $session_id)
    {
        $sql='select t.*,b.name as branch_name,s.name as subject_name
        from timetable_exam as t
        LEFT join branch as b on b.id = t.branch_id
        LEFT JOIN subject as s on s.id = t.subject_id
        where t.branch_id= '.$branch_id.'
        and t.exam_id= '.$classID.'
        and t.section_id= '.$sectionID.'
        and t.session_id='.$session_id.'
        ORDER BY t.id asc';
        $query = $this->db->query($sql);
        return $query->result_array();
       
    }
    
    
    
      
    public function getStudentReportCard($student_id, $session_id)
    {
        $result = array();
        $this->db->select('enroll.roll,student.*,c.name as class_name,se.name as section_name,IFNULL(parent.father_name,"N/A") as father_name,IFNULL(parent.mother_name,"N/A") as mother_name');
        $this->db->from('enroll');
        $this->db->join('student', 'student.id = enroll.student_id', 'inner');
        $this->db->join('class as c', 'c.id = enroll.class_id', 'left');
        $this->db->join('section as se', 'se.id = enroll.section_id', 'left');
        $this->db->join('parent', 'parent.id = student.parent_id', 'left');
        $this->db->where('enroll.student_id', $student_id);
        $this->db->where('enroll.session_id', $session_id);
        $result['student'] = $this->db->get()->row_array();

        $this->db->select('m.mark as get_mark,IFNULL(m.absent, 0) as get_abs,subject.name as subject_name, te.mark_distribution, e.name, te.exam_id as term_id');
        $this->db->from('mark as m');
        $this->db->join('subject', 'subject.id = m.subject_id', 'left');
        $this->db->join('timetable_exam as te', 'te.exam_id = m.exam_id and te.class_id = m.class_id and te.section_id = m.section_id and te.subject_id = m.subject_id', 'left');
        $this->db->join('exam as e', 'e.id = te.exam_id', 'left');
        // $this->db->where('m.exam_id', $examID);
        $this->db->where('m.student_id', $student_id);
        $this->db->where('m.session_id', $session_id);
        $result['exam'] = $this->db->get()->result_array();
        return $result;
    }
    
    
    
    
    public function getInvoiceStatus()
    {
        // $status = "";
        $sql = "SELECT SUM(`fee_groups_details`.`amount`) as `total`, min(`fee_allocation`.`id`) as `inv_no` FROM `fee_allocation` LEFT JOIN `fee_groups_details` ON `fee_groups_details`.`fee_groups_id` = `fee_allocation`.`group_id` LEFT JOIN `fees_type` ON `fees_type`.`id` = `fee_groups_details`.`fee_type_id` WHERE `fee_allocation`.`student_id` = " . $this->db->escape($id) . " AND `fee_allocation`.`session_id` = " . $this->db->escape($userID);
        $balance = $this->db->query($sql)->row_array();
        $invNo = str_pad($balance['inv_no'], 4, '0', STR_PAD_LEFT);

        $sql = "SELECT IFNULL(SUM(`fee_payment_history`.`amount`), 0) as `amount`, IFNULL(SUM(`fee_payment_history`.`discount`), 0) as `discount`, IFNULL(SUM(`fee_payment_history`.`fine`), 0) as `fine` FROM `fee_payment_history` LEFT JOIN `fee_allocation` ON `fee_payment_history`.`allocation_id` = `fee_allocation`.`id` WHERE `fee_allocation`.`student_id` = " . $this->db->escape($id) . " AND `fee_allocation`.`session_id` = " . $this->db->escape($userID);
        $paid = $this->db->query($sql)->row_array();

        if ($paid['amount'] == 0) {
            $status = 'unpaid';
        } elseif ($balance['total'] == ($paid['amount'] + $paid['discount'])) {
            $status = 'total';
        } elseif ($paid['amount'] > 1) {
            $status = 'partly';
        }
        return array('status' => $status, 'invoice_no' => $invNo);
    }

    public function getInvoiceDetails($student_id, $get_session_id)
    {
        // echo "--------",$this->db->escape($student_id);
        // echo "--------",$this->db->escape(get_session_id());
        $sql = "SELECT `fee_allocation`.`group_id`,`fee_allocation`.`prev_due`,`fee_allocation`.`id` as `allocation_id`, `fees_type`.`name`, `fees_type`.`system`, `fee_groups_details`.`amount`, `fee_groups_details`.`due_date`, `fee_groups_details`.`fee_type_id` FROM `fee_allocation` LEFT JOIN
        `fee_groups_details` ON `fee_groups_details`.`fee_groups_id` = `fee_allocation`.`group_id` LEFT JOIN `fees_type` ON `fees_type`.`id` = `fee_groups_details`.`fee_type_id` WHERE
        `fee_allocation`.`student_id` = " . $student_id . " AND `fee_allocation`.`session_id` = " . $get_session_id . " ORDER BY `fee_allocation`.`group_id` ASC";
        $student = array();
        $r = $this->db->query($sql)->result_array();
        foreach ($r as $key => $value) {
            if ($value['system'] == 1) {
                $value['amount'] = $value['prev_due'];
            }
            $student[] = $value;
        }
        return $student;
    }

    public function getInvoiceBasic($student_id)
    {
        // echo "parth";
        // $sessionID = get_session_id();
        $this->db->select('s.id,e.branch_id,s.first_name,s.last_name,s.email as student_email,s.current_address as student_address,c.name as class_name,b.school_name,b.email as school_email,b.mobileno as school_mobileno,b.address as school_address');
        $this->db->from('enroll as e');
        $this->db->join('student as s', 's.id = e.student_id', 'inner');
        $this->db->join('class as c', 'c.id = e.class_id', 'left');
        $this->db->join('branch as b', 'b.id = e.branch_id', 'left');

        $this->db->join('parent', 'b.id = parent.branch_id');
        $array = array('e.student_id' => $student_id);
        $this->db->where($array);

        return $this->db->get()->row_array();
    }
    
    public function getStudentDetails()
    {
        $sessionID = $this->input->post('session_id');
        $studentID = $this->input->post('student_id');
       
        $this->db->select('CONCAT_WS(" ",s.first_name, s.last_name) as fullname,s.email as student_email,s.register_no,e.branch_id,e.student_id,s.hostel_id,s.room_id,s.route_id,s.vehicle_id,e.class_id,e.section_id,c.name as class_name,se.name as section_name,b.school_name,b.email as school_email,b.mobileno as school_mobileno,b.address as school_address, e.session_id');
        $this->db->from('enroll as e');
        $this->db->join('student as s', 's.id = e.student_id', 'inner');
        $this->db->join('branch as b', 'b.id = e.branch_id', 'left');
        $this->db->join('class as c', 'c.id = e.class_id', 'left');
        $this->db->join('section as se', 'se.id = e.section_id', 'left');
        $this->db->where('s.id', $studentID);
        $this->db->where('e.session_id', $sessionID);
        return $this->db->get()->row_array();
    }
    
    public function student_leave_type($branch_id)
    {
        
         $sql='select id,name,days
                from leave_category
                where branch_id ='.$branch_id.'
                AND role_id=7';
        $query = $this->db->query($sql);
        if($query)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }  
        // $this->db->select('id,name,days');
        // $this->db->from('leave_category');
        // $this->db->where('branch_id', $branch_id);
        // $this->db->where('role_id', '7');
    
        // return $this->db->get()->row_array();
    }
     public function book_issue_request($branch_id)
    {

        // $this->db->select('id,title')->get_where('book', array('branch_id' => $branch_id));

        $this->db->select('id,title');
        $this->db->from('book');
        $this->db->where('branch_id', $branch_id);
        // $this->db->where('role_id', '7');
    
        $query = $this->db->get();
        if($query)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }  
    }



}

?>