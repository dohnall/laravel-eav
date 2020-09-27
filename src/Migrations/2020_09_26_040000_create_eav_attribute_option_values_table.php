<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEavAttributeOptionValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('eav_attribute_option_values');
        Schema::create('eav_attribute_option_values', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('option_id')->index();
            $table->unsignedBigInteger('lang_id')->default(1)->index();
            $table->string('value')->nullable();

            $table->foreign('option_id')->references('id')->on('eav_attribute_options')->onDelete('cascade');
            $table->foreign('lang_id')->references('id')->on('eav_languages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eav_attribute_option_values');
    }
}
