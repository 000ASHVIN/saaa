<!DOCTYPE html>
<!-- Template Name: Clip-Two - Responsive Admin Template build with Twitter Bootstrap 3.x | Author: ClipTheme -->
<!--[if IE 8]><html class="ie8" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en">
	<!--<![endif]-->
	<!-- start: HEAD -->
	<head>
		<title>ECMS | @yield('title', 'Dashboard') | @yield('description', '')</title>
		<!-- start: META -->
		<!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta content="E-Learning, Content Management and Member Management System" name="description" />
		<meta content="Gerhard Theunissen" name="author" />
		<!-- end: META -->

		<!-- start: GOOGLE FONTS -->
		<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
		<!-- end: GOOGLE FONTS -->

		<!-- start: MAIN CSS -->
		<link rel="stylesheet" href="/assets/admin/vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="/assets/admin/vendor/fontawesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="/assets/admin/vendor/themify-icons/themify-icons.min.css">
		<link href="/assets/admin/vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
		<link href="/assets/admin/vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
		<link href="/assets/admin/vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
		<!-- end: MAIN CSS -->

		<!-- start: Theme CSS -->
		<link rel="stylesheet" href="/assets/admin/assets/css/styles.css">
		<link rel="stylesheet" href="/assets/admin/assets/css/plugins.css">
		<link rel="stylesheet" href="/assets/admin/assets/css/themes/theme-1.css" id="skin_color" />
		<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css" rel="stylesheet" media="screen">
		<link href="/assets/admin/vendor/sweetalert/ie9.css" rel="stylesheet" media="screen">
		@yield('styles')

		<!-- Select 2 -->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" rel="stylesheet" />

		<!-- end: Theme CSS -->
		<style>
			span.select2-container.select2-container--default.select2-container--open {
				z-index: 9999 !important;
			}
		</style>
		<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
		@yield('css')
		<!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
        @include('includes.globals')
		<span style="background: #ffffff; width: 100%; height: 100%" ></span>
	</head>
	<!-- end: HEAD -->

	<body>
	<div class="se-pre-con"></div>
		<div id="app">
			<!-- sidebar -->
			@include('admin.partials.sidebar')
			<!-- / sidebar -->
			<div class="app-content">
				
				<!-- start: TOP NAVBAR -->
				@include('admin.partials.topbar')

				<!-- end: TOP NAVBAR -->

				<div class="main-content" >
					<div class="wrap-content container" id="container">
						<!-- start: DASHBOARD TITLE -->
						@include('admin.partials.title')
						<!-- end: DASHBOARD TITLE -->
						@yield('content')
					</div>
				</div>

			</div>

			<!-- start: FOOTER -->
			<footer>
				<div class="footer-inner">
					<div class="pull-left">
						&copy; <span class="current-year"></span><span class="text-bold text-uppercase"> ECMS</span>. <span>All rights reserved</span>
					</div>
					<div class="pull-right">
						<span class="go-top"><i class="ti-angle-up"></i></span>
					</div>
				</div>
			</footer>
			<!-- end: FOOTER -->

			<!-- start: OFF-SIDEBAR -->
			<div id="off-sidebar" class="sidebar">
				<div class="sidebar-wrapper">
					<ul class="nav nav-tabs nav-justified">
						<li class="active">
							<a href="#off-users" aria-controls="off-users" role="tab" data-toggle="tab">
								<i class="ti-comments"></i>
							</a>
						</li>
						<li>
							<a href="#off-favorites" aria-controls="off-favorites" role="tab" data-toggle="tab">
								<i class="ti-heart"></i>
							</a>
						</li>
						<li>
							<a href="#off-settings" aria-controls="off-settings" role="tab" data-toggle="tab">
								<i class="ti-settings"></i>
							</a>
						</li>
					</ul>
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="off-users">
							<div id="users" toggleable active-class="chat-open">
								<div class="users-list">
									<div class="sidebar-content perfect-scrollbar">
										<h5 class="sidebar-title">On-line</h5>
										<ul class="media-list">
											<li class="media">
												<a data-toggle-class="chat-open" data-toggle-target="#users" href="#">
													<i class="fa fa-circle status-online"></i>
													<img alt="..." src="/assets/admin/assets/images/avatar-2.jpg" class="media-object">
													<div class="media-body">
														<h4 class="media-heading">Nicole Bell</h4>
														<span> Content Designer </span>
													</div>
												</a>
											</li>
											<li class="media">
												<a data-toggle-class="chat-open" data-toggle-target="#users" href="#">
													<div class="user-label">
														<span class="label label-success">3</span>
													</div>
													<i class="fa fa-circle status-online"></i>
													<img alt="..." src="/assets/admin/assets/images/avatar-3.jpg" class="media-object">
													<div class="media-body">
														<h4 class="media-heading">Steven Thompson</h4>
														<span> Visual Designer </span>
													</div>
												</a>
											</li>
											<li class="media">
												<a data-toggle-class="chat-open" data-toggle-target="#users" href="#">
													<i class="fa fa-circle status-online"></i>
													<img alt="..." src="/assets/admin/assets/images/avatar-4.jpg" class="media-object">
													<div class="media-body">
														<h4 class="media-heading">Ella Patterson</h4>
														<span> Web Editor </span>
													</div>
												</a>
											</li>
											<li class="media">
												<a data-toggle-class="chat-open" data-toggle-target="#users" href="#">
													<i class="fa fa-circle status-online"></i>
													<img alt="..." src="/assets/admin/assets/images/avatar-5.jpg" class="media-object">
													<div class="media-body">
														<h4 class="media-heading">Kenneth Ross</h4>
														<span> Senior Designer </span>
													</div>
												</a>
											</li>
										</ul>
										<h5 class="sidebar-title">Off-line</h5>
										<ul class="media-list">
											<li class="media">
												<a data-toggle-class="chat-open" data-toggle-target="#users" href="#">
													<img alt="..." src="/assets/admin/assets/images/avatar-6.jpg" class="media-object">
													<div class="media-body">
														<h4 class="media-heading">Nicole Bell</h4>
														<span> Content Designer </span>
													</div>
												</a>
											</li>
											<li class="media">
												<a data-toggle-class="chat-open" data-toggle-target="#users" href="#">
													<div class="user-label">
														<span class="label label-success">3</span>
													</div>
													<img alt="..." src="/assets/admin/assets/images/avatar-7.jpg" class="media-object">
													<div class="media-body">
														<h4 class="media-heading">Steven Thompson</h4>
														<span> Visual Designer </span>
													</div>
												</a>
											</li>
											<li class="media">
												<a data-toggle-class="chat-open" data-toggle-target="#users" href="#">
													<img alt="..." src="/assets/admin/assets/images/avatar-8.jpg" class="media-object">
													<div class="media-body">
														<h4 class="media-heading">Ella Patterson</h4>
														<span> Web Editor </span>
													</div>
												</a>
											</li>
											<li class="media">
												<a data-toggle-class="chat-open" data-toggle-target="#users" href="#">
													<img alt="..." src="/assets/admin/assets/images/avatar-9.jpg" class="media-object">
													<div class="media-body">
														<h4 class="media-heading">Kenneth Ross</h4>
														<span> Senior Designer </span>
													</div>
												</a>
											</li>
											<li class="media">
												<a data-toggle-class="chat-open" data-toggle-target="#users" href="#">
													<img alt="..." src="/assets/admin/assets/images/avatar-10.jpg" class="media-object">
													<div class="media-body">
														<h4 class="media-heading">Ella Patterson</h4>
														<span> Web Editor </span>
													</div>
												</a>
											</li>
											<li class="media">
												<a data-toggle-class="chat-open" data-toggle-target="#users" href="#">
													<img alt="..." src="/assets/admin/assets/images/avatar-5.jpg" class="media-object">
													<div class="media-body">
														<h4 class="media-heading">Kenneth Ross</h4>
														<span> Senior Designer </span>
													</div>
												</a>
											</li>
										</ul>
									</div>
								</div>
								<div class="user-chat">
									<div class="chat-content">
										<div class="sidebar-content perfect-scrollbar">
											<a class="sidebar-back pull-left" href="#" data-toggle-class="chat-open" data-toggle-target="#users"><i class="ti-angle-left"></i> <span>Back</span></a>
											<ol class="discussion">
												<li class="messages-date">
													Sunday, Feb 9, 12:58
												</li>
												<li class="self">
													<div class="message">
														<div class="message-name">
															Peter Clark
														</div>
														<div class="message-text">
															Hi, Nicole
														</div>
														<div class="message-avatar">
															<img src="/assets/admin/assets/images/avatar-1.jpg" alt="">
														</div>
													</div>
													<div class="message">
														<div class="message-name">
															Nicole Bell
														</div>
														<div class="message-text">
															How are you?
														</div>
														<div class="message-avatar">
															<img src="/assets/admin/assets/images/avatar-1.jpg" alt="">
														</div>
													</div>
												</li>
												<li class="other">
													<div class="message">
														<div class="message-name">
															Nicole Bell
														</div>
														<div class="message-text">
															Hi, i am good
														</div>
														<div class="message-avatar">
															<img src="/assets/admin/assets/images/avatar-2.jpg" alt="">
														</div>
													</div>
												</li>
												<li class="self">
													<div class="message">
														<div class="message-name">
															Peter Clark
														</div>
														<div class="message-text">
															Glad to see you ;)
														</div>
														<div class="message-avatar">
															<img src="/assets/admin/assets/images/avatar-1.jpg" alt="">
														</div>
													</div>
												</li>
												<li class="messages-date">
													Sunday, Feb 9, 13:10
												</li>
												<li class="other">
													<div class="message">
														<div class="message-name">
															Nicole Bell
														</div>
														<div class="message-text">
															What do you think about my new Dashboard?
														</div>
														<div class="message-avatar">
															<img src="/assets/admin/assets/images/avatar-2.jpg" alt="">
														</div>
													</div>
												</li>
												<li class="messages-date">
													Sunday, Feb 9, 15:28
												</li>
												<li class="other">
													<div class="message">
														<div class="message-name">
															Nicole Bell
														</div>
														<div class="message-text">
															Alo...
														</div>
														<div class="message-avatar">
															<img src="/assets/admin/assets/images/avatar-2.jpg" alt="">
														</div>
													</div>
													<div class="message">
														<div class="message-name">
															Nicole Bell
														</div>
														<div class="message-text">
															Are you there?
														</div>
														<div class="message-avatar">
															<img src="/assets/admin/assets/images/avatar-2.jpg" alt="">
														</div>
													</div>
												</li>
												<li class="self">
													<div class="message">
														<div class="message-name">
															Peter Clark
														</div>
														<div class="message-text">
															Hi, i am here
														</div>
														<div class="message-avatar">
															<img src="/assets/admin/assets/images/avatar-1.jpg" alt="">
														</div>
													</div>
													<div class="message">
														<div class="message-name">
															Nicole Bell
														</div>
														<div class="message-text">
															Your Dashboard is great
														</div>
														<div class="message-avatar">
															<img src="/assets/admin/assets/images/avatar-1.jpg" alt="">
														</div>
													</div>
												</li>
												<li class="messages-date">
													Friday, Feb 7, 23:39
												</li>
												<li class="other">
													<div class="message">
														<div class="message-name">
															Nicole Bell
														</div>
														<div class="message-text">
															How does the binding and digesting work in AngularJS?, Peter?
														</div>
														<div class="message-avatar">
															<img src="/assets/admin/assets/images/avatar-2.jpg" alt="">
														</div>
													</div>
												</li>
												<li class="self">
													<div class="message">
														<div class="message-name">
															Peter Clark
														</div>
														<div class="message-text">
															oh that's your question?
														</div>
														<div class="message-avatar">
															<img src="/assets/admin/assets/images/avatar-1.jpg" alt="">
														</div>
													</div>
													<div class="message">
														<div class="message-name">
															Peter Clark
														</div>
														<div class="message-text">
															little reduntant, no?
														</div>
														<div class="message-avatar">
															<img src="/assets/admin/assets/images/avatar-1.jpg" alt="">
														</div>
													</div>
													<div class="message">
														<div class="message-name">
															Peter Clark
														</div>
														<div class="message-text">
															literally we get the question daily
														</div>
														<div class="message-avatar">
															<img src="/assets/admin/assets/images/avatar-1.jpg" alt="">
														</div>
													</div>
												</li>
												<li class="other">
													<div class="message">
														<div class="message-name">
															Nicole Bell
														</div>
														<div class="message-text">
															I know. I, however, am not a nerd, and want to know
														</div>
														<div class="message-avatar">
															<img src="/assets/admin/assets/images/avatar-2.jpg" alt="">
														</div>
													</div>
												</li>
												<li class="self">
													<div class="message">
														<div class="message-name">
															Peter Clark
														</div>
														<div class="message-text">
															for this type of question, wouldn't it be better to try Google?
														</div>
														<div class="message-avatar">
															<img src="/assets/admin/assets/images/avatar-1.jpg" alt="">
														</div>
													</div>
												</li>
												<li class="other">
													<div class="message">
														<div class="message-name">
															Nicole Bell
														</div>
														<div class="message-text">
															Lucky for us :)
														</div>
														<div class="message-avatar">
															<img src="/assets/admin/assets/images/avatar-2.jpg" alt="">
														</div>
													</div>
												</li>
											</ol>
										</div>
									</div>
									<div class="message-bar">
										<div class="message-inner">
											<a class="link icon-only" href="#"><i class="fa fa-camera"></i></a>
											<div class="message-area">
												<textarea placeholder="Message"></textarea>
											</div>
											<a class="link" href="#">
												Send
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane" id="off-favorites">
							<div class="users-list">
								<div class="sidebar-content perfect-scrollbar">
									<h5 class="sidebar-title">Favorites</h5>
									<ul class="media-list">
										<li class="media">
											<a href="#">
												<img alt="..." src="/assets/admin/assets/images/avatar-7.jpg" class="media-object">
												<div class="media-body">
													<h4 class="media-heading">Nicole Bell</h4>
													<span> Content Designer </span>
												</div>
											</a>
										</li>
										<li class="media">
											<a href="#">
												<div class="user-label">
													<span class="label label-success">3</span>
												</div>
												<img alt="..." src="/assets/admin/assets/images/avatar-6.jpg" class="media-object">
												<div class="media-body">
													<h4 class="media-heading">Steven Thompson</h4>
													<span> Visual Designer </span>
												</div>
											</a>
										</li>
										<li class="media">
											<a href="#">
												<img alt="..." src="/assets/admin/assets/images/avatar-10.jpg" class="media-object">
												<div class="media-body">
													<h4 class="media-heading">Ella Patterson</h4>
													<span> Web Editor </span>
												</div>
											</a>
										</li>
										<li class="media">
											<a href="#">
												<img alt="..." src="/assets/admin/assets/images/avatar-2.jpg" class="media-object">
												<div class="media-body">
													<h4 class="media-heading">Kenneth Ross</h4>
													<span> Senior Designer </span>
												</div>
											</a>
										</li>
										<li class="media">
											<a href="#">
												<img alt="..." src="/assets/admin/assets/images/avatar-4.jpg" class="media-object">
												<div class="media-body">
													<h4 class="media-heading">Ella Patterson</h4>
													<span> Web Editor </span>
												</div>
											</a>
										</li>
										<li class="media">
											<a href="#">
												<img alt="..." src="/assets/admin/assets/images/avatar-5.jpg" class="media-object">
												<div class="media-body">
													<h4 class="media-heading">Kenneth Ross</h4>
													<span> Senior Designer </span>
												</div>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane" id="off-settings">
							<div class="sidebar-content perfect-scrollbar">
								<h5 class="sidebar-title">General Settings</h5>
								<ul class="media-list">
									<li class="media">
										<div class="padding-10">
											<div class="display-table-cell">
												<input type="checkbox" class="js-switch" checked />
											</div>
											<span class="display-table-cell vertical-align-middle padding-left-10">Enable Notifications</span>
										</div>
									</li>
									<li class="media">
										<div class="padding-10">
											<div class="display-table-cell">
												<input type="checkbox" class="js-switch" />
											</div>
											<span class="display-table-cell vertical-align-middle padding-left-10">Show your E-mail</span>
										</div>
									</li>
									<li class="media">
										<div class="padding-10">
											<div class="display-table-cell">
												<input type="checkbox" class="js-switch" checked />
											</div>
											<span class="display-table-cell vertical-align-middle padding-left-10">Show Offline Users</span>
										</div>
									</li>
									<li class="media">
										<div class="padding-10">
											<div class="display-table-cell">
												<input type="checkbox" class="js-switch" checked />
											</div>
											<span class="display-table-cell vertical-align-middle padding-left-10">E-mail Alerts</span>
										</div>
									</li>
									<li class="media">
										<div class="padding-10">
											<div class="display-table-cell">
												<input type="checkbox" class="js-switch" />
											</div>
											<span class="display-table-cell vertical-align-middle padding-left-10">SMS Alerts</span>
										</div>
									</li>
								</ul>
								<div class="save-options">
									<button class="btn btn-success">
										<i class="icon-settings"></i><span>Save Changes</span>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- end: OFF-SIDEBAR -->
		</div>
		<!-- start: MAIN JAVASCRIPTS -->
		<script src="/assets/admin/vendor/jquery/jquery.min.js"></script>
		<script src="/assets/admin/vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="/assets/admin/vendor/modernizr/modernizr.js"></script>
		<script src="/assets/admin/vendor/jquery-cookie/jquery.cookie.js"></script>
		<script src="/assets/admin/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
		<script src="/assets/admin/vendor/switchery/switchery.min.js"></script>
		{{--<script script src="/assets/admin/vendor/ckeditor/ckeditor.js"></script>--}}
		<script src="//cdn.ckeditor.com/4.6.2/full/ckeditor.js"></script>
		<script src="/assets/admin/vendor/ckeditor/adapters/jquery.js"></script>
		<!-- end: MAIN JAVASCRIPTS -->
		<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<script src="/assets/admin/vendor/Chart.js/Chart.min.js"></script>
		<script src="/assets/admin/vendor/jquery.sparkline/jquery.sparkline.min.js"></script>
		<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<!-- start: CLIP-TWO JAVASCRIPTS -->
		<script src="/assets/admin/assets/js/main.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>

		<!-- Select 2 -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>

		<!-- confirm delete -->
		<script src="/assets/admin/assets/js/bootstrap-confirm.js"></script>

		<!-- Copy to clipboard -->
		<script src="//cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.4.0/clipboard.min.js"></script>

		<script>
			$(window).load(function() {
				$(".se-pre-con").delay(1000).fadeOut("slow");;
			});
		</script>

		<!-- start: JavaScript Event Handlers for this page -->
		@include('sweet::alert')

		@yield('scripts')

		<script type="text/javascript" src="/assets/admin/vendor/moment/moment.min.js"></script>
		<script type="text/javascript" src="/assets/admin/vendor/bootstrap-daterangepicker/daterangepicker.js"></script>
		<!-- end: JavaScript Event Handlers for this page -->
		<!-- end: CLIP-TWO JAVASCRIPTS -->

	<script>
		$('.ckeditor').ckeditor({
		    height: '250px',
            toolbar: [
                { name: 'document', items: [ 'Source'] },
                { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript'] },
                { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', ] },
                { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley'] },
                { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
            ],
		})
	</script>

	<script>
        function spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
        }
	</script>

	<script>
        $('.is-date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
        });
	</script>
	</body>
</html>