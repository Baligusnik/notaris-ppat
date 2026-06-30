<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deed_numberings', function (Blueprint $table) {
            $table->unsignedInteger('serial_number')->nullable()->after('category');
            $table->string('monthly_number')->nullable()->after('deed_number');
            $table->time('time_start')->nullable()->after('deed_date');
            $table->time('time_end')->nullable()->after('time_start');
            $table->date('letter_date')->nullable()->after('party_secondary');
            $table->date('registered_date')->nullable()->after('letter_date');
            $table->date('instrument_date')->nullable()->after('registered_date');
            $table->date('due_date')->nullable()->after('instrument_date');
            $table->json('extra_data')->nullable()->after('note');
        });
    }

    public function down(): void
    {
        Schema::table('deed_numberings', function (Blueprint $table) {
            $table->dropColumn([
                'serial_number',
                'monthly_number',
                'time_start',
                'time_end',
                'letter_date',
                'registered_date',
                'instrument_date',
                'due_date',
                'extra_data',
            ]);
        });
    }
};
