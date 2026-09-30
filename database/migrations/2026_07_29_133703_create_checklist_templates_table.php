<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('checklist_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobdesk_id')->constrained('jobdesks')->onDelete('cascade');
            $table->string('field_name');
            $table->string('field_label');
            $table->enum('field_type', ['text', 'number', 'select', 'checkbox', 'textarea']);
            $table->json('options')->nullable(); // Untuk select
            $table->boolean('is_required')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('default_value')->nullable();
            $table->text('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('checklist_templates');
    }
};