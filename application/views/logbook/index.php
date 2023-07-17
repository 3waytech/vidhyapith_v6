<?php $widget = (is_superadmin_loggedin() ? "col-md-6" : "col-md-offset-3 col-md-6"); ?>
<section class="panel">
    <header class="panel-heading">
        <h4 class="panel-title"><?=translate('select_ground')?></h4>
    </header>
    <?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
    <div class="panel-body">
        <div class="row mb-sm">
            <?php if (is_superadmin_loggedin()): ?>
            <div class="col-md-6 mb-sm">
                <div class="form-group">
                    <label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
                    <?php
                        $arrayBranch = $this->app_lib->getSelectList('branch');
                        echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' required onchange='getStaffListRole(this.value, 3)'
                        data-width='100%' data-plugin-selectTwo data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
            </div>
            <?php endif; ?>
            <div class="<?php echo $widget; ?> mb-sm">
                <div class="form-group">
                    <label class="control-label"><?=translate('teacher')?> <span class="required">*</span></label>
                    <?php
                        $arrayStaff = $this->app_lib->getStaffList($branch_id, 3);
                        echo form_dropdown("staff_id", $arrayStaff, set_value('staff_id'), "class='form-control' id='staff_id' onchange='getSectionByClass(this.value,0)'
                        required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
                    ?>
                </div>
            </div>
        </div>
        
        <div class="row mb-sm">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label"><?=translate('date')?></label>
                    <input type="date" class="form-control" name="date" value="<?php echo set_value('date'); ?>" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label"><?=translate('std')?></label>
                    <input type="text" class="form-control" name="std" value="<?php echo set_value('std'); ?>" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label"><?=translate('lec_no')?></label>
                    <input type="text" class="form-control" name="lec_no" value="<?php echo set_value('lec_no'); ?>" />
                </div>
            </div>
        </div>
        
    </div>
    <footer class="panel-footer">
        <div class="row">
            <div class="col-md-offset-10 col-md-2">
                <button type="submit" class="btn btn btn-default btn-block">
                    <i class="fas fa-filter"></i> <?=translate('filter')?>
                </button>
            </div>
        </div>
    </footer>
    <?php echo form_close();?>
</section>

<?php if(isset($logbook)): ?>
	<section class="panel appear-animation mt-sm" data-appear-animation="<?php echo $global_config['animations'];?>" data-appear-animation-delay="100">
	<div class="tabs-custom">
		<ul class="nav nav-tabs"> 
			<li class="active">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?=translate('logbook_list')?></a>
			</li>
<?php if (get_permission('logbook', 'is_add')): ?>
			<li>
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?=translate('create_logbook')?></a>
			</li>
<?php endif; ?>
		</ul>
		<div class="tab-content">
			<div id="list" class="tab-pane active">
				<div class="row">
				
				</div>
				
				<table class="table table-bordered table-hover mb-none table-export">
					<thead>
						<tr>
							<th><?=translate('id')?></th>
							<th><?=translate('date')?></th>
							<th><?=translate('lec_no')?></th>
							<th><?=translate('std')?></th>
							<th><?=translate('sub_name')?></th>
							<th><?=translate('start_time')?></th>
							<th><?=translate('end_time')?></th>
							<th><?=translate('cource_planning')?></th>
							<th><?=translate('homework')?></th>
							<th><?=translate('action')?></th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$count = 1;
						foreach($logbook as $row):	?>
						<tr>
							<td><?php echo $row['id'];?></td>
							<td><?php echo date('d-m-Y', strtotime($row['date'])); ?></td>
							<td><?php echo $row['lec_no'];?></td>
							<td><?php echo $row['std'];?></td>
							<td><?php echo $row['sub_name'];?></td>
							<td><?php echo date('h:i A', strtotime($row['start_time'])); ?></td>
        					<td><?php echo date('h:i A', strtotime($row['end_time'])); ?></td>
							<td><?php echo $row['cource_planning'];?></td>
							<td><?php echo $row['homework'];?></td>
							<td>
							<?php if (get_permission('logbook', 'is_edit')): ?>
								<!-- logbook update link -->
								<a href="<?php echo base_url('logbook/edit/' . $row['id']);?>" class="btn btn-circle btn-default icon" >
									<i class="fas fa-pen-nib"></i>
								</a>
							<?php endif; if (get_permission('logbook', 'is_delete')): ?>
								<!-- delete link -->
								<?php echo btn_delete('logbook/delete/' . $row['id']);?>
							<?php endif; ?>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
<?php if (get_permission('logbook', 'is_add')): ?>
			<div class="tab-pane" id="create">
				<?php echo form_open('logbook/save', array('class' => 'form-horizontal form-bordered frm-submit'));?>
					<?php if (is_superadmin_loggedin()): ?>
						
					<?php endif; ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('date')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="date" class="form-control" name="date" />
							<span class="error"></span>
						</div>
					</div>
					<input type="hidden" name="staff_id" id="" value="<?php echo $teacherID;?>">
					<input type="hidden" name="branch_id" id="" value="<?php echo $branch_id;?>" >

					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('lec_no')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="lec_no" />
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('std')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="std" />
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('sub_name')?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="sub_name" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('start_time')?></label>
						<div class="col-md-6">
							<input type="time" class="form-control" name="start_time" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('end_time')?></label>
						<div class="col-md-6">
							<input type="time" class="form-control" name="end_time" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('cource_planning')?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="cource_planning" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('homework')?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="homework" />
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
									<i class="fas fa-plus-circle"></i> <?=translate('save')?>
								</button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>
<?php endif; ?>
		</div>
	</div>
</section>
	</div>
<?php endif;?>