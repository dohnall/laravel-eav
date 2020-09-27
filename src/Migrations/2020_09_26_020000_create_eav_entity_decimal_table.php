<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEavEntityDecimalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('eav_entity_decimal');
        Schema::create('eav_entity_decimal', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('lang_id')->default(1)->index();
            $table->unsignedBigInteger('attribute_id')->index();
            $table->unsignedBigInteger('entity_id')->index();
            $table->decimal('value', 12, 4)->nullable();

            $table->foreign('attribute_id')->references('id')->on('eav_attributes')->onDelete('cascade');
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
        Schema::dropIfExists('eav_entity_decimal');
    }
}
