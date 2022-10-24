<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	</head>
	<body style="margin:0; padding:0;">
		<br style="margin:0; padding:0;" />
		<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
		<title></title>
		<meta charset="UTF-8" />
		<meta content="width=device-width, initial-scale=1.0" name="viewport" />
		<span class="preheader" style="display:none!important;mso-hide:all;"
			>{{ @$subject }}</span
		>
		<div class="span" style="background-color: #f1f1f1">
			<style>
				body {
					background-color: #f1f1f1;
				}
			</style>
			<div style="background-color: #f1f1f1">
				<table
					align="center"
					border="0"
					cellpadding="0"
					cellspacing="0"
					class="wrapper_table"
					mc:repeatable=""
					style="max-width: 600px"
					width="600"
				>
					<tbody>
						<tr>
							<td class="noresponsive">
								<table
									align="center"
									border="0"
									cellpadding="0"
									cellspacing="0"
									width="600"
								>
									<tbody>
										<tr>
											<td
												border="0"
												cellpadding="0"
												cellspacing="0"
												height="1px; min-width: 600;"
												style="line-height:1px;"
											>
												&nbsp;
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
								<a href="#" style="text-decoration: none" target="_blank"
									><img
										alt=""
										border="0"
										src="{{ url('/')  }}/assets/themes/taxfaculty/img/event_registration.jpg"
										style="display: block;"
										title=""
										width="600"
									/>
								</a>
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
								class="content space_class"
								height="10"
								style="font-size: 1px; line-height: 1px"
								width="600"
							>
								&nbsp;
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
																	<span style="color:#696969;"
																		><span style="font-size:14px;"
																			><span
																				style="font-family:arial,helvetica,sans-serif;"
																				>Dear {{
																				@ucwords(strtolower($data['first_name']))
																				}},
																			</span></span
																		></span
																	><br />
																</p>

																<div style="LINE-HEIGHT: 22px"></div>
																<table
																	border="0"
																	cellpadding="0"
																	cellspacing="0"
																	width="100%"
																>
																	<tbody>
																		<tr>
																			<td
																				align="left"
																				style="font-size: 14px; font-family: Arial, Helvetica, sans-serif; FONT-WEIGHT: 400; COLOR: #696969;"
																			>
																				<span style="font-size:14px;">
																					<span
																						style="font-family:arial,helvetica,sans-serif"
																					>
																						Thank you for registering to attend
																						the event
																						<span style="color: #8cc03c;">
																							<strong
																								>{{ trim($data['event_name'], ' ') }}</strong
																							></span
																						>
																						on<span style="color:#8cc03c;">
																							<strong>
																								@foreach($dates as $date)
																								<strong>{{ $date }}</strong>
																								@if ($date != end($dates))
																								&nbsp;, @endif @endforeach
																							</strong></span
																						>.
																						<br />
																						<br />
																						Wondering where to find your slides,
																						assessment and recording for this
																						webinar? These can be accessed from
																						your profile by logging in and
																						navigating to your
																						<span style="color:#8cc03c;">
																							<strong
																								>Dashboard > My Events</strong
																							>
																						</span>
																						and then clicking on
																						<span style="color:#8cc03c;"
																							><strong>"View Content"</strong>
																						</span>
																						next to the webinar.

																						<br />
																						<br />
																						On this page,you will find a tab to
																						join the webinar. In some instances,
																						you will be required to insert a
																						passcode (if one is required).
																						Please access the webinar slides by
																						clicking on the<span
																							style="color:#8cc03c;"
																						>
																							<strong>"Files"</strong>
																						</span>
																						tab.
																						<br />
																						<br />
																						If you are unable to attend the
																						webinar, the recording will be made
																						available to you shortly after the
																						event on your Tax Faculty profile
																						under the<span
																							style="color:#8cc03c;"
																						>
																							<strong
																								>My Events – Past Events – View
																								Content</strong
																							></span
																						>
																						section.
																						<br />
																						<br />
																						For any questions regarding the
																						webinar, please contact us on 012
																						943 7002 or
																						<a
																							href="mailto:events@taxfaculty.ac.za"
																							style="color:#8cc03c;"
																							>events@taxfaculty.ac.za
																						</a>
																					</span>
																				</span>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>
														<tr>
															<td class="height15 space-class" height="20">
																&nbsp;
															</td>
														</tr>
													</tbody>
												</table>
											</td>
											<td class="padding" width="20">&nbsp;</td>
										</tr>
										<tr>
											<td
												bgcolor="#ebebeb"
												class="light_grey_bg1 space_class"
												colspan="3"
												height="2"
												style="background: #ebebeb; font-size: 1px; line-height: 1px"
											>
												&nbsp;
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
								class="content height40 space_class"
								height="10"
								style="font-size: 1px; line-height: 1px"
								width="600"
							>
								&nbsp;
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
								bgcolor="#8cc03c"
								class="content red_bg"
								style="background: #8cc03c"
								width="600"
							>
								<img
									alt=""
									border="0"
									src="{{ url('/')  }}/assets/themes/taxfaculty/img/footer_banner.jpg"
									style="display: block;"
									title=""
									width="600"
								/>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<br />
		</div>
	</body>
</html>
