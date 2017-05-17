<!DOCTYPE html>
<html>
	<head>

		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>@Budgets - @yield('page_title')</title>

		<!-- CSS -->
		<link rel="stylesheet" href="/css/app.css">

		<!-- TODO: refactor css out to external file -->
		<style>
			header, footer {
				text-align: center;
				padding-top: 30px;
				padding-bottom: 30px;
			}
			header {
				font-size: 2em;
			}
		</style>
	</head>
	<body>

		<header>
			Budgets
		</header>
		<!-- TODO: Put in some sort of nav bar -->

		<main id="page_content">
			@yield('content')
		<main>

		<div class="clearfix"></div>

		<footer>
			Copyright &copy; Kyler Johnson 2017
		</footer>

		<!-- JavaScript -->
		<script src="/js/app.js"></script>
		@yield('scripts')

	</body>
</html>
