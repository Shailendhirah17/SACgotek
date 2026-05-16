<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSmPaymentGatewaySettingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sm_payment_gateway_settings', function (Blueprint $blueprint): void {
            $blueprint->increments('id');
            $blueprint->string('gateway_name')->nullable();
            $blueprint->string('gateway_username')->nullable();
            $blueprint->string('gateway_password')->nullable();
            $blueprint->string('gateway_signature')->nullable();
            $blueprint->string('gateway_client_id')->nullable();
            $blueprint->string('gateway_mode')->nullable();
            $blueprint->string('gateway_secret_key')->nullable();
            $blueprint->string('gateway_secret_word')->nullable();
            $blueprint->string('gateway_publisher_key')->nullable();
            $blueprint->string('gateway_private_key')->nullable();
            $blueprint->tinyInteger('active_status')->default(0);
            $blueprint->timestamps();

            $blueprint->text('bank_details')->nullable();
            $blueprint->text('cheque_details')->nullable();

            $blueprint->integer('created_by')->nullable()->default(1)->unsigned();
            $blueprint->integer('updated_by')->nullable()->default(1)->unsigned();

            $blueprint->integer('school_id')->nullable()->default(1)->unsigned();
            $blueprint->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');

            $blueprint->boolean('service_charge')->nullable()->default(false);
            $blueprint->string('charge_type', 2)->nullable()->comment('P=percentage, F=Flat');
            $blueprint->float('charge')->nullable()->default(0.00);

        });

        DB::table('sm_payment_gateway_settings')->insert([
            [
                'gateway_name' => 'PayPal',
                'gateway_username' => 'demo@paypal.com',
                'gateway_password' => '12334589',
                'gateway_client_id' => 'CLIENT_ID_PLACEHOLDER',
                'gateway_secret_key' => 'SECRET_KEY_PLACEHOLDER',
                'created_at' => date('Y-m-d h:i:s'),

            ],
        ]);

        DB::table('sm_payment_gateway_settings')->insert([
            [
                'gateway_name' => 'Stripe',
                'gateway_username' => 'demo@strip.com',
                'gateway_password' => '12334589',
                'gateway_client_id' => 'CLIENT_ID_PLACEHOLDER',
                'gateway_secret_key' => 'SECRET_KEY_PLACEHOLDER',
                'gateway_secret_word' => 'SECRET_WORD_PLACEHOLDER',
                'created_at' => date('Y-m-d h:i:s'),

            ],

        ]);

        DB::table('sm_payment_gateway_settings')->insert([
            [
                'gateway_name' => 'Paystack',
                'gateway_username' => 'demo@gmail.com',
                'gateway_password' => '12334589',
                'gateway_client_id' => 'CLIENT_ID_PLACEHOLDER',
                'gateway_secret_key' => 'SECRET_KEY_PLACEHOLDER',
                'gateway_publisher_key' => 'PUBLISHER_KEY_PLACEHOLDER',
                'created_at' => date('Y-m-d h:i:s'),

            ],
        ]);

        DB::table('sm_payment_gateway_settings')->insert([
            [
                'gateway_name' => 'Bank',
                'created_at' => date('Y-m-d h:i:s'),
            ],

        ]);

        DB::table('sm_payment_gateway_settings')->insert([
            [
                'gateway_name' => 'Cheque',
                'created_at' => date('Y-m-d h:i:s'),
            ],

        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sm_payment_gateway_settings');
    }
}
