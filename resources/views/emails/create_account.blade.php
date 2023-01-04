@include('emails.header') 
<!-- Email Body -->
<tr>
	<td class="body" width="100%" cellpadding="0" cellspacing="0" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';background-color:#edf2f7;margin:0;padding:0;width:100%;border-bottom: 0px solid #edf2f7; border-top: 0px solid #edf2f7;">
		<table class="inner-body" align="center" cellpadding="0" cellspacing="0" role="presentation" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';background-color:#ffffff;border-color:#e8e5ef;border-width:1px;margin:0 auto;padding:0;width:100%;border-bottom: 0px solid #edf2f7; border-top: 0px solid #edf2f7;">
			<tbody>
				<!-- Body content -->
				<tr>
					<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">
						<p style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';font-size:15px;line-height:1.5em;margin-top:0;text-align:left;margin-bottom: 15px">Dear {{$user['account_name']}},</p>

						<p style="box-sizing:border-box;font-family:'-apple-system',BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';font-size:15px;line-height:1.5em;margin-top:0;text-align:left;margin-bottom: 15px;">Congratulations! Your PopCom account has been created.</p>
					</td>
				</tr>
				<tr>
					<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">
						<p style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';font-size:15px;line-height:1.5em;margin-top:0;text-align:left;">You may now login into your account using credentials below.<br>When you log in for the first time you will be guided step-by-step to complete setting </br> up your account with information including:<br/><br/>
							1. Payment Settings<br/>
							2. Company Details <br>
							3. Receipt Settings
						</p>
					</td>
				</tr>
				<tr>
					<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">
						<p style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';font-size:15px;line-height:1.5em;margin-top:15px;text-align:left;">Your login details are as follow <br/>
							<b>Website: <a href="{{config('constants.WEB_URL')}}" target="_blank">{{config('constants.WEB_URL')}}</a></b> <br>
							<b>Email:</b> {{$user['email']}}<br>
							<b>Password:</b> {{$user['password']}}
						</p>
					</td>
				</tr>
				<tr>
					<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">
						<p style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';font-size:15px;line-height:1.5em;margin-top:15px;text-align:left;">Please contact <a href="mailto: admin@popcom.shop">admin@popcom.shop</a>. with any questions.
						</p>
					</td>
				</tr>

<!-- footer -->
@include('emails.footer')