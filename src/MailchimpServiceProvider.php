<?php

namespace Mailchimp;

use Illuminate\Support\ServiceProvider;

class MailchimpServiceProvider extends ServiceProvider
{
  /**
    *  Register paths to be published by publish
    *  @return void
    */
  public function boot()
  {
    $this->publishes([
      __DIR__ . '/config/mailchimp.php' => config_path('mailchimp.php'),
    ]);
  }

  /**
    *  Register the bindings in the container
    *  @return void
    */
  public function register()
  {
    $this->app->bind('Mailchimp\Mailchimp', function($app){
      $config = $app['config']['mailchimp'];

      return new Mailchimp($config['api_key']);
    });
  }
}

 ?>
