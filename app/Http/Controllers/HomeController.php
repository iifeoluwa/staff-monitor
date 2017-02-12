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
    	$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    	
    	$users = DB::table('users')->get();
    	$attendanceData = [];

    	foreach ($users as $user) {

    		foreach ($months as $key => $value) {

    			$month = $key + 1;

				$attendanceData[$user->id]['late'][$value] = $this->getLateness($user->staff_id, $month);
				$attendanceData[$user->id]['prompt'][$value] = $this->getPromptness($user->staff_id, $month);    			
    		}
    	}
    	
    	$data['attendance'] = $attendanceData;
    	$data['months'] = $months;
    	$data['users'] = $users;

       	return view('index', $data);
    }


   /* 	Fetch single user.
	*	@param $userId {the user's unique staff id}
    */
    public function user($userId)
    {
    	$staffId = DB::table('users')->where('id', $userId)->pluck('staff_id');
    	$staffName = DB::table('users')->where('id', $userId)->pluck('name');
    	
    	$attendanceData = DB::table('attendance')->where('staff_id', $staffId)->get();

    	$staffData = [];

    	foreach ($attendanceData as $value) {

    		//Parse date to better format
    		$year = Carbon::parse($value->date)->format('Y');
    		$month = Carbon::parse($value->date)->format('F');

    		//Parse time to better format
    		$time = Carbon::parse($value->time);
    		$time->format('h:i:s A'); 

   			$dateMarker = $month . ' ' . $year;
   			
   			if (empty($staffData[$year]['marker'])) {
	    		$staffData[$year]['marker'][] = $dateMarker;
	       	}
	     	
   			$staffData[$year][] =	[
					   			'time' => $time,
					   			'date' => $value->date,
					   			'month' => $month					   		
					   		]; 

	    	}   
    	$data = [];
    	$data['staffData'] = $staffData;
    	$data['name'] = $staffName[0];
    	
       	return view('user', $data);
    }

    /* 	Get lateness
	*	@param $id {the user's unique staff id}
	*	@param $month {month of the year whose lateness is required}
    */
    public function getLateness($id=null, $month = null)
    {
    	if (!empty($id) && !empty($month)) {
			
			$staff = DB::table('attendance')->where('staff_id', $id)
											->whereMonth('date', $month)
											->pluck('late');

			$total = $staff->count();
			$noOfTimesLate = 0;

			foreach ($staff as $value) {
				if ($value == 'Y') {
					$noOfTimesLate++;
				}
			}

			if ($total !== 0) {
							
				$lateness = floor(($noOfTimesLate / $total) * 100) . '%';
				
				return $lateness; 	
			}else{
				return '0%';
			}

    	}
    }

    public function getPromptness($id=null, $month = null)
    {
    	if (!empty($id) && !empty($month)) {
			
			$staff = DB::table('attendance')->where('staff_id', $id)
											->whereMonth('date', $month)
											->pluck('late');

			$total = $staff->count();
			$noOfTimesLate = 0;

			foreach ($staff as $value) {
				if ($value == 'N') {
					$noOfTimesLate++;
				}
			}

			if ($total !== 0) {

				$promptness = floor(($noOfTimesLate / $total) * 100) . '%';
			
				return $promptness;

			}else{
				return '0%';
			}
			
			 	
    	}
    }
}
