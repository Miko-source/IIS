<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// Tabulka musí být pojmenovaná „users“, protože Laravel automaticky
// mapuje model User na tabulku users. Změna názvu by rozbila
// přihlašování, Eloquent ORM i další funkce frameworku.

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('surname');
        //     $table->string('email')->unique();
        //     $table->string('password', 255);
        //     $table->string('contact')->nullable();
        //     $table->string('address')->nullable();
        //     $table->enum('role', [
        //         'admin',
        //         'campaign_manager',
        //         'coordinator',
        //         'worker',
        //         'guest'
        //     ])->default('guest');
        //     $table->timestamps();
        // });

        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('target_group')->nullable();
            $table->text('description')->nullable();
            $table->string('sources')->nullable();
            $table->timestamps();
        });
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            // má (1:N)
            $table->foreignId('topic_id')
                  ->constrained('topics')
                  ->onDelete('cascade');
            // správce kampaně (1:N)
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete(); //když se smaže uživatel, user_id, pořád zůstane správce admin
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
        });

        Schema::create('steps', function (Blueprint $table) {
            $table->id();
            // skládá se z (1:N)
            $table->foreignId('campaign_id')
                ->constrained('campaigns')
                ->onDelete('cascade');
            // koordinátor kroku (1:N)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users');
            $table->boolean('is_completed')->default(false);
            $table->string('name'); 
            $table->integer('order');
            $table->text('description')->nullable();

            $table->timestamps();
        });
        Schema::create('types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // náleží (1:N)
            $table->foreignId('step_id')
                  ->constrained('steps')
                  ->onDelete('cascade');
            // podléhá (1:N)
            $table->foreignId('type_id')
                  ->constrained('types');
            $table->decimal('cost', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
        });

        //Realizátoři aktivit (M:N) - "Realizuje"
        Schema::create('activity_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')
                  ->constrained('activities')
                  ->onDelete('cascade');
            $table->foreignId('user_id')        // realizátor
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->tinyInteger('is_confirmed')->default(0);  // 0 = pending
                // koordinátor musí potvrdit
            $table->boolean('is_completed')->default(false); //pro messages
            $table->timestamps();
            $table->unique(['activity_id', 'user_id']);
        });

        // M:N - Viditelnost kampaní pro uživatele
        // může ji upravovat jen správce kampaně nebo admin
        // Správce kampaně "přidává uživatele do kampaně"
        Schema::create('campaign_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')
                  ->constrained('campaigns')
                  ->onDelete('cascade');
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->timestamps();
            $table->unique(['campaign_id', 'user_id']);
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')        // patří k (1:N)
                  ->constrained('activities')   
                  ->onDelete('cascade');            //pripadne upravit    
            $table->foreignId('user_id')            // podává (1:N)
                  ->constrained('users');
            $table->text('content');
            $table->boolean('success')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('activity_user');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('types');
        Schema::dropIfExists('steps');
        Schema::dropIfExists('campaign_user');
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('topics');
        // Schema::dropIfExists('users');
    }
};
