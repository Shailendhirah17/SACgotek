<?php

use Illuminate\Support\Facades\Route;

Route::get('user-custom-menu/medical.vaccination', function () {
    return redirect()->route('medical.vaccination');
});

// TC
Route::get('tc-list', 'Admin\SchoolExtensionController@tcList')->name('tc.index')->middleware('userRolePermission:tc-list');
Route::get('tc-create', 'Admin\SchoolExtensionController@tcCreate')->name('tc.create')->middleware('userRolePermission:tc-list');
Route::post('tc-store', 'Admin\SchoolExtensionController@tcStore')->name('tc.store');
Route::get('tc-edit/{id}', 'Admin\SchoolExtensionController@tcEdit')->name('tc.edit');
Route::post('tc-update', 'Admin\SchoolExtensionController@tcUpdate')->name('tc.update');
Route::get('tc-delete/{id}', 'Admin\SchoolExtensionController@tcDelete')->name('tc.delete');
Route::get('tc-show/{id}', 'Admin\SchoolExtensionController@tcShow')->name('tc.show');
Route::get('tc-get-students', 'Admin\SchoolExtensionController@tcGetStudents')->name('tc.get-students');

// Medical
Route::get('medical-records', 'Admin\SchoolExtensionController@medicalRecords')->name('medical.records')->middleware('userRolePermission:medical-records');
Route::post('medical-records-store', 'Admin\SchoolExtensionController@medicalRecordsStore')->name('medical.records.store');
Route::get('medical-records-edit/{id}', 'Admin\SchoolExtensionController@medicalRecordsEdit')->name('medical.records.edit');
Route::post('medical-records-update', 'Admin\SchoolExtensionController@medicalRecordsUpdate')->name('medical.records.update');
Route::get('medical-records-delete/{id}', 'Admin\SchoolExtensionController@medicalRecordsDelete')->name('medical.records.delete');
Route::get('vaccination-records', 'Admin\SchoolExtensionController@vaccinationRecords')->name('medical.vaccination')->middleware('userRolePermission:vaccination-records');
Route::post('vaccination-records-store', 'Admin\SchoolExtensionController@vaccinationStore')->name('medical.vaccination.store');
Route::post('vaccination-records-update', 'Admin\SchoolExtensionController@vaccinationUpdate')->name('medical.vaccination.update');
Route::get('vaccination-records-delete/{id}', 'Admin\SchoolExtensionController@vaccinationDelete')->name('medical.vaccination.delete');

// Book Bank
Route::get('book-bank', 'Admin\SchoolExtensionController@bookBank')->name('book-bank.index')->middleware('userRolePermission:book-bank');
Route::post('book-bank-store', 'Admin\SchoolExtensionController@bookBankStore')->name('book-bank.store');
Route::get('book-bank-edit/{id}', 'Admin\SchoolExtensionController@bookBankEdit')->name('book-bank.edit');
Route::post('book-bank-update', 'Admin\SchoolExtensionController@bookBankUpdate')->name('book-bank.update');
Route::get('book-bank-delete/{id}', 'Admin\SchoolExtensionController@bookBankDelete')->name('book-bank.delete');
Route::get('book-bank-issue', 'Admin\SchoolExtensionController@bookBankIssue')->name('book-bank.issue')->middleware('userRolePermission:book-bank');
Route::post('book-bank-issue-store', 'Admin\SchoolExtensionController@bookBankIssueStore')->name('book-bank.issue.store');
Route::get('book-bank-return/{id}', 'Admin\SchoolExtensionController@bookBankReturn')->name('book-bank.return');

// Thirukkural
Route::get('thirukkural', 'Admin\SchoolExtensionController@thirukkural')->name('thirukkural.index')->middleware('userRolePermission:thirukkural');
Route::post('thirukkural-store', 'Admin\SchoolExtensionController@thirukkuralStore')->name('thirukkural.store');
Route::post('thirukkural-update', 'Admin\SchoolExtensionController@thirukkuralUpdate')->name('thirukkural.update');
Route::get('thirukkural-delete/{id}', 'Admin\SchoolExtensionController@thirukkuralDelete')->name('thirukkural.delete');

// =====================================
// HOSTEL EXTENSION ROUTES
// =====================================
Route::get('hostel-dashboard', 'Admin\SchoolExtensionController@hostelDashboard')->name('hostel.dashboard');
Route::get('hostel-list', 'Admin\SchoolExtensionController@hostelList')->name('hostel.index');
Route::post('hostel-store', 'Admin\SchoolExtensionController@hostelStore')->name('hostel.store');
Route::get('hostel-rooms', 'Admin\SchoolExtensionController@hostelRooms')->name('hostel.rooms');
Route::post('hostel-room-store', 'Admin\SchoolExtensionController@hostelRoomStore')->name('hostel.room.store');
Route::get('hostel-allocation', 'Admin\SchoolExtensionController@hostelAllocation')->name('hostel.allocation');
Route::post('hostel-allocation-store', 'Admin\SchoolExtensionController@hostelAllocationStore')->name('hostel.allocation.store');
Route::get('hostel-movements', 'Admin\SchoolExtensionController@movements')->name('hostel.movements');
Route::post('hostel-movements-store', 'Admin\SchoolExtensionController@movementsStore')->name('hostel.movements.store');
Route::get('hostel-permissions', 'Admin\SchoolExtensionController@permissions')->name('hostel.permissions');
Route::get('hostel-permission-status/{id}/{status}', 'Admin\SchoolExtensionController@permissionStatus')->name('hostel.permission.status');
Route::get('hostel-discipline', 'Admin\SchoolExtensionController@discipline')->name('hostel.discipline');
Route::post('hostel-discipline-store', 'Admin\SchoolExtensionController@disciplineStore')->name('hostel.discipline.store');
Route::get('hostel-visitors', 'Admin\SchoolExtensionController@visitors')->name('hostel.visitors');
Route::post('hostel-visitor-store', 'Admin\SchoolExtensionController@visitorStore')->name('hostel.visitor.store');
Route::get('hostel-fee', 'Admin\SchoolExtensionController@hostelFee')->name('hostel.fee');
Route::post('hostel-fee-store', 'Admin\SchoolExtensionController@hostelFeeStore')->name('hostel.fee.store');
Route::get('hostel-meals', 'Admin\SchoolExtensionController@hostelMeals')->name('hostel.meals');
Route::post('hostel-meal-store', 'Admin\SchoolExtensionController@hostelMealStore')->name('hostel.meal.store');
Route::post('hostel-update', 'Admin\SchoolExtensionController@hostelUpdate')->name('hostel.update');

// =====================================
// VENDOR EXTENSION ROUTES
// =====================================
Route::get('vendor-dashboard', 'Admin\SchoolExtensionController@vendorDashboard')->name('vendor.dashboard');
Route::get('vendor-list', 'Admin\SchoolExtensionController@vendorList')->name('vendor.index');
Route::post('vendor-store', 'Admin\SchoolExtensionController@vendorStore')->name('vendor.store');
Route::post('vendor-update', 'Admin\SchoolExtensionController@vendorUpdate')->name('vendor.update');
Route::get('vendor-delete/{id}', 'Admin\SchoolExtensionController@vendorDelete')->name('vendor.delete');
Route::get('purchase-orders', 'Admin\SchoolExtensionController@purchaseOrders')->name('purchase-order.index');
Route::post('purchase-orders-store', 'Admin\SchoolExtensionController@purchaseOrdersStore')->name('purchase-order.store');
Route::get('vendor-payments', 'Admin\SchoolExtensionController@vendorPayments')->name('vendor.payments');
Route::post('vendor-payments-store', 'Admin\SchoolExtensionController@vendorPaymentsStore')->name('vendor.payment.store');
Route::get('vendor-payment-delete/{id}', 'Admin\SchoolExtensionController@vendorPaymentDelete')->name('vendor.payment.delete');
Route::get('vendor-evaluations', 'Admin\SchoolExtensionController@evaluations')->name('vendor.evaluations');
Route::post('vendor-evaluations-store', 'Admin\SchoolExtensionController@evaluationsStore')->name('vendor.evaluation.store');
Route::get('vendor-penalties', 'Admin\SchoolExtensionController@penalties')->name('vendor.penalties');
Route::post('vendor-penalties-store', 'Admin\SchoolExtensionController@penaltiesStore')->name('vendor.penalty.store');
Route::get('vendor-documents', 'Admin\SchoolExtensionController@documents')->name('vendor.documents');
Route::post('vendor-documents-store', 'Admin\SchoolExtensionController@documentsStore')->name('vendor.document.store');
Route::get('vendor-agreements', 'Admin\SchoolExtensionController@agreements')->name('vendor.agreements');
Route::post('vendor-agreements-store', 'Admin\SchoolExtensionController@agreementsStore')->name('vendor.agreement.store');

// =====================================
// CANTEEN ROUTES
// =====================================
Route::get('canteen-dashboard', 'Admin\SchoolExtensionController@canteenDashboard')->name('canteen.dashboard');
Route::get('canteen-wallets', 'Admin\SchoolExtensionController@wallets')->name('canteen.wallets');
Route::post('canteen-wallets-store', 'Admin\SchoolExtensionController@walletStore')->name('canteen.wallets.store');
Route::post('canteen-wallets-recharge', 'Admin\SchoolExtensionController@rechargeWallet')->name('canteen.wallets.recharge');
Route::get('canteen-categories', 'Admin\SchoolExtensionController@categories')->name('canteen.categories');
Route::post('canteen-categories-store', 'Admin\SchoolExtensionController@categoryStore')->name('canteen.category.store');
Route::get('canteen-items', 'Admin\SchoolExtensionController@items')->name('canteen.items');
Route::post('canteen-items-store', 'Admin\SchoolExtensionController@itemStore')->name('canteen.item.store');
Route::get('canteen-transactions', 'Admin\SchoolExtensionController@transactions')->name('canteen.transactions');
Route::get('canteen-pos', 'Admin\SchoolExtensionController@pos')->name('canteen.pos');
Route::post('canteen-pos-process', 'Admin\SchoolExtensionController@posProcess')->name('canteen.pos.process');

// Hostel Missing Routes
Route::get('hostel-delete/{id}', 'Admin\SchoolExtensionController@hostelDelete')->name('hostel.delete');
Route::get('hostel-room-delete/{id}', 'Admin\SchoolExtensionController@hostelRoomDelete')->name('hostel.room.delete');
Route::get('hostel-vacate/{id}', 'Admin\SchoolExtensionController@hostelVacate')->name('hostel.vacate');
Route::get('hostel-get-rooms', 'Admin\SchoolExtensionController@hostelGetRooms')->name('hostel.get-rooms');
Route::get('hostel-meal-delete/{id}', 'Admin\SchoolExtensionController@hostelMealDelete')->name('hostel.meal.delete');
Route::get('hostel-fee-pay/{id}', 'Admin\SchoolExtensionController@hostelFeePay')->name('hostel.fee.pay');

// Vendor Missing Routes
Route::get('purchase-order-delete/{id}', 'Admin\SchoolExtensionController@purchaseOrderDelete')->name('purchase-order.delete');
Route::get('purchase-order-status/{id}/{status}', 'Admin\SchoolExtensionController@purchaseOrderStatus')->name('purchase-order.status');
