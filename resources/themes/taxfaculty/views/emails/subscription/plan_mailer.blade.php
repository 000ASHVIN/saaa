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

		<link
			href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700"
			rel="stylesheet"
			type="text/css"
		/>

		<title>{{ config('app.name') }}</title>

		<style type="text/css">
			body {
				margin: 0;
			}
			p,
			li {
				font-family: "Calibri (Body)";
				color: #595959;
			}
			.mail-link a {
				text-decoration: none;
				color: #595959;
				cursor: text;
			}
		</style>
	</head>
	<body>
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
															style="color:#000000; font-family: 'Helvetica'; font-size: 14px; font-weight: 400; line-height: 23px"
														>
															<img
																src="{{ url('/') }}/assets/mailers/images/header.jpg"
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
														<td class="height15 space-class" height="20"></td>
													</tr>
													<tr>
														<td
															align="left"
															class="weight_400 lato lighter_grey_text"
															style="color:#000000; font-family: 'Helvetica'; font-size: 14px; font-weight: 400; line-height: 23px"
														>
															<p>Dear <b>{{ $user->first_name.' '.$user->last_name }},</b></p>
															<p>
																We are pleased to confirm that your
																<b>{{ $plan_data['name'] }}</b> has
																been activated to allow you, as a registered
																learner, to access events of interest and to
																start your learning journey.
															</p>
															<p>
																This subscription plan give you access to
																professional and technical content that ensures
																both your knowledge and skills are maintained so
																you remain professionally competent.
															</p>
															<p>
																To read more on what is included on your CPD
																Subscription Plan, <a
																	class="taxfaculty-link"
																	href="{{ url('/') }}/{{ $plan_data['url'] }}"
																	style="text-decoration: none;color: #92d050;border-bottom: 1px solid #92d050!important;"
																	>click here</a
																>.
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
										<td class="padding" width="20">&nbsp;</td>
										<td class="content_row" width="760">
											<table border="0" cellpadding="0" cellspacing="0">
												<tbody>
													<tr>
														<td
															align="left"
															class="weight_400 lato lighter_grey_text"
															style="color:#000000; font-family: 'Helvetica'; font-size: 14px; font-weight: 400; line-height: 23px"
														>
															<p style="color: #92d050;">
																<b>What are the benefits?</b>
															</p>

															<ul>
																<li>Live Webinars</li>
																<li>
																	Scheduled Webinars available on Demand
																	(Catch-up)
																</li>
																<li>
																	Access to the Knowledge Centre’s Technical
																	content to help you solve problems for your
																	clients and employer anytime, anywhere,
																	On-Demand
																</li>
															</ul>
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
															style="color:#000000; font-family: 'Helvetica'; font-size: 14px; font-weight: 400; line-height: 23px"
														>
															<p
																style="font-family: Arial;
                                                        font-style:italic;
                                                        color: #92d050;"
															>
																<b
																	>Stay up to date and access the latest
																	technical information - any time, any
																	place.</b
																>
															</p>

															<p style="color: #92d050;">
																<b
																	>The Technical Resource Centre service
																	includes:</b
																>
															</p>

															<ul>
																<li>
																	Commonly asked technical questions and the
																	option to ask our technical experts questions
																</li>
																<li>Extensive range of relevant webinars</li>
																<li>Tax Acts online</li>
																<li>
																	50% discount to practical case study workshops
																</li>
															</ul>
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
															style="color:#000000; font-family: 'Helvetica'; font-size: 14px; font-weight: 400; line-height: 23px"
														>
															<div
																style="padding: 0px 10px;border: 1px solid black;"
															>
																<p
																	style="color:#92d050;text-align:center;padding-bottom:10px;padding-top:5px"
																>
																	<b>FREQUENTLY ASKED QUESTIONS</b>
																</p>
																<p
																	style="font-family: Arial;
                                                            font-style:italic;"
																>
																	For a range of
																	<span
																		style="border-bottom: 1px solid #666666!important;"
																		>frequently asked questions</span
																	>
																	(such as how to book for a webinar, where to
																	accessa webinar on your profile, how to
																	complete questionnaires and more), please
																	<a
																		href="{{ url('/') }}/faq"
																		style="text-decoration: none; color: #92d050;border-bottom: 1px solid #92d050!important;"
																		><b>click here</b></a
																	>
																	to view the step-by-step process and
																	explanatory videos.
																</p>
																<p
																	style="font-family: Arial;
                                                        font-style:italic;"
																>
																	You will also receive monthly updates on
																	upcoming events to help keep you informed and
																	up to date.
																</p>
															</div>
														</td>
													</tr>
													<tr>
														<td class="height15 space-class" height="10">
															&nbsp;
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
														<td class="height15 space-class" height="20">
															&nbsp;
														</td>
													</tr>
													<tr>
														<td
															align="left"
															class="weight_400 lato lighter_grey_text"
															style="color:#000000; font-family: 'Helvetica'; font-size: 14px; font-weight: 400; line-height: 23px"
														>
															<p>
																Your dedicated consultant will be Tshepo Magopa.
																If you require any assistance, he can be reached
																on
																<span class="mail-link"
																	>events@taxfaculty.ac.za</span
																>
																or 012 943 7002.
															</p>
															<p>We trust you will enjoy this package.</p>
															<p><b>Yours in learning,</b></p>
															<p>
																<b
																	>Tshepo Magopa<br />Senior Administrator:
																	Learning and Development</b
																>
															</p>
														</td>
													</tr>
													<tr>
														<td class="height15 space-class" height="20"></td>
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
															style="color:#000000; font-family: 'Helvetica'; font-size: 14px; font-weight: 400; line-height: 23px"
														>
															<img
																src="{{ url('/') }}/assets/mailers/images/footer.jpg"
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
