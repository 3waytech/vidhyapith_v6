<?php

class APi_parent_model extends CI_Model{



    public function login_credential($username, $password)
    {
        $this->db->select('*');
        $this->db->from('login_credential');
        $this->db->where('mobile_no', $username);
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
            $sql = "SELECT `student`.`id`, CONCAT_WS(' ',`student`.`first_name`, `student`.`last_name`) as `name`, `student`.`email`, `student`.`photo`, `enroll`.`branch_id` FROM `student` INNER JOIN `enroll` ON `enroll`.`student_id` = `student`.`id` WHERE `student`.`id` = " . $this->db->escape($userID);
            return $this->db->query($sql)->row_array();
        } else {
            $sql = "SELECT `name`,`email`,`photo`,`branch_id` FROM `staff` WHERE `id` = " . $this->db->escape($userID);
            return $this->db->query($sql)->row_array();
        }
    }


    public function getSingleParent($id)
    {
        $this->db->select('parent.*,login_credential.role as role_id,login_credential.active,login_credential.username,login_credential.id as login_id, roles.name as role');
        $this->db->from('parent');
        $this->db->join('login_credential', 'login_credential.user_id = parent.id and login_credential.role = "6"', 'inner');
        $this->db->join('roles', 'roles.id = login_credential.role', 'left');
        $this->db->where('parent.id', $id);

        // if (!is_superadmin_loggedin()) {
        //     $this->db->where('parent.branch_id', get_loggedin_branch_id());
        // }
        $query = $this->db->get();
        // if ($query->num_rows() == 0) {
        //     show_404();
        // }

        return $query->row_array();
    }

    public function getprofile($user_id)
    {
        $this->db->select('*');
        $this->db->where(['id'=>$user_id]);
        $query = $this->db->get('parent');
        return $query->row();
    }
    
     public function uploadImage($role, $fields = "user_photo") {
		$return_photo = 'defualt.png';
		$old_user_photo = $this->input->post('old_user_photo');
		if (isset($_FILES["$fields"]) && !empty($_FILES["$fields"]['name'])) {
			$config['upload_path'] = './uploads/images/' . $role . '/';
			$config['allowed_types'] = '*';
			$config['overwrite'] = FALSE;
			$config['encrypt_name'] = TRUE;
			$this->upload->initialize($config);
			if ($this->upload->do_upload("$fields")) {
	            // need to unlink previous photo
	            if (!empty($old_user_photo)) {
	            	$unlink_path = 'uploads/images/' . $role . '/';
	                if (file_exists($unlink_path . $old_user_photo)) {
	                    @unlink($unlink_path . $old_user_photo);
	                }
	            }
				$return_photo = $this->upload->data('file_name');
			}
		}else{
			if (!empty($old_user_photo)){
				$return_photo = $old_user_photo;
			}
		}
		return $return_photo;
	}

public function changePassword($data)
    {
        $emailTemplate = $this->getEmailTemplates(3, $data['branch_id']);
        if ($emailTemplate['notified'] == 1) {
            $user = $this->application_model->getUserNameByRoleID(loggedin_role_id(), get_loggedin_user_id());
            if (!empty($user['email'])) {
                $message = $emailTemplate['template_body'];
                $message = str_replace("{institute_name}", get_type_name_by_id('branch', $data['branch_id']), $message);
                $message = str_replace("{name}", $user['name'], $message);
                $message = str_replace("{email}", $user['email'], $message);
                $message = str_replace("{password}", $data['password'], $message);
                $msgData['recipient'] = $user['email'];
                $msgData['subject'] = $emailTemplate['subject'];
                $msgData['message'] = $message;
                $msgData['branch_id'] = $data['branch_id'];
                $this->sendEmail($msgData);
            }
        }
    }
    
    public function getEmailTemplates($id, $branchID = '')
    {
        if (empty($branchID)) {
            $branchID = $this->application_model->get_branch_id();
        }
        $this->db->select('td.*');
        $this->db->from('email_templates_details as td');
        $this->db->where('td.template_id', $id);
        $this->db->where('td.branch_id', $branchID);
        $result = $this->db->get()->row_array();
        if (empty($result)) {
            $array = array(
                'notified' => '', 
                'template_body' => '', 
                'subject' => '', 
            );
            return $array;
        } else {
           return $result;
        }
    }
    public function sendEmail($data)
    {
        if (empty($data['branch_id'])) {
            $data['branch_id'] = $this->application_model->get_branch_id();
        }
        if ($this->mailer->send($data)) {
            return true;
        } else {
            return false;
        }
    }


    public function parentUpdate($data)
    {
        $update_data = array(
            'name' => $data['name'],
            'relation' => $data['relation'],
            'father_name' => $data['father_name'],
            'mother_name' => $data['mother_name'],
            'occupation' => $data['occupation'],
            'income' => $data['income'],
            'education' => $data['education'],
            'email' => $data['email'],
            'mobileno' => $data['mobileno'],
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'photo' => $this->uploadImage('parent'),
            'facebook_url' => $data['facebook'],
            'linkedin_url' => $data['linkedin'],
            'twitter_url' => $data['twitter'],
        );

        // UPDATE ALL INFORMATION IN THE DATABASE
        $this->db->where('id', get_loggedin_user_id());
        $this->db->update('parent', $update_data);
    }
    
    public function fetchallchild($userID)
    {
            // $this->db->select("student.*,parent.name");
            // $this->db->from('student');
            // $this->db->where('parent.id', $userID);
            // $this->db->join('parent', 'student.parent_id = parent.id');
            // $query = $this->db->get();
        
            $sql="select student.*,parent.name,e.class_id,e.section_id,e.roll,e.session_id,c.name as class_name,se.name as section_name, e.student_id as student_id
            from student
            JOIN enroll as e
            join class as c
            join section as se
            join parent on student.parent_id = parent.id
            where parent.id = ". $userID ." AND e.student_id = student.id AND e.class_id = c.id AND e.section_id = se.id;";    
        $query = $this->db->query($sql);
            if($query->num_rows() != 0)
            {
                return $query->result();
            }
            else
            {
                return false;
            }      
    }


    public function fetchbranchprofile($mobileno,$branchID)
    {
        $this->db->select("branch.*");
        $this->db->from('branch');
        // $this->db->where('parent.id',$userID);
        $array = array('parent.mobileno' => $mobileno, 'branch.id' => $branchID);

        $this->db->where($array);
        
        $this->db->join('parent', 'branch.id = parent.branch_id');
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
    

    public function getallbranchteacher($mobileno,$branchID)
    {
        $this->db->select("staff.*");
        $this->db->from('staff');
        $array = array('branch.id' =>$branchID, 'parent.mobileno' => $mobileno,'staff.department !=' => 1);
        $this->db->where($array);
        $this->db->join('branch', 'branch.id = staff.branch_id');
        $this->db->join('parent', 'branch.id = parent.branch_id');
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



    public function fetchsingleteacher($teacherID,$userID,$branchID)
    {
            $this->db->select("staff.*");
            $this->db->from('staff');
            // $this->db->where('parent.id',$userID);
            $array = array('branch.id' =>$branchID, 'parent.id' => $userID,'staff.id' => $teacherID);
            $this->db->where($array);
            $this->db->join('branch', 'branch.id = staff.branch_id');
            $this->db->join('parent', 'branch.id = parent.branch_id');
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


    public function geteventlist($userID,$branchID)
    {
        $this->db->select("event.*");
        $this->db->from('event');
        $array = array('branch.id' =>$branchID, 'parent.id' => $userID,'event.status'=>1);
        $this->db->where($array);
        $this->db->join('branch', 'branch.id = event.branch_id');
        $this->db->join('parent', 'branch.id = parent.branch_id');
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

    public function getattachmentslist($mobileno,$branchID)
    {
        $this->db->select("attachments.*");
        $this->db->from('attachments');
        $array = array('branch.id' =>$branchID, 'parent.mobileno' => $mobileno);
        $this->db->where($array);
        $this->db->join('branch', 'branch.id = attachments.branch_id');
        $this->db->join('parent', 'branch.id = parent.branch_id');
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

    public function getbooklist($branchID)
    {
        $this->db->select("book.*");
        $this->db->from('book');
        // $array = array('branch.id' =>$branchID, 'parent.id' => $userID);
        $this->db->where('branch.id', $branchID);
        $this->db->join('branch', 'branch.id = book.branch_id');
        // $this->db->join('parent', 'branch.id = parent.branch_id');
        $query = $this->db->get();
    
        if($query->num_rows() != 0)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }      
    }



    // public function book_issues($branchID, $userID)
    // {
    //     // $this->db->select('bi.*,b.title,b.isbn_no,b.edition,b.author,b.publisher,c.name as category_name');
    //     $this->db->select('bi.*');
    //     $this->db->from('book_issues as bi');
        
    //     $array = array('branch.id' =>$branchID, 'parent.id' => $userID);
    //     $this->db->where($array);

    //     // $this->db->join('branch', 'b.branch_id = branch.id');
    //     // $this->db->join('branch', 'branch.id = bi.branch_id');

    //     $this->db->join('branch', 'branch.id = bi.branch_id');
    //     $this->db->join('parent', 'branch.id = parent.branch_id');


    //     // $this->db->join('book as b', 'b.id = bi.book_id');
    //     // $this->db->join('book_category as c', 'c.id = b.category_id');

        

       


    //     // $this->db->where('bi.session_id', get_session_id());
    //     // $this->db->where('bi.user_id', get_loggedin_user_id());
    //     // $this->db->where('bi.role_id', loggedin_role_id());
    //     $this->db->order_by('bi.id', 'desc');
    //     // $booklist =  $this->db->get()->result_array();

    //     $query = $this->db->get();
    
    //     if($query->num_rows() != 0)
    //     {
    //         return $query->result_array();
    //     }
    //     else
    //     {
    //         return false;
    //     }      
    // }


    public function subjectlist($classID)
    {
        // $this->db->select('subject_assign.subject_id,subject.name,subject.subject_code');
        //     $this->db->from('subject_assign');
        //     $this->db->join('subject', 'subject.id = subject_assign.subject_id', 'left');
        //     $this->db->where('subject_assign.class_id', $classID);
        //     $query = $this->db->get();
        
        // $sql="SELECT s.*, b.name as branch_name FROM `subject` as s JOIN `branch` as b JOIN `login_credential` as l WHERE l.user_id = s.branch_id";    
        $sql="select sa.subject_id,sa.class_id,sa.teacher_id,s.name as subject_name,s.subject_code,s.subject_type,s.subject_author,t.name as teacher_name
from subject_assign as sa
join subject as s on s.id = sa.subject_id
left join staff as t on t.id = sa.teacher_id
where sa.class_id = ".$classID."";    
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


    public function class_schedulelist($class_id, $session_id)
    {
        // echo "sdbhfasdj";
        /*
        $this->db->select('CONCAT_WS(" ",s.first_name, s.last_name) as fullname,s.email as student_email,s.register_no,e.branch_id,e.student_id,s.hostel_id,s.room_id,s.route_id,s.vehicle_id,e.class_id,e.section_id,c.name as class_name,se.name as section_name,b.school_name,b.email as school_email,b.mobileno as school_mobileno,b.address as school_address');
        $this->db->from('enroll as e');
        $this->db->join('student as s', 's.id = e.student_id', 'inner');
        $this->db->join('branch as b', 'b.id = e.branch_id', 'left');
        $this->db->join('class as c', 'c.id = e.class_id', 'left');
        $this->db->join('section as se', 'se.id = e.section_id', 'left');
        $this->db->where('s.id', $userID);
        $this->db->where('e.session_id', $branchID);
        return $this->db->get()->row_array();
*/

        // $sql='select *, s.first_name, s.last_name as fullname, s.id = e.student_id, b.id = e.branch_id, c.id = e.class_id, se.id = e.section_id, sub.name, s.email as student_email,s.register_no,e.branch_id,e.student_id,s.hostel_id,s.room_id,s.route_id,s.vehicle_id,e.class_id,e.section_id,c.name as class_name,se.name as section_name,b.school_name,b.email as school_email,b.mobileno as school_mobileno,b.address as school_address from enroll as e join student as s join branch as b join class as c join section as se JOIN subject as sub where s.id='.$userID.' AND e.session_id='.$branchID.'';
        $sql='select t.*,s.name,subject.name as subject_name from timetable_class as t join staff as s on s.id = t.teacher_id JOIN subject on subject.id = t.subject_id where t.class_id='.$class_id.' AND t.session_id='.$session_id.'';
        $query = $this->db->query($sql);
        // echo "dfn",$query;
        // $query = $this->db->query($sql);
        if($query)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }      
    }
    
    
     public function fetchhostel($register_no)
    {
            $this->db->select("hostel.name,hostel_category.name,hostel_category.description,hostel_category.type,hostel.address,hostel.watchman,hostel.remarks,");
            $this->db->from('hostel');
            // $this->db->where('parent.id',$userID);
            $array = array('student.register_no' => $register_no);

            $this->db->where($array);

            $this->db->join('student', 'hostel.id = student.hostel_id');
            // $this->db->join('student', 'hostel_room.id = student.room_id');
            $this->db->join('hostel_category', 'hostel.category_id = hostel_category.id');
            // $this->db->join('parent', 'student.branch_id = parent.branch_id');
          
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


 public function gettransport_assign($id,$branchID)
    {
        
        $this->db->select('transport_route.name,transport_route.start_place,transport_route.stop_place,transport_vehicle.vehicle_no,transport_vehicle.capacity,transport_vehicle.insurance_renewal,transport_vehicle.	driver_name,transport_vehicle.driver_phone,transport_vehicle.driver_license');
        $this->db->from('transport_assign');
        $this->db->join('student','student.route_id = transport_assign.route_id');
        $this->db->join('transport_route','transport_route.id = transport_assign.route_id');
        $this->db->join('transport_vehicle','transport_vehicle.id = transport_assign.vehicle_id');
        $this->db->join('branch', 'branch.id = transport_route.branch_id');
        $array = array('student.id' => $id, 'branch.id' => $branchID);
        $this->db->where($array);

        return $this->db->get()->row_array();
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
    
       public function book_issues($id,$branchID)
    {
        $this->db->select('bi.date_of_issue,bi.date_of_expiry,bi.return_date,bi.fine_amount,bi.status,b.title,b.isbn_no,b.edition,b.author,b.publisher,c.name as category_name');
        $this->db->from('book_issues as bi');
        $this->db->join('book as b', 'b.id = bi.book_id', 'left');
        $this->db->join('book_category as c', 'c.id = b.category_id', 'left');
        // $this->db->where('bi.session_id', get_session_id());
        
           $this->db->join('branch', 'branch.id = bi.branch_id');

        $array = array('bi.user_id' => $id, 'branch.id' => $branchID);
        $this->db->where($array);
        
        // $this->db->where('bi.user_id', $id);
        // $this->db->where('bi.role_id', 7);
        $this->db->order_by('bi.id', 'desc');

        return $this->db->get()->result_array();
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
     public function getExamTimetableByModal($examID, $classID, $sectionID, $branchID, $session_id)
    {
        // $sessionID = 6;
        $this->db->select('t.*,s.name as subject_name,eh.hall_no');
        $this->db->from('timetable_exam as t');
        $this->db->join('subject as s', 's.id = t.subject_id', 'left');
        $this->db->join('exam_hall as eh', 'eh.id = t.hall_id', 'left');
        // if (!empty($branchID)) {
           $this->db->where('t.branch_id', $branchID);
        // } else {
        //     if (!is_superadmin_loggedin()) {
        //         $this->db->where('t.branch_id', get_loggedin_branch_id());
        //     }
        // }
        $this->db->where('t.exam_id', $examID);
        $this->db->where('t.class_id', $classID);
        $this->db->where('t.section_id', $sectionID);
        $this->db->where('t.session_id', $session_id);
        return $this->db->get();
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


  public function get_attendance_by_date($id, $branchID)
    {
       
        // $sql = "SELECT * FROM `student_attendance` as s WHERE
        // s.date LIKE '$d%' AND 
        // s.student_id=".$id." and s.branch_id=".$branchID."";
        $sql = "SELECT * FROM `student_attendance` as s WHERE s.student_id=".$id." and s.branch_id=".$branchID."";

        return $this->db->query($sql)->result_array();

        // $sql = "SELECT student_attendance.* FROM student_attendance WHERE student_id = " . $this->db->escape($studentID) . " AND date = " . $this->db->escape($date);
        // return $this->db->query($sql)->row_array();
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

        // $query = $this->db->get();
    
        // if($query->num_rows() != 0)
        // {
        //     return $query->result_array();
        // }
        // else
        // {
        //     return false;
        // }      

        return $this->db->get()->result_array();
        
        /*
        
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
        $this->db->where('homework.session_id', $session_id);
        $this->db->order_by('homework.id', 'desc');
        return $this->db->get()->result_array();
        
        */
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


    public function getInvoiceBasic($student_id,$userID)
    {
        // echo "parth";
        // $sessionID = get_session_id();
        $this->db->select('s.id,e.branch_id,s.first_name,s.last_name,s.email as student_email,s.current_address as student_address,c.name as class_name,b.school_name,b.email as school_email,b.mobileno as school_mobileno,b.address as school_address');
        $this->db->from('enroll as e');
        $this->db->join('student as s', 's.id = e.student_id', 'inner');
        $this->db->join('class as c', 'c.id = e.class_id', 'left');
        $this->db->join('branch as b', 'b.id = e.branch_id', 'left');

        $this->db->join('parent', 'b.id = parent.branch_id');
        $array = array('e.student_id' => $student_id, 'parent.mobileno' => $userID);
        $this->db->where($array);

        return $this->db->get()->row_array();
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

    public function getStudentFeeDeposit($allocationID, $typeID)
    {
        $sqlDeposit = "SELECT IFNULL(SUM(`amount`), '0.00') as `total_amount`, IFNULL(SUM(`discount`), '0.00') as `total_discount`, IFNULL(SUM(`fine`), '0.00') as `total_fine` FROM `fee_payment_history` WHERE `allocation_id` = " . $this->db->escape($allocationID) . " AND `type_id` = " . $this->db->escape($typeID);
        return $this->db->query($sqlDeposit)->row_array();
    }
    
 
    public function fetchallkids($mobileno)
    {
            // $this->db->select("student.*,parent.name");
            // $this->db->from('student');
            // $this->db->where('parent.mobileno', $mobileno);
            // $this->db->join('parent', 'student.parent_id = parent.id');
            $sql="select student.*,parent.name,e.class_id,e.section_id,e.roll,e.session_id,c.name as class_name,se.name as section_name, e.student_id as student_id, e.branch_id as branch_id,
            tv.driver_name, tv.driver_phone
                        from student
                        JOIN enroll as e
                        join class as c
                        join section as se
                        join parent on student.parent_id = parent.id
                        left join transport_vehicle tv on tv.id = student.vehicle_id
                        where parent.mobileno = ". $mobileno ." AND e.student_id = student.id AND e.class_id = c.id AND e.section_id = se.id";
            // $sql="select student.*,parent.name,e.class_id,e.section_id,e.roll,e.session_id,c.name as class_name,se.name as section_name, e.student_id as student_id, e.branch_id as branch_id
            // from student
            // JOIN enroll as e
            // join class as c
            // join section as se
            // join parent on student.parent_id = parent.id
            // where parent.mobileno = ". $mobileno ." AND e.student_id = student.id AND e.class_id = c.id AND e.section_id = se.id;";  
            $query = $this->db->query($sql);
        
            if($query->num_rows() != 0)
            {
                return $query->result();
            }
            else
            {
                return false;
            }      
    }



}