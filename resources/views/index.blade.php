<!DOCTYPE html>
<html>
<head>
	<title>Staff Monitor</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<div id="hero">
		<h4>Staff Monitor</h4>
	</div>

	@foreach($users as $user)
		{{$user->name}}
		<br>
	@endforeach
</body>
</html>