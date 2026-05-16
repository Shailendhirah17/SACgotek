<?php
/**
 * Tally ERP9 Accounting Export Integration
 * Extracts fee payments and formats them into Tally-compliant XML.
 */

use Illuminate\Support\Facades\DB;

// Configuration
$school_id = 1;
$startDate = date('Y-m-01'); // First day of current month
$endDate = date('Y-m-d');    // Today

// Fetch recent fee payments
$payments = DB::table('sm_fees_payments')
    ->where('school_id', $school_id)
    ->whereBetween('payment_date', [$startDate, $endDate])
    ->get();

// Generate Tally-Compliant XML String
$xml = new \SimpleXMLElement('<ENVELOPE/>');
$header = $xml->addChild('HEADER');
$header->addChild('TALLYREQUEST', 'Import Data');
$body = $xml->addChild('BODY');
$importData = $body->addChild('IMPORTDATA');
$requestDesc = $importData->addChild('REQUESTDESC');
$requestDesc->addChild('REPORTNAME', 'All Masters');
$static_var = $requestDesc->addChild('STATICVARIABLES');
$static_var->addChild('SVCURRENTCOMPANY', 'InfixEdu Institutional Account');
$requestData = $importData->addChild('REQUESTDATA');

foreach ($payments as $payment) {
    $tallyMessage = $requestData->addChild('TALLYMESSAGE');
    $voucher = $tallyMessage->addChild('VOUCHER');
    $voucher->addAttribute('VCHTYPE', 'Receipt');
    $voucher->addAttribute('ACTION', 'Create');
    $voucher->addAttribute('OBJVIEW', 'Accounting Voucher View');
    
    $voucher->addChild('DATE', date('Ymd', strtotime($payment->payment_date)));
    $voucher->addChild('VOUCHERTYPENAME', 'Receipt');
    $voucher->addChild('NARRATION', 'Fee collection - Receipt No: ' . $payment->receipt_no);
    
    // Ledger entry for Cash/Bank (Debit)
    $ledgerEntries = $voucher->addChild('ALLLEDGERENTRIES.LIST');
    $ledgerEntries->addChild('LEDGERNAME', strtoupper($payment->payment_mode));
    $ledgerEntries->addChild('ISDEEMEDPOSITIVE', 'Yes'); // Debit
    $ledgerEntries->addChild('AMOUNT', '-' . $payment->amount); // Tally uses negative for Debit!
    
    // Ledger entry for "Tuition Fees / Revenue" (Credit)
    $ledgerEntries2 = $voucher->addChild('ALLLEDGERENTRIES.LIST');
    $ledgerEntries2->addChild('LEDGERNAME', 'Tuition Fees A/C');
    $ledgerEntries2->addChild('ISDEEMEDPOSITIVE', 'No'); // Credit
    $ledgerEntries2->addChild('AMOUNT', $payment->amount); 
}

// Ensure the directory exists
$dir = storage_path('app/tally_exports');
if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}

// Save the file natively
$filename = 'Tally_Export_' . date('Ymd_His') . '.xml';
$filePath = $dir . '/' . $filename;
$xml->asXML($filePath);

echo "✅ Tally ERP9 XML Generation Successful!\n";
echo "📊 Total Transactions Exported: " . $payments->count() . "\n";
echo "💾 Export File Saved To: " . $filePath . "\n";
echo "📥 Accountants can now directly import this XML into Tally software.\n";
