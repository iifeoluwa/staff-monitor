<!DOCTYPE html>
<html>
<head>
	<title>Staff Monitor</title>
	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.13/css/dataTables.material.min.css">
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/style.css') }}">
</head>
<body>
	<div id="hero">
		<h3>Staff Monitor</h3><br>
	</div>
	<span><em>Attendance Information</em></span>

	<div id="container">
		<h4>Staff Name: {{$name}} </h4>

		<div>
			@foreach($staffData as $data)
				<div>{{$data['marker'][0]}}</div>
				<table id="staffData" class="mdl-data-table" cellspacing="0" width="70%">
					<thead>
			            <tr>
			                <th>Date</th>
			                <th>Time</th>
			            </tr>
			        </thead>
					
					<tbody>
						
					</tbody>
				
				</table>
			@endforeach
		</div>
		<hr>
		
	</div>
	
	<script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/dataTables.material.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
		    $('#staffData').DataTable( {
		    	"ordering": false,

		        columnDefs: [
		            {
		                targets: [ 0, 1, 2 ],
		                className: 'mdl-data-table__cell--non-numeric'
		            }
		        ]
		    } );
		} );
	</script>
</body>
</html>