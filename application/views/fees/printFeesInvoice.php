<?php
$currency_symbol = $global_config['currency_symbol'];
$extINTL = extension_loaded('intl');
if ($extINTL == true) {
	$spellout = new NumberFormatter("en", NumberFormatter::SPELLOUT);
}
?>
<style>
.feefooter {
    text-decoration: underline;
    font-weight: 800;
}

.feesprint {
    /*position: absolute;*/
    /*bottom: 10px;*/
}
</style>
<div class="row">
    <div class="col-lg-5 pull-right">
        <ul class="amounts">
            <li><strong><?=translate('grand_total')?> :</strong>
                <?=$currency_symbol . number_format($total_amount, 2, '.', ''); ?></li>
            <li><strong><?=translate('discount')?> :</strong>
                <?=$currency_symbol . number_format($total_discount, 2, '.', ''); ?></li>
            <li><strong><?=translate('paid')?> :</strong>
                <?=$currency_symbol . number_format($total_paid, 2, '.', ''); ?></li>
            <li><strong><?=translate('fine')?> :</strong>
                <?=$currency_symbol . number_format($total_fine, 2, '.', ''); ?></li>
            <?php if ($total_balance != 0): ?>
            <li><strong><?=translate('total_paid')?> (<?=translate('with_fine')?>) :</strong>
                <?=$currency_symbol . number_format($total_paid + $total_fine, 2, '.', ''); ?></li>
            <li>
                <?php if (get_loggedin_branch_id() == 4) { ?>
                    <strong><?=translate('remaining_fee')?> : </strong>
                <?php }else{ ?>
                    <strong><?=translate('balance')?> : </strong>
                <?php } ?>                
                <?php
				$numberSPELL = "";
				$total_balance = number_format($total_balance, 2, '.', '');
				if ($extINTL == true) {
					$numberSPELL = ' </br>( ' . ucwords($spellout->format($total_balance)) . ' )';
				}
				echo $currency_symbol . $total_balance . $numberSPELL;
				?>
            </li>
            <?php else:
				$paidWithFine = number_format(($total_paid + $total_fine), 2, '.', '');
				?>
            <li>
                <strong><?=translate('total_paid')?> (with fine) : </strong>
                <?php
				$numberSPELL = "";
				if ($extINTL == true) {
					$numberSPELL = ' </br>( ' . ucwords($spellout->format($paidWithFine)) . ' )';
				}
				echo $currency_symbol . $paidWithFine . $numberSPELL;
				?>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<div class="row" style="padding-top:40px;>
    <div class="col-6 text-left feesprint">
        <strong> * Note : Fees Once Taken not Refundable</strong>
        <span style="padding:0px 30px 0px 30px;" class="feefooter">Account Clerk.</span>
        <span class="feefooter">Receiver Sign.</span>
    </div>

</div>