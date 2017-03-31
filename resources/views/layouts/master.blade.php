<!DOCTYPE html>
<html>
	<head>

		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>@Budgets - @yield('page_title')</title>

		<!-- CSS -->
		<link rel="stylesheet" href="/css/app.css">

		<style>
			.header, .footer {
				text-align: center;
				padding-top: 30px;
				padding-bottom: 30px;
			}
			.header {
				font-size: 2em;
			}
		</style>


	</head>
	<body>
		<!-- TODO: Put in some sort of nav bar -->
		<div class="header">
			Budgets
		</div>
		<div id="page_content">
			@yield('content')
		<div>
		<div class="clearfix"></div>
		<div class="footer">
			Copyright &copy; Kyler Johnson 2017
		</div>
		<!-- JavaScript -->
		<script src="/js/app.js"></script>
	</body>
</html>
