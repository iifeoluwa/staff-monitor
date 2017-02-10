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
		$userData = $users->find(74);
		$attendanceData = $attendance->find(80);

		$storage_path = storage_path();
    	$file = $storage_path . '/attendance.csv';

    	$reader = Excel::load($file);

		//populate users table in db from csv file if there's no user data present
		if (empty($userData)) {

	    	$result = $reader->get(['staff_id', 'first_name', 'last_name', 'email'])->toArray();

    		for ($i=0; $i < count($result); $i++) { 
    			
    			$staffId = $result[$i]['staff_id'];
    			$firstName = $result[$i]['first_name'];
    			$lastName = $result[$i]['last_name'];
    			$email = $result[$i]['email'];

    			// Check if the staff_id exists in db
    			$staffStatus = $users->where('staff_id', $staffId)->first();
    		    			    		
    			if (empty($staffStatus)) {

    				$user = new User;

    				$user->name = $firstName . ' ' . $lastName;
    				$user->email = $email;
    				$user->staff_id = $staffId;
    				
    				$user->save();

    			}
    		}
		}

		//populate attendance table in db from csv file if there's no user data present
		 if (empty($attendanceData)) {
			$result = $reader->get(['date', 'time', 'staff_id']);
		
			for ($i=0; $i < count($result); $i++) { 

				$dayData = new Attendance;
				
				$date = $result[$i]['date'];
				$staffId = $result[$i]['staff_id'];
				$time = $result[$i]['time'];

				//check if teacher's arrival time is above 8am
				$arrivalTime = explode(':', $time);
				echo $dayData->late = $arrivalTime[0] > 8 ? 'Y' : 'N';
				die;
			}
		}
	}
    public function index()
    {
       	return view('index');
    }
}
