<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================================
        // HOSTEL MODULE — Drop existing basic tables & recreate enhanced
        // ============================================================

        // Drop in reverse dependency order
        Schema::dropIfExists('sm_hostel_facility_access');
        Schema::dropIfExists('sm_hostel_visitors');
        Schema::dropIfExists('sm_hostel_discipline');
        Schema::dropIfExists('sm_hostel_permissions');
        Schema::dropIfExists('sm_hostel_movements');
        Schema::dropIfExists('sm_hostel_meals');
        Schema::dropIfExists('sm_hostel_fees');
        Schema::dropIfExists('sm_hostel_allocations');
        Schema::dropIfExists('sm_hostel_rooms');
        Schema::dropIfExists('sm_hostels');

        Schema::create('sm_hostels', function (Blueprint $table) {
            $table->id();
            $table->string('hostel_name');
            $table->enum('type', ['boys', 'girls', 'mixed'])->default('mixed');
            $table->text('address')->nullable();
            $table->integer('capacity')->default(0);
            $table->string('warden_name')->nullable();
            $table->string('warden_phone', 20)->nullable();
            $table->string('warden_email')->nullable();
            $table->boolean('rfid_enabled')->default(false);
            $table->string('rfid_reader_id')->nullable()->comment('Future RFID reader device ID');
            $table->text('facilities')->nullable()->comment('JSON list of facilities');
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
        });

        Schema::create('sm_hostel_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hostel_id');
            $table->string('room_no', 20);
            $table->string('room_type')->nullable()->comment('single, double, dormitory');
            $table->integer('capacity')->default(1);
            $table->integer('floor')->default(0);
            $table->decimal('fee_per_month', 10, 2)->default(0);
            $table->text('amenities')->nullable()->comment('JSON list');
            $table->enum('status', ['available', 'occupied', 'maintenance', 'reserved'])->default('available');
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
            $table->foreign('hostel_id')->references('id')->on('sm_hostels')->onDelete('cascade');
        });

        Schema::create('sm_hostel_allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hostel_id');
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('student_id');
            $table->string('rfid_card_uid')->nullable()->comment('RFID card unique identifier');
            $table->date('join_date');
            $table->date('leave_date')->nullable();
            $table->enum('status', ['active', 'vacated', 'suspended'])->default('active');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
            $table->foreign('hostel_id')->references('id')->on('sm_hostels')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('sm_hostel_rooms')->onDelete('cascade');
        });

        Schema::create('sm_hostel_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('allocation_id');
            $table->unsignedBigInteger('student_id');
            $table->string('fee_month', 7)->comment('YYYY-MM');
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('paid', 10, 2)->default(0);
            $table->enum('status', ['pending', 'partial', 'paid', 'overdue'])->default('pending');
            $table->date('payment_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('reference_no')->nullable();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
            $table->foreign('allocation_id')->references('id')->on('sm_hostel_allocations')->onDelete('cascade');
        });

        Schema::create('sm_hostel_meals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hostel_id');
            $table->string('meal_type')->comment('breakfast, lunch, dinner, snack');
            $table->string('menu_description')->nullable();
            $table->string('day_of_week')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
            $table->foreign('hostel_id')->references('id')->on('sm_hostels')->onDelete('cascade');
        });

        // NEW hostel tables
        Schema::create('sm_hostel_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('hostel_id');
            $table->enum('direction', ['entry', 'exit']);
            $table->dateTime('scanned_at');
            $table->string('rfid_card_uid')->nullable();
            $table->string('scan_method')->default('manual')->comment('manual, rfid, biometric');
            $table->string('gate')->nullable()->comment('Main Gate, Side Gate etc.');
            $table->boolean('authorized')->default(true);
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('recorded_by')->nullable()->comment('Staff user ID');
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
        });

        Schema::create('sm_hostel_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('hostel_id');
            $table->enum('permission_type', ['outing', 'overnight', 'home_visit', 'medical', 'other'])->default('outing');
            $table->dateTime('from_datetime');
            $table->dateTime('to_datetime');
            $table->text('reason');
            $table->string('destination')->nullable();
            $table->string('guardian_contact')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'expired', 'returned'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('actual_return')->nullable();
            $table->text('warden_remarks')->nullable();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
        });

        Schema::create('sm_hostel_discipline', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('hostel_id');
            $table->string('incident_type')->comment('noise, unauthorized_exit, damage, misconduct, late_entry, other');
            $table->date('incident_date');
            $table->time('incident_time')->nullable();
            $table->text('description');
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->string('action_taken')->nullable()->comment('warning, fine, suspension, expulsion');
            $table->decimal('fine_amount', 10, 2)->default(0);
            $table->enum('status', ['reported', 'investigating', 'action_taken', 'resolved', 'appealed'])->default('reported');
            $table->unsignedBigInteger('reported_by')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
        });

        Schema::create('sm_hostel_visitors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('hostel_id');
            $table->string('visitor_name');
            $table->string('visitor_phone', 20)->nullable();
            $table->string('relationship')->nullable();
            $table->string('id_proof_type')->nullable();
            $table->string('id_proof_number')->nullable();
            $table->dateTime('check_in');
            $table->dateTime('check_out')->nullable();
            $table->text('purpose')->nullable();
            $table->enum('status', ['checked_in', 'checked_out', 'denied'])->default('checked_in');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
        });

        Schema::create('sm_hostel_facility_access', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('hostel_id');
            $table->string('facility_type')->comment('laundry, gym, study_room, common_room, kitchen');
            $table->dateTime('access_time');
            $table->dateTime('exit_time')->nullable();
            $table->string('rfid_card_uid')->nullable();
            $table->string('scan_method')->default('manual');
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
        });

        // ============================================================
        // VENDOR MODULE — Drop existing & recreate enhanced
        // ============================================================

        Schema::dropIfExists('sm_vendor_agreements');
        Schema::dropIfExists('sm_vendor_documents');
        Schema::dropIfExists('sm_vendor_penalties');
        Schema::dropIfExists('sm_vendor_evaluations');
        Schema::dropIfExists('sm_vendor_payments');
        Schema::dropIfExists('sm_purchase_orders');
        Schema::dropIfExists('sm_vendors');

        Schema::create('sm_vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_name');
            $table->string('vendor_code')->nullable()->unique();
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('gstin', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('category')->nullable()->comment('stationery, electronics, food, maintenance, services');
            $table->enum('empanelment_status', ['pending', 'approved', 'suspended', 'blacklisted'])->default('pending');
            $table->date('empanelment_date')->nullable();
            $table->decimal('overall_rating', 3, 1)->default(0);
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_ifsc')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('blacklist_reason')->nullable();
            $table->date('blacklisted_at')->nullable();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
        });

        Schema::create('sm_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->nullable();
            $table->unsignedBigInteger('vendor_id');
            $table->date('order_date');
            $table->date('expected_delivery')->nullable();
            $table->date('actual_delivery')->nullable();
            $table->text('items_description')->nullable();
            $table->text('items_json')->nullable()->comment('JSON array of items');
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'ordered', 'delivered', 'cancelled', 'returned'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
            $table->foreign('vendor_id')->references('id')->on('sm_vendors')->onDelete('cascade');
        });

        Schema::create('sm_vendor_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('purchase_order_id')->nullable();
            $table->decimal('amount', 12, 2);
            $table->date('payment_date');
            $table->string('payment_method')->nullable()->comment('bank_transfer, cheque, cash, upi');
            $table->string('reference_no')->nullable();
            $table->string('cheque_no')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('completed');
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
            $table->foreign('vendor_id')->references('id')->on('sm_vendors')->onDelete('cascade');
        });

        Schema::create('sm_vendor_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('evaluation_period')->comment('Q1-2026, 2026 etc.');
            $table->decimal('quality_score', 3, 1)->default(0)->comment('1-5');
            $table->decimal('delivery_score', 3, 1)->default(0);
            $table->decimal('pricing_score', 3, 1)->default(0);
            $table->decimal('communication_score', 3, 1)->default(0);
            $table->decimal('compliance_score', 3, 1)->default(0);
            $table->decimal('overall_score', 3, 1)->default(0);
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('recommendations')->nullable();
            $table->unsignedBigInteger('evaluated_by')->nullable();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
            $table->foreign('vendor_id')->references('id')->on('sm_vendors')->onDelete('cascade');
        });

        Schema::create('sm_vendor_penalties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('purchase_order_id')->nullable();
            $table->string('penalty_type')->comment('late_delivery, quality_issue, non_compliance, breach');
            $table->text('description');
            $table->decimal('penalty_amount', 10, 2)->default(0);
            $table->date('penalty_date');
            $table->enum('status', ['issued', 'acknowledged', 'paid', 'waived', 'disputed'])->default('issued');
            $table->text('corrective_action')->nullable();
            $table->date('corrective_deadline')->nullable();
            $table->boolean('corrective_completed')->default(false);
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
            $table->foreign('vendor_id')->references('id')->on('sm_vendors')->onDelete('cascade');
        });

        Schema::create('sm_vendor_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('document_type')->comment('registration, tax, license, insurance, certificate');
            $table->string('document_name');
            $table->string('file_path')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected', 'expired'])->default('pending');
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
            $table->foreign('vendor_id')->references('id')->on('sm_vendors')->onDelete('cascade');
        });

        Schema::create('sm_vendor_agreements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('agreement_title');
            $table->string('agreement_number')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->text('terms')->nullable();
            $table->decimal('contract_value', 12, 2)->default(0);
            $table->string('file_path')->nullable();
            $table->enum('status', ['draft', 'active', 'expired', 'terminated', 'renewed'])->default('active');
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
            $table->foreign('vendor_id')->references('id')->on('sm_vendors')->onDelete('cascade');
        });

        // ============================================================
        // CANTEEN MODULE — All new tables
        // ============================================================

        Schema::create('sm_canteen_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('icon')->nullable();
            $table->enum('health_tag', ['healthy', 'moderate', 'junk'])->default('moderate');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
        });

        Schema::create('sm_canteen_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->string('item_code')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->decimal('cost_price', 8, 2)->default(0);
            $table->string('unit')->default('piece')->comment('piece, plate, glass, bowl');
            $table->boolean('is_available')->default(true);
            $table->boolean('is_vegetarian')->default(true);
            $table->integer('calories')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('sm_canteen_categories')->onDelete('cascade');
        });

        Schema::create('sm_canteen_wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('rfid_card_uid')->nullable()->comment('For future RFID tap-to-pay');
            $table->decimal('balance', 10, 2)->default(0);
            $table->decimal('daily_limit', 8, 2)->default(200)->comment('Max daily spend');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('parent_id')->nullable()->comment('Parent who funds wallet');
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
            $table->unique(['student_id', 'school_id']);
        });

        Schema::create('sm_canteen_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id');
            $table->unsignedBigInteger('student_id');
            $table->enum('type', ['purchase', 'recharge', 'refund', 'adjustment']);
            $table->decimal('amount', 10, 2);
            $table->decimal('balance_after', 10, 2);
            $table->unsignedBigInteger('item_id')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('payment_method')->nullable()->comment('wallet, cash, upi, rfid');
            $table->string('reference_no')->nullable();
            $table->string('recharged_by')->nullable()->comment('parent, admin, self');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
            $table->foreign('wallet_id')->references('id')->on('sm_canteen_wallets')->onDelete('cascade');
        });

        Schema::create('sm_canteen_inventory', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->decimal('stock_quantity', 10, 2)->default(0);
            $table->decimal('min_stock_level', 10, 2)->default(10);
            $table->string('unit')->default('units');
            $table->date('last_restocked')->nullable();
            $table->decimal('last_restock_qty', 10, 2)->default(0);
            $table->decimal('last_restock_cost', 10, 2)->default(0);
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
            $table->foreign('item_id')->references('id')->on('sm_canteen_items')->onDelete('cascade');
        });

        Schema::create('sm_canteen_daily_sales', function (Blueprint $table) {
            $table->id();
            $table->date('sale_date');
            $table->integer('total_transactions')->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->decimal('total_profit', 12, 2)->default(0);
            $table->text('top_items_json')->nullable()->comment('JSON of top selling items');
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
        });

        Schema::create('sm_canteen_restrictions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->enum('restriction_type', ['category_block', 'item_block', 'daily_limit', 'time_restriction']);
            $table->string('restriction_value')->nullable()->comment('e.g. max amount, time range');
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('set_by')->nullable()->comment('parent or admin user id');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
        });

        Schema::create('sm_canteen_suppliers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id')->nullable()->comment('Link to vendor module');
            $table->string('supplier_name');
            $table->string('contact_person')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('supply_type')->nullable()->comment('fruits, vegetables, dairy, grains, beverages');
            $table->boolean('is_active')->default(true);
            $table->decimal('total_supplied', 12, 2)->default(0);
            $table->unsignedBigInteger('school_id')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Canteen
        Schema::dropIfExists('sm_canteen_suppliers');
        Schema::dropIfExists('sm_canteen_restrictions');
        Schema::dropIfExists('sm_canteen_daily_sales');
        Schema::dropIfExists('sm_canteen_inventory');
        Schema::dropIfExists('sm_canteen_transactions');
        Schema::dropIfExists('sm_canteen_wallets');
        Schema::dropIfExists('sm_canteen_items');
        Schema::dropIfExists('sm_canteen_categories');

        // Vendor
        Schema::dropIfExists('sm_vendor_agreements');
        Schema::dropIfExists('sm_vendor_documents');
        Schema::dropIfExists('sm_vendor_penalties');
        Schema::dropIfExists('sm_vendor_evaluations');
        Schema::dropIfExists('sm_vendor_payments');
        Schema::dropIfExists('sm_purchase_orders');
        Schema::dropIfExists('sm_vendors');

        // Hostel
        Schema::dropIfExists('sm_hostel_facility_access');
        Schema::dropIfExists('sm_hostel_visitors');
        Schema::dropIfExists('sm_hostel_discipline');
        Schema::dropIfExists('sm_hostel_permissions');
        Schema::dropIfExists('sm_hostel_movements');
        Schema::dropIfExists('sm_hostel_meals');
        Schema::dropIfExists('sm_hostel_fees');
        Schema::dropIfExists('sm_hostel_allocations');
        Schema::dropIfExists('sm_hostel_rooms');
        Schema::dropIfExists('sm_hostels');
    }
};
