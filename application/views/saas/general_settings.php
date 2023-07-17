<div class="row">
	<div class="col-md-3">
        <?php include 'sidebar.php'; ?>
    </div>
    <div class="col-md-9">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><i class="fa-solid fa-sliders"></i> <?=translate('general') . " " . translate('settings')?></h4>
            </header>
            <?php echo form_open_multipart('saas/settings_general' . get_request_url(), array('class' => 'form-horizontal  frm-submit-data')); ?>
                <div class="panel-body">
                    <!-- General Setting -->
                    <section class="panel pg-fw">
                        <div class="panel-body">
                            <h5 class="chart-title mb-xs"><i class="fas fa-bell"></i> <?=translate('dashboard') . " " . translate('alert_setting')?></h5>
                            <div class="mt-lg">
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?=translate('expired_alert')?> <span class="required">*</span></label>
                                    <div class="col-md-6">
                                        <?php
                                        $array = array(
                                            '0' => translate('no'),
                                            '1' => translate('yes'),
                                        );
                                        echo form_dropdown("expired_alert", $array, set_value('expired_alert', $config['expired_alert']), "class='form-control' data-plugin-selectTwo data-minimum-results-for-search='Infinity' id='expired_alert'
                                        data-width='100%' ");
                                        ?>
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="col-md-4 control-label"><?=translate('expired_alert_days')?> <span class="required">*</span></label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control alert_settings" <?php echo $config['expired_alert'] == 0 ? 'disabled' : '' ?> name="expired_alert_days" value="<?php echo $config['expired_alert_days'] ?>" />
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?=translate('expired_reminder_message')?> <span class="required">*</span></label>
                                    <div class="col-md-6">
                                        <textarea type="text" rows="3" class="form-control alert_settings" <?php echo $config['expired_alert'] == 0 ? 'disabled' : '' ?> name="expired_reminder_message"><?php echo $config['expired_alert_message'] ?></textarea>
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?=translate('expired_message')?> <span class="required">*</span></label>
                                    <div class="col-md-6 mb-lg">
                                        <textarea type="text" rows="3" class="form-control alert_settings" <?php echo $config['expired_alert'] == 0 ? 'disabled' : '' ?> name="expired_message"><?php echo $config['expired_message'] ?></textarea>
                                        <span class="error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- SEO Setting -->
                    <section class="panel pg-fw">
                        <div class="panel-body">
                            <h5 class="chart-title mb-xs"><i class="fas fa-search"></i> <?=translate('seo')?></h5>
                            <div class="mt-lg">
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?=translate('site') . " " . translate('title')?> <span class="required">*</span></label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="seo_title" value="<?php echo $config['seo_title'] ?>" />
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="col-md-4 control-label"><?=translate('meta') . " " . translate('keyword')?></label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="seo_keyword" value="<?php echo $config['seo_keyword'] ?>" />
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?=translate('meta') . " " . translate('description')?></label>
                                    <div class="col-md-6">
                                        <textarea type="text" rows="3" class="form-control" name="seo_description"><?php echo $config['seo_description'] ?></textarea>
                                        <span class="error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- SEO Setting -->
                    <section class="panel pg-fw">
                        <div class="panel-body">
                            <h5 class="chart-title mb-xs"><i class="fab fa-google"></i> <?=translate('google_analytics')?></h5>
                            <div class="mt-lg">
                                <div class="form-group">
                                    <label class="col-md-4 control-label"><?=translate('google_analytics')?></label>
                                    <div class="col-md-6 mb-lg">
                                        <textarea type="text" rows="3" class="form-control" name="google_analytics"><?php echo $config['google_analytics'] ?></textarea>
                                        <span class="error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-3 col-sm-offset-3">
                            <button type="submit" class="btn btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                                <i class="fas fa-plus-circle"></i> <?=translate('save');?>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>
<script type="text/javascript">
    $('#expired_alert').on('change', function(){
        if (this.value == 1) {
            $(".alert_settings").prop('disabled', false);
        } else {
            $(".alert_settings").prop('disabled', true);
        }
    });
</script>