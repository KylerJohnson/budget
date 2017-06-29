<?php
	$request_parameters = Request::segments();
?>

<div class="col-sm-3 col-md-2 sidebar">
	<ul class="nav nav-sidebar">
		<li {!! $request_parameters[0] == 'expenses' ? 'class="active"':''!!} >
			<a href="/expenses">
				Overview
				@if($request_parameters[0] == 'expenses')
					<span class="sr-only">(current)</span>
				@endif
			</a>
		</li>
		<li><a href="#">Reports</a></li>
		<li><a href="#">Export</a></li>
	</ul>
	<ul class="nav nav-sidebar">
		<li><a href="">Nav item</a></li>
		<li><a href="">Nav item again</a></li>
		<li><a href="">One more nav</a></li>
		<li><a href="">Another nav item</a></li>
		<li><a href="">More navigation</a></li>
	</ul>
	<ul class="nav nav-sidebar">
		<li><a href="">Nav item again</a></li>
		<li><a href="">One more nav</a></li>
		<li><a href="">Another nav item</a></li>
	</ul>
</div>

