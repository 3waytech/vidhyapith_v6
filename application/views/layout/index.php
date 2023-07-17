<!doctype html>
<html class="fixed sidebar-left-sm <?php echo ($theme_config['dark_skin'] == 'true' ? 'dark' : 'sidebar-light');?>">
<!-- html header -->
<?php $this->load->view('layout/header.php');?>

<!-- <body class="loading-overlay-showing" data-loading-overlay> -->
<?php if ($global_config['preloader_backend'] == 1) { ?>
<body class="loading-overlay-showing" data-loading-overlay>
	<!-- page preloader -->
	<div class="loading-overlay dark">
		<div class="ring-loader">
			Loading <span></span>
		</div>
	</div>
<?php } else { ?>
<body>
<?php } ?>
	<section class="body">
		<!-- top navbar-->
		<?php $this->load->view('layout/topbar.php');?>
		<div class="inner-wrapper">
			<!-- sidebar -->
			<?php 
			if (is_student_loggedin() || is_parent_loggedin()) {
				$this->load->view('userrole/sidebar'); 
			} else {
				$this->load->view('layout/sidebar'); 
			} 
			?>
			<!-- page main content -->
			<section role="main" class="content-body">
				<header class="page-header">
					<a class="page-title-icon" href="<?php echo base_url('dashboard');?>"><i class="fas fa-home"></i></a>
					<h2><?php echo $title;?></h2>
				</header>
				<?php $this->load->view($sub_page); ?>
			</section>
		</div>
	</section>

	<!-- JS Script -->
	<?php $this->load->view('layout/script.php');?>
	
	<?php
	$alertclass = "";
	if($this->session->flashdata('alert-message-success')){
		$alertclass = "success";
	} else if ($this->session->flashdata('alert-message-error')){
		$alertclass = "error";
	} else if ($this->session->flashdata('alert-message-info')){
		$alertclass = "info";
	}
	if($alertclass != ''):
		$alert_message = $this->session->flashdata('alert-message-'. $alertclass);
	?>
		<script type="text/javascript">
			swal({
				toast: true,
				position: 'top-end',
				type: '<?php echo $alertclass?>',
				title: '<?php echo $alert_message?>',
				confirmButtonClass: 'btn btn-default',
				buttonsStyling: false,
				timer: 8000
			})
		</script>
	<?php endif; ?>

	<!-- sweetalert box -->
	<script type="text/javascript">
		function confirm_modal(delete_url) {
			swal({
				title: "<?php echo translate('are_you_sure')?>",
				text: "<?php echo translate('delete_this_information')?>",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn btn-default swal2-btn-default",
				cancelButtonClass: "btn btn-default swal2-btn-default",
				confirmButtonText: "<?php echo translate('yes_continue')?>",
				cancelButtonText: "<?php echo translate('cancel')?>",
				buttonsStyling: false,
				footer: "<?php echo translate('deleted_note')?>"
			}).then((result) => {
				if (result.value) {
					$.ajax({
						url: delete_url,
						type: "POST",
						success:function(data) {
							swal({
							title: "<?php echo translate('deleted')?>",
							text: "<?php echo translate('information_deleted')?>",
							buttonsStyling: false,
							showCloseButton: true,
							focusConfirm: false,
							confirmButtonClass: "btn btn-default swal2-btn-default",
							type: "success"
							}).then((result) => {
								if (result.value) {
									location.reload();
								}
							});
						}
					});
				}
			});
		}
		
		function confirm_lc_move(move_url) {
            swal({
                title: "<?php echo translate('are_you_sure')?>",
                text: "<?php echo translate('move_this_information')?>",
                html: `
                        <input type="text" id="lc_number" class="swal2-input" placeholder="<?php echo translate('enter_lc_number')?>" required>
                        <input type="date" id="lc_date" class="swal2-input" value="<?=set_value('admission_date', date('Y-m-d'))?>" data-plugin-datepicker	data-plugin-options='{ "todayHighlight" : true }' required>
                        <select name="schoolleavereason" class="swal2-input" id="schoolleavereason" required>
                            <option value="">Select reason</option>
                            <option value="transferanotherschoool">Transfer to another school</option>
                            <option value="completeprimary">Completed primary education</option>
                            <option value="completesecondary">Complete secondary education</option>
                            <option value="completehighersecondary">Complete higher secondary education</option>
                        </select>
                        <div style="display: flex;">
                        <input type="text" id="present_days" class="swal2-input" style="width: 50%!important; margin-right: 10px;" placeholder="<?php echo translate('present_days')?>" required>
                        <input type="text" id="total_days" class="swal2-input" style="width: 50%!important;" placeholder="<?php echo translate('total_days')?>" required>
                        </div>
                        <input type="text" id="reason" class="swal2-input" placeholder="<?php echo translate('enter_reason')?>" required>
                    `,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn btn-default swal2-btn-default",
                cancelButtonClass: "btn btn-default swal2-btn-default",
                confirmButtonText: "<?php echo translate('yes_continue')?>",
                cancelButtonText: "<?php echo translate('cancel')?>",
                buttonsStyling: false,
                footer: "<?php echo translate('move')?>"
            }).then((result) => {
                if (result.value) {
                    // Manually validate the form
                    var lcNumberInput = document.getElementById('lc_number');
                    var lcDateInput = document.getElementById('lc_date');
                    var schoolleavereasonInput = document.getElementById('schoolleavereason');
                    var present_days = document.getElementById('present_days');
                    var total_days = document.getElementById('total_days');
                    var reasonInput = document.getElementById('reason');

                    if (lcNumberInput.checkValidity() && lcDateInput.checkValidity() && schoolleavereasonInput
                        .checkValidity() && reasonInput.checkValidity()) {
                        var lcNumber = lcNumberInput.value;
                        var lcDate = lcDateInput.value;
                        var schoolleavereason = schoolleavereasonInput.value;
                        var present_days = present_days.value;
                        var total_days = total_days.value;
                        var reason = reasonInput.value;

                        // Add the LC number, LC date, and reason to the data to be sent to the server
                        var data = {
                            lcNumber: lcNumber,
                            lcDate: lcDate,
                            schoolleavereason: schoolleavereason,
                            present_days: present_days,
                            total_days: total_days,
                            reason: reason
                        };

                        $.ajax({
                            url: move_url,
                            type: "POST",
                            data: data,
                            success: function(data) {
                                swal({
                                    title: "<?php echo translate('move')?>",
                                    text: "<?php echo translate('move_this_information');?>",
                                    buttonsStyling: false,
                                    showCloseButton: true,
                                    focusConfirm: false,
                                    confirmButtonClass: "btn btn-default swal2-btn-default",
                                    type: "success"
                                }).then((result) => {
                                    if (result.value) {
                                        location.reload();
                                    }
                                });
                            }
                        });
                    } else {
                        // Form validation failed, display an error message
                        swal({
                            title: "Validation Error",
                            text: "Please fill in all the required fields.",
                            buttonsStyling: false,
                            closeOnClickOutside: false,
                            closeOnEsc: false,
                            confirmButtonClass: "btn btn-default swal2-btn-default",
                            type: "error"
                        });
                    }
                }
            });
        }
	</script>
    <?php 
    $config = $this->application_model->whatsappChat();
    if ($config['backend_enable_chat'] == 1) {
    ?>
    <div class="whatsapp-popup">
        <div class="whatsapp-button">
            <i class="fab fa-whatsapp i-open"></i>
            <i class="far fa-times-circle fa-fw i-close"></i>
        </div>
        <div class="popup-content">
            <div class="popup-content-header">
                <i class="fab fa-whatsapp"></i>
                <h5><?php echo $config['header_title'] ?><span><?php echo $config['subtitle'] ?></span></h5>
            </div>
            <div class="whatsapp-content">
                <ul>
                <?php $whatsappAgent = $this->application_model->whatsappAgent(); 
                    foreach ($whatsappAgent as $key => $value) {
                        $online = "offline";
                        if (strtolower($value->weekend) != strtolower(date('l'))) {
                            $now = time();
                            $starttime = strtotime($value->start_time);
                            $endtime = strtotime($value->end_time);
                            if ($now >= $starttime && $now <= $endtime) {
                                $online = "online";
                            }
                        }
                ?>
                    <li class="<?php echo $online ?>">
                        <a class="whatsapp-agent" href="javascript:void(0)" data-number="<?php echo $value->whataspp_number; ?>">
                            <div class="whatsapp-img">
                                <img src="<?php echo get_image_url('whatsapp_agent', $value->agent_image); ?>" class="whatsapp-avatar" width="60" height="60">
                            </div>
                            <div>
                                <span class="whatsapp-text">
                                    <span class="whatsapp-label"><?php echo $value->agent_designation; ?> - <span class="status"><?php echo ucfirst($online) ?></span></span> <?php echo $value->agent_name; ?>
                                </span>
                            </div>
                        </a>
                    </li>
                <?php } ?>
                </ul>
            </div>
            <div class="content-footer">
                <p><?php echo $config['footer_text'] ?></p>
            </div>
        </div>
    </div>
    <?php } ?>
    <script>
		(function () {
		if (window.history && window.history.pushState) {
			$(window).on('popstate', function () {
			window.history.forward();
			});
		}
		window.history.pushState('nohb', null, '');
		})();
	</script>
</body>
</html>