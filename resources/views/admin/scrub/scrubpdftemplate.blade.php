<!DOCTYPE html>
<html lang='en'>

<body style='border:1px solid #000; padding:9px;'>
	<div id='invoice-POS'>
		<center id='top'>
			<div class='info'>
				<center><img src='data:image/png;base64,{{$logo}}' width='20% !important'></center>
				<p style='font-size:28px; line-height: 0px !important; color:blue;'>{{$export->name}}</p>
			</div>
		</center>
		<div id='mid'>
			<hr>
			<div class='info'>
				<div id='bot'>
					<div id='table'>
						<table>
							<tr>
								<td>
									<p>Please find the below receipt of the <span style='color:green;'><strong><u><i>{{$export->name}}
													</i></u></strong></span>
                                                    </p>
								</td>
							</tr>
							<tr>
								<td>
									<h4><span style='color:red;'>User Name:-</span>
                                        {{ucwords($user->name)}}
									</h4>
								</td>
								<td>
									<h4><span style='color:red;'>Email Id:-</span>
                                        {{strtolower($user->email)}}
									</h4>
								</td>
							</tr>
							<tr>
								<td>
									<h4><span style='color:red;'>Company Name:-</span>
                                        {{($user->company_name) ? ucwords($user->company_name) : "N/A"}}
									</h4>
								</td>
								<td>
									<h4><span style='color:red;'>Total Contacts:-</span>
                                        {{$export->total_count}}
									</h4>
								</td>
							</tr>
							<tr>
								<td>
									<h4><span style='color:red;'>Dnc Contacts:-</span>
                                        {{$export->active_count }}
									</h4>
								</td>
								<td>
									<h4><span style='color:red;'>Clean Contacts:-</span>
                                        {{$export->inactive_count}}
									</h4>
								</td>
							</tr>
							<tr>
								<td>
									<h4><span style='color:red;'>Invalid DNC Count:-</span>
                                        {{$export->invalid_dnc_count}}
									</h4>
								</td>
							</tr>
							<tr>
								<td>
									<h4><span style='color:red;'>IP Address:-</span>
                                        {{$ipaddress}}
                                    </h4>
								</td>
								<td>
									<h4><span style='color:red;'>Time Stamp:-</span>
										{{-- @dd($export); --}}
										@if($export->status === 'processed')
										{{ date('m/d/Y H:i:s', strtotime($updated_at->setTimezone('America/New_York'))) }}
										@endif
									</h4>
								</td>
							</tr>
						</table>
					</div><!--End Table-->
					<div id='legalcopy'><br><br><br><br><br><br><br><br>
						<h6 style='text-align: center'><strong>Thank you!</strong>
							<hr>
							<h2 style='text-align: center'>Have a Nice Day</h2>
						</h6>
						<hr>
					</div>
					<center><img src='data:image/png;base64,{{$logo}}' width='20% !important'></center>
				</div><!--End Invoice-->
			</div><!--End InvoiceBot-->
</body>

</html>