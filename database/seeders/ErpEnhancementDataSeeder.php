<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SmHostel;
use App\Models\SmHostelRoom;
use App\Models\SmHostelAllocation;
use App\Models\SmHostelMovement;
use App\Models\SmHostelPermission;
use App\Models\SmHostelDiscipline;
use App\Models\SmHostelVisitor;
use App\Models\SmVendor;
use App\Models\SmPurchaseOrder;
use App\Models\SmVendorPayment;
use App\Models\SmVendorEvaluation;
use App\Models\SmVendorPenalty;
use App\Models\SmVendorDocument;
use App\Models\SmVendorAgreement;
use App\Models\SmCanteenCategory;
use App\Models\SmCanteenItem;
use App\Models\SmCanteenWallet;
use App\Models\SmCanteenTransaction;
use App\SmStudent;
use App\User;
use Illuminate\Support\Str;

class ErpEnhancementDataSeeder extends Seeder
{
    public function run()
    {
        $school_id = 1;
        $admin = User::where('role_id', 1)->first() ?? User::first();
        $students = SmStudent::where('school_id', $school_id)->take(20)->get();

        if ($students->isEmpty()) {
            return; // Needs basic students to seed
        }

        // ==========================================
        // 1. HOSTEL SEEDING
        // ==========================================
        $hostels = [
            ['name' => 'Boys Hostel A', 'type' => 'boys', 'capacity' => 100],
            ['name' => 'Girls Hostel B', 'type' => 'girls', 'capacity' => 100],
        ];

        foreach ($hostels as $h) {
            $hostel = SmHostel::create([
                'hostel_name' => $h['name'],
                'type' => $h['type'],
                'capacity' => $h['capacity'],
                'warden_name' => 'Warden ' . Str::random(5),
                'warden_phone' => '+91 9876543210',
                'school_id' => $school_id,
            ]);

            for ($i = 1; $i <= 5; $i++) {
                $room = SmHostelRoom::create([
                    'hostel_id' => $hostel->id,
                    'room_no' => $h['type'][0] . '-' . 100 + $i,
                    'room_type' => 'Double',
                    'capacity' => 2,
                    'fee_per_month' => 1500,
                    'status' => 'available',
                    'school_id' => $school_id,
                ]);

                // Allocate a student
                if ($students->count() >= $i) {
                    $student = $students[$i-1];
                    SmHostelAllocation::create([
                        'hostel_id' => $hostel->id,
                        'room_id' => $room->id,
                        'student_id' => $student->id,
                        'rfid_card_uid' => strtoupper(Str::random(8)),
                        'join_date' => now()->subMonths(2),
                        'status' => 'active',
                        'school_id' => $school_id,
                    ]);

                    // Seed Movements
                    SmHostelMovement::create([
                        'student_id' => $student->id,
                        'hostel_id' => $hostel->id,
                        'direction' => 'exit',
                        'scanned_at' => now()->subHours(5),
                        'gate' => 'Main Gate',
                        'school_id' => $school_id,
                    ]);
                    SmHostelMovement::create([
                        'student_id' => $student->id,
                        'hostel_id' => $hostel->id,
                        'direction' => 'entry',
                        'scanned_at' => now()->subHours(1),
                        'gate' => 'Main Gate',
                        'school_id' => $school_id,
                    ]);

                    // Permissions
                    SmHostelPermission::create([
                        'student_id' => $student->id,
                        'hostel_id' => $hostel->id,
                        'permission_type' => 'outing',
                        'from_datetime' => now()->addDays(1),
                        'to_datetime' => now()->addDays(2),
                        'reason' => 'Family visit',
                        'status' => 'pending',
                        'school_id' => $school_id,
                    ]);

                    // Discipline
                    if ($i % 3 == 0) {
                        SmHostelDiscipline::create([
                            'student_id' => $student->id,
                            'hostel_id' => $hostel->id,
                            'incident_type' => 'late_entry',
                            'incident_date' => now()->subDays(5),
                            'description' => 'Arrived after curfew',
                            'severity' => 'low',
                            'status' => 'action_taken',
                            'school_id' => $school_id,
                        ]);
                    }
                }
            }
        }

        // ==========================================
        // 2. VENDOR SEEDING
        // ==========================================
        $vendorNames = ['Tech Supplies Inc', 'Fresh Foods Co', 'Campus Maintenance Services'];
        
        foreach ($vendorNames as $vname) {
            $vendor = SmVendor::create([
                'vendor_name' => $vname,
                'category' => 'General',
                'contact_person' => 'Mr. ' . Str::random(4),
                'phone' => '1234567890',
                'school_id' => $school_id,
            ]);

            // Purchase Order
            $po = SmPurchaseOrder::create([
                'po_number' => 'PO-' . rand(1000, 9999),
                'vendor_id' => $vendor->id,
                'order_date' => now()->subDays(10),
                'total_amount' => 5000,
                'status' => 'approved',
                'school_id' => $school_id,
            ]);

            // Payment
            SmVendorPayment::create([
                'vendor_id' => $vendor->id,
                'purchase_order_id' => $po->id,
                'amount' => 5000,
                'payment_date' => now()->subDays(2),
                'payment_method' => 'bank_transfer',
                'school_id' => $school_id,
            ]);

            // Evaluation
            SmVendorEvaluation::create([
                'vendor_id' => $vendor->id,
                'evaluation_period' => 'Q1-2026',
                'overall_score' => 4.5,
                'school_id' => $school_id,
                'evaluated_by' => $admin->id ?? 1,
            ]);

            // Agreement
            SmVendorAgreement::create([
                'vendor_id' => $vendor->id,
                'agreement_title' => 'Annual Supply Contract',
                'start_date' => now()->subMonths(1),
                'end_date' => now()->addMonths(11),
                'status' => 'active',
                'school_id' => $school_id,
            ]);
        }

        // ==========================================
        // 3. CANTEEN SEEDING
        // ==========================================
        $cat1 = SmCanteenCategory::create(['name' => 'Meals', 'health_tag' => 'healthy', 'school_id' => $school_id]);
        $cat2 = SmCanteenCategory::create(['name' => 'Beverages', 'health_tag' => 'moderate', 'school_id' => $school_id]);
        $cat3 = SmCanteenCategory::create(['name' => 'Snacks', 'health_tag' => 'junk', 'school_id' => $school_id]);

        $items = [
            ['name' => 'Veg Thali', 'cat' => $cat1->id, 'price' => 5.00],
            ['name' => 'Chicken Wrap', 'cat' => $cat1->id, 'price' => 6.50],
            ['name' => 'Fresh Juice', 'cat' => $cat2->id, 'price' => 2.50],
            ['name' => 'Coffee', 'cat' => $cat2->id, 'price' => 1.50],
            ['name' => 'French Fries', 'cat' => $cat3->id, 'price' => 3.00],
        ];

        foreach ($items as $it) {
            SmCanteenItem::create([
                'item_name' => $it['name'],
                'category_id' => $it['cat'],
                'price' => $it['price'],
                'school_id' => $school_id,
                'is_available' => true,
            ]);
        }

        // Wallets & Transactions
        foreach ($students as $student) {
            $wallet = SmCanteenWallet::create([
                'student_id' => $student->id,
                'rfid_card_uid' => strtoupper(Str::random(8)),
                'balance' => rand(20, 100),
                'daily_limit' => 50,
                'school_id' => $school_id,
            ]);

            // Transaction
            SmCanteenTransaction::create([
                'wallet_id' => $wallet->id,
                'student_id' => $student->id,
                'type' => 'recharge',
                'amount' => 100,
                'balance_after' => $wallet->balance + 100,
                'payment_method' => 'cash',
                'school_id' => $school_id,
            ]);
        }
    }
}
