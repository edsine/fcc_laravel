<?php

// database/migrations/xxxx_xx_xx_create_settings_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('provider_name'); // Name of the mail provider (Gmail, Yahoo, etc.)
            $table->string('hostname'); // IMAP hostname (e.g., imap.gmail.com)
            $table->integer('port')->default(993); // Port for IMAP, default is 993
            $table->boolean('ssl')->default(true); // SSL enabled or not
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}

