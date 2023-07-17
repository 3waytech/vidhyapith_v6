<div class="row">
	<div class="col-md-12">
		
		<section class="panel appear-animation" data-appear-animation="<?=$global_config['animations'] ?>" data-appear-animation-delay="100">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-user-graduate"></i> <?php echo translate('student_list');?></h4>
			</header>
			<div class="panel-body mb-md">
				<table class="table table-bordered table-condensed table-hover table-export">
					<thead>
						<tr>
							
							<th class="no-sort"><?=translate('photo')?></th>
							<th><?=translate('name')?></th>
							<th><?=translate('birthday')?></th>
							<th><?=translate('permanent_address')?></th>
							<th><?=translate('mobileno')?></th>
							<th><?=translate('parent_name')?></th>
							<th><?=translate('email_parent')?></th>
							<th><?=translate('mobileno_parent')?></th>
							<th><?=translate('reason')?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($past_students as $row):
						?>
						<tr>
							
							<td class="center"><img src="<?php echo get_image_url('student', $row['photo']); ?>" height="50"></td>
							<td><?php echo $row['first_name'] . " " . $row['last_name'];?></td>
                            <td>
							<?php
								if(!empty($row['birthday'])){
									$birthday = new DateTime($row['birthday']);
									$today = new DateTime('today');
									$age = $birthday->diff($today)->y;
									echo html_escape($age);
								}else{
									echo "N/A";
								}
							?>
                            </td>
							<td><?php echo $row['permanent_address'];?></td>
							<td><?php echo $row['mobileno'];?></td>
							<td><?php echo $row['parent_name'];?></td>
							<td><?php echo $row['email_parent'];?></td>
							<td><?php echo $row['mobileno_parent'];?></td>
							<td><?php echo $row['reason'];?></td>
						
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</section>
	</div>
</div>
