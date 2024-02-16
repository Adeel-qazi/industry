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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('first_nation');
            $table->integer('treaty');
            $table->string('tribal_council');
            $table->string('website')->nullable();
            $table->string('mailing_address');
            $table->string('town');
            $table->string('province');
            $table->string('postal_code');
            $table->string('phone');
            $table->text('physical_location');
            $table->string('language');
            $table->integer('on_reserve_membership');
            $table->integer('off_reserve_membership');
            $table->string('chief');
            $table->text('councillor');
            $table->integer('term');
            $table->date('election_date');
            $table->string('economic_development_corp')->nullable();
            $table->string('ec_dev_corp_website')->nullable();
            $table->text('existing_company');
            $table->text('equity_investment')->nullable();
            $table->text('partner')->nullable();
            $table->text('news')->nullable();

            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
