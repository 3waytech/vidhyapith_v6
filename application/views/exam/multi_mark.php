<?php $widget = (is_superadmin_loggedin() ? 2 : 3); ?>
<div class="row">
    <div class="col-md-12">
        <header class="panel-heading">
            <h4 class="panel-title">
                <i class="fas fa-file-archive"></i> <?=translate('multiple_import')?>
            </h4>
        </header>
        <div class="panel-body">
            <?php if ($this->session->flashdata('csvimport')): ?>
            <div class="alert-danger p-sm"><?php echo $this->session->flashdata('csvimport'); ?></div>
            <?php endif; ?>
            <div class="form-group mt-md">
                <div class="col-md-12 mb-md">
                    <a class="btn btn-default pull-right" href="<?=base_url('exam/csv_Sampledownloader')?>">
                        <i class='fas fa-file-download'></i> Download Sample Import File
                    </a>
                </div>
                <!-- <div class="col-md-12">
                    <div class="alert alert-subl">
                        <strong>Instructions :</strong><br />
                        1. Download the first sample file.<br />
                        2. Open the downloaded 'csv' file and carefully fill the details of the student. <br />
                        3. The date you are trying to enter the "Birthday" and "AdmissionDate" column make sure the date
                        format is Y-m-d (<?=date('Y-m-d')?>). <br />
                        4. Do not import the duplicate "Roll Number" And "Register No". <br />
                        5. For student "Gender" use Male, Female value. <br />
                        6. For student "RTE_student" use Yes, No value. <br />
                        7. If enable Automatically Generate login details, leave the "username" and "password" columns
                        blank. <br />
                        8. The Category name comes from another table, so for the "Category", enter Category ID (can be
                        found on the Category page). <br />
                        9. If a parent is existing / if you want to use the same parent information for multiple
                        students only enter the "GuardianUsername" and leave other columns blank.
                    </div>
                </div> -->
            </div>
            <?php if (is_superadmin_loggedin()): ?>
            <div class="form-group">
                <label class="control-label col-md-3"><?php echo translate('branch');?> <span
                        class="required">*</span></label>
                <div class="col-md-6">
                    <?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' onchange='getClassByBranch(this.value)'
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
                    <span class="error"><?=form_error('branch_id')?></span>
                </div>
            </div>
            <?php endif; ?>
            <section class="panel">
                <?php echo form_open_multipart('exam/mark_save_csv_import', array('class' => 'validate'));?>
                <header class="panel-heading">
                    <h4 class="panel-title"><?=translate('select_ground')?></h4>
                </header>
                <div class="panel-body">
                    <div class="row mb-sm">
                        <?php if (is_superadmin_loggedin()): ?>
                        <div class="col-md-4 mb-sm">
                            <div class="form-group">
                                <label class="control-label"><?=translate('branch')?> <span
                                        class="required">*</span></label>
                                <?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-<?php echo $widget; ?> mb-sm">
                            <div class="form-group">
                                <label class="control-label"><?=translate('exam')?> <span
                                        class="required">*</span></label>
                                <?php
								if(isset($branch_id)){
									$arrayExam = array("" => translate('select'));
									$exams = $this->db->get_where('exam', array('branch_id' => $branch_id,'session_id' => get_session_id()))->result();
									foreach ($exams as $row){
										$arrayExam[$row->id] = $this->application_model->exam_name_by_id($row->id);
									}
								} else {
									$arrayExam = array("" => translate('select_branch_first'));
								}
								echo form_dropdown("exam_id", $arrayExam, set_value('exam_id'), "class='form-control' id='exam_id' required data-plugin-selectTwo
								data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
                            </div>
                        </div>
                        <div class="col-md-4 mb-sm">
                            <div class="form-group">
                                <label class="control-label"><?=translate('class')?> <span
                                        class="required">*</span></label>
                                <?php
								$arrayClass = $this->app_lib->getClass($branch_id);
								echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0)'
								required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
                            </div>
                        </div>
                        <div class="col-md-<?php echo $widget; ?> mb-sm">
                            <div class="form-group">
                                <label class="control-label"><?=translate('section')?> <span
                                        class="required">*</span></label>
                                <?php
								$arraySection = $this->app_lib->getSections(set_value('class_id'), false);
								echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' id='section_id' required
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?=translate('subject')?> <span
                                        class="required">*</span></label>
                                <?php
								if(!empty(set_value('class_id'))) {
									$arraySubject = array("" => translate('select'));
									$query = $this->subject_model->getSubjectByClassSection(set_value('class_id'), set_value('section_id'));
									$subjects = $query->result_array();
									foreach ($subjects as $row){
										$subjectID = $row['subject_id'];
										$arraySubject[$subjectID] = $row['subjectname'];
									}
								} else {
									$arraySubject = array("" => translate('select_class_first'));
								}
								echo form_dropdown("subject_id", $arraySubject, set_value('subject_id'), "class='form-control' id='subject_id' required
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"><?=translate('exam_type')?> <span
                                        class="required">*</span></label>
                                <?php
								
                                $distributions = json_decode($timetable_detail['mark_distribution'], true);
							
                                echo form_dropdown("type_id", $distributions, set_value('type_id'), "class='form-control' id='type_id' required
                                data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Select CSV File <span class="required">*</span></label>
                    <div class="col-md-6 mb-lg">
                        <input type="file" name="userfile" class="dropify" data-allowed-file-extensions="csv" />
                        <?php echo form_error('userfile', '<label class="error">', '</label>'); ?>
                    </div>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-2">
                            <button type="submit" name="save" value="1" class="btn btn btn-default btn-block">
                                <i class="fas fa-plus-circle"></i> <?=translate('import')?>
                            </button>
                        </div>
                    </div>
                </footer>
                <?php echo form_close();?>
            </section>

        </div>
    </div>

    <script type="text/javascript">
    $(document).ready(function() {
        $('#branch_id').on('change', function() {
            var branchID = $(this).val();
            getClassByBranch(branchID);
            getExamByBranch(branchID);
            $('#subject_id').html('').append('<option value=""><?=translate("select")?></option>');
        });

        $('#section_id').on('change', function() {
            var classID = $('#class_id').val();
            var sectionID = $(this).val();
            $.ajax({
                url: base_url + 'subject/getByClassSection',
                type: 'POST',
                data: {
                    classID: classID,
                    sectionID: sectionID
                },
                success: function(data) {
                    $('#subject_id').html(data);
                }
            });
        });
        $('#subject_id').on('change', function() {
            var classID = $('#class_id').val();
            var sectionID = $('#section_id').val();
            var exam_id = $('#exam_id').val();
            var subject_id = $(this).val();
            $.ajax({
                url: base_url + 'subject/getByExamType',
                type: 'POST',
                data: {
                    classID: classID,
                    sectionID: sectionID,
                    subject_id: subject_id,
                    exam_id: exam_id
                },
                success: function(data) {
                    $('#type_id').html(data);
                }
            });
        });
    });
    </script>