<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return [
    'SUPER_ADMIN_DOC_LINK'  => '',
    'DOC_LINK'              => '',
    'APP_ENV'               => '',
    'WEB_URL'               => '',
    'ADMIN_EMAIL'           => '',
    'APP_VERSION'           => '',
    'WEB_VERSION'           => '',
    'DashboardMsg'          => "How's business for Flat Our of Heels?",

    //Common
    'CURRENTEPOCH'          => round(microtime(true)*1000),
    'CommonError'           => 'Oops something went wrong. Please try again later.',
    'SurveyLink'            => 'https://editor.wyzerr.com/login',
    'ShoppingSubject'       => 'Thank you for shopping with us.',
    //Common
    
    'EXISTS_EMAIL'          => 'This email is already taken.',
    'EXISTS_ACCOUNT_NAME'   => 'This account name is already taken.',

    'ProductAddSuccess'     => "Product added successfully.",
    'ProductAddError'       => "Product not added, please add at least one variant.",
    'ProductUpdateSuccess'  => "Product updated successfully.",
    'ProductDeleteSuccess'  => "Product deleted successfully.",
    'ProductDelete'         => 'You cannot delete this product as this product is used in one of the machines.',
    'ProductVariantDelete'  => 'You cannot delete this variant as this variant is used in one of the machines.',
    'ProductNameCheck'      => "Product name already exist.",
    'ProductRetireSuccess'  => "Product retire successfully.",

    'MachineUpdateSuccess'  => "Machine Inventory updated successfully.",
    'MachineAddError'       => "Product variant not exist, please add variant.",  
    'MachineAddSuccess'     => "Machine added successfully.",
    'MachineNameError'      => "Machine name already exist.",   
    'MachinesUpdateSuccess' => "Machine updated successfully.", 

    'SettingUpdateSuccess'  => "Setting updated successfully.",
    'SettingOTPSuccess'     => "OTP sent successfully.",
    'SettingPassError'      => "Please enter valid password.",
    'SettingOTPError'       => "Please enter valid OTP.",
    'SettingAuthSuccess'    => "Two Factor Authentication enable successfully.",
    'ReceiptUpdateSuccess'  => "Receipt setting updated successfully.",
    'ReceiptEmailSuccess'   => "Receipt test mail sent successfully.",
    'ReceiptEmailError'     => 'Email not sent. Please check your email confrigration.',

    'SubAccountAddSuccess'  => "Sub account added successfully.",
    'SubAccountIDError'     => "Account ID invalid. Please try again later.",

    'AdvertDeleteWar'       => 'You are trying to delete an ad that is currently active on a machine. Would you like to proceed and delete?',
    'AdvertAddSuccess'      => "Advertisement added successfully.",
    'AdvertDeleteSuccess'   => "Advertisement deleted successfully.",
    'AdvertUpdateSuccess'   => "Advertisement updated successfully.",
    
    'custome_text1'         => "Thank You for your shopping us! We hope to see you again soon.",
    'custome_text2'         => "Visit our website &lt; Add Your Website &gt; for more information.",
];