<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Auth;

use App\Models\AccountSetting;
use App\Models\SubAccountSettings;

class ContentController extends Controller
{
    public function __construct(){
        $this->title = 'Content';
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request){
        $page_title = $this->title;
        $parentSetting = $subAccountSetting = array();
        $accountSetting = AccountSetting::where('account_id',Auth::user()->account_id)->first();
        if(Auth::user()->accountDetails->account_type == 'sub'){
            $parentSetting =  AccountSetting::where('account_id', Auth::user()->accountDetails->account_id_parent)->first();
            $subAccountSetting = SubAccountSettings::where('account_id',Auth::user()->accountDetails->account_id_parent)->first();
        }
        return view('apps.content.index', compact('page_title','accountSetting','parentSetting','subAccountSetting'));
    }

    //Save
    public function store(Request $request){
        $input = $request->all();
        $searchInput['account_setting_id'] = $input['account_setting_id'];

        AccountSetting::updateorCreate($searchInput, $input);

        if(Auth::user()->accountDetails->account_type == 'ent'){
            $settingInput['cms_faq'] = $input['cms__faq_active_yn'];
            $settingInput['cms_contact'] = $input['cms_contact_us_active_yn'];
            $settingInput['cms_about'] = $input['cms_about_active_yn'];
            $settingInput['cms_testimonail'] = $input['cms_testimonials_active_yn'];
            $settingInput['cms_privacy'] = 'Y';
            $settingInput['cms_terms'] = $input['cms_terms_of_use_active_yn'];

            SubAccountSettings::where('account_id',Auth::user()->account_id)->update($settingInput);
        }
        toastr()->success('Content updated successfully!'); 
        return redirect('app/content');
    }
}