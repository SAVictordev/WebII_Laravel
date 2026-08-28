<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('price_on_books', function (Blueprint $table) {
            $table->renameColumn('value', 'price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('price_on_books', function (Blueprint $table) {
            $table->renameColumn('price', 'value');
        });
    }
};
