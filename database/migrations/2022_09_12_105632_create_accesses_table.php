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
            // Уникальный номер записи.
            $table->id();
            // Токен доступа. Позволяет обращаться к сервисам amoCRM от имени пользователя. Срок жизни 1 сутки.
            $table->text('access_token');
            // Токен обновления. Используется для обновления access токена. Срок жизни 3 месяца.
            $table->text('refresh_token');
            // Вреня в формате Unix. Время истечения refresh токена.
            $table->integer('expires_in');
            // Дата создания и обновления записи.
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
