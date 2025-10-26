<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Hapus kolom yang tidak diperlukan
            $table->dropColumn(['seller_id', 'admin_id', 'service_id']);

            // Tambahkan kolom user_id
            $table->unsignedBigInteger('user_id')->after('id'); // Letakkan setelah kolom id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Relasi ke tabel users
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Tambahkan kembali kolom yang dihapus (rollback)
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();

            // Hapus kolom user_id
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}

