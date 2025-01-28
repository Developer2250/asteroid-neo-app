<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>@yield('title', 'Neo Stats')</title>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="p-8">
	<div class="max-w-4xl mx-auto">
		@yield('content')
	</div>
</body>

</html>
