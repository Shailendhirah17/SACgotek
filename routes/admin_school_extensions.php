<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SchoolExtensionController;

Route::get('user-custom-menu/medical.vaccination', function () {
    return redirect()->route('medical.vaccination');
});

Route::controller(SchoolExtensionController::class)->group(function (): void {
    // TC
    Route::get('tc-list', 'tcList')->name('tc.index')->middleware('userRolePermission:tc-list');
    Route::get('tc-create', 'tcCreate')->name('tc.create')->middleware('userRolePermission:tc-list');
    Route::post('tc-store', 'tcStore')->name('tc.store');
    Route::get('tc-edit/{id}', 'tcEdit')->name('tc.edit');
    Route::post('tc-update', 'tcUpdate')->name('tc.update');
    Route::get('tc-delete/{id}', 'tcDelete')->name('tc.delete');
    Route::get('tc-show/{id}', 'tcShow')->name('tc.show');
    Route::get('tc-get-students', 'tcGetStudents')->name('tc.get-students');

    // Medical
    Route::get('medical-records', 'medicalRecords')->name('medical.records')->middleware('userRolePermission:medical-records');
    Route::post('medical-records-store', 'medicalRecordsStore')->name('medical.records.store');
    Route::get('medical-records-edit/{id}', 'medicalRecordsEdit')->name('medical.records.edit');
    Route::post('medical-records-update', 'medicalRecordsUpdate')->name('medical.records.update');
    Route::get('medical-records-delete/{id}', 'medicalRecordsDelete')->name('medical.records.delete');
    Route::get('vaccination-records', 'vaccinationRecords')->name('medical.vaccination')->middleware('userRolePermission:vaccination-records');
    Route::post('vaccination-records-store', 'vaccinationStore')->name('medical.vaccination.store');
    Route::post('vaccination-records-update', 'vaccinationUpdate')->name('medical.vaccination.update');
    Route::get('vaccination-records-delete/{id}', 'vaccinationDelete')->name('medical.vaccination.delete');

    // Book Bank
    Route::get('book-bank', 'bookBank')->name('book-bank.index')->middleware('userRolePermission:book-bank');
    Route::post('book-bank-store', 'bookBankStore')->name('book-bank.store');
    Route::get('book-bank-edit/{id}', 'bookBankEdit')->name('book-bank.edit');
    Route::post('book-bank-update', 'bookBankUpdate')->name('book-bank.update');
    Route::get('book-bank-delete/{id}', 'bookBankDelete')->name('book-bank.delete');
    Route::get('book-bank-issue', 'bookBankIssue')->name('book-bank.issue')->middleware('userRolePermission:book-bank');
    Route::post('book-bank-issue-store', 'bookBankIssueStore')->name('book-bank.issue.store');
    Route::get('book-bank-return/{id}', 'bookBankReturn')->name('book-bank.return');
    Route::get('thirukkural', 'thirukkural')->name('thirukkural.index')->middleware('userRolePermission:thirukkural');
    Route::post('thirukkural-store', 'thirukkuralStore')->name('thirukkural.store');
    Route::post('thirukkural-update', 'thirukkuralUpdate')->name('thirukkural.update');
    Route::get('thirukkural-delete/{id}', 'thirukkuralDelete')->name('thirukkural.delete');

    // Vendor
    Route::get('vendor-list', 'vendorList')->name('vendor.index')->middleware('userRolePermission:vendor-list');
    Route::post('vendor-store', 'vendorStore')->name('vendor.store');
    Route::get('vendor-edit/{id}', 'vendorEdit')->name('vendor.edit');
    Route::post('vendor-update', 'vendorUpdate')->name('vendor.update');
    Route::get('vendor-delete/{id}', 'vendorDelete')->name('vendor.delete');
    Route::get('purchase-orders', 'purchaseOrders')->name('purchase-order.index')->middleware('userRolePermission:purchase-orders');
    Route::get('purchase-orders-create', 'purchaseOrdersCreate')->name('purchase-order.create');
    Route::post('purchase-orders-store', 'purchaseOrdersStore')->name('purchase-order.store');
    Route::get('purchase-orders-status/{id}/{status}', 'purchaseOrdersStatus')->name('purchase-order.status');
    Route::get('purchase-orders-show/{id}', 'purchaseOrdersShow')->name('purchase-order.show');
    Route::get('purchase-orders-delete/{id}', 'purchaseOrdersDelete')->name('purchase-order.delete');
    Route::get('vendor-payments', 'vendorPayments')->name('vendor.payments')->middleware('userRolePermission:vendor-list');
    Route::post('vendor-payments-store', 'vendorPaymentsStore')->name('vendor.payment.store');
    Route::get('vendor-payments-delete/{id}', 'vendorPaymentsDelete')->name('vendor.payment.delete');

    // Hostel
    Route::get('hostel-list', 'hostelList')->name('hostel.index')->middleware('userRolePermission:hostel-list');
    Route::post('hostel-store', 'hostelStore')->name('hostel.store');
    Route::get('hostel-edit/{id}', 'hostelEdit')->name('hostel.edit');
    Route::post('hostel-update', 'hostelUpdate')->name('hostel.update');
    Route::get('hostel-delete/{id}', 'hostelDelete')->name('hostel.delete');
    Route::get('hostel-rooms', 'hostelRooms')->name('hostel.rooms');
    Route::post('hostel-room-store', 'hostelRoomStore')->name('hostel.room.store');
    Route::get('hostel-room-edit/{id}', 'hostelRoomEdit')->name('hostel.room.edit');
    Route::post('hostel-room-update', 'hostelRoomUpdate')->name('hostel.room.update');
    Route::get('hostel-room-delete/{id}', 'hostelRoomDelete')->name('hostel.room.delete');
    Route::get('hostel-allocation', 'hostelAllocation')->name('hostel.allocation')->middleware('userRolePermission:hostel-list');
    Route::post('hostel-allocation-store', 'hostelAllocationStore')->name('hostel.allocation.store');
    Route::get('hostel-fee', 'hostelFee')->name('hostel.fee')->middleware('userRolePermission:hostel-list');
    Route::post('hostel-fee-store', 'hostelFeeStore')->name('hostel.fee.store');
    Route::get('hostel-fee-pay/{id}', 'hostelFeePay')->name('hostel.fee.pay');
    Route::post('hostel-meal-store', 'hostelMealStore')->name('hostel.meal.store');
    Route::get('hostel-meal-delete/{id}', 'hostelMealDelete')->name('hostel.meal.delete');
    Route::get('hostel-vacate/{id}', 'hostelVacate')->name('hostel.vacate');
    Route::get('hostel-get-rooms', 'hostelGetRooms')->name('hostel.get-rooms');
    Route::get('hostel-meals', 'hostelMeals')->name('hostel.meals')->middleware('userRolePermission:hostel-list');
});
