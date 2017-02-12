<!DOCTYPE html>
<html>
<head>
	<title>Staff Monitor</title>
	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.13/css/dataTables.material.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

	<div id="hero">
		<h3>Staff Monitor</h3>
	</div>

	<table id="userTable" class="mdl-data-table" cellspacing="0" width="30%">
		<thead>
            <tr>
            	<th>#</th>
                <th>Name</th>
                <th>Email</th>
                <?php 
                	for ($i=0; $i < count($months); $i++) { 
                		echo "<th>$months[$i]</th>";
                	}
				?>
            </tr>
        </thead>

        <tbody>
        	@foreach($users as $user)
				<tr>
	                <td>{{$user->id}}</td>
	                <td><a href="/user/{{$user->id}}">{{$user->name}}</a></td>
	                <td>{{$user->email}}</td>
	                <td>{{$user->email}}</td>
	                <td>{{$user->email}}</td>
	                <td>{{$user->email}}</td>
	                <td>{{$user->email}}</td>
	                <td>{{$user->email}}</td>
	                <td>{{$user->email}}</td>
	                <td>{{$user->email}}</td>
	                <td>{{$user->email}}</td>
	                <td>{{$user->email}}</td>
	                <td>{{$user->email}}</td>
	                <td>{{$user->email}}</td>
	                <td>{{$user->email}}</td>

	                
	            </tr>
			@endforeach
        </tbody>
	</table>

	<script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/dataTables.material.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
		    $('#userTable').DataTable( {
		    	"paging":   true,
		        "ordering": true,,
		        "info":     true,
		    	"scrollX": true,
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