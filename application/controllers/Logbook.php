<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Logbook extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('logbook_model');
    }

    public function index()
    {
        // check access permission
        if (!get_permission('logbook', 'is_view')) {
            access_denied();
        }
        
        $branchID = $this->application_model->get_branch_id();
        $teacherID = $this->input->post('staff_id');
        $date = $this->input->post('date');
        $std = $this->input->post('std');
        $lecNo = $this->input->post('lec_no');
        
        if (is_superadmin_loggedin()) {
            $branchID = $this->input->post('branch_id');
        }
        
        $arraylogbook = array(
            'teacher_id' => $teacherID,
            'branch_id' => $branchID,
            'session_id' => get_session_id(),
        );
    
        if ($date) {
            $arraylogbook['date'] = $date;
        }
        
        if ($std) {
            $arraylogbook['std'] = $std;
        }
        
        if ($lecNo) {
            $arraylogbook['lec_no'] = $lecNo;
        }
        
        $this->data['logbook'] = $this->db->get_where('logbook', $arraylogbook)->result_array();
        
        $this->data['teacherID'] = $teacherID;
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('teacher') . " " . translate('logbook');
        $this->data['sub_page'] = 'logbook/index';
        $this->data['main_menu'] = 'transfer';
        $this->load->view('layout/index', $this->data);
    }
    
    
    public function save() 
    {
        if ($_POST) {
            if (is_superadmin_loggedin()) {
                $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
                $this->form_validation->set_rules('staff_id', translate('teacher'), 'required');
                // $this->form_validation->set_rules('session_id', translate('session'), 'required');
            }
    

            $this->form_validation->set_rules('lec_no', translate('lec_no'), 'trim|required');
            $this->form_validation->set_rules('std', translate('std'), 'trim|required');
            $this->form_validation->set_rules('sub_name', translate('sub_name'), 'trim|required');
            $this->form_validation->set_rules('cource_planning', translate('cource_planning'), 'trim|required');
            // $this->form_validation->set_rules('homework', translate('homework'), 'trim|required');
            $id = $this->input->post('logbook_id');
            if ($this->form_validation->run() !== false) {

                // $branchID = $this->application_model->get_branch_id();
                $branchID = $this->input->post('branch_id');
                $staffID = $this->input->post('staff_id');
                $sessionID = get_session_id();
    
                $arraylogbook = array(
                    
                    'date' => $this->input->post('date'), 
                    'lec_no' => $this->input->post('lec_no'),
                    'std' => $this->input->post('std'),
                    'sub_name' => $this->input->post('sub_name'),
                    'start_time' => $this->input->post('start_time'),
                    'end_time' => $this->input->post('end_time'),
                    'cource_planning' => $this->input->post('cource_planning'),
                    'homework' => $this->input->post('homework'),
                    'branch_id' => $branchID,
                    'teacher_id' => $staffID,
                    'session_id' => $sessionID,                
                );
                if (empty($id)) {
                    if (get_permission('logbook', 'is_add')) {
                        $this->db->insert('logbook', $arraylogbook);
                        
                    }
                    set_alert('success', translate('information_has_been_saved_successfully'));
                } else {
                    if (get_permission('logbook', 'is_edit')) { 
                        
                        $this->db->where('id', $id);                      
                        $this->db->update('logbook', $arraylogbook);
                    }
                    set_alert('success', translate('information_has_been_updated_successfully'));
                }
                $url = base_url('logbook');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    public function edit($id = 'id')   {
        if (!get_permission('logbook', 'is_edit')) {
            access_denied();
        }
        $this->data['logbook'] = $this->logbook_model->getlogbookList($id);
        $this->data['title'] = translate('logbook');
        $this->data['sub_page'] = 'logbook/edit';
        $this->data['main_menu'] = 'logbook';
        $this->load->view('layout/index', $this->data);
    }
    public function delete($id = 'id')
    {
        if (get_permission('logbook', 'is_delete')) {
            $this->db->where('id', $id);
            $this->db->delete('logbook');
        
        }
    }
}
?>