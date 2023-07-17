<style type="text/css">
@media print {
    .pagebreak {
        page-break-before: always;
    }

    .table-bordered>thead>tr>th,
    .table-bordered>tbody>tr>th,
    .table-bordered>tfoot>tr>th,
    .table-bordered>thead>tr>td,
    .table-bordered>tbody>tr>td,
    .table-bordered>tfoot>tr>td {
        border-color: #000 !important;
    }
}

body {
    margin: 0;
}

.mark-container {
    background: #fff;
    width: 1100px;
    position: relative;
    z-index: 2;
    margin: 0 auto;
    padding: 20px 30px;
}

table {
    border-collapse: collapse;
    width: 100%;
    margin: 0 auto;
}

<?php if ($landscape==true) {
    ?>@media print {
        @page {
            size: landscape;
        }

        <?php
    }

    ?>
}
</style>

<?php

$extINTL = extension_loaded('intl');
if (count($student_array)) {
    foreach ($student_array as $sc => $studentID) {
        $result = $this->exam_progress_model->getStudentReportCard($studentID, $sessionID);
        $student = $result['student'];
        $branchID = $student['branch_id'];
        $classId = $student['class_id'];
        $getSchool = $this->db->where(array('id' => $branchID))->get('branch')->row_array();
        $schoolYear = get_type_name_by_id('schoolyear', $sessionID, 'school_year');
        $sectionId = $student['section_id'];

        ?>
<div class="mark-container" style="height: 100%;">
    <?php if ($header == true) {?>
    <table border="0" style="margin-top: 10px; height: 100px;">
        <tbody>
            <tr>
                <td style="width:40%;vertical-align: top;"><img style="max-width:225px;"
                        src="<?=$this->application_model->getBranchImage($branchID, 'report-card-logo')?>"></td>
                <td style="width:60%;vertical-align: top;">
                    <table align="right" class="table-head text-right">
                        <tbody>
                            <tr>
                                <th style="font-size: 26px;" class="text-right"><?=$getSchool['school_name']?></th>
                            </tr>
                            <tr>
                                <th style="font-size: 14px; padding-top: 4px;" class="text-right">Academic Session :
                                    <?=$schoolYear?></th>
                            </tr>
                            <tr>
                                <td><?=$getSchool['address']?></td>
                            </tr>
                            <tr>
                                <td><?=$getSchool['mobileno']?></td>
                            </tr>
                            <tr>
                                <td><?=$getSchool['email']?></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <?php }?>
    <div style="width: 100%;">
        <div style="width: 100%; float: left;">
            <center>
                <h3 <?php if ($header == true) {?>style="margin-top: 0px;" <?php } else {?>style="margin-top: 200px;"
                    <?php }?>><?=$exam_name;?></h3>
            </center>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Name</th>
                        <td colspan="7">
                            <?=$student['first_name'] . " " . $student['last_name'] . " " . $student['father_name']?>
                        </td>
                        <!--<th>Register No</td>-->
                        <!--<td><?=$student['register_no']?></td>-->
                        <!--<th>Roll Number</td>-->
                        <!--<td><?=$student['roll']?></td>-->
                    </tr>
                    <tr>
                        <!-- <th>Father Name</td> -->
                        <!-- <td><?=$student['father_name']?></td> -->
                        <!--<th>Admission Date</td>-->
                        <!--<td><?=_d($student['admission_date'])?></td>-->
                        <!--<th>Date of Birth</td>-->
                        <!--<td><?=_d($student['birthday'])?></td>-->
                    </tr>
                    <tr>
                        <th>Register No</td>
                        <td><?=$student['register_no']?></td>
                        <th>Roll Number</td>
                        <td><?=$student['roll']?></td>
                        <!--<th>Mother Name</td>-->
                        <!--<td><?=$student['mother_name']?></td>-->
                        <th>Student GR No</td>
                        <td><?=$student['student_gr_no']?></td>
                        <th>Class</td>
                        <td><?=$student['class_name'] . " (" . $student['section_name'] . ")"?></td>
                        <!--<th>Gender</td>-->
                        <!--<td><?=ucfirst($student['gender'])?></td>-->
                    </tr>
                </tbody>
            </table>
        </div>
        <!--<div style="width: 20%; float: left; text-align: right;">-->
        <!--	<img src="<?php echo get_image_url('student', $student['photo']); ?>" style="margin-top: 20px; border-radius: 10px;" height="120">-->
        <!--</div>-->
    </div>
    <table class="table table-condensed table-bordered mt-lg" id="tabletotal<?php echo $studentID; ?>">
        <thead>
            <tr>
                <th rowspan="2" class="text-center">Sr. No.</th>
                <th rowspan="2">Subject</th>
                <?php
$examCount = array();
        foreach ($examArray as $examID) {
            $getExam = $this->db->where(array('id' => $examID))->get('exam')->row_array();
            $examName = get_type_name_by_id('exam', $examID);
            $markDistribution = json_decode($getExam['mark_distribution'], true);
            $markDistributionCount = count($markDistribution);

            // Print the name of the exam
            echo '<th colspan="' . $markDistributionCount . '"><center>' . $examName . '</center></th>';

            // Increment the counter for this exam
            if (!isset($examCount[$examID])) {
                $examCount[$examID] = $markDistributionCount;
            } else {
                $examCount[$examID] += $markDistributionCount;
            }
        }
        ?>
                <th rowspan="2" class="text-center">Over All Mark</th>
                <!-- <th>Remark</th> -->
                <th rowspan="2" class="text-center">Annual</th>
                <!-- <th>Subject Position</th> -->
                <th rowspan="2" class="text-center">Grade</th>
            </tr>
            <tr>
                <?php
foreach ($examArray as $examID) {
            $markDistribution = json_decode($this->db->where(array('id' => $examID))->get('exam')->row()->mark_distribution, true);

            // Print the name of each mark distribution for this exam
            foreach ($markDistribution as $markDistributionID) {
                echo '<th class="text-center">' . get_type_name_by_id('exam_mark_distribution', $markDistributionID) . '</th>';
            }
        }
        ?>


            </tr>
        </thead>
        <tbody>
            <?php
        $srno = 1;
        $colspan = count($examArray) + 7;
        $total_grade_point = 0;
        $grand_obtain_marks = 0;
        $grand_full_marks = 0;
        $result_status = 1;
        $getSubjectsList = $this->subject_model->getSubjectByClassSection($student['class_id'], $student['section_id']);
        $getSubjectsList = $getSubjectsList->result_array();
        foreach ($getSubjectsList as $row) {
            $subTotalObtain = 0;
            $subTotalFull = 0;
            ?>
            <tr>
                <td class="text-center"><?php echo $srno; ?></td>
                <td valign="middle" width="15%"><?=$row['subjectname']?></td>

                <?php foreach ($examArray as $id) {?>
                <!-- <td valign="middle"> -->
                <!-- <tr> -->
                <?php
                $getExamTotalMark = $this->exam_progress_model->getExamTotalMark($studentID, $sessionID, $row['subject_id'], $id);
                $subTotalObtain += $getExamTotalMark['grand_obtain_marks'];
                $subTotalFull += $getExamTotalMark['grand_full_marks'];
                ?>
                <!-- </tr> -->
                <!-- </td> -->
                <?php }
            $srno++;?>
                <td valign="middle" class="text-center"><?php

            echo $subTotalObtain . " / " . $subTotalFull;
            // echo number_format($cumulative_Average, 1, '.', '') . "%";
            ?></td>

                <!-- <td valign="middle"><?php echo $grade['remark']; ?></td> -->
                <td valign="middle" class="text-center"><?php
                    if (empty($subTotalObtain)) {
                        $cumulative_Average = 0;
                    } else {
                        $grand_obtain_marks += $subTotalObtain;
                        $grand_full_marks += $subTotalFull;
                        $cumulative_Average = (($subTotalObtain * 100) / $subTotalFull);
                    }
                    echo number_format($cumulative_Average, 0, '.', '') . "/" . '100'?>
                </td>
                <!-- <td valign="middle"><?php echo $this->exam_progress_model->getSubjectPosition($student['class_id'], $student['section_id'], $examArray, $sessionID, $row['subject_id'], $subTotalObtain); ?></td> -->
                <td valign="middle" class="text-center">
                    <?php $grade = $this->exam_progress_model->get_grade($cumulative_Average, $branchID); $total_grade_point += $grade['grade_point']; echo $grade['name']; ?>
                </td>
            </tr>
            <?php }?>
            <tr>
                <td colspan="2" class="text-weight-semibold">Total</td>
                <?php

                    $sum_val = 1;
                    foreach ($examArray as $key => $id) {
                        // $getExamTotalMark = $this->exam_progress_model->getExamTotalMarkFooter($studentID, $sessionID, $row['subject_id'], $id);
                        // $subTotalObtain += $getExamTotalMark['grand_obtain_marks'];
                        // $subTotalFull += $getExamTotalMark['grand_full_marks'];
                        foreach ($getExamTotalMark as $key2 => $examMark) {

                            echo '<td id="sum' . $studentID . $sum_val . '" class="text-center text-weight-semibold"></td>';
                            // echo $sum_val;
                            $sum_val++;
                        }
                        // echo '<td>hello</td>';

                    }
                    echo '<td id="sum' . $studentID . $sum_val . '" class="text-center text-weight-semibold"></td>';
                    $sum_val++;
                    echo '<td id="sum' . $studentID . $sum_val . '" class="text-center text-weight-semibold"></td>';
                ?>
            </tr>
            <tr>
                <td valign="top" class="text-weight-semibold">Rank :</td>
                <td valign="top" colspan="<?=$colspan?>">
                    <?php
                        $getSubjectsList = $this->subject_model->getSubjectByClassSection($student['class_id'], $student['section_id']);
                        $getSubjectsList = $getSubjectsList->result_array();
                        $tmp_subjects = array();
                        $tmp_exams = array();
                        $tmp_students = array();
                        $marks = (object) array();
                        foreach ($getSubjectsList as $row) {
                            // echo "----------------s---------dsaa-------------",json_encode($examArray);
                            array_push($tmp_subjects, $row['subject_id']);
                            $subject_id = $row['subject_id'];
                            foreach ($examArray as $id) {
                                // $output = "(" . implode(", ", $examArray) . ")";
                                array_push($tmp_exams, $id);
                                $exam_id = $id;
                                $this->db->select('s.id, CONCAT_WS(" ", s.first_name, s.last_name) as fullname');
                                $this->db->from('enroll as e');
                                $this->db->join('student as s', 'e.student_id = s.id', 'inner');
                                $this->db->join('login_credential as l', 'l.user_id = s.id and l.role = 7', 'inner');
                                $this->db->join('class as c', 'e.class_id = c.id', 'left');
                                $this->db->join('section as se', 'e.section_id = se.id', 'left');
                                $this->db->join('student_category as sc', 'sc.id = s.category_id', 'left');
                                $this->db->where('e.class_id', $classId);
                                $this->db->where('e.branch_id', $branchID);
                                $this->db->where('e.session_id', get_session_id());

                                $studentList = $this->db->get()->result_array();
                                foreach ($studentList as $student) {
                                    $studentID2 = $student['id'];
                                    array_push($tmp_students, $studentID2);

                                    $getExamTotalMark = $this->exam_progress_model->getExamTotalMarkRenk($studentID2, $sessionID, $subject_id, $exam_id);
                                    // echo "getExamTotalMark -- ",$studentID,"-----------", json_encode($getExamTotalMark);
                                    // $marks->$studentID = $getExamTotalMark;
                                    $getExamTotalMark['grand_obtain_marks'] = $marks->{$student['id']}['grand_obtain_marks'] + $getExamTotalMark['grand_obtain_marks'];
                                    $getExamTotalMark['grand_full_marks'] = $marks->{$student['id']}['grand_full_marks'] + $getExamTotalMark['grand_full_marks'];
                                    // print_r($getExamTotalMark);
                                    $marks->{$student['id']} = $getExamTotalMark; // Add a new key based on subject_id and assign a value

                                    $student_id = $student['id'];

                                    // Extract the obtain marks and full marks for the given student ID
                                    $obtain_marks = $marks->{$student['id']}['grand_obtain_marks'];
                                    $full_marks = $marks->{$student['id']}['grand_full_marks'];
                                    // echo "here";
                                    // Calculate the percentage obtained by the student
                                    $percentage = ($full_marks > 0) ? ($obtain_marks / $full_marks) * 100 : 0;

                                    // Create an array with percentages obtained by all the students
                                    $percentages = [];
                                    foreach ($marks as $id => $data) {
                                        $obtain_marks = $data["grand_obtain_marks"];
                                        $full_marks = $data["grand_full_marks"];
                                        $percentages[$id] = ($full_marks > 0) ? ($obtain_marks / $full_marks) * 100 : 0;
                                    }

                                    // // Sort the array in descending order based on the percentage obtained
                                    // arsort($percentages);

                                    // // Find the index of the student with the given ID
                                    // $rank = 1;
                                    // foreach ($percentages as $id => $percentage) {
                                    //     if ($id == $student_id) {
                                    //         break;
                                    //     }
                                    //     $rank++;
                                    // }
                                    // $marks->{$student['id']}['rank'] = $rank;
                                    // // echo "Rank of student $student_id is $rank";
                                }
                            }
                        }
                        arsort($percentages);

                        $currentRank = 0;
                        $previousPercentage = null;

                        foreach ($percentages as $id => $percentage) {
                            if ($percentage !== $previousPercentage) {
                                $currentRank++;
                            }
                            
                            $marks->{$id}['rank'] = $currentRank;
                            $previousPercentage = $percentage;
                        }

                        foreach ($studentList as $student) {
                            $studentId = $student['id'];
                            $rank = $marks->{$studentId}['rank'];
                            // echo "Rank of student $studentId is $rank";
                        }
                        echo json_encode($marks->{$studentID}['rank']);
                        // echo json_encode($marks->{$studentID}['rank']);
                        // echo json_encode($tmp_exams);
                        // echo json_encode($tmp_students);

                    ?>
                </td>

            </tr>
            <tr>
                <td class="text-weight-semibold">Total</td>
                
                <td colspan="<?=$colspan?>" id='sum<?php echo $studentID.$sum_val.$studentID; ?>' class='same-data<?php echo $studentID; ?>'></td>

                <?php 
                    // $getExamTotalMark = $this->exam_progress_model->getExamTotalMarkExclude($studentID, $sessionID, $subje_id);
                    
                    // $cumulative_Average123 = (($getExamTotalMark['totalMarks'] * 100) / $getExamTotalMark['TotalFullMarks']);
                    // $cumulative_Average124 = (($getExamTotalMark['TotalFullMarks'] * 100) / $subTotalFull);
                    // $excludesub = number_format($cumulative_Average123, 0, '.', '');
                    // $excludesubtotal = number_format($cumulative_Average124, 0, '.', '');
                    // echo "-------------",$excludesub;
                    // echo "-------------",$excludesubtotal;
                    $total = 0;
                    foreach ($subje_id as $subjectrow) {
                        $getExamTotalMark = $this->exam_progress_model->getExamTotalMarkExcludenew($studentID, $sessionID, $subjectrow);
                        // echo "-----------------------------------------ds-------------------------",json_encode($subjectrow);
                        $cumulative_Average123 = (($getExamTotalMark['totalMarks'] * 100) / $getExamTotalMark['TotalFullMarks']);
                        $cumulative_Average124 = (($getExamTotalMark['TotalFullMarks'] * 100) / $subTotalFull);
                        $excludesub = number_format($cumulative_Average123, 0, '.', '');
                        $excludesubtotal = count($subje_id) * 100;
                        $total += $excludesub;
                        $totalfull = $excludesubtotal;
                        $percentageequal = ($sum - $excludesub) . " / " . ($total - $totalfull);
                       
                    }
                    
                    // echo "Total of all excludesubtotal: " . $total;
                    // echo "Total of all excludesubtotal:totalfull " . $totalfull;

                ?>
            </tr>
            <!-- <tr class="text-weight-semibold">
                <td valign="top">GRAND TOTAL :</td>
                <?php
                    foreach ($examArray as $key => $id) {
                        $getExamTotalMark = $this->exam_progress_model->getExamTotalMarkGrandTotal($studentID, $sessionID, $row['subject_id'], $id);
                        // $subTotalObtain += $getExamTotalMark['grand_obtain_marks'];
                        // $subTotalFull += $getExamTotalMark['grand_full_marks'];
                        // echo "----------total_marks-------------------",$total_marks;
                        // echo "--------------subTotalFull---------------",$subTotalFull;
                    }
                ?>

                <?php if ($subje_id) {
                    $getExamTotalMark = $this->exam_progress_model->getExamTotalMarkExclude($studentID, $sessionID, $subje_id);
                       ?>
                    
                        <td valign="top" colspan="<?=$colspan - 3?>">
                            <?=$grand_obtain_marks - $getExamTotalMark['totalMarks'] . "/" . $grand_full_marks - $getExamTotalMark['TotalFullMarks'];?>,
                            Average :
                            <?php $percentage = ($grand_obtain_marks * 100) / $grand_full_marks;
                    echo number_format($percentage, 2, ".", "")?>%
                        </td>
                        <?php } else {?>
                        <td valign="top" colspan="<?=$colspan - 3?>"><?=$grand_obtain_marks . "/" . $grand_full_marks;?>,
                            Average :
                            <?php $percentage = ($grand_obtain_marks * 100) / $grand_full_marks;
                            echo number_format($percentage, 2, ".", "")?>%
                        </td>
                        <?php }
                                if ($percentage > 33) {
                                    $result = '<td colspan="3" class="text-center">Result : Pass</td>';
                                } else {
                                    $result = '<td colspan="3" class="text-center">Result : Fail</td>';
                                }
                        ?>

            </tr> -->
            <tr>
                <td class="text-weight-semibold">Overall Grade</td>

                <td valign="middle" colspan="<?=$colspan?>">
                    <?php
                        if (empty($subTotalObtain)) {
                            $cumulative_Average = 0;
                        } else {
                            $cumulative_Average = ($grand_obtain_marks * 100) / $grand_full_marks;
                            $grade = $this->exam_progress_model->get_grade($cumulative_Average, $branchID);
                            $total_grade_point += $grade['grade_point'];
                            echo $grade['name'];

                        }
                        // echo number_format($cumulative_Average, 0, '.', '') . "/" .'100'
                        ?>
                                </td>

            <!--                </tr>-->

            <!--                <?php if ($extINTL == true) {?>-->
            <!--                <tr class="text-weight-semibold">-->
            <!--                    <td valign="top">GRAND TOTAL IN WORDS :</td>-->
            <!--                    <td valign="top" colspan="<?=$colspan?>">-->
            <!--                $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);-->
            <!--                echo ucwords($f->format($grand_obtain_marks));-->
            <!--                ?>-->
            <!--    </td>-->
            <!--</tr>-->
            <?php }?>
            <?php if ($gpa == true) {?>
            <tr class="text-weight-semibold">
                <td valign="top">GPA :</td>
                <td valign="top" colspan="<?=$colspan?>">
                    <?=number_format(($total_grade_point / count($getSubjectsList)), 2, '.', '')?>%</td>
            </tr>
            <?php }?>
        </tbody>
    </table>
    <div style="width: 100%; display: flex;">
        <div style="width: 50%; padding-right: 15px;">
            <?php
if ($attendance == true) {
            $year = explode('-', $schoolYear);
            $getTotalWorking = $this->db->where(array('student_id' => $studentID, 'status !=' => 'H', 'year(date)' => $year[0]))->get('student_attendance')->num_rows();
            $getTotalAttendance = $this->db->where(array('student_id' => $studentID, 'status' => 'P', 'year(date)' => $year[0]))->get('student_attendance')->num_rows();
            $attenPercentage = empty($getTotalWorking) ? '0.00' : ($getTotalAttendance * 100) / $getTotalWorking;
            ?>
            <table class="table table-bordered table-condensed">
                <tbody>
                    <tr>
                        <th colspan="2" class="text-center">Attendance</th>
                    </tr>
                    <tr>
                        <th style="width: 65%;">No. of working days</th>
                        <td><?=$getTotalWorking?></td>
                    </tr>
                    <tr>
                        <th style="width: 65%;">No. of days attended</th>
                        <td><?=$getTotalAttendance?></td>
                    </tr>
                    <tr>
                        <th style="width: 65%;">Attendance Percentage</th>
                        <td><?=number_format($attenPercentage, 2, '.', '')?>%</td>
                    </tr>
                </tbody>
            </table>
            <?php }?>
        </div>
        <?php if ($grade_scale == true) {?>
        <div style="width: 50%; padding-left: 15px;">
            <table class="table table-condensed table-bordered">
                <tbody>
                    <tr>
                        <th colspan="3" class="text-center">Grading Scale</th>
                    </tr>
                    <tr>
                        <th>Grade</th>
                        <th>Min Percentage</th>
                        <th>Max Percentage</th>
                    </tr>
                    <?php
$grade = $this->db->where('branch_id', $branchID)->get('grade')->result_array();
            foreach ($grade as $key => $row) {
                ?>
                    <tr>
                        <td style="width: 30%;"><?=$row['name']?></td>
                        <td style="width: 30%;"><?=$row['lower_mark']?>%</td>
                        <td style="width: 30%;"><?=$row['upper_mark']?>%</td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
        <?php }?>
    </div>

    <?php if ($footer == true) {?>
    <?php if (!empty($remarks_array[$studentID])) {?>
    <div style="width: 100%;">
        <table class="table table-condensed table-bordered">
            <tbody>
                <tr>
                    <th style="width: 250px;">Remarks</th>
                    <td><?=$remarks_array[$studentID]?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php }?>
    <table style="width:100%; outline:none; margin-top: 35px;">
        <tbody>
            <tr>
                <?php if ($date == true) {?>
                <td style="font-size: 15px; text-align:left;">Print Date : <?=_d($print_date)?></td>
                <?php }?>
                <td style="border-top: 1px solid #ddd; font-size:15px;text-align:left">Principal's Signature</td>
                <td style="border-top: 1px solid #ddd; font-size:15px;text-align:center;">Class Teacher's Signature</td>
                <?php if ($parentsign == true) {?>
                <td style="border-top: 1px solid #ddd; font-size:15px;text-align:right;">Parent's Signature</td>
                <?php }?>
            </tr>
        </tbody>
    </table>
</div>

<!-- <div class="pagebreak"> </div> -->

<?php }

// echo $dom->saveHTML();
        echo '<script> var table = document.getElementById("tabletotal' . $studentID . '");
var rows = table.rows;
var sums = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
var sumstotal = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
// var sums = Array.from({ length: rows[0].cells.length }, () => 0);
// var sumstotal = Array.from({ length: rows[0].cells.length }, () => 0);
// console.log(rows.length);
for (var i = 1; i < rows.length; i++) {
    var cells = rows[i].cells;
    for (var j = 1; j < cells.length; j++) {
        if (cells[j].innerHTML.includes("/") && !cells[j].innerHTML.includes(",")) {
            let data = cells[j].innerHTML.split("/")
            sums[j] += parseInt(data[0]);
            sumstotal[j] += parseInt(data[1]);
        }
    }
}
for (var i = 1; i < sums.length; i++) {
    var sum = sums[i];
    var total = sumstotal[i];
    if (i === 0) {
        document.getElementById("sum<?php echo $studentID;?>" + (i + 1)).innerHTML = "";
} else {
if (total > 0) {
document.getElementById("sum' . $studentID . '" + (i + -1)).innerHTML = sum + " / " + total;
var totalElements = document.querySelectorAll(".same-data'. $studentID .'");
totalElements.forEach(function(elem) {
    var excludesub = '. $total.'
    var totalfull = parseInt('. $totalfull .')
        let newVar = (parseFloat(sum - excludesub) * 100) / parseInt(total - totalfull)
        var result = "";
        if (newVar > 33) {
            result = "<td colspan=3>Result : Pass</td>";
        } else {
            result = "<td colspan=3>Result : Fail</td>";
        }
    if(excludesub && totalfull && result){
        
elem.innerHTML = (sum - excludesub) + " / " + (total - totalfull) + " ,\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0Average : " + newVar.toFixed(2) + " \xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0\xa0 " + result;
      
        var percentageequal = (sum - excludesub) + " / " + (total - totalfull);
    }
    else{
        elem.innerHTML = sum - 0 +" / " + total;
    }
  });
} 
// else {
// document.getElementById("sum' . $studentID . '" + (i + 1)).innerHTML = sum;
// }
}
} </script>';
}}?>
