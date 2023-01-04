<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kiosk;
use App\Models\KioskMonitor;
use App\Models\Account;
use App\Models\User;

use Mail;
use App\Mail\MachineAlert;

use DateTime;

class MachineStatusCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'every20min:machine_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){

        // get all active kiosk machines
        $kiosks = Kiosk::where(['kiosk_status' => 'Y', 'alert_email_yn' => 'Y'])->get();
        if($kiosks->isNotEmpty()){
            foreach ($kiosks as $kiosk) {
                // \Log::info("kiosks" .$kiosk->kiosk_id);
                $date = date("Y-m-d H:i:s", time() - 3600);
                $cdate = strtotime($date) * 1000 ;
                
                // get latest record
                $kioskMonitorLatest = KioskMonitor::where('kiosk_id',$kiosk->kiosk_id)
                // ->where('monitor_dt', '<', $cdate)
                // ->whereNotIn('monitor_status', ['ready', 'error'])
                ->whereRaw("date_format(from_unixtime(monitor_dt/1000, '%Y-%m-%d'), '%Y-%m-%d')=date_format(now(), '%Y-%m-%d') AND date_format(from_unixtime(monitor_dt/1000, '%Y-%m-%d %H:%i:%s'), '%Y-%m-%d %H:%i:%s')<DATE_SUB(NOW(), INTERVAL 20 MINUTE)")
                ->orderby('kiosk_monitor_id', 'DESC')->first();
                
                // for first time
                if(!empty($kioskMonitorLatest)){
                    if($kioskMonitorLatest->mail_sent_yn != 'Y'){
                        $accountDetails = Account::where('account_id', $kiosk->account_id)->first();
                        $userDetails    = User::where('account_id', $kiosk->account_id)->first();
                        
                        // sent mail to admin for machine down
                        $to = config('constants.ADMIN_EMAIL');
                        try{
                            \Log::info("Machine offline email send to ".$to);
                            Mail::to($to)->cc($userDetails->email)->send(new MachineAlert($kiosk, $accountDetails));
                        }catch(\Exception $e){
                            \Log::info("Machine Alert failed for ".$to);
                        }

                        // update flag
                        $updateKioskMonitor = KioskMonitor::find($kioskMonitorLatest->kiosk_monitor_id);
                        $updateKioskMonitor->mail_sent_yn = 'Y';
                        $updateKioskMonitor->save();
                    }
                }
            }
        }
        return 0;
    }
    
}
