<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{

	public function __construct()
	{
		//instantiate models
		$attendance = new Attendance;
		$users = new User;

		//try to get records from db to ascertain if data has been populated.
		$userData = $users->find(74);
		$attendanceData = $attendance->find(80);

		//get path to csv file
		$storage_path = storage_path();
    	$file = $storage_path . '/attendance.csv';

    	//load csv file
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
			$resultCount = count($result);
			
			for ($i=0; $i < $resultCount; $i++) {

				//create instance of Attendance model
				$dayData = new Attendance;
				
				$date = $result[$i]['date'];
				$staffId = $result[$i]['staff_id'];
				$time = $result[$i]['time'];

				//get staff attendance data already stored in db
				$dataCheck = Attendance::where('staff_id', $staffId)
							->pluck('date');
				
				if ($dataCheck->isEmpty()) {

					//check if teacher's arrival time is above 8am
					$arrivalTime = explode(':', $time);

					//prepare data for storage
					$dayData->late = $arrivalTime[0] > 8 ? 'Y' : 'N';
					$dayData->staff_id = $staffId;
					$dayData->time = $time;
					$dayData->date = $date;

					//save data
					$dayData->save();
				
				}else{
					foreach ($dataCheck as $value) {
						//store first attendance entry only
						if ($date !== $value) {
														
							//check if teacher's arrival time is above 8am
							$arrivalTime = explode(':', $time);

							//prepare data for storage
							$dayData->late = $arrivalTime[0] > 8 ? 'Y' : 'N';
							$dayData->staff_id = $staffId;
							$dayData->time = $time;
							$dayData->date = $date;

							//save data
							$dayData->save();
						}
					}					
				}

			}
			
		}
	}
    public function index()
    {
    	// $dt = Carbon::parse('2012-9-5 23:26:11.123789');
    	// echo $dt->year;

    	$user = DB::table('users')->get();
    	$data['users'] = $user;

       	return view('index', $data);
    }
}
