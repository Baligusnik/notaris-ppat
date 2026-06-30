<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deed_numberings', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['notary', 'protest', 'legalization', 'waarmerking', 'ppat'])->index();
            $table->string('deed_number');
            $table->string('deed_title');
            $table->date('deed_date')->index();
            $table->string('party_primary');
            $table->string('party_secondary')->nullable();
            $table->string('normalized_parties')->index();
            $table->text('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['category', 'deed_number', 'deed_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deed_numberings');
    }
};
