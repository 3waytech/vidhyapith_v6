<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?=base_url('logbook/index')?>"><i class="fas fa-list-ul"></i> <?=translate('logbook') . ' ' . translate('list')?></a>
			</li>
			<li class="active">
				<a href="#edit" data-toggle="tab"><i class="far fa-edit"></i> <?=translate('edit') . ' ' . translate('logbook')?></a>
			</li>
		</ul>
				
		<div class="tab-content">
			<div id="edit" class="tab-pane active">
				<?php echo form_open('logbook/save', array('class' => 'form-horizontal form-bordered frm-submit'));?>
				<input type="hidden" class="form-control" name="logbook_id" placeholder="<?php echo $logbook[0]['id'];?>" value="<?=$logbook[0]['id']?>" />
				<input type="hidden" class="form-control" name="staff_id" value="<?=$logbook[0]['teacher_id']?>" placeholder="<?php echo $logbook[0]['teacher_id'];?>"/>
				<input type="hidden" class="form-control" name="branch_id" value="<?=$logbook[0]['branch_id']?>" placeholder="<?php echo $logbook[0]['branch_id'];?>"/>

				<?php if (is_superadmin_loggedin()): ?>
						
					<?php endif; ?>
					<div class="form-group">
						<label class="col-md-3 control-label">
							<?=translate('date')?> <span class="required">*</span>
						</label>
						<div class="col-md-6">
							<input type="date" class="form-control" name="date" value="<?=$logbook[0]['date']?>" placeholder="<?php echo $logbook[0]['date'];?>"/>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">
							<?=translate('lec_no')?> <span class="required">*</span>
						</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="lec_no" value="<?php echo $logbook[0]['lec_no']; ?>" placeholder="<?php echo $logbook[0]['lec_no'];?>"/>
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">
							<?=translate('std')?> <span class="required">*</span>
						</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="std" value="<?=$logbook[0]['std']?>" placeholder="<?php echo $logbook[0]['std'];?>"/>
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">
							<?=translate('sub_name')?> <span class="required">*</span>
						</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="sub_name" value="<?=$logbook[0]['sub_name']?>" placeholder="<?php echo $logbook[0]['sub_name'];?>"/>
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">
							<?=translate('start_time')?> <span class="required">*</span>
						</label>
						<div class="col-md-6">
							<input type="time" class="form-control" name="start_time" value="<?=$logbook[0]['start_time']?>" placeholder="<?php echo $logbook[0]['timing'];?>"/>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">
							<?=translate('end_time')?> <span class="required">*</span>
						</label>
						<div class="col-md-6">
							<input type="time" class="form-control" name="end_time" value="<?=$logbook[0]['end_time']?>" placeholder="<?php echo $logbook[0]['timing'];?>"/>
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">
							<?=translate('cource_planning')?> <span class="required">*</span>
						</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="cource_planning" placeholder="<?php echo $logbook[0]['cource_planning'];?>" value="<?=$logbook[0]['cource_planning']?>" />
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">
							<?=translate('homework')?> <span class="required">*</span>
						</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="homework" value="<?=$logbook[0]['homework']?>" placeholder="<?php echo $logbook[0]['homework'];?>"/>
							<span class="error"></span>
						</div>
					</div>

					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
									<i class="fas fa-plus-circle"></i> <?=translate('update')?>
								</button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</section>