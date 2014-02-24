<?php
App::uses('AppHelper', 'View/Helper');

/**
 * Croogo Helper
 *
 * @category Helper
 * @package  Croogo.Croogo.View.Helper
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class UserHelper extends AppHelper {
	
	function Gettimedifference($date){
		 $date1 = $date;
		 $date2 = date('Y-m-d H:i:s');
		 $diff = abs(strtotime($date2) - strtotime($date1));
		 $years = floor($diff / (365*60*60*24));
		 $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		 $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
		 
		$hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60));
		$minuts = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);
		$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minuts*60));
		$string = "";
		if($years!=0){
			$string.= $years." Year";
		}if($months!=0){
			$string.= $months." Months";
		}if($days!=0){
			if($days==1){
				$string.= $days." Day";
			}else{
				$string.= $days." Days";
			}
		}
		if($minuts!=0){
			if($minuts==1){
				$string.= $minuts." Min";
			}else{
				$string.= $minuts." Min";
			}
		}
		if($years==0 && $months==0 && $days==0 && $hours==0 && $minuts==0){
			$string = "Few min ";
		}
		 return $string." ago";
	}
	function GettimedifferencedayBase($date){
		 $date1 = $date;
		 $date2 = date('Y-m-d H:i:s');
		 $diff = abs(strtotime($date2) - strtotime($date1));
		 $years = floor($diff / (365*60*60*24));
		 $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		 $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
		 
		$hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60));
		$minuts = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);
		$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minuts*60));
		$string = "";
		if($years!=0){
			$string.= $years." Year";
		}if($months!=0){
			$string.= $months." Months";
		}if($days!=0){ 
			if($days==1 && $months==0){
				$string.= "Today";
			}else{
				$string.= $days." Days";
			}
		}
		 
		if($years==0 && $months==0 && $days==0){
			  
			$string = "Today";
		}
		 return $string;
	}
	
	function Getunreadmessage($userId){
			App::import("Model", "Users.Usermessage");  
			$model = new Usermessage();  
			 $conditions = "send_to = '". $userId."' and `readmessage`=0"; 
			return $totalUsers = $model->find('count', array('conditions' => $conditions));
			  
	}
	function checkReviews($lessonid ,$rate_to,$rate_by){
		App::import("Model", "Users.Review");  
		$model = new Review(); 
		  $conditions = "	lesson_id  = '". $lessonid."' and `rate_by`='".$rate_by."' AND `rate_to` = '".$rate_to."'"; 
		return $totalUsers = $model->find('first', array('conditions' => $conditions)); 		
	}
	function Getunreadlesson($userObject){
		App::import("Model", "Users.Lesson");  
		$model = new Lesson(); 
		$field = "created";
		 $read = "readlessontutor";
		if($userObject['role_id'] == 4){
			$field = "tutor";
			$read = "readlesson";
		}
		if($userObject['role_id'] == 2){
			$field = "created";
			$read = "readlessontutor";
		}
		  $conditions = "	`$field`  = '". $userObject['id']."' and `$read`='0'"; 
		return $totalUsers = $model->find('count', array('conditions' => $conditions)); 		
	}
	function getCategoryusercount($cateogyrid){
		App::import("Model", "Category");  
		$model = new Category();  
		
		  $results = $model->find('first',array('conditions'=>array('parent_id'=>$cateogyrid)));  
		App::import("Model", "Users.User");  
		$userd = new User();  
	if(!empty($results)){  
		 
		return $userd->find('count',array('conditions'=>array('subject LIKE'=>'%'.$results['Category']['name'].'%')));
		   
		}else{
		return 0;
		}
	}
	function GetInvitesCount($userObject){
		App::import("Model", "Users.Userpoint");  
		$model = new Userpoint(); 
		$field = "user_id";
		 
		  $conditions = "	`$field`  = '". $userObject['id']."'     "; 
		  $totalUsers = $model->find('first', array(
		'conditions' => $conditions,
		'fields'=>'sum(point) as point'
		)); 	
			if($totalUsers[0]['point']>0){
			return $totalUsers[0]['point'];
			}else{
			return 0;
			}
	}
	
}
