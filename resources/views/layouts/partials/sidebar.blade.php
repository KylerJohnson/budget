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
		<li {!! $request_parameters[0]== 'analytics' ? 'class="active"':'' !!}>
			<a href="/analytics">
				Analytics
				@if($request_parameters[0] == 'analytics')
					<span class="sr-only">(current)</span>
				@endif
			</a>
		</li>
		<li {!! $request_parameters[0]== 'budget_settings' ? 'class="active"':'' !!}>
			<a href="/budget_settings">
				Budget Settings
				@if($request_parameters[0] == 'budget_settings')
					<span class="sr-only">(current)</span>
				@endif
			</a>
		</li>
	</ul>
</div>

