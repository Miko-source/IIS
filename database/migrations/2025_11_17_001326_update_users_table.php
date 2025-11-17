<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Doplníme všechny dodatečné sloupce, které v defaultní migraci chybí
            $table->string('surname')->after('name');
            $table->string('contact')->nullable()->after('password');
            $table->string('address')->nullable()->after('contact');
            $table->enum('role', [
                'admin',
                'campaign_manager',
                'coordinator',
                'worker'
            ])->default('guest')->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['surname', 'contact', 'address', 'role']);
        });
    }
};


