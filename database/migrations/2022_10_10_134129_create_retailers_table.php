<?php

use App\Models\Organization;
use App\Models\Retailer;
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
        Schema::create('retailers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->enum('continent', Organization::CONTINENTS)->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->json('products_categories')->nullable();
            $table->json('selling_methods')->nullable();
            $table->enum('marketplaces', Retailer::MARKETPLACES)->nullable();
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
        Schema::dropIfExists('retailers');
    }
};
