<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSubscriptionBillingTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add subscription_rate to sm_general_settings (global admin setting)
        if (Schema::hasTable('sm_general_settings')) {
            if (!Schema::hasColumn('sm_general_settings', 'subscription_rate')) {
                Schema::table('sm_general_settings', function (Blueprint $table) {
                    $table->float('subscription_rate', 10, 2)->default(7.00)->after('school_name');
                });
            }
        }

        // 2. Create sm_subscription_coupons
        if (!Schema::hasTable('sm_subscription_coupons')) {
            Schema::create('sm_subscription_coupons', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->decimal('amount', 10, 2);
                $table->enum('type', ['fixed', 'percentage'])->default('fixed');
                $table->integer('usage_limit')->default(0); // 0 means unlimited
                $table->date('expired_at')->nullable();
                $table->integer('active_status')->default(1);
                $table->timestamps();
            });
        }

        // 3. Create sm_applied_coupons
        if (!Schema::hasTable('sm_applied_coupons')) {
            Schema::create('sm_applied_coupons', function (Blueprint $table) {
                $table->id();
                $table->integer('school_id')->unsigned();
                $table->integer('coupon_id')->unsigned();
                $table->decimal('discount_amount', 10, 2);
                $table->timestamps();
            });
        }

        // 4. Update sm_subscription_payments to track discounts
        if (Schema::hasTable('sm_subscription_payments')) {
            if (!Schema::hasColumn('sm_subscription_payments', 'discount_amount')) {
                Schema::table('sm_subscription_payments', function (Blueprint $table) {
                    $table->decimal('discount_amount', 10, 2)->default(0.00)->after('amount');
                    $table->integer('coupon_id')->nullable()->after('discount_amount');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sm_applied_coupons');
        Schema::dropIfExists('sm_subscription_coupons');
        
        if (Schema::hasTable('sm_general_settings')) {
            Schema::table('sm_general_settings', function (Blueprint $table) {
                $table->dropColumn('subscription_rate');
            });
        }

        if (Schema::hasTable('sm_subscription_payments')) {
            Schema::table('sm_subscription_payments', function (Blueprint $table) {
                $table->dropColumn(['discount_amount', 'coupon_id']);
            });
        }
    }
}
