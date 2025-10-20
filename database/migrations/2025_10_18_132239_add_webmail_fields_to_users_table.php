<?php

// database/migrations/xxxx_xx_xx_add_webmail_fields_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model as Eloquent;

class AddWebmailFieldsToUsersTable extends Migration
{
    public function up()
    {

        Eloquent::unguard();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $path = 'database/sql/fcc_db1.sql';
        DB::unprepared(file_get_contents($path));
        //$this->command->info('All services setup tables migrated!');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Eloquent::reguard();
       
    }

    public function down()
    {
       /*  Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['webmail_email', 'webmail_password']);
        }); */
    }
}

