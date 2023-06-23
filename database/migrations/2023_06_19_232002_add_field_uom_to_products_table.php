<?php

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
        Schema::table('products', function (Blueprint $table) {
            $table->enum('uom', [
                'pcs',
                'box',
                'lusin',
                'kodi',
                'rim',
                'gross',
                'meter',
                'sachet',
                'centimeter',
                'milimeter',
                'liter',
                'mililiter',
                'gram',
                'miligram',
                'kilogram',
                'ton',
                'kwintal',
                'ons',
                'mg',
                'ml',
                'cc',
                'buah',
                'butir',
                'lembar',
                'batang',
                'kantong',
                'karung',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('uom');
        });
    }
};
