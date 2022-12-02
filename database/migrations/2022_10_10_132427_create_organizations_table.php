<?php

use App\Models\Organization;
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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->enum('company_type', Organization::TYPES)->nullable();
            $table->integer('number_of_users')->default(1);
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('continent', Organization::CONTINENTS)->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->json('products_categories')->nullable();
            $table->json('selling_methods')->nullable();
            $table->enum('marketplaces', Organization::MARKETPLACES)->nullable();
            $table->boolean('has_list_uploaded')->default(false);
            $table->boolean('has_details_completed')->default(false);
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
        Schema::dropIfExists('organizations');
    }
};
