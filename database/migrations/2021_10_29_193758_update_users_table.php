<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->after('id');
            $table->string('username')->unique()->after('id');
            $table->float('deposit')->after('password')->default(0);
            $table->dropColumn('email_verified_at');
            $table->dropColumn('email');
            $table->dropColumn('name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('deposit');
            $table->renameColumn('username', 'name');
            $table->timestamp('email_verified_at')->nullable()->after('email');
        });
    }
}
