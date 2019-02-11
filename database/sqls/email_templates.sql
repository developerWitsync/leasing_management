
INSERT INTO `email_templates` (`id`, `title`, `template_subject`, `template_code`, `template_body`, `template_special_variables`, `created_at`, `updated_at`) VALUES
(1,	'Verify Your Email Address',	'Verify Your Email Address',	'EMAIL_VERIFICATION',	'<table cellpadding="0" cellspacing="0" style="width:100%">
	<tbody>
		<tr>
			<td>
			<table border="1" cellpadding="0" cellspacing="0" style="width:100%">
				<tbody>
					<tr>
						<td style="background-color:#f5f5f5"><a href="[[APP_LINK]]">[[APP_NAME]] </a></td>
					</tr>
					<!-- Email Body -->
					<tr>
						<td>
						<table align="center" cellpadding="0" cellspacing="0"><!-- Body content -->
							<tbody>
								<tr>
									<td>
									<h1>Hi [[USER_NAME]],</h1>

									<p>Thanks for registering your account at Mobile Wallet. Please click on the below email address to verify your email address.</p>
									<!-- Action -->

									<table align="center" cellpadding="0" cellspacing="0" style="width:100%">
										<tbody>
											<tr>
												<td><!-- Border based button
                       https://litmus.com/blog/a-guide-to-bulletproof-buttons-in-email-design -->
												<table border="0" cellpadding="0" cellspacing="0" style="width:100%">
													<tbody>
														<tr>
															<td>
															<table border="0" cellpadding="0" cellspacing="0">
																<tbody>
																	<tr>
																		<td><a href="[[ACTION_URL]]" target="_blank">Verify Email Address</a></td>
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
									<!-- Sub copy -->

									<table>
										<tbody>
											<tr>
												<td>
												<p>If you&rsquo;re having trouble with the button above, copy and paste the URL below into your web browser.</p>

												<p>[[ACTION_URL]]</p>

												<p>&nbsp;</p>

												<p>Thanks,<br />
												The [[APP_NAME]] Team</p>
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
					<tr>
						<td style="background-color:#f5f5f5">
						<table align="center" cellpadding="0" cellspacing="0" style="width:100%">
							<tbody>
								<tr>
									<td>
									<p>&copy; 2018 [[APP_NAME]]. All rights reserved.</p>

									<p>[[COMPANY_NAME]]<br />
									1234 Street Rd.<br />
									Suite 1234</p>
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
',	'[[APP_NAME]],[[APP_LINK]],[[USER_NAME]],[[ACTION_URL]],[[COMPANY_NAME]]',	'2018-11-12 23:44:15',	'2018-12-23 23:15:16'),
(2,	'Contact Us Page',	'Contact Us Page',	'CONTACT_US ',	'<table cellpadding="0" cellspacing="0" style="width:100%">
	<tbody>
		<tr>
			<td>
			<table border="1" cellpadding="0" cellspacing="0" style="width:100%">
				<tbody>
					<tr>
						<td style="background-color:#f5f5f5"><a href="[[APP_LINK]]">[[APP_NAME]] </a></td>
					</tr>
					<!-- Email Body -->
					<tr>
						<td>
						<table align="center" cellpadding="0" cellspacing="0" style="width:100%"><!-- Body content -->
							<tbody>
								<tr>
									<td>
									<h1>Hi [[FIRST_NAME]] [[LAST_NAME]],</h1>

									<p>Thank you for contacting us.we will contact you as soon as possible</p>
									<!-- Action --></td>
								</tr>
								<tr>
									<td>
									<table border="0" cellpadding="0" cellspacing="0" style="width:100%">
										<tbody>
											<tr>
												<td>
												<table border="0" cellpadding="0" cellspacing="0">
													<tbody>
														<tr>
															<td>Email-ID:-&nbsp; <strong>[[EMAIL]]</strong></td>
														</tr>
														<tr>
															<td>Phone Number:- <strong>[[PHONE]]</strong></td>
														</tr>
														<tr>
															<td>No Of Real Estate:- <strong>[[NO_OF_REALESTATE]]</strong></td>
														</tr>
														<tr>
															<td>Comments:- <strong>[[COMMENTS]]</strong></td>
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
						</td>
					</tr>
					<tr>
						<td style="background-color:#f5f5f5">
						<table align="center" cellpadding="0" cellspacing="0" style="width:100%">
							<tbody>
								<tr>
									<td>
									<p>&copy; 2018 [[APP_NAME]]. All rights reserved.</p>

									<p>[[COMPANY_NAME]]<br />
									1234 Street Rd.<br />
									Suite 1234</p>
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
',	'[[FIRST_NAME]],[[LAST_NAME]],[[EMAIL]],[[PHONE]],[[NO_OF_REALESTATE]],[[COMMENTS]],[[APP_NAME]],[[COMPANY_NAME]]',	'2018-11-12 23:44:15',	'2018-12-17 05:13:12'),
(4,	'USER CREATE PAGE',	'User Create',	'USER_CREATE',	'<table cellpadding="0" cellspacing="0" style="width:100%">
	<tbody>
		<tr>
			<td>
			<table border="1" cellpadding="0" cellspacing="0" style="width:100%">
				<tbody>
					<tr>
						<td style="background-color:#f5f5f5"><a href="[[APP_LINK]]">[[APP_NAME]] </a></td>
					</tr>
					<!-- Email Body -->
					<tr>
						<td>
						<table align="center" cellpadding="0" cellspacing="0" style="width:100%"><!-- Body content -->
							<tbody>
								<tr>
									<td>
									<h1>Hi [[NAME]],</h1>

									<p>User Craeted succssfully.</p>
									<!-- Action --></td>
								</tr>
								<tr>
									<td>
									<table border="0" cellpadding="0" cellspacing="0" style="width:100%">
										<tbody>
											<tr>
												<td>
												<table border="0" cellpadding="0" cellspacing="0">
													<tbody>
														<tr>
															<td>Email-ID:-&nbsp; <strong>[[EMAIL]]</strong></td>
														</tr>
														<tr>
															<td>Phone Number:- <strong>[[PHONE]]</strong></td>
														</tr>
														<tr>
															<td>Desgination:- <strong>[[DESIGNATION]]</strong></td>
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
						</td>
					</tr>
					<tr>
						<td style="background-color:#f5f5f5">
						<table align="center" cellpadding="0" cellspacing="0" style="width:100%">
							<tbody>
								<tr>
									<td>
									<p>&copy; 2018 [[APP_NAME]]. All rights reserved.</p>

									<p>[[COMPANY_NAME]]<br />
									1234 Street Rd.<br />
									Suite 1234</p>
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
',	'[[NAME]],[[EMAIL]],[[DESIGNATION]] [[PHONE]]',	NULL,	'2018-12-20 02:22:15');

-- 2018-12-28 07:15:17