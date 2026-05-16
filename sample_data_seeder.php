<?php

use App\SmStudent;
use App\Models\SmTransferCertificate;
use App\Models\SmMedicalRecord;
use App\Models\SmVaccinationRecord;
use App\Models\SmBookBank;
use App\Models\SmVendor;
use App\Models\SmHostel;
use App\Models\SmThirukkural;

echo "--- STARTING SAMPLE DATA SEEDING (RETRY) ---\n";

$school_id = 1;
$academic_id = 1;

// 1. Repair Student Links
echo "Repairing Student Links...\n";
SmStudent::where('id', '<=', 10)->update([
    'class_id' => 1,
    'section_id' => 1,
    'school_id' => $school_id,
    'academic_id' => $academic_id
]);

// 2. Transfer Certificates
echo "Seeding TCs...\n";
for ($i = 1; $i <= 5; $i++) {
    SmTransferCertificate::updateOrCreate(
        ['student_id' => $i],
        [
            'tc_no' => 'TC/2026/00' . $i,
            'reason' => 'Completed Higher Secondary Education',
            'date' => now()->subDays($i)->toDateString(),
            'class_name' => 'Class 10',
            'section_name' => 'A',
            'school_id' => $school_id,
            'academic_id' => $academic_id
        ]
    );
}

// 3. Medical Records
echo "Seeding Medical Records...\n";
$blood_groups = ['A+', 'B+', 'O+', 'AB+'];
for ($i = 1; $i <= 5; $i++) {
    SmMedicalRecord::updateOrCreate(
        ['student_id' => $i],
        [
            'blood_group' => $blood_groups[$i % 4],
            'weight' => 45.0 + $i,
            'height' => 150.0 + $i,
            'medical_history' => 'General health checkup normal.',
            'allergies' => 'None reported.',
            'school_id' => $school_id,
            'academic_id' => $academic_id
        ]
    );
    
    SmVaccinationRecord::updateOrCreate(
        ['student_id' => $i],
        [
            'vaccine_name' => 'COVID-19 Booster',
            'dose' => 'Dose 1',
            'date_given' => now()->subMonths(2)->toDateString(),
            'school_id' => $school_id,
            'academic_id' => $academic_id
        ]
    );
}

// 4. Book Bank
echo "Seeding Book Bank...\n";
$books = [
    ['name' => 'Mathematics Part I', 'author' => 'NCERT', 'isbn' => '978-8174505088'],
    ['name' => 'Physics Concepts', 'author' => 'H.C. Verma', 'isbn' => '978-8177091878'],
    ['name' => 'Discovery of India', 'author' => 'J.L. Nehru', 'isbn' => '978-0143031031'],
];
foreach($books as $b) {
    SmBookBank::updateOrCreate(
        ['book_name' => $b['name']],
        [
            'author' => $b['author'],
            'isbn' => $b['isbn'],
            'publisher' => 'Academic Press',
            'total_copies' => 10,
            'available_copies' => 10,
            'class' => '1',
            'subject' => 'Core',
            'school_id' => $school_id
        ]
    );
}

// 5. Thirukkural
echo "Seeding Thirukkural...\n";
$kurals = [
    [
        'no' => 1, 
        'tamil' => 'அகர முதல எழுத்தெல்லாம் ஆதி பகவன் முதற்றே உலகு.',
        'english' => 'A, as its first of letters, every speech maintains; The Primal Deity is first through all the world\'s domains.',
        'exp' => 'Just as the alphabet starts with "A", the world starts with the Almighty.'
    ],
    [
        'no' => 2,
        'tamil' => 'கற்றதனால் ஆய பயனென்கொல் வாலறிவன் நற்றாள் தொழாஅர் எனின்.',
        'english' => 'No fruit have men of all their studied lore, Save they the Purely Wise One-s feet adore.',
        'exp' => 'The ultimate goal of education is to surrender at the feet of the Almighty.'
    ],
];
foreach($kurals as $k) {
    SmThirukkural::updateOrCreate(
        ['kural_no' => $k['no']],
        [
            'section' => 'Araththuppaal',
            'chapter' => 'God',
            'kural_tamil' => $k['tamil'],
            'kural_english' => $k['english'],
            'explanation' => $k['exp'],
            'school_id' => $school_id
        ]
    );
}

// 6. Vendors & Hostels
echo "Seeding Vendors & Hostels...\n";
SmVendor::updateOrCreate(
    ['vendor_name' => 'Global Stationary Supplies'],
    [
        'email' => 'contact@globalsupplies.com',
        'phone' => '9887766554',
        'address' => 'Delhi, India',
        'school_id' => $school_id
    ]
);

SmHostel::updateOrCreate(
    ['hostel_name' => 'Main Boys Hostel'],
    [
        'type' => 'Boys',
        'address' => 'Campus North Wing',
        'capacity' => 100,
        'school_id' => $school_id
    ]
);

echo "--- SEEDING COMPLETED SUCCESSFULLY ---\n";
