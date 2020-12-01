<?php

namespace Config;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

    class Database{

        public function __construct()
        {
            $capsule = new Capsule;
            //database id15300749_programacion3
            //pass  _\@Sf6~aUDggO7tm
            //username id15300749_useradmin
            $capsule->addConnection([
                'driver'    => 'mysql',
                'host'      => 'localhost',
                'database'  => 'id15300749_programacion3',
                'username'  => 'id15300749_useradmin',
                'password'  => '_\@Sf6~aUDggO7tm',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ]);

            // Set the event dispatcher used by Eloquent models... (optional)

            $capsule->setEventDispatcher(new Dispatcher(new Container));

            // Make this Capsule instance available globally via static methods... (optional)
            $capsule->setAsGlobal();

            // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
            $capsule->bootEloquent();
        }
    
    }

?>