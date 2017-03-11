<!DOCTYPE html>
<html>
	<head>

		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>@Budgets - @yield('page_title')</title>

		<!-- CSS -->
		<link rel="stylesheet" href="/css/bootstrap.min.css">
		<link rel="stylesheet" href="/css/bootstrap-theme.min.css">

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

		<!-- JavaScript -->
		<script src="/js/jquery-3.0.0.min.js"></script>
		<script src="/js/bootstrap.min.js"></script>

	</head>
	<body>
		<!-- TODO: Put in some sort of nav bar -->
		<div class="header">
			Budgets
		</div>
		<div class="container">
			@yield('content')
		<div>
		<div class="footer">
			Copyright &copy; Kyler Johnson 2017
		</div>
	</body>
</html>
