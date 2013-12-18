<html>
<head>
	<title>CMS</title>
</head>
<body>
	<div class="page-container">
		@if (Session::get('message'))
			<div class="cms-message">
				{{ Session::get('message') }}
			</div>
		@endif

		<div class="page">
			@if (isset($page))
				{{ $page }}
			@endif
		</div>
	</div>
</body>
</html>