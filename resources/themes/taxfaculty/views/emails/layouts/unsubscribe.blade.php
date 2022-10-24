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
															<td class="height15 space-class" height="10">
																&nbsp;
															</td>
														</tr>
														<tr>
															<td style="font-size: 12px; color: #696969;">
                                                                If you don't want to receive this type of email in the future, please
                                                                <a
                                                                    href="{{ route('unsubscribe.email', $user->email)}}"
                                                                    style="color: #696969"
                                                                    target="_blank"
                                                                    >unsubcribe</a
                                                                >
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