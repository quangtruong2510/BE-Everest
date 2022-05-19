<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefreshTokensTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
{
    Schema::create('refresh_tokens', function (Blueprint $table) {
      $table->string('refresh_token');
      $table->bigInteger('user_id');
      $table->timestamp('expired_at');
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
    Schema::dropIfExists('refresh_tokens');
  }
}
