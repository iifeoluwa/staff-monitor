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
		<a href="/home">Go Home</a>
	</div>
	
	<div id="container">
		<h4>Staff Name: {{$name}} </h4>

		<div>
		<?php $i = 0; ?>
			@foreach($staffData as $key => $data)
				<div><strong>Period: </strong>{{$key}}</div>
				<div>Percentage Lateness: {{$data['late']}}</div>
				<div>Percentage Early Arrivals: {{$data['prompt']}}</div>
				<table id="staffData-{{$i}}" class="mdl-data-table" cellspacing="0" width="20%">
					<thead>
			            <tr>
			                <th>Date</th>			               
			                <th>Arrival</th>
			            </tr>
			        </thead>
					
					
					<tbody>
					@foreach($data as $key => $staff)
						@if(is_int($key))
							<tr>
								<td>{{ $staff['date']}}</td>
								<td>{{ $staff['arrival']}}</td>
							</tr>
						@endif
						<?php $i++?>
					@endforeach
					</tbody>
					
				</table>
				<hr>
			@endforeach
		</div>
		
		
	</div>
	
	<script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/dataTables.material.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
		    $('#staffData-0').DataTable( {
		    	"ordering": true,

		        columnDefs: [
		            {
		                targets: [ 0, 1, 2 ],
		                className: 'mdl-data-table__cell--non-numeric'
		            }
		        ]
		    } );

		    $('#staffData-1').DataTable( {
		    	"ordering": true,

		        columnDefs: [
		            {
		                targets: [ 0, 1, 2 ],
		                className: 'mdl-data-table__cell--non-numeric'
		            }
		        ]
		    } );

		    $('#staffData-2').DataTable( {
		    	"ordering": true,

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