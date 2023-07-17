<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = 'home';
$route['404_override'] = 'errors';
$route['translate_uri_dashes'] = FALSE;


// Api Route 

$route['api/demo'] = 'api/Auth_Controller_Parents/index';
$route['api/login'] = 'api/Auth_Controller_Parents/login';
$route['api/profile'] = 'api/ParentsApiController/profile';
$route['api/update'] = 'api/ParentsApiController/update';
$route['api/my_children'] = 'api/ParentsApiController/my_children';
$route['api/single_children'] = 'api/ParentsApiController/single_children';
$route['api/getbranchdetails'] = 'api/ParentsApiController/getbranchdetails';
$route['api/getallbranch_teacherlist'] = 'api/ParentsApiController/getallbranch_teacherlist';
$route['api/getteacher_profile'] = 'api/ParentsApiController/getteacher_profile';
$route['api/eventlist'] = 'api/ParentsApiController/eventlist';
$route['api/attachmentslist'] = 'api/ParentsApiController/attachmentslist';
$route['api/booklist'] = 'api/ParentsApiController/booklist';
$route['api/book_issue_list'] = 'api/ParentsApiController/book_issue_list';
$route['api/subjectlist'] = 'api/ParentsApiController/subjectlist';

// sfhd


$route['api/logout'] = 'api/ParentsApiController/logout';
$route['api/change_pass'] = 'api/ParentsApiController/change_pass';
$route['api/subj_list'] = 'api/ParentsApiController/subj_list';
$route['api/class_schedule'] = 'api/ParentsApiController/class_schedule';
$route['api/exam'] = 'api/ParentsApiController/exam';
$route['api/examschedule'] = 'api/ParentsApiController/examschedule';
$route['api/getExamTimetableM'] = 'api/ParentsApiController/getExamTimetableM';
$route['api/viewexam'] = 'api/ParentsApiController/viewexam';
$route['api/reportcard'] = 'api/ParentsApiController/exam_report_card';
$route['api/fees_invoice'] = 'api/ParentsApiController/fees_invoice';
$route['api/attendance_report'] = 'api/ParentsApiController/attendance_report';
$route['api/my_kids'] = 'api/ParentsApiController/my_kids';
$route['api/hostel_list'] = 'api/ParentsApiController/hostel_list';
$route['api/route_list_parents'] = 'api/ParentsApiController/route_list';
$route['api/transport_assign'] = 'api/ParentsApiController/transport_assign';
$route['api/leave_request'] = 'api/ParentsApiController/leave_request';

$route['api/homeworklist'] = 'api/ParentsApiController/homeworklist';
// $route['api/attendance_report'] = 'api/ParentsApiController/attendance_report';


// Driver

$route['api/driver'] = 'api/DriverApiController/index';
$route['api/driverlogin'] = 'api/DriverApiController/login';
$route['api/driverprofile'] = 'api/DriverApiController/driverprofile';



// students api
$route['api/studentlogin'] = 'api/Auth_Controller_students/login';
$route['api/index'] = 'api/ApiStudentsController/index';
$route['api/teachers'] = 'api/ApiStudentsController/teachers';
$route['api/parent_info'] = 'api/ApiStudentsController/parent_info';
$route['api/profile'] = 'api/ApiStudentsController/profile';
$route['api/attachments'] = 'api/ApiStudentsController/attachments';
$route['api/event'] = 'api/ApiStudentsController/event';
$route['api/book_list'] = 'api/ApiStudentsController/booklist';
$route['api/book_issue'] = 'api/ApiStudentsController/book_issue';
$route['api/route_list'] = 'api/ApiStudentsController/route_list';
$route['api/hostellist'] = 'api/ApiStudentsController/hostel_list';
// $route['api/homeworklist'] = 'api/ApiStudentsController/homeworklist';


$route['api/subject'] = 'api/ApiStudentsController/subjects';
$route['api/homework'] = 'api/ApiStudentsController/homeworklist';

$route['api/attendance'] = 'api/ApiStudentsController/attendance';
$route['api/leave_list'] = 'api/ApiStudentsController/leave_list';
$route['api/live_class_list'] = 'api/ApiStudentsController/live_class_list';

$route['api/online_exam'] = 'api/ApiStudentsController/online_exam';
$route['api/class_schedule_stud'] = 'api/ApiStudentsController/class_schedule_stud';

$route['api/examschedule_stud'] = 'api/ApiStudentsController/examschedule_stud';
$route['api/exam_stud'] = 'api/ApiStudentsController/exam_stud';
$route['api/viewexam_stud'] = 'api/ApiStudentsController/viewexam_stud';

$route['api/reportcard_stud'] = 'api/ApiStudentsController/reportcard_stud';
$route['api/change_pass_stud'] = 'api/ApiStudentsController/change_pass_stud';

$route['api/fees_history'] = 'api/ApiStudentsController/fees_history';
$route['api/leave_request_student'] = 'api/ApiStudentsController/leave_request';
$route['api/leave_check_today'] = 'api/ApiStudentsController/leave_check_today';
$route['api/book_title'] = 'api/ApiStudentsController/book_title';
$route['api/book_request'] = 'api/ApiStudentsController/book_request';



// teachers api 
$route['api/index3'] = 'api/Auth_Controller_Teachers/index';
$route['api/teacher_login'] = 'api/Auth_Controller_Teachers/login';

$route['api/teacher/(:any)'] = 'api/ApiTeachersController/$1';