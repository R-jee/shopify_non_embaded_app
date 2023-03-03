<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('shop_url')->nullable()->default('NULL');
            $table->smallInteger('is_enabled')->default(0);
            $table->smallInteger('send_email_welcome')->default(1);
            $table->smallInteger('verified_email')->default(1);

            $table->boolean('webhook_status');
            $table->boolean('status')->default(0);
            $table->string('shopify_scripttag_id')->nullable()->default('NULL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configurations');
    }
}
