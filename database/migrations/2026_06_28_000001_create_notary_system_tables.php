<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('office_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['notary', 'ppat']);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('file_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('office_service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('file_number')->unique();
            $table->string('tracking_code')->unique();
            $table->string('applicant_name');
            $table->string('applicant_phone')->nullable();
            $table->string('applicant_email')->nullable();
            $table->enum('status', ['submitted', 'in_process', 'waiting_document', 'completed', 'cancelled'])->default('submitted')->index();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('file_progress_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_application_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('position');
            $table->string('title');
            $table->enum('status', ['pending', 'running', 'done', 'cancelled'])->default('pending')->index();
            $table->text('admin_note')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });

        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_application_id')->constrained()->cascadeOnDelete();
            $table->string('document_name');
            $table->text('note')->nullable();
            $table->enum('status', ['requested', 'uploaded', 'verified', 'rejected'])->default('requested')->index();
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();
        });

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_application_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->enum('mode', ['office', 'online', 'phone'])->default('office');
            $table->enum('status', ['requested', 'approved', 'rejected', 'rescheduled', 'cancelled'])->default('requested')->index();
            $table->text('feedback')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['appointment_date', 'appointment_time', 'mode']);
        });

        Schema::create('staff_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('attendance_date');
            $table->time('check_in_time')->nullable();
            $table->enum('status', ['present', 'late', 'permission', 'sick', 'absent'])->default('present')->index();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'attendance_date']);
        });

        Schema::create('notary_device_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('device_token')->unique();
            $table->string('device_name')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notary_device_sessions');
        Schema::dropIfExists('staff_attendances');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('document_requests');
        Schema::dropIfExists('file_progress_steps');
        Schema::dropIfExists('file_applications');
        Schema::dropIfExists('office_services');
    }
};
