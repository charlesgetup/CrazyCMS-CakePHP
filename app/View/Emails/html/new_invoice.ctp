<table class="table-row" width="600" bgcolor="#FFFFFF" style="table-layout: fixed; background-color: #ffffff;" cellspacing="0" cellpadding="0" border="0">
	<tbody>
		<tr>
			<td class="table-row-td" style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; font-weight: normal; padding-left: 36px; padding-right: 36px;" valign="top" align="left">
				<table class="table-col" align="left" width="528" cellspacing="0" cellpadding="0" border="0" style="table-layout: fixed;">
					<tbody>
						<tr>
							<td class="table-col-td" width="528" style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; font-weight: normal;" valign="top" align="left">
								<table class="header-row" width="528" cellspacing="0" cellpadding="0" border="0" style="table-layout: fixed;">
									<tbody>
										<tr>
											<td class="header-row-td" width="528" style="font-size: 28px; margin: 0px; font-family: Arial, sans-serif; font-weight: normal; line-height: 19px; color: #478fca; padding-bottom: 10px; padding-top: 15px;" valign="top" align="left">
												<?php echo __('Hi'); ?>, <?php echo @$invoice["ClientUser"]["name"]; ?>
											</td>
										</tr>
									</tbody>
								</table>
								<table class="header-row" width="528" cellspacing="0" cellpadding="0" border="0" style="table-layout: fixed;">
									<tbody>
										<tr>
											<td class="header-row-td" width="528" style="font-family: Arial, sans-serif; font-weight: normal; line-height: 19px; color: #444444; margin: 0px; font-size: 18px; padding-bottom: 8px; padding-top: 10px;" valign="top" align="left">
                                                <?php echo __('Thank you for using ' .$companyName .' online service!'); ?>
                                                <br /><br />
												<?php 
												    echo __('New invoice has arrived.');
												?>
												<br /><br />
												<?php
												    echo __('Invoice download link:') .' <a href="' .$invoiceFile .'" target="_blank" style="color: #428bca; text-decoration: none; background-color: transparent;" />' .$invoiceFileName .'</a>';
												?>
												<br /><br />
                                                <?php
												    echo __('To view your invoice online, please <a href="http://<?php echo $companyDomain; ?>/login" target="_blank" style="color: #428bca; text-decoration: none; background-color: transparent;" />sign in</a> and you will find it in the Payment section.'); 
												?>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>


<table class="table-space" height="12"
	style="height: 12px; font-size: 0px; line-height: 0; width: 600px; background-color: #ffffff;"
	width="600" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0"
	border="0">
	<tbody>
		<tr>
			<td class="table-space-td" valign="middle" height="12"
				style="height: 12px; width: 600px; background-color: #ffffff;"
				width="600" bgcolor="#FFFFFF" align="left">&nbsp;</td>
		</tr>
	</tbody>
</table>
<table class="table-row" width="600" bgcolor="#FFFFFF"
	style="table-layout: fixed; background-color: #ffffff;" cellspacing="0"
	cellpadding="0" border="0">
	<tbody>
		<tr>
			<td class="table-row-td"
				style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; font-weight: normal; padding-left: 36px; padding-right: 36px;"
				valign="top" align="left">
				<table class="table-col" align="left" width="528" cellspacing="0"
					cellpadding="0" border="0" style="table-layout: fixed;">
					<tbody>
						<tr>
							<td class="table-col-td" width="528"
								style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; font-weight: normal;"
								valign="top" align="left">
								<table width="100%" cellspacing="0" cellpadding="0" border="0"
									style="table-layout: fixed;">
									<tbody>
										<tr>
											<td width="100%" bgcolor="#d9edf7"
												style="font-family: Arial, sans-serif; line-height: 19px; color: #31708f; font-size: 14px; font-weight: normal; padding: 15px; border: 1px solid #bce8f1; background-color: #d9edf7;"
												valign="top" align="left">
												<?php if(isset($invoice['PaymentInvoice']['is_auto_created']) && empty($invoice['PaymentInvoice']['is_auto_created'])): ?>
                                                    <?php echo __("Due Date"); ?>:&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo @$invoice['PaymentInvoice']['due_date']; ?></strong>
                                                <?php else: ?>
                                                    <?php echo __("Transaction ID"); ?>:&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo @$invoice['PaymentPayPalGateway']['transaction_id']; ?></strong>
                                                <?php endif; ?> 
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>

<table class="table-space" height="6"
	style="height: 6px; font-size: 0px; line-height: 0; width: 600px; background-color: #ffffff;"
	width="600" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0"
	border="0">
	<tbody>
		<tr>
			<td class="table-space-td" valign="middle" height="6"
				style="height: 6px; width: 600px; background-color: #ffffff;"
				width="600" bgcolor="#FFFFFF" align="left">&nbsp;</td>
		</tr>
	</tbody>
</table>

<table class="table-space" height="6"
	style="height: 6px; font-size: 0px; line-height: 0; width: 600px; background-color: #ffffff;"
	width="600" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0"
	border="0">
	<tbody>
		<tr>
			<td class="table-space-td" valign="middle" height="6"
				style="height: 6px; width: 600px; background-color: #ffffff;"
				width="600" bgcolor="#FFFFFF" align="left">&nbsp;</td>
		</tr>
	</tbody>
</table>
<table class="table-space" height="32"
	style="height: 32px; font-size: 0px; line-height: 0; width: 600px; background-color: #ffffff;"
	width="600" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0"
	border="0">
	<tbody>
		<tr>
			<td class="table-space-td" valign="middle" height="32"
				style="height: 32px; width: 600px; padding-left: 18px; padding-right: 18px; background-color: #ffffff;"
				width="600" bgcolor="#FFFFFF" align="center">&nbsp;
				<table bgcolor="#E8E8E8" height="0" width="100%" cellspacing="0"
					cellpadding="0" border="0">
					<tbody>
						<tr>
							<td bgcolor="#E8E8E8" height="1" width="100%"
								style="height: 1px; font-size: 0;" valign="top" align="left">&nbsp;</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>

<table class="table-row" width="600" bgcolor="#FFFFFF"
	style="table-layout: fixed; background-color: #ffffff;" cellspacing="0"
	cellpadding="0" border="0">
	<tbody>
		<tr>
			<td class="table-row-td"
				style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; font-weight: normal; padding-left: 36px; padding-right: 36px;"
				valign="top" align="left">
				<table class="table-col" align="left" width="528" cellspacing="0"
					cellpadding="0" border="0" style="table-layout: fixed;">
					<tbody>
						<tr>
							<td class="table-col-td" width="528"
								style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; font-weight: normal;"
								valign="top" align="left"><span
								style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 14px;">
									<?php echo __("Invoice Content"); ?>:
							</span>

								<table class="table-space" height="12"
									style="height: 12px; font-size: 0px; line-height: 0; width: 528px; background-color: #ffffff;"
									width="528" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0"
									border="0">
									<tbody>
										<tr>
											<td class="table-space-td" valign="middle" height="12"
												style="height: 12px; width: 528px; background-color: #ffffff;"
												width="528" bgcolor="#FFFFFF" align="left">&nbsp;</td>
										</tr>
									</tbody>
								</table>

								<table class="table-space" height="16"
									style="height: 16px; font-size: 0px; line-height: 0; width: 528px; background-color: #ffffff;"
									width="528" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0"
									border="0">
									<tbody>
										<tr>
											<td class="table-space-td" valign="middle" height="16"
												style="height: 16px; width: 528px; background-color: #ffffff;"
												width="528" bgcolor="#FFFFFF" align="left">&nbsp;</td>
										</tr>
									</tbody>
								</table>

								<div style="margin: 0px; padding: 5px 0px 5px 25px;">
									<?php echo @$invoice['PaymentInvoice']['content']; ?>
								</div>

								<table class="table-space" height="16"
									style="height: 16px; font-size: 0px; line-height: 0; width: 528px; background-color: #ffffff;"
									width="528" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0"
									border="0">
									<tbody>
										<tr>
											<td class="table-space-td" valign="middle" height="16"
												style="height: 16px; width: 528px; background-color: #ffffff;"
												width="528" bgcolor="#FFFFFF" align="left">&nbsp;</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>