<?php

use App\Models\Organization;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * user_id is the id of the authenticated user, for better identifying who has the connections
     * the rest of the fields are properties of the company who belogs to the authenticated user's connections network
     * Except first field, none of the properties belong to the User model, they belong to the organization or to the related organization type
     *
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('organization_id');
            $table->enum('organization_type', Organization::TYPES);
            $table->string('name');
            $table->string('continent');
            $table->string('country');
            $table->string('city');
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
        Schema::dropIfExists('connections');
    }
};
