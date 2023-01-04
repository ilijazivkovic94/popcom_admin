@include('emails.header') 
<!-- Email Body -->
<tr>
	<td class="body" width="100%" cellpadding="0" cellspacing="0" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';background-color:#edf2f7;margin:0;padding:0;width:100%;border-bottom: 0px solid #edf2f7; border-top: 0px solid #edf2f7;">
		<table class="inner-body" align="center" cellpadding="0" cellspacing="0" role="presentation" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';background-color:#ffffff;border-color:#e8e5ef;border-width:1px;margin:0 auto;padding:0;width:100%;border-bottom: 0px solid #edf2f7; border-top: 0px solid #edf2f7;">
			<tbody>
				<!-- Body content -->
				<tr>
					<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">
						<p style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';font-size:15px;line-height:1.5em;margin-top:0;text-align:left;">Dear {{$user->accountDetails->account_name}},<br/></p>
					</td>
				</tr>
				<tr>
					<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">
						<p style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';font-size:15px;line-height:1.5em;margin-top:0;text-align:left;"><br/> We have regenerated the PIN for your PopCom kiosk - {{$kiosk['kiosk_identifier']}}. You can find this new PIN on the machine settings page for this kiosk in your PopCom RMS website.<br/><br/>
						This PIN is required to access the admin functions on your kiosk POS. Please protect this PIN as it is an important security feature for your device.
						</p>
					</td>
				</tr>
				<tr>
					<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">
						<p style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';font-size:15px;line-height:1.5em;margin-top:0;text-align:left;"><br/>If you did not authorize this PIN regeneration, please contact us immediately at <a href="mailto: admin@popcom.shop">admin@popcom.shop</a>.
						</p>
					</td>
				</tr>

<!-- footer -->
@include('emails.footer')