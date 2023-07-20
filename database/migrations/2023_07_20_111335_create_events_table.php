<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('event_title', 200);
            $table->timestamp('event_start_date');
            $table->timestamp('event_end_date');
            $table->unsignedInteger('organization_id');
            $table->index('organization_id');
            $table->foreign('organization_id')
            ->references('id')
            ->on('authorizations')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
