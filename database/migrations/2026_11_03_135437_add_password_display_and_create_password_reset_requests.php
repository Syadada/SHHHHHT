<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'password_display')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('password_display')->nullable()->after('password');
            });
        }

        if (!Schema::hasTable('password_reset_requests')) {
            Schema::create('password_reset_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('username');
                $table->string('nama_instansi')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('status')->default('MENUNGGU'); // MENUNGGU, DIRESET
                $table->string('password_baru')->nullable();
                $table->unsignedBigInteger('reset_by')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('reset_by')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_requests');
        if (Schema::hasColumn('users', 'password_display')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('password_display');
            });
        }
    }
};
