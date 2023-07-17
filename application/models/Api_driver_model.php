<?php

class Api_driver_model extends CI_Model{

    public function login_credential($email, $password)
    {
        $this->db->select('*');
        $this->db->from('login_credential');
        $this->db->where('mobile_no', $email);
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
            $sql = "SELECT `name`,`email`,`photo`,`branch_id`, `mobileno` FROM `staff` WHERE `id` = " . $this->db->escape($userID);
            return $this->db->query($sql)->row_array();
        }
    }
    
    public function getSingleDriver($mobile_no)
    {

        $sql = "SELECT * FROM `staff` WHERE`mobileno` = ". $mobile_no ."";
        return $this->db->query($sql)->row_array();
    }
}

?>