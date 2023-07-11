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
            $table->integer('user_id')->index();
            $table->string('title');
            $table->string('slug')->index();
            $table->text('source_url')->nullable();
            $table->string('description')->nullable();
            $table->integer('page_number')->nullable()->index();
            $table->integer('price')->index()->default(0);
            $table->integer('original_size')->default(0)->comment('Kich thuoc goc');
            $table->string('original_format')->default(0)->comment('kick thuoc sau khi format');
            $table->text('full_text')->nullable();
            $table->string('disks')->nullable();
            $table->string('path')->nullable();
            $table->integer('type')->default(0)->comment('0: default, 1: text, 2: image, 3: ...');
            $table->string('language')->nullable()->comment('language code');
            $table->string('country')->nullable()->comment('country code');
            $table->integer('rating_value')->default(0);
            $table->integer('rating_count')->default(0);
            $table->integer('viewed_count')->default(0);
            $table->integer('downloaded_count')->default(0);
            $table->integer('shared_count')->default(0);
            $table->boolean('active')->default(false);
            $table->integer('is_public')->default(1)->comment('0: private, 1: public');
            $table->integer('is_approved')->default(0)->comment('0: wating, 1: yes, -1: no');
            $table->integer('can_download')->default(1);
            $table->dateTime('approved_at')->nullable();
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
