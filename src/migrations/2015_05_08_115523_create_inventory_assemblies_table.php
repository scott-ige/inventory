<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryAssembliesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('inventory_assemblies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('inventory_id');
            $table->foreignId('part_id');
            $table->foreignId('quantity')->nullable();

            /*
            $table->foreign('inventory_id')->references('id')->on('inventories')->onDelete('cascade');
            $table->foreign('part_id')->references('id')->on('inventories')->onDelete('cascade');
            */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('inventory_assemblies');
    }
}
