<?php 

namespace Hexters\Ladmin\Routes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route as BaseRoute;
use Hexters\Ladmin\Http\Middleware\LadminLoginMiddleware;

class Ladmin {

  public static function route($function) {
    BaseRoute::group([ 
      'prefix' => 'administrator',
      'namespace' => 'Administrator',
      'as' => 'administrator.'
    ], function() use ($function) {
      Auth::routes([ 'register' => false ]);
      BaseRoute::group([
        'middleware' => [ LadminLoginMiddleware::class ],
      ], function() use ($function) {
  
        BaseRoute::resource('/', 'DashboardController')->only(['index']);
        BaseRoute::resource('/notification', 'NotificationController')->only(['index', 'update']);
        BaseRoute::resource('/profile', 'ProfileController')->only(['index', 'store']);

        BaseRoute::group(['as' => 'account.', 'prefix' => 'account'], function() {
          BaseRoute::resource('/admin', 'UserAdminController');
        });

        BaseRoute::group(['as' => 'system.', 'prefix' => 'system'], function() {
          BaseRoute::resource('/log', 'LogController')->only(['index']);
        });

        BaseRoute::group(['as' => 'access.', 'prefix' => 'access'], function() {
          BaseRoute::resource('/role', 'RoleController');
          BaseRoute::resource('/permission', 'PermissionController')->only(['index', 'show', 'update']);
        });

        $function();
  
      });
    });
  }

}