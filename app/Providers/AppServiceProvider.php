<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
  /**
  * Register any application services.
  *
  * @return void
  */
  public function register()
  {
    //
  }

  /**
  * Bootstrap any application services.
  *
  * @return void
  */
  public function boot()
  {
    //

    Schema::defaultStringLength(191);
    config(['api_key' => 'e2f076d77998bbb2921165ee490297a4']);
    date_default_timezone_set('Asia/Jakarta');

    //phonenumber
    Validator::extend('phone', function($attribute, $value, $parameters, $validator) {
      return preg_match('%^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$%i', $value) && strlen($value) >= 10;
    });
    Validator::replacer('phone', function($message, $attribute, $rule, $parameters) {
      return str_replace(':attribute',$attribute, ':attribute is invalid phone number');
    });

    //MAIL_LOG_CHANNEL
    if (class_exists('Swift_Preferences')) {
        \Swift_Preferences::getInstance()->setTempDir(storage_path().'/tmp');
    } else {
        \Log::warning('Class Swift_Preferences does not exists');
    }

  }
}
