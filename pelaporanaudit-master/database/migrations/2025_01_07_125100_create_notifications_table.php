<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Penerima notifikasi
            $table->unsignedBigInteger('sender_id')->nullable(); // Pengirim notifikasi (optional, bisa admin/seller/user)
            $table->string('type')->nullable(); // Jenis notifikasi (e.g., 'message', 'alert')
            $table->text('message'); // Isi notifikasi
            $table->boolean('is_read')->default(false); // Status notifikasi (belum dibaca atau sudah)
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}