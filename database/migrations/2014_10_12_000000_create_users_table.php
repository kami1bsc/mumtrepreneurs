<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); 
            $table->string('username', 30)->default("");            
            $table->string('email', 30)->default(""); 
            $table->timestamp('email_verified_at')->nullable();            
            $table->string('profile_image')->default("profile_images/default.png");
            $table->string('community_url')->default("");
            $table->text('community_description')->default("");
            $table->string('are_you_disciplined')->default("");
            $table->string('community_industry')->default("");
            $table->string('community_size')->default("");
            $table->string('city')->default("");
            $table->string('intrested_in_learning')->default("");
            $table->string('like_to_contribute')->default("");
            $table->string('social_media_url')->default("");
            $table->Integer('total_posts')->default(0);
            $table->Integer('total_followers')->default(0);
            $table->Integer('total_followings')->default(0);           
            $table->string('password')->default("");
            $table->string('device_type', 20)->default("");
            $table->string('token');
            $table->string('type')->default(""); 
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
        Schema::dropIfExists('users');
    }
}
