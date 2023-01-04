<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Config;
use View;
use Auth;
use Stripe;

use App\Models\Subscription;
use App\Models\Kiosk;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(){
        if (\Schema::hasTable('parameters')) {
            $system_parameters = DB::table('parameters')->get();
            if ($system_parameters){
                foreach ($system_parameters as $data) {
                    if($data->parameter_key == "stripe_secret_key"){
                        $stripe_secret_key = $data->parameter_value;
                        Config::set('services.stripe.secret', $stripe_secret_key);
                    }elseif($data->parameter_key == "stripe_publish_key"){
                        $stripe_publish_key = $data->parameter_value;
                        Config::set('services.stripe.key', $stripe_publish_key);
                    }elseif($data->parameter_key == "iam_access_key"){
                        $key = $data->parameter_value;
                    }elseif($data->parameter_key == "s3_bucket"){
                        $bucket = $data->parameter_value;
                    }elseif($data->parameter_key == "iam_secret_key"){
                        $secret = $data->parameter_value;
                    }elseif($data->parameter_key == "aws_region"){
                        $region = $data->parameter_value;
                    }elseif($data->parameter_key == "ses_username"){
                        $ses_username = $data->parameter_value;
                    }elseif($data->parameter_key == "ses_password"){
                        $ses_password = $data->parameter_value;
                    }elseif($data->parameter_key == "ses_email"){
                        $ses_email = $data->parameter_value;
                    }elseif($data->parameter_key == 'documentation_link_admin'){
                        Config::set('constants.SUPER_ADMIN_DOC_LINK', $data->parameter_value);
                    }elseif($data->parameter_key == 'documentation_link'){
                        Config::set('constants.DOC_LINK', $data->parameter_value);
                    } elseif($data->parameter_key == 'app_env'){
                        Config::set('constants.APP_ENV', $data->parameter_value);
                    }elseif($data->parameter_key == 'website_url'){
                        Config::set('constants.WEB_URL', $data->parameter_value);
                    }elseif($data->parameter_key == 'admin_email'){
                        Config::set('constants.ADMIN_EMAIL', $data->parameter_value);
                    }elseif($data->parameter_key == 'version'){
                        Config::set('constants.APP_VERSION', $data->parameter_value);
                    }elseif($data->parameter_key == 'web_version'){
                        Config::set('constants.WEB_VERSION', $data->parameter_value);
                    }
                }

                $aws = array(
                    'driver' => 's3',
                    'key'    => $key,
                    'secret' => $secret,
                    'region' => $region,
                    'bucket' => $bucket
                );

                $mailSetup = array(
                    'transport'     => 'smtp',
                    'host'          => 'email-smtp.us-east-1.amazonaws.com',
                    'port'          => '587',
                    'encryption'    => 'tls',
                    'username'      => $ses_username,
                    'password'      => $ses_password,
                    'timeout'       => null,
                    'auth_mode'     => null,
                );

                $mail_form = array('address' => $ses_email, 'name' => 'PopCom');

                // setup filesystem
                Config::set('filesystems.disks.s3', $aws);
                // setup mail
                Config::set('mail.mailers.smtp', $mailSetup);
                Config::set('mail.from', $mail_form);

                //SMS Setup
                $AWS_SMS_Setup = [
                    'key'    => $key,
                    'secret' => $secret,
                ];
                Config::set('aws.credentials', $AWS_SMS_Setup);
            }
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(){

        if(config('app.env') === 'development') {
            \URL::forceScheme('https');
        } else if(config('app.env') === 'staging') {
            \URL::forceScheme('https');
        } else if(config('app.env') === 'local_development') {
            \URL::forceScheme('http');
        } else if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        }

        View::composer('*', function($view){

            Stripe\Stripe::setApiKey(Config::get('services.stripe.secret'));
            $settingFlag = $subscriptionFlag = 0;
            if (Auth::check() && Auth::user()->user_admin_yn == 'N'){
                $accountType = Auth::user()->accountSetting()->first();
                if(Auth::user()->accountDetails->account_type == 'ent') {
                    if($accountType->main_setting_yn == 'Y'){
                        $settingFlag = 1;
                    }
                } else {
                    if($accountType->main_setting_yn == 'Y' && $accountType->receipt_setting_yn == 'Y'){
                        $settingFlag = 1;
                    }
                }

                // check user subscription
                if(Auth::user()->accountDetails->account_bypass_subs == 'N'){
                    // check subscription purchase or not
                    $getSubscription = Subscription::where('user_id',Auth::id())->first();
                    if(!empty($getSubscription)){
                        // check wether subscription is active or not
                        if(Auth::user()->subscribed($getSubscription->name)) {
                            // check user has default method or not
                            $paymentMethod = Auth::user()->defaultPaymentMethod();
                            if(empty($paymentMethod)){
                                $subscriptionFlag = 1;
                            }else{
                                // check whether any machine assign or not
                                $kiosk_count = Kiosk::where('account_id',Auth::user()->account_id)->where('kiosk_status','Y')->count();
                                if($kiosk_count == 0){
                                    $subscriptionFlag = 1;
                                }
                            }
                        }else{
                            $subscriptionFlag = 1;
                        }
                    }
                }
                view()->share(['settingFlag' => $settingFlag, 'subscriptionFlag' => $subscriptionFlag]);
            }
        });
    }
}
