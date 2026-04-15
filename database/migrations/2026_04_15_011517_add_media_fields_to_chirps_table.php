<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chirps', function (Blueprint $table) {
            $table->string('image')->nullable()->after('message');
            $table->string('audio')->nullable()->after('image');
            $table->text('link_url')->nullable()->after('audio');
            $table->string('link_title')->nullable()->after('link_url');
            $table->string('link_description')->nullable()->after('link_title');
            $table->string('link_image')->nullable()->after('link_description');
        });
    }

    public function down(): void
    {
        Schema::table('chirps', function (Blueprint $table) {
            $table->dropColumn(['image', 'audio', 'link_url', 'link_title', 'link_description', 'link_image']);
        });
    }
};