<?php

namespace App\Http\Controllers;

use App\User;
use App\Attendance;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{

	public function __construct()
	{
		
		$attendance = new Attendance;
		$users = new User;

		//get record with id 1 from db
		$data = $users->find(80);
				
		//populate db from csv file if there's no data in the db
		if (empty($data)) {

			$storage_path = storage_path();
	    	$file = $storage_path . '/attendance.csv';

	    	$reader = Excel::load($file);
	    	$result = $reader->get(['staff_id', 'first_name', 'last_name', 'email'])->toArray();

    		for ($i=0; $i < count($result); $i++) { 
    			
    			$staffId = $result[$i]['staff_id'];
    			$firstName = $result[$i]['first_name'];
    			$lastName = $result[$i]['last_name'];
    			$email = $result[$i]['email'];

    			// Check if the staff_id exists in db
    			$staffStatus = $user->where('staff_id', $staffId)->first();
    		    			    		
    			if (empty($staffStatus)) {

    				$user = new User;

    				$user->name = $firstName . ' ' . $lastName;
    				$user->email = $email;
    				$user->staff_id = $staffId;
    				
    				$user->save();

    			}
    		}
    		echo "End of for";

		}
	}
    public function index()
    {
       	return view('index');
    }
}
