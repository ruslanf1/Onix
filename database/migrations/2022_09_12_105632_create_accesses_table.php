<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accesses', function (Blueprint $table) {
            $table->id();
            $table->text('access_token')->comment('Токен доступа. Позволяет обращаться к сервисам amoCRM от имени пользователя. Срок жизни 1 сутки.');
            $table->text('refresh_token')->comment('Токен обновления. Используется для обновления access токена. Срок жизни 3 месяца.');
            $table->integer('expires_in')->comment('Вреня в формате Unix. Время истечения refresh токена.');
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
        Schema::dropIfExists('accesses');
    }
};
