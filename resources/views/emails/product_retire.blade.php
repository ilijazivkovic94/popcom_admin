@include('emails.header') 
<!-- Email Body -->
<tr>
	<td class="body" width="100%" cellpadding="0" cellspacing="0" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';background-color:#edf2f7;margin:0;padding:0;width:100%;border-bottom: 0px solid #edf2f7; border-top: 0px solid #edf2f7;">
		<table class="inner-body" align="center" cellpadding="0" cellspacing="0" role="presentation" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';background-color:#ffffff;border-color:#e8e5ef;border-width:1px;margin:0 auto;padding:0;width:100%;border-bottom: 0px solid #edf2f7; border-top: 0px solid #edf2f7;">
			<tbody>
				<!-- Body content -->
				<tr>
					<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">
						<p style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';font-size:15px;line-height:1.5em;margin-top:0;text-align:left;">Hello {{$user['account_name']}},<br/></p>
					</td>
				</tr>
				<tr>
					<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">
						<p style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';font-size:15px;line-height:1.5em;margin-top:0;text-align:left;"><br/> {{$orgName['account_name']}} Org has retired the following product:.<br/><br/>
							{{$product['product_name']}}<br/>
							<?= $product['product_description'];?><br>
						</p>
							@if($variantData->isNotEmpty())
							Product Varient:<br><br>
							<table class="inner-body" align="center" cellpadding="0" cellspacing="0" role="presentation" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';background-color:#ffffff;border-color:#e8e5ef;border-width:1px;margin:0 auto;padding:0;width:100%">
								<tbody>
									<tr>
										<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">Sr.No.</td>
										<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">Product Identifier</td>
										<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">Variant Type</td>
										<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">Variant Name</td>
										<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">Default Price</td>
									</tr>
									@foreach($variantData as $key => $val)
										<tr>
											<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">{{$key+1}}</td>
											<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">{{$val->product_identifier}}</td>
											<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">{{$val->variant_sku}}</td>
											<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">{{$val->variant_name}}</td>
											<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">{{$val->variant_price}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
							<br>
							@endif
							@if(!empty($product['product_image']))
								Product Key Image:<br><br> <img src="{{$product['product_image']}}" width="200px" /><br>
							<br>
							@endif
						
					</td>
				</tr>
				<tr>
					<td class="content-cell" style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';padding: 0 20px 0 20px;">
						<p style="font-family:'-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';font-size:15px;line-height:1.5em;margin-top:0;text-align:left;"><br/><br/><br/><!-- Please note, this machine has age/consumption regulation features activated. Please go to EDIT MACHINE to update the Minimum Agesetting for this machine. -->
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