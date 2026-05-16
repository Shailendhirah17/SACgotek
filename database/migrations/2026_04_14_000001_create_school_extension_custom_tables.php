<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Transfer Certificates
        if (! Schema::hasTable('sm_transfer_certificates')) {
            Schema::create('sm_transfer_certificates', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_id');
                $table->string('tc_no')->nullable();
                $table->string('reason')->nullable();
                $table->date('date')->nullable();
                $table->string('class_name')->nullable();
                $table->string('section_name')->nullable();
                $table->unsignedInteger('school_id')->nullable();
                $table->unsignedBigInteger('academic_id')->nullable();
                $table->timestamps();
            });
        }

        // 2. Medical Records
        if (! Schema::hasTable('sm_medical_records')) {
            Schema::create('sm_medical_records', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_id');
                $table->string('blood_group', 10)->nullable();
                $table->decimal('weight', 5, 2)->nullable();
                $table->decimal('height', 5, 2)->nullable();
                $table->text('medical_history')->nullable();
                $table->text('allergies')->nullable();
                $table->text('current_medications')->nullable();
                $table->unsignedInteger('school_id')->nullable();
                $table->unsignedBigInteger('academic_id')->nullable();
                $table->timestamps();
            });
        }

        // 3. Vaccination Records
        if (! Schema::hasTable('sm_vaccination_records')) {
            Schema::create('sm_vaccination_records', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_id');
                $table->string('vaccine_name');
                $table->date('date_given')->nullable();
                $table->string('dose', 50)->nullable();
                $table->string('administered_by')->nullable();
                $table->text('remarks')->nullable();
                $table->unsignedInteger('school_id')->nullable();
                $table->unsignedBigInteger('academic_id')->nullable();
                $table->timestamps();
            });
        }

        // 4. Book Bank
        if (! Schema::hasTable('sm_book_banks')) {
            Schema::create('sm_book_banks', function (Blueprint $table) {
                $table->id();
                $table->string('book_name');
                $table->string('author')->nullable();
                $table->string('isbn', 50)->nullable();
                $table->string('publisher')->nullable();
                $table->unsignedInteger('total_copies')->default(1);
                $table->unsignedInteger('available_copies')->default(1);
                $table->string('class')->nullable();
                $table->string('subject')->nullable();
                $table->unsignedInteger('school_id')->nullable();
                $table->timestamps();
            });
        }

        // 5. Book Bank Issues
        if (! Schema::hasTable('sm_book_bank_issues')) {
            Schema::create('sm_book_bank_issues', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('book_id');
                $table->unsignedBigInteger('student_id');
                $table->date('issued_date');
                $table->date('due_date')->nullable();
                $table->date('return_date')->nullable();
                $table->enum('status', ['issued', 'returned', 'overdue'])->default('issued');
                $table->unsignedInteger('school_id')->nullable();
                $table->timestamps();
            });
        }

        // 6. Thirukkural
        if (! Schema::hasTable('sm_thirukkurals')) {
            Schema::create('sm_thirukkurals', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('kural_no')->nullable();
                $table->string('section')->nullable(); // Araththuppaal / Porutpaal / Inpaththuppaal
                $table->string('chapter')->nullable();
                $table->text('kural_tamil')->nullable();
                $table->text('kural_english')->nullable();
                $table->text('explanation')->nullable();
                $table->unsignedInteger('school_id')->nullable();
                $table->timestamps();
            });
        }

        // 7. Vendors
        if (! Schema::hasTable('sm_vendors')) {
            Schema::create('sm_vendors', function (Blueprint $table) {
                $table->id();
                $table->string('vendor_name');
                $table->string('email')->nullable();
                $table->string('phone', 20)->nullable();
                $table->text('address')->nullable();
                $table->string('gstin', 20)->nullable();
                $table->string('contact_person')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->unsignedInteger('school_id')->nullable();
                $table->timestamps();
            });
        }

        // 8. Purchase Orders
        if (! Schema::hasTable('sm_purchase_orders')) {
            Schema::create('sm_purchase_orders', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('vendor_id');
                $table->date('order_date');
                $table->text('items_description')->nullable();
                $table->decimal('total_amount', 12, 2)->default(0);
                $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
                $table->text('notes')->nullable();
                $table->unsignedInteger('school_id')->nullable();
                $table->timestamps();
            });
        }

        // 9. Vendor Payments
        if (! Schema::hasTable('sm_vendor_payments')) {
            Schema::create('sm_vendor_payments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('vendor_id');
                $table->unsignedBigInteger('purchase_order_id')->nullable();
                $table->decimal('amount', 12, 2);
                $table->date('payment_date');
                $table->string('payment_method', 50)->nullable();
                $table->string('reference_no', 100)->nullable();
                $table->text('notes')->nullable();
                $table->unsignedInteger('school_id')->nullable();
                $table->timestamps();
            });
        }

        // 10. Hostels
        if (! Schema::hasTable('sm_hostels')) {
            Schema::create('sm_hostels', function (Blueprint $table) {
                $table->id();
                $table->string('hostel_name');
                $table->enum('type', ['boys', 'girls', 'mixed'])->default('mixed');
                $table->text('address')->nullable();
                $table->unsignedInteger('capacity')->default(0);
                $table->string('warden_name')->nullable();
                $table->string('warden_phone', 20)->nullable();
                $table->unsignedInteger('school_id')->nullable();
                $table->timestamps();
            });
        }

        // 11. Hostel Rooms
        if (! Schema::hasTable('sm_hostel_rooms')) {
            Schema::create('sm_hostel_rooms', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('hostel_id');
                $table->string('room_no', 20);
                $table->string('room_type', 50)->nullable(); // Single/Double/Triple
                $table->unsignedInteger('capacity')->default(1);
                $table->decimal('fee_per_month', 10, 2)->default(0);
                $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
                $table->unsignedInteger('school_id')->nullable();
                $table->timestamps();
            });
        }

        // 12. Hostel Allocations
        if (! Schema::hasTable('sm_hostel_allocations')) {
            Schema::create('sm_hostel_allocations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('hostel_id');
                $table->unsignedBigInteger('room_id');
                $table->unsignedBigInteger('student_id');
                $table->date('join_date');
                $table->date('leave_date')->nullable();
                $table->enum('status', ['active', 'vacated'])->default('active');
                $table->unsignedInteger('school_id')->nullable();
                $table->timestamps();
            });
        }

        // 13. Hostel Fees
        if (! Schema::hasTable('sm_hostel_fees')) {
            Schema::create('sm_hostel_fees', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('hostel_id');
                $table->unsignedBigInteger('room_id')->nullable();
                $table->decimal('amount', 10, 2);
                $table->unsignedTinyInteger('month'); // 1-12
                $table->unsignedSmallInteger('year');
                $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
                $table->date('paid_at')->nullable();
                $table->unsignedInteger('school_id')->nullable();
                $table->timestamps();
            });
        }

        // 14. Hostel Meals
        if (! Schema::hasTable('sm_hostel_meals')) {
            Schema::create('sm_hostel_meals', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('hostel_id');
                $table->enum('meal_type', ['breakfast', 'lunch', 'dinner', 'snack']);
                $table->string('description')->nullable();
                $table->decimal('price', 8, 2)->default(0);
                $table->date('date')->nullable();
                $table->unsignedInteger('school_id')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sm_hostel_meals');
        Schema::dropIfExists('sm_hostel_fees');
        Schema::dropIfExists('sm_hostel_allocations');
        Schema::dropIfExists('sm_hostel_rooms');
        Schema::dropIfExists('sm_hostels');
        Schema::dropIfExists('sm_vendor_payments');
        Schema::dropIfExists('sm_purchase_orders');
        Schema::dropIfExists('sm_vendors');
        Schema::dropIfExists('sm_thirukkurals');
        Schema::dropIfExists('sm_book_bank_issues');
        Schema::dropIfExists('sm_book_banks');
        Schema::dropIfExists('sm_vaccination_records');
        Schema::dropIfExists('sm_medical_records');
        Schema::dropIfExists('sm_transfer_certificates');
    }
};
