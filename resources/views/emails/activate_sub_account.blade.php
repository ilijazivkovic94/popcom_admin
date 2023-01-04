@include('emails.header') 
<!-- Email Body -->
<tr>
	<td class="body" width="100%" cellpadding="0" cellspacing="0" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';background-color:#edf2f7;margin:0;padding:0;width:100%;border-bottom: 0px solid #edf2f7; border-top: 0px solid #edf2f7;">
		<table class="inner-body" align="center" cellpadding="0" cellspacing="0" role="presentation" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';background-color:#ffffff;border-color:#e8e5ef;border-width:1px;margin:0 auto;padding:0;width:100%;border-bottom: 0px solid #edf2f7; border-top: 0px solid #edf2f7;">
			<tbody>
				<!-- Body content -->
				<tr>
					<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">
						<p style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';font-size:15px;line-height:1.5em;margin-top:0;text-align:left;margin-bottom: 15px">Dear {{$parent_account['account_name']}},</p>
					</td>
				</tr>
				<tr>
					<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">
						<p style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';font-size:15px;line-height:1.5em;margin-top:0;text-align:left;margin-bottom: 15px">The following Sub-Account has been activated under your account:<br/><br/>
							<b>Sub-Account ID:</b> {{$user['account_id']}}<br/>
							<b>Sub-Account Name:</b> {{$user['account_name']}}<br>
							<b>Sub-Account Email:</b> {{$user['email']}}
						</p>
					</td>
				</tr>
				<tr>
					<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">
						<p style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';font-size:15px;line-height:1.5em;margin-top:0;text-align:left;margin-bottom: 15px">This sub-account will receive a welcome email with their log-in credentials to their PopCom SaaS website and instructions on getting started with their account. You may now add machines to this sub-account.
						</p>
					</td>
				</tr>
				<tr>
					<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">
						<p style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';font-size:15px;line-height:1.5em;margin-top:0;text-align:left;">Please contact <a href="mailto: admin@popcom.shop">admin@popcom.shop</a>. with any questions.
						</p>
					</td>
				</tr>

<!-- footer -->
@include('emails.footer')