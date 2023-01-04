<?php 

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use Carbon\Carbon;
use View;
use DateTime;
use DateTimeZone;
use Auth;

use App\Models\Parameter;
use App\Models\Account;
use App\Models\SubAccountSettings;
use DateInterval;
use DatePeriod;


class CommonHelper
{   
    //Date Formate
    public static function DateFormat(string $string){
        if($string == ''){
            return '';
        }
        
        $tempDate = explode('-', $string);
        if( count($tempDate) > 1){
            return $string;
        }else{
            $seconds    = ($string/1000);
            $seconds    = date("m-d-Y", $seconds);
            return $seconds;
        }
    }
    
    // Generate Randon string...eg:password, pin
    public static function randomString(){
    	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomChar = '';
	    for ($i = 0; $i < 8; $i++) {
	        $randomChar .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomChar;
    }

    //Admin Mail
    public static function ADMIN_EMAIL(){
        $adminEmail = '';
        $admin_email = Parameter::where('parameter_key', 'admin_email')->first();
        if($admin_email){
            $adminEmail = $admin_email->parameter_value;
        }
        return $adminEmail;
    }


    //Sub Account Setting
    public static function SubAccountSetting(string $string){
        $parentAccountID = Auth::user()->accountDetails()->pluck('account_id_parent')->first();
        if($parentAccountID != 0){
            return SubAccountSettings::where('account_id', $parentAccountID)->pluck($string)->first();
        }else{
            return 'Y';
        }
    }

    //Sub Account Details
    public static function SubAccountDetails(){
        $account_id = Auth::user()->account_id;
        if($account_id != 0){
            return Account::where('account_id_parent', $account_id)->get();
        }else{
            return [];
        }
    }

    //Parent Account Name
    public static function ParentAccountName(){
        $parentAccountID = Auth::user()->accountDetails()->pluck('account_id_parent')->first();
        if($parentAccountID != 0){
            return Account::where('account_id', $parentAccountID)->pluck('account_name')->first();
        }else{
            return '';
        }
    }

    public static function getDateArrayList($filtertype,$startdate=null,$endate=null){

          $current_date = date('Y-m-d');
          switch ($filtertype) {
              case "today":
                  $datelist = array(
                              '00'=>'12 AM',
                              '01'=>'1 AM',
                              '02'=>'2 AM',
                              '03'=>'3 AM',
                              '04'=>'4 AM',
                              '05'=>'5 AM',
                              '06'=>'6 AM',
                              '07'=>'7 AM',
                              '08'=>'8 AM',
                              '09'=>'9 AM',
                              '10'=>'10 AM',
                              '11'=>'11 AM',
                              '12'=>'12 PM',
                              '13'=>'1 PM',
                              '14'=>'2 PM',
                              '15'=>'3 PM',
                              '16'=>'4 PM',
                              '17'=>'5 PM',
                              '18'=>'6 PM',
                              '19'=>'7 PM',
                              '20'=>'8 PM',
                              '21'=>'9 PM',
                              '22'=>'10 PM',
                              '23'=>'11 PM'
                              );
                  break;
              case "week":
                    $week = date('W', strtotime($current_date));
                    $year = date('Y', strtotime($current_date));
                    $dto = new DateTime();
                    $start = $dto->setISODate($year, $week, 0)->format('Y-m-d');
                    $end = $dto->setISODate($year, $week, 7)->format('Y-m-d');
                    
                    $begin = new DateTime($start);
                    $end = new DateTime($end);
                    $interval = new DateInterval('P1D'); // 1 Day
                    $dateRange = new DatePeriod($begin, $interval, $end);

                    $datelist = [];
                    foreach ($dateRange as $date) {
                        $datelist[] = $date->format("Y-m-d");
                    }
                  break;
              case "month":
                  for($i = 1; $i <=  date('t'); $i++)
                  {
                     $datelist[] = date('Y') . "-" . date('m') . "-" . str_pad($i, 2, '0', STR_PAD_LEFT);
                  }
                  break;
              case "year":
                  for($i = 1; $i <= 12; $i++)
                  {
                     $datelist[] = date('M', mktime(0, 0, 0, $i, 10)).' '.date('Y');
                  }
                  break;
              case "lastyear":
                  for($i = 1; $i <= 12; $i++)
                  {
                     $datelist[] = date('M', mktime(0, 0, 0, $i, 10)).' '.date("Y",strtotime("-1 year"));
                  }
                  break;
              case "picker":
                    $begin      = new DateTime($startdate);
                    $end        = new DateTime($endate);
                    $end        = $end->modify( '+1 day' ); 

                    $interval   = new DateInterval('P1D'); // 1 Day
                    $dateRange  = new DatePeriod($begin, $interval, $end);

                    $datelist   = [];
                    foreach ($dateRange as $date) {
                        $datelist[] = $date->format("Y-m-d");
                    }
                    //var_dump($datelist);die;
                  break;         
              default:
                  $datelist = array();
          }

          return $datelist;
    }

    public static function dateConversion($date,$tz,$format){
        if(empty($tz)){
           $tz = 'America/New_York'; 
        } 
        $start_date = new DateTime($date,new DateTimeZone('GMT'));
        $start_date->setTimezone(new DateTimeZone($tz));
        $time_start = $start_date->format($format);   
        return $time_start;
    }
}

?>