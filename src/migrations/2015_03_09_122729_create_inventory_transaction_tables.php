<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryTransactionTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->nullable();
            $table->unsignedInteger('stock_id');
            $table->string('name')->nullable();
            $table->string('state');
            $table->decimal('quantity', 8, 2)->default(0);

            /*
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('set null');

            $table->foreign('stock_id')->references('id')->on('inventory_stocks')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            */
        });

        Schema::create('inventory_transaction_histories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->nullable();
            $table->unsignedInteger('transaction_id');

            /*
             * Allows tracking states for each transaction
             */
            $table->string('state_before');
            $table->string('state_after');

            /*
             * Allows tracking the quantities of each transaction
             */
            $table->string('quantity_before');
            $table->string('quantity_after');

            /*
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('restrict')
                ->onDelete('set null');

            $table->foreign('transaction_id')->references('id')->on('inventory_transactions')
                ->onUpdate('restrict')
                ->onDelete('cascade');
            */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('inventory_transaction_histories');
        Schema::dropIfExists('inventory_transactions');
    }
}
