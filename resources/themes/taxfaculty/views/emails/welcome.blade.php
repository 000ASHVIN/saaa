<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:v="urn:schemas-microsoft-com:vml">
	<head>
		<!-- Define Charset -->
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

		<!-- Responsive Meta Tag -->
		<meta
			name="viewport"
			content="width=device-width; initial-scale=1.0; maximum-scale=1.0;"
		/>

		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

		<title>{{ config('app.name') }}</title>

		<style type="text/css">
			body {
				margin: 0;
			}
			p {
				margin: 15px 0px;
			}
			p,
			li {
				/* font-family: "Calibri (Body)"; */
				color: #696969;
			}
			.mail-link a {
				text-decoration: none;
				color: #595959;
				cursor: text;
			}

			@media(max-width: 768px) {
				.feature-icons {
					max-width: 80%;
				}
			}
		</style>
	</head>
	<body style="font-family:Roboto; color:#696969;">
		<div class="container" style="background-color: #f1f1f1">
			<table
				align="center"
				class="benifits"
				border="0"
				cellpadding="0"
				cellspacing="0"
				class="wrapper_table"
				style="background: no-repeat center top / 100% 100%; max-width: 600px"
				width="600"
			>
				<tbody>
					<tr>
						<td
							bgcolor="#ffffff"
							class="content white_bg"
							style="background: #ffffff"
							width="600"
						>
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tbody>
									<tr>
										<td class="content_row" width="600">
											<table border="0" cellpadding="0" cellspacing="0">
												<tbody>
													<tr>
														<td
															align="left"
															class="weight_400 lato lighter_grey_text"
															style="color:#000000;  font-size: 14px; font-weight: 400; line-height: 23px"
														>
															<img
																src="{{ route('home') }}/assets/themes/taxfaculty/img/welcome/welcome_banner.jpg"
																style="display:block; margin-left: auto; margin-right: auto;width:100%;" width="600"
															/>
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

			<table
				align="center"
				border="0"
				cellpadding="0"
				cellspacing="0"
				class="wrapper_table"
				style="background: no-repeat center top / 100% 100%; max-width: 600px"
				width="600"
			>
				<tbody>
					<tr>
						<td
							bgcolor="#ffffff"
							class="content white_bg"
							style="background: #ffffff"
							width="600"
						>
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tbody>
									<tr>
										<td class="padding" width="20">&nbsp;</td>
										<td align="center" class="content_row" width="760">
											<table border="0" cellpadding="0" cellspacing="0">
												<tbody>
													<tr>
														<td
															align="left"
															class="weight_400 lato lighter_grey_text"
															style="color:#000000;  font-size: 14px; font-weight: 400; line-height: 23px"
														>
															<p>Hi {{ $user->first_name }},</p>
															<p>
																Congratulations on registering your profile on The Tax Faculty website and for starting your skills development journey with us. The best way to protect your future in this uncertain time is to invest in your skills today. 
															</p>
															<p>
																We are confident that you will not only benefit greatly from our CPD webinars and tax courses, but that you will also gain much needed skills and practical know-how imparted from our industry experts and your fellow learners.
															</p>
															<p>
																Your future starts here.
															</p>
														</td>
													</tr>
												</tbody>
											</table>
										</td>
										<td class="padding" width="20">&nbsp;</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>

            <table
                align="center"
				class=""
				border="0"
				cellpadding="0"
				cellspacing="0"
				class="wrapper_table"
				style="background-color: #ffffff;"
				width="600">
                <tr>
                    <td width="50%" align="center">
						<a href="{{url('/')}}/subscription_plans" style="text-decoration:none;">
							<p style="color:##6a739e;"><strong>Keep your knowledge up to date</strong></p>
							<img src="{{ route('home') }}/assets/themes/taxfaculty/img/welcome/view_our_subscription_plans.jpg" width="167" class="feature-icons">
						</a>
                    </td>
                    <td width="50%" align="center">
						<a href="{{url('/')}}/events" style="text-decoration:none;">
							<p style="color:##6a739e;"><strong>Join our live events</strong></p>
							<img src="{{ route('home') }}/assets/themes/taxfaculty/img/welcome/see_our_upcoming_events.jpg" width="167" class="feature-icons">
						</a>
                    </td>
				</tr>
				<tr>
					<td height="10"></td>
					<td height="10"></td>
				</tr>
                <tr>
                    <td width="50%" align="center">
						<a href="{{url('/')}}/webinars_on_demand" style="text-decoration:none;">
							<p style="color:##6a739e;"><strong>Missed something?</strong></p>
							<img src="{{ route('home') }}/assets/themes/taxfaculty/img/welcome/catch_up_with_our_wod.jpg" width="167" class="feature-icons">
						</a>
                    </td>
                    <td width="50%" align="center">
						<a href="{{url('/')}}/courses" style="text-decoration:none;">
							<p style="color:##6a739e;"><strong>Upcoming courses in tax</strong></p>
							<img src="{{ route('home') }}/assets/themes/taxfaculty/img/welcome/find_the_perfect_course.jpg" width="167" class="feature-icons">
						</a>
                    </td>
				</tr>
				<tr>
					<td colspan="2" style="padding:20px 20px 0px 20px;">
						<p style="border-top:3px solid #eaeaea; margin:0px;">
					</td>
				</tr>
            </table>
			<table
				align="center"
				class=""
				border="0"
				cellpadding="0"
				cellspacing="0"
				class="wrapper_table"
				style="background: no-repeat center top / 100% 100%; max-width: 600px"
				width="600">
				<tbody>
					<tr>
						<td
							bgcolor="#ffffff"
							class="content white_bg"
							style="background: #ffffff"
							width="600">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tbody>
									<tr>
										<td class="padding" width="20">&nbsp;</td>
										<td class="content_row" width="760">
											<table border="0" cellpadding="0" cellspacing="0">
												<tbody>
													<tr>
														<td
															align="left"
															class="weight_400 lato lighter_grey_text"
															style="color:#000000;  font-size: 14px; font-weight: 400; line-height: 23px"
														>

															<p style="color: #92d050; font-size:20px; margin-bottom:10px;">
																<strong>Get social with us </strong>
															</p>
                                                            <p style="margin-top:0px; margin-bottom:30px;">Make sure to connect with us on our various social channels. Never miss out on live updates and tax news of the day. </p>
															
															<a href="https://www.linkedin.com/company/the-tax-faculty" style="text-decoration:none;">
																<img src="{{ route('home') }}/assets/themes/taxfaculty/img/welcome/Linkedin_Lime.png" width="50">&nbsp;
															</a>
															
															<a href="https://www.facebook.com/TheTaxFaculty/" style="text-decoration:none;">
																<img src="{{ route('home') }}/assets/themes/taxfaculty/img/welcome/Facebook_Lime.png" width="50">&nbsp;
															</a>

															<a href="https://twitter.com/thetaxfaculty" style="text-decoration:none;">
																<img src="{{ route('home') }}/assets/themes/taxfaculty/img/welcome/Twitter_Lime.png" width="50">&nbsp;
															</a>

															<a href="https://www.youtube.com/channel/UCdUuCo_4wflmQpcob64K5oQ" style="text-decoration:none;">
																<img src="{{ route('home') }}/assets/themes/taxfaculty/img/welcome/Youtube_Lime.png" width="50">
																&nbsp;
															</a>

														</td>
													</tr>
													<tr>
														<td colspan="2" style="padding-top:20px;">
															<p style="border-top:3px solid #eaeaea; margin:0px;">
														</td>
													</tr>
												</tbody>
											</table>
										</td>
										<td class="padding" width="20">&nbsp;</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>

			<table
				align="center"
				class=""
				border="0"
				cellpadding="0"
				cellspacing="0"
				class="wrapper_table"
				style="background: no-repeat center top / 100% 100%; max-width: 600px"
				width="600"
			>
				<tbody>
					<tr>
						<td
							bgcolor="#ffffff"
							class="content white_bg"
							style="background: #ffffff"
							width="600"
						>
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tbody>
									<tr>
										<td class="padding" width="20">&nbsp;</td>
										<td class="content_row" width="760">
											<table border="0" cellpadding="0" cellspacing="0">
												<tbody>
													<tr>
														<td
															align="left"
															class="weight_400 lato lighter_grey_text"
															style="color:#000000;  font-size: 14px; font-weight: 400; line-height: 23px"
														>
															<p>Yours in learning,<br/>
																<b style="color: #92d050;">The Tax Faculty</b>
															</p>
														</td>
													</tr>
												</tbody>
											</table>
										</td>
										<td class="padding" width="20">&nbsp;</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>

			<table
				align="center"
				class=""
				border="0"
				cellpadding="0"
				cellspacing="0"
				class="wrapper_table"
				style="background: no-repeat center top / 100% 100%; max-width: 600px"
				width="600"
			>
				<tbody>
					<tr>
						<td
							bgcolor="#ffffff"
							class="content white_bg"
							style="background: #ffffff"
							width="600"
						>
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tbody>
									<tr>
										<td class="content_row" width="600">
											<table border="0" cellpadding="0" cellspacing="0">
												<tbody>
													<tr>
														<td
															align="left"
															class="weight_400 lato lighter_grey_text"
															style="color:#000000;  font-size: 14px; font-weight: 400; line-height: 23px"
														>
															<img
																src="{{ route('home') }}/assets/themes/taxfaculty/img/welcome/footer.jpg"
																style="display:block; margin-left: auto; margin-right: auto;width:100%;"
																width="600"
															/>
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
		</div>
	</body>
</html>