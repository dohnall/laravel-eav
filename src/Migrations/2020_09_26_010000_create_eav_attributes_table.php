<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEavAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('eav_attributes');
        Schema::create('eav_attributes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('entity_type_id')->index();
            $table->string('name')->nullable();
            $table->string('slug')->nullable()->index();
            $table->string('input_type')->nullable();
            $table->string('frontend_input_renderer')->nullable();
            $table->unsignedTinyInteger('required')->default(0);
            $table->unsignedTinyInteger('collection')->default(0);
            $table->unsignedTinyInteger('unique')->default(0);
            $table->unsignedTinyInteger('system')->default(0);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('sort_order')->index();
            $table->timestamps();

            $table->foreign('entity_type_id')->references('id')->on('eav_entity_types')->onDelete('cascade');
            $table->unique(['entity_type_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eav_attributes');
    }
}
