<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('category_id')->default(1)->index();
            $table->string('title');
            $table->string('slug')->index();
            $table->text('source_url')->nullable();
            $table->text('description')->nullable();
            $table->integer('page_number')->default(0)->index();
            $table->integer('price')->index()->default(0);
            $table->integer('original_size')->default(0)->comment('Kich thuoc goc');
            $table->string('original_format')->default(0)->comment('kick thuoc sau khi format');
            $table->longText('full_text')->nullable();
            $table->string('disks')->nullable();
            $table->string('path')->nullable();
            $table->string('type')->default('pdf');
            $table->string('language')->nullable()->comment('language code');
            $table->string('country')->nullable()->comment('country code');
            $table->integer('helpful_count')->default(0);
            $table->integer('unhelpful_count')->default(0);
            $table->integer('viewed_count')->default(0);
            $table->integer('downloaded_count')->default(0);
            $table->integer('shared_count')->default(0);
            $table->boolean('active')->default(false);
            $table->integer('is_public')->default(0)->comment('0: private, 1: public');
            $table->integer('is_approved')->default(0)->comment('0: wating, 1: yes, -1: no');
            $table->integer('can_download')->default(1);
            $table->dateTime('approved_at')->nullable();
            $table->json('payload')->nullable();
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
        Schema::dropIfExists('documents');
    }
}
