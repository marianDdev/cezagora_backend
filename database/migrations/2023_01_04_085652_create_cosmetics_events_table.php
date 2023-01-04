<?php

use App\Models\CosmeticsEvent;
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
        Schema::create('cosmetics_events', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_live')->default(true);
            $table->string('title');
            $table->text('description');
            $table->string('country');
            $table->string('city');
            $table->string('address');
            $table->string('host')->nullable();
            $table->string('link');
            $table->string('credit');
            $table->timestamp('start_at');
            $table->timestamp('end_at')->nullable();
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
        Schema::dropIfExists('cosmetics_events');
    }
};
