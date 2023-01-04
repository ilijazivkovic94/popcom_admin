@include('emails.header') 
<!-- Email Body -->
<tr>
	<td class="body" width="100%" cellpadding="0" cellspacing="0" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';background-color:#edf2f7;margin:0;padding:0;width:100%;border-bottom: 0px solid #edf2f7; border-top: 0px solid #edf2f7;">
		<table class="inner-body" align="center" cellpadding="0" cellspacing="0" role="presentation" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';background-color:#ffffff;border-color:#e8e5ef;border-width:1px;margin:0 auto;padding:0;width:100%;border-bottom: 0px solid #edf2f7; border-top: 0px solid #edf2f7;">
			<tbody>
				<!-- Body content -->
				<tr>
					<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">
						<p style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';font-size:15px;line-height:1.5em;margin-top:0;text-align:left;">Dear Admin,</p>
					</td>
				</tr>
				<tr>
					<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">
						<p style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';font-size:15px;line-height:1.5em;margin-top:0;text-align:left;">
							{{$user['pre_account_name']}} has added the following machine to the {{$user['sub_account_name']}} sub-account which requires PopCom approval and action. Please log in to complete required fields and activate this machine.
						</p>
					</td>
				</tr>
				<tr>
					<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">
						<p style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';font-size:15px;line-height:1.5em;margin-top:0;text-align:left;">
							<b>Parent Account:</b> {{$user['pre_account_name']}}<br/>
							<b>Sub-Account ID:</b> {{$user['sub_account_id']}}<br/>
							<b>Sub-Account Name:</b> {{$user['sub_account_name']}}<br/>
							<b>Sub-Account Email:</b> {{$user['sub_account_email']}}<br/>
							<b>Machine Name:</b> {{$user['machine_name']}}<br/>
						</p>
					</td>
				</tr>
				<tr>
					<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">
						<p style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';font-size:15px;line-height:1.5em;margin-top:0;text-align:left;">NEXT STEPS: Please go to MANAGE MACHINES for this account, take the following steps:<br/><br/>
							1. Edit the Machine to add the Facial Detection License # and Save.<br/>
							2. Note if this machine is to be age-regulated, ensure that the subscription plan for this sub-account is set appropriately.<br>
							3. ACTIVATE this machine.<br>
						</p>
					</td>
				</tr>
				<tr>
					<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">
						<p style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';font-size:15px;line-height:1.5em;margin-top:0;text-align:left;">Once you have updated these settings, an email will be sent to {{$user['pre_account_name']}} informing them that this sub-account is now active, and a welcome email will be sent to the sub-account {{$user['sub_account_name']}} to step them through account set up.
						</p>
					</td>
				</tr>
				

<!-- footer -->
@include('emails.footer')