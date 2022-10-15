<?php

use App\Models\Organization;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * user_id is the id of the authenticated user, for better identifying who is receiving the requests
     * the rest of the fields are properties of the company who made the request
     * Except first field, none of the properties belong to the User model, they belong to the organization or to the related organization type
     *
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connection_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('organization_id');
            $table->enum('organization_type', Organization::TYPES);
            $table->string('name');
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
        Schema::dropIfExists('connection_requests');
    }
};
