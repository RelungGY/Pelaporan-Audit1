<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToKategoriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('kategori', function (Blueprint $table) {
        $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('namakategori');
    });
}

public function down()
{
    Schema::table('kategori', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}

}
