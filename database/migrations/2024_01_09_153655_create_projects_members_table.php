<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('project_id');
            $table->unsignedBiginteger('member_id');

            $table->foreign('project_id')->references('id')
                 ->on('projects')->onDelete('cascade');
            $table->foreign('member_id')->references('id')
                ->on('members')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects_members');
    }
};
