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
.left_main{
	padding-left:10px;
	width: 48%;

}
.left_data{
	padding-left:10px;
	border: 1px solid black;
	text-align:left;
}
</style>
<div class="row">
    <div class="pull-left left_main">
        <div class="left_data">
			
            <?php 
				$uniqueChequeNos = array(); // Array to store unique cheque numbers
				$uniquetransactNo = array();
				$uniquetransferId = array();

				foreach ($pay_via_details as $item) {
					$details = json_decode($item['pay_via_details'], true); // Decode JSON string into an associative array

					$this->db->where('id', $item['pay_via']);
					$paymentHistoryDetails = $this->db->select("name")->get('payment_types')->row_array();
					// print_r($paymentHistoryDetails);
				// 	echo '<br>';
				// 	echo "<h5>Payment Type :".$paymentHistoryDetails['name']."</h5>";
					if (isset($details['chequeNo'])) {
					    
						$chequeNo = $details['chequeNo'];
						
				
						// Check if chequeNo is already shown
						if (!in_array($chequeNo, $uniqueChequeNos)) {
							$uniqueChequeNos[] = $chequeNo;
				            echo '<br>';
					        echo "<h5>Payment Type :".$paymentHistoryDetails['name']."</h5>";
							// Display chequeNo details
							echo "Cheque No: $chequeNo<br>";
							echo "Bank Name: " . $details['bankName'] . "<br>";
							echo "Cheque Date: " . $details['chequeDate'] . "<br>";
						}
					} elseif (isset($details['transactionNo'])) {
					    
						$transactNo = $details['transactionNo'];

						if (!in_array($transactNo, $uniquetransactNo)) {
							$uniquetransactNo[] = $transactNo;
                            echo '<br>';
					        echo "<h5>Payment Type :".$paymentHistoryDetails['name']."</h5>";
							// Display transferId details
							echo "transactionNo No.: " . $details['transactionNo'] . "<br>";
							echo "<br>";

						}
					} elseif (isset($details['transferId'])) {
                        
						$transferId = $details['transferId'];

						if (!in_array($transferId, $uniquetransferId)) {
							$uniquetransferId[] = $transferId;
							echo '<br>';
    					    echo "<h5>Payment Type :".$paymentHistoryDetails['name']."</h5>";
						// 	// Display transferId details
							echo "Transfer Id: " . $details['transferId'] . "<br>";
							echo "<br>";
						}
					}else{
					    echo '<br>';
					    echo "<h5>Payment Type :".$paymentHistoryDetails['name']."</h5>";
					}
				}
			?>
        </div>
    </div>
	<div class="col-lg-5 pull-right">
		<ul class="amounts">
			<li><strong><?=translate('sub_total')?> :</strong> <?=$currency_symbol . number_format($total_paid + $total_discount, 2, '.', ''); ?></li>
			<li><strong><?=translate('discount')?> :</strong> <?=$currency_symbol . number_format($total_discount, 2, '.', ''); ?></li>
			<li><strong><?=translate('paid')?> :</strong> <?=$currency_symbol . number_format($total_paid, 2, '.', ''); ?></li>
			<li><strong><?=translate('fine')?> :</strong> <?=$currency_symbol . number_format($total_fine, 2, '.', ''); ?></li>
			<li>
				<strong><?=translate('total_paid')?> (<?=translate('with_fine')?>) : </strong> 
				<?php
				$numberSPELL = "";
				$grand_paid = number_format($total_paid + $total_fine, 2, '.', '');
				if ($extINTL == true) {
					$numberSPELL = ' </br>( ' . ucwords($spellout->format($grand_paid)) . ' )';
				}
				echo $currency_symbol . $grand_paid . $numberSPELL;
				?>
			</li>
		</ul>
	</div>
</div>
<div class="row" style="padding-top:40px;">
    <div class="col-6 text-left feesprint">
        <strong> * Note : Fees Once Taken not Refundable</strong>
        <span style="padding:0px 30px 0px 30px;" class="feefooter">Account Clerk.</span>
        <span class="feefooter">Receiver Sign.</span>
    </div>
</div>
