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
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendedor_id')->constrained('vendedors');
            $table->string('name');
            $table->text('description');
            $table->float('price');
            $table->enum('category', [
                "smartphones", "laptops",
                "fragrances","skincare",
                "groceries","home-decoration",
                "furniture","tops","womens-dresses",
                "womens-shoes","mens-shirts",
                "mens-shoes","mens-watches",
                "womens-watches","womens-bags",
                "womens-jewellery","sunglasses",
                "automotive","motorcycle",
                "lighting"
            ]);
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
        Schema::dropIfExists('produtos');
    }
};
