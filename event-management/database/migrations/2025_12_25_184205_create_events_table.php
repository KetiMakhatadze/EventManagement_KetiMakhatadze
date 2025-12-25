<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ორგანიზატორი
            $table->string('title');
            $table->text('description');
            $table->string('image')->nullable();
            $table->string('location');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('total_seats');
            $table->integer('available_seats');
            $table->decimal('price', 8, 2)->default(0);
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('draft');
            $table->softDeletes();
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
