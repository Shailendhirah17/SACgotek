<?php

namespace App\Http\Controllers\Admin;

use App\SmClass;
use App\SmStudent;
use App\Models\SmHostel;
use App\Models\SmVendor;
use App\Models\SmBookBank;
use App\Models\SmHostelFee;
use App\Models\SmHostelMeal;
use App\Models\SmHostelRoom;
use Illuminate\Http\Request;
use App\Models\SmThirukkural;
use App\Models\SmVendorPayment;
use App\Models\SmMedicalRecord;
use App\Models\SmBookBankIssue;
use App\Models\SmPurchaseOrder;
use App\Models\SmHostelAllocation;
use App\Models\SmVaccinationRecord;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use App\Models\SmTransferCertificate;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\RedirectResponse;

class SchoolExtensionController extends Controller
{
    private function schoolId(): int
    {
        return (int) (auth()->user()->school_id ?? 1);
    }

    // ==========================================
    // 1. Transfer Certificate (TC)
    // ==========================================
    public function tcList()
    {
        $tcs = SmTransferCertificate::where('school_id', $this->schoolId())->get();
        $classes = SmClass::where('school_id', $this->schoolId())->get();
        $students = SmStudent::where('school_id', $this->schoolId())->get();
        return view('backEnd.tc.index', compact('tcs', 'students', 'classes'));
    }

    public function tcCreate(): View
    {
        $classes = SmClass::where('school_id', $this->schoolId())->get();
        return view('backEnd.tc.index', compact('classes'));
    }

    public function tcStore(Request $request): RedirectResponse
    {
        $request->validate([
            'student_id' => 'required|integer',
            'date'       => 'required|date',
        ]);
        try {
            SmTransferCertificate::create([
                'student_id'  => $request->student_id,
                'tc_no'       => $request->tc_no,
                'reason'      => $request->reason,
                'date'        => date('Y-m-d', strtotime($request->date)),
                'school_id'   => $this->schoolId(),
                'academic_id' => getAcademicId(),
            ]);
            Toastr::success('TC issued successfully', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Something went wrong: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('tc.index');
    }

    public function tcShow($id): View|RedirectResponse
    {
        $tc = SmTransferCertificate::with('student')->find($id);
        if (! $tc) {
            Toastr::error('TC not found', 'Error');
            return redirect()->route('tc.index');
        }
        return view('backEnd.tc.show', compact('tc'));
    }

    public function tcEdit($id): RedirectResponse
    {
        return redirect()->route('tc.index');
    }

    public function tcUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'tc_id' => 'required|integer',
            'date'  => 'required|date',
        ]);
        try {
            $tc = SmTransferCertificate::where('school_id', $this->schoolId())->findOrFail($request->tc_id);
            $tc->update([
                'tc_no'  => $request->tc_no,
                'reason' => $request->reason,
                'date'   => date('Y-m-d', strtotime($request->date)),
            ]);
            Toastr::success('TC updated successfully', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Something went wrong: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('tc.index');
    }

    public function tcDelete($id): RedirectResponse
    {
        try {
            SmTransferCertificate::where('school_id', $this->schoolId())->where('id', $id)->delete();
            Toastr::success('TC deleted', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Could not delete TC', 'Error');
        }
        return redirect()->route('tc.index');
    }

    public function tcGetStudents(Request $request): \Illuminate\Http\JsonResponse
    {
        $students = SmStudent::where('class_id', $request->class_id)
            ->where('school_id', $this->schoolId())
            ->select('id', 'first_name', 'last_name', 'admission_no')
            ->get();
        return response()->json($students);
    }

    // ==========================================
    // 2. Medical Records & Vaccination
    // ==========================================
    public function medicalRecords()
    {
        $records = SmMedicalRecord::with('student')->where('school_id', $this->schoolId())->get();
        $classes = SmClass::where('school_id', $this->schoolId())->get();
        $students = SmStudent::where('school_id', $this->schoolId())->get();
        return view('backEnd.medical.index', compact('records', 'students', 'classes'));
    }

    public function medicalRecordsStore(Request $request): RedirectResponse
    {
        $request->validate(['student_id' => 'required|integer']);
        try {
            SmMedicalRecord::create([
                'student_id'          => $request->student_id,
                'blood_group'         => $request->blood_group,
                'weight'              => $request->weight,
                'height'              => $request->height,
                'allergies'           => $request->allergies,
                'medical_history'     => $request->medical_history,
                'current_medications' => $request->current_medications,
                'school_id'           => $this->schoolId(),
                'academic_id'         => getAcademicId(),
            ]);
            Toastr::success('Medical record saved', 'Success');
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('medical.records');
    }

    public function medicalRecordsEdit($id): RedirectResponse
    {
        return redirect()->route('medical.records');
    }

    public function medicalRecordsUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'record_id' => 'required|integer',
        ]);
        try {
            $record = SmMedicalRecord::where('school_id', $this->schoolId())->findOrFail($request->record_id);
            $record->update([
                'blood_group'         => $request->blood_group,
                'weight'              => $request->weight,
                'height'              => $request->height,
                'allergies'           => $request->allergies,
                'medical_history'     => $request->medical_history,
                'current_medications' => $request->current_medications,
            ]);
            Toastr::success('Medical record updated', 'Success');
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('medical.records');
    }

    public function medicalRecordsDelete($id): RedirectResponse
    {
        try {
            SmMedicalRecord::where('school_id', $this->schoolId())->where('id', $id)->delete();
            Toastr::success('Record deleted', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Could not delete record', 'Error');
        }
        return redirect()->route('medical.records');
    }

    public function vaccinationRecords()
    {
        $records = SmVaccinationRecord::with('student')->where('school_id', $this->schoolId())->get();
        $classes = SmClass::where('school_id', $this->schoolId())->get();
        $students = SmStudent::where('school_id', $this->schoolId())->get();
        return view('backEnd.medical.vaccination_index', compact('records', 'students', 'classes'));
    }

    public function vaccinationStore(Request $request): RedirectResponse
    {
        $request->validate([
            'student_id'   => 'required|integer',
            'vaccine_name' => 'required|string|max:255',
        ]);
        try {
            SmVaccinationRecord::create([
                'student_id'       => $request->student_id,
                'vaccine_name'     => $request->vaccine_name,
                'date_given'       => $request->date_given,
                'dose'             => $request->dose,
                'administered_by'  => $request->administered_by,
                'remarks'          => $request->remarks,
                'school_id'        => $this->schoolId(),
                'academic_id'      => getAcademicId(),
            ]);
            Toastr::success('Vaccination record saved', 'Success');
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('vaccination-records');
    }

    public function vaccinationUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'record_id'    => 'required|integer',
            'vaccine_name' => 'required|string|max:255',
        ]);
        try {
            $record = SmVaccinationRecord::where('school_id', $this->schoolId())->findOrFail($request->record_id);
            $record->update([
                'vaccine_name'     => $request->vaccine_name,
                'date_given'       => $request->date_given,
                'dose'             => $request->dose,
                'administered_by'  => $request->administered_by,
                'remarks'          => $request->remarks,
            ]);
            Toastr::success('Vaccination record updated', 'Success');
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('vaccination-records');
    }

    public function vaccinationDelete($id): RedirectResponse
    {
        try {
            SmVaccinationRecord::where('school_id', $this->schoolId())->where('id', $id)->delete();
            Toastr::success('Record deleted', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Could not delete', 'Error');
        }
        return redirect()->route('vaccination-records');
    }

    // ==========================================
    // 3. Book Bank & Thirukkural
    // ==========================================
    public function bookBank()
    {
        $books = SmBookBank::where('school_id', $this->schoolId())->latest()->get();
        $classes = SmClass::where('school_id', $this->schoolId())->get();
        return view('backEnd.bookBank.index', compact('books', 'classes'));
    }

    public function bookBankIssue(): View
    {
        $classes = SmClass::where('school_id', $this->schoolId())->get();
        $books   = SmBookBank::where('school_id', $this->schoolId())->get();
        $issues  = SmBookBankIssue::with(['book', 'student'])->where('school_id', $this->schoolId())->latest()->get();
        return view('backEnd.bookBank.issue', compact('classes', 'books', 'issues'));
    }

    public function bookBankStore(Request $request): RedirectResponse
    {
        $request->validate(['book_name' => 'required|string|max:255']);
        try {
            $total = max(1, (int) $request->total_copies);
            SmBookBank::create([
                'book_name'        => $request->book_name,
                'author'           => $request->author,
                'isbn'             => $request->isbn,
                'publisher'        => $request->publisher,
                'total_copies'     => $total,
                'available_copies' => $total,
                'class'            => $request->class,
                'subject'          => $request->subject,
                'school_id'        => $this->schoolId(),
            ]);
            Toastr::success('Book added to bank', 'Success');
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('book-bank.index');
    }

    public function bookBankEdit($id): RedirectResponse
    {
        return redirect()->route('book-bank.index');
    }

    public function bookBankUpdate(Request $request): RedirectResponse
    {
        $request->validate(['book_id' => 'required|integer', 'book_name' => 'required|string|max:255']);
        try {
            $book = SmBookBank::where('school_id', $this->schoolId())->findOrFail($request->book_id);
            $total = max(1, (int) ($request->total_copies ?? $book->total_copies));
            $diff = $total - $book->total_copies;
            
            $book->update([
                'book_name'        => $request->book_name,
                'author'           => $request->author,
                'isbn'             => $request->isbn,
                'publisher'        => $request->publisher,
                'class'            => $request->class,
                'subject'          => $request->subject,
                'total_copies'     => $total,
                'available_copies' => max(0, $book->available_copies + $diff),
            ]);
            Toastr::success('Book updated', 'Success');
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('book-bank.index');
    }

    public function bookBankDelete($id): RedirectResponse
    {
        try {
            SmBookBank::where('school_id', $this->schoolId())->where('id', $id)->delete();
            Toastr::success('Book deleted', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Could not delete', 'Error');
        }
        return redirect()->route('book-bank.index');
    }

    public function bookBankIssueStore(Request $request): RedirectResponse
    {
        $request->validate([
            'book_id'     => 'required|integer',
            'student_id'  => 'required|integer',
            'issued_date' => 'required|date',
        ]);
        try {
            $book = SmBookBank::find($request->book_id);
            if (! $book || $book->available_copies < 1) {
                Toastr::error('Book not available', 'Error');
                return redirect()->back();
            }
            SmBookBankIssue::create([
                'book_id'     => $request->book_id,
                'student_id'  => $request->student_id,
                'issued_date' => $request->issued_date,
                'due_date'    => $request->due_date,
                'status'      => 'issued',
                'school_id'   => $this->schoolId(),
            ]);
            $book->decrement('available_copies');
            Toastr::success('Book issued successfully', 'Success');
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->back();
    }

    public function bookBankReturn($id): RedirectResponse
    {
        try {
            $issue = SmBookBankIssue::find($id);
            if ($issue) {
                $issue->update(['status' => 'returned', 'return_date' => now()->toDateString()]);
                SmBookBank::find($issue->book_id)?->increment('available_copies');
                Toastr::success('Book returned successfully', 'Success');
            }
        } catch (\Exception $e) {
            Toastr::error('Could not process return', 'Error');
        }
        return redirect()->back();
    }

    public function thirukkural()
    {
        $sections = ['Araththuppaal', 'Porutpaal', 'Inpaththuppaal'];
        $kurals = SmThirukkural::where('school_id', $this->schoolId())->orderBy('kural_no')->get();
        return view('backEnd.bookBank.thirukkural', compact('sections', 'kurals'));
    }

    public function thirukkuralStore(Request $request): RedirectResponse
    {
        try {
            SmThirukkural::create([
                'kural_no'      => $request->kural_no,
                'section'       => $request->section,
                'chapter'       => $request->chapter,
                'kural_tamil'   => $request->kural_tamil,
                'kural_english' => $request->kural_english,
                'explanation'   => $request->explanation,
                'school_id'     => $this->schoolId(),
            ]);
            Toastr::success('Thirukkural saved', 'Success');
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('thirukkural.index');
    }

    public function thirukkuralUpdate(Request $request): RedirectResponse
    {
        try {
            $kural = SmThirukkural::where('school_id', $this->schoolId())->findOrFail($request->id);
            $kural->update([
                'kural_no'      => $request->kural_no,
                'section'       => $request->section,
                'chapter'       => $request->chapter,
                'kural_tamil'   => $request->kural_tamil,
                'kural_english' => $request->kural_english,
                'explanation'   => $request->explanation,
            ]);
            Toastr::success('Thirukkural updated', 'Success');
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('thirukkural.index');
    }

    public function thirukkuralDelete($id): RedirectResponse
    {
        try {
            SmThirukkural::where('school_id', $this->schoolId())->where('id', $id)->delete();
            Toastr::success('Kural deleted', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Could not delete', 'Error');
        }
        return redirect()->route('thirukkural.index');
    }

    // ==========================================
    // 4. Vendor Management
    // ==========================================
    public function vendorList(): View
    {
        $vendors = SmVendor::where('school_id', $this->schoolId())->latest()->get();
        return view('backEnd.vendor.index', compact('vendors'));
    }

    public function vendorStore(Request $request): RedirectResponse
    {
        $request->validate(['vendor_name' => 'required|string|max:255']);
        try {
            SmVendor::create([
                'vendor_name'    => $request->vendor_name,
                'contact_person' => $request->contact_person,
                'email'          => $request->email,
                'phone'          => $request->phone,
                'gstin'          => $request->gstin,
                'address'        => $request->address,
                'school_id'      => $this->schoolId(),
            ]);
            Toastr::success('Vendor registered', 'Success');
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('vendor.index');
    }

    public function vendorEdit($id): RedirectResponse
    {
        return redirect()->route('vendor.index');
    }

    public function vendorUpdate(Request $request): RedirectResponse
    {
        $request->validate(['vendor_id' => 'required|integer', 'vendor_name' => 'required|string|max:255']);
        try {
            $vendor = SmVendor::where('school_id', $this->schoolId())->findOrFail($request->vendor_id);
            $vendor->update([
                'vendor_name'    => $request->vendor_name,
                'contact_person' => $request->contact_person,
                'email'          => $request->email,
                'phone'          => $request->phone,
                'gstin'          => $request->gstin,
                'address'        => $request->address,
            ]);
            Toastr::success('Vendor updated', 'Success');
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('vendor.index');
    }

    public function vendorDelete($id): RedirectResponse
    {
        try {
            SmVendor::where('school_id', $this->schoolId())->where('id', $id)->delete();
            Toastr::success('Vendor deleted', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Could not delete', 'Error');
        }
        return redirect()->route('vendor.index');
    }

    public function purchaseOrders(): View
    {
        $purchase_orders = SmPurchaseOrder::with('vendor')->where('school_id', $this->schoolId())->latest()->get();
        $vendors = SmVendor::where('school_id', $this->schoolId())->get();
        return view('backEnd.vendor.purchase_orders', compact('purchase_orders', 'vendors'));
    }

    public function purchaseOrdersCreate(): RedirectResponse
    {
        return redirect()->route('purchase-order.index');
    }

    public function purchaseOrdersStore(Request $request): RedirectResponse
    {
        $request->validate([
            'vendor_id'    => 'required|integer',
            'order_date'   => 'required|date',
            'total_amount' => 'required|numeric|min:0',
        ]);
        try {
            SmPurchaseOrder::create([
                'vendor_id'         => $request->vendor_id,
                'order_date'        => $request->order_date,
                'items_description' => $request->items_description,
                'total_amount'      => $request->total_amount,
                'notes'             => $request->notes,
                'status'            => 'pending',
                'school_id'         => $this->schoolId(),
            ]);
            Toastr::success('Purchase order created', 'Success');
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('purchase-order.index');
    }

    public function purchaseOrdersStatus($id, $status): RedirectResponse
    {
        try {
            SmPurchaseOrder::where('school_id', $this->schoolId())->where('id', $id)
                ->update(['status' => $status]);
            Toastr::success('Purchase order updated', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Could not update status', 'Error');
        }
        return redirect()->route('purchase-order.index');
    }

    public function purchaseOrdersShow($id): View|RedirectResponse
    {
        $po = SmPurchaseOrder::with('vendor')->find($id);
        if (! $po) {
            Toastr::error('Order not found', 'Error');
            return redirect()->route('purchase-order.index');
        }
        return view('backEnd.vendor.purchase_orders', ['purchase_orders' => collect([$po]), 'vendors' => SmVendor::where('school_id', $this->schoolId())->get()]);
    }

    public function purchaseOrdersDelete($id): RedirectResponse
    {
        try {
            SmPurchaseOrder::where('school_id', $this->schoolId())->where('id', $id)->delete();
            Toastr::success('Purchase order deleted', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Could not delete', 'Error');
        }
        return redirect()->route('purchase-order.index');
    }

    public function vendorPaymentsStore(Request $request): RedirectResponse
    {
        $request->validate([
            'vendor_id'    => 'required|integer',
            'amount'       => 'required|numeric|min:0',
            'payment_date' => 'required|date',
        ]);
        try {
            SmVendorPayment::create([
                'vendor_id'         => $request->vendor_id,
                'purchase_order_id' => $request->purchase_order_id ?: null,
                'amount'            => $request->amount,
                'payment_date'      => $request->payment_date,
                'payment_method'    => $request->payment_method,
                'reference_no'      => $request->reference_no,
                'notes'             => $request->notes,
                'school_id'         => $this->schoolId(),
            ]);
            Toastr::success('Payment recorded', 'Success');
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('vendor.index');
    }

    public function vendorPaymentsDelete($id): RedirectResponse
    {
        try {
            SmVendorPayment::where('school_id', $this->schoolId())->where('id', $id)->delete();
            Toastr::success('Payment deleted', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Could not delete', 'Error');
        }
        return redirect()->back();
    }

    // Vendor Payments page (GET)
    public function vendorPayments(): View
    {
        $payments       = SmVendorPayment::with('vendor')->where('school_id', $this->schoolId())->latest()->get();
        $vendors        = SmVendor::where('school_id', $this->schoolId())->get();
        $purchase_orders = SmPurchaseOrder::where('school_id', $this->schoolId())->where('status', 'approved')->get();
        return view('backEnd.vendor.payments', compact('payments', 'vendors', 'purchase_orders'));
    }

    // ==========================================
    // 5. Hostel Management
    // ==========================================
    public function hostelList(): View
    {
        $hostels = SmHostel::where('school_id', $this->schoolId())->latest()->get();
        return view('backEnd.hostel.index', compact('hostels'));
    }

    public function hostelStore(Request $request): RedirectResponse
    {
        $request->validate(['hostel_name' => 'required|string|max:255']);
        try {
            SmHostel::create([
                'hostel_name'  => $request->hostel_name,
                'type'         => $request->type ?? 'mixed',
                'address'      => $request->address,
                'capacity'     => $request->capacity ?? 0,
                'warden_name'  => $request->warden_name,
                'warden_phone' => $request->warden_phone,
                'school_id'    => $this->schoolId(),
            ]);
            Toastr::success('Hostel added', 'Success');
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('hostel.index');
    }

    public function hostelEdit($id): RedirectResponse
    {
        return redirect()->route('hostel.index');
    }

    public function hostelUpdate(Request $request): RedirectResponse
    {
        $request->validate(['hostel_id' => 'required|integer', 'hostel_name' => 'required|string|max:255']);
        try {
            $hostel = SmHostel::where('school_id', $this->schoolId())->findOrFail($request->hostel_id);
            $hostel->update([
                'hostel_name' => $request->hostel_name,
                'type'        => $request->type ?? 'mixed',
                'address'     => $request->address,
                'capacity'    => $request->capacity ?? 0,
                'warden_name' => $request->warden_name,
                'warden_phone'=> $request->warden_phone,
            ]);
            Toastr::success('Hostel updated', 'Success');
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('hostel.index');
    }

    public function hostelDelete($id): RedirectResponse
    {
        try {
            SmHostel::where('school_id', $this->schoolId())->where('id', $id)->delete();
            Toastr::success('Hostel deleted', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Could not delete', 'Error');
        }
        return redirect()->route('hostel.index');
    }

    public function hostelRooms(): View
    {
        $rooms   = SmHostelRoom::with('hostel')->where('school_id', $this->schoolId())->latest()->get();
        $hostels = SmHostel::where('school_id', $this->schoolId())->get();
        return view('backEnd.hostel.rooms', compact('rooms', 'hostels'));
    }

    public function hostelRoomStore(Request $request): RedirectResponse
    {
        $request->validate([
            'hostel_id' => 'required|integer',
            'room_no'   => 'required|string|max:20',
        ]);
        try {
            SmHostelRoom::create([
                'hostel_id'     => $request->hostel_id,
                'room_no'       => $request->room_no,
                'room_type'     => $request->room_type,
                'capacity'      => $request->capacity ?? 1,
                'fee_per_month' => $request->fee_per_month ?? 0,
                'status'        => 'available',
                'school_id'     => $this->schoolId(),
            ]);
            Toastr::success('Room added', 'Success');
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('hostel.rooms');
    }

    public function hostelRoomEdit($id): RedirectResponse
    {
        return redirect()->route('hostel.rooms');
    }

    public function hostelRoomUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'room_id'     => 'required|integer',
            'room_no'     => 'required|string',
            'hostel_id'   => 'required|integer'
        ]);
        try {
            $room = SmHostelRoom::where('school_id', $this->schoolId())->findOrFail($request->room_id);
            $room->update([
                'room_no'        => $request->room_no,
                'hostel_id'      => $request->hostel_id,
                'room_type'      => $request->room_type,
                'capacity'       => $request->capacity ?? 1,
                'fee_per_month'  => $request->fee_per_month ?? 0,
            ]);
            Toastr::success('Room updated successfully', 'Success');
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('hostel.rooms');
    }

    public function hostelRoomDelete($id): RedirectResponse
    {
        try {
            SmHostelRoom::where('school_id', $this->schoolId())->where('id', $id)->delete();
            Toastr::success('Room deleted', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Could not delete', 'Error');
        }
        return redirect()->route('hostel.rooms');
    }

    public function hostelAllocationStore(Request $request): RedirectResponse
    {
        $request->validate([
            'hostel_id'  => 'required|integer',
            'room_id'    => 'required|integer',
            'student_id' => 'required|integer',
            'join_date'  => 'required|date',
        ]);
        try {
            // Overbooking guard: check room capacity vs active allocations
            $room = SmHostelRoom::find($request->room_id);
            if ($room) {
                $activeCount = SmHostelAllocation::where('room_id', $request->room_id)
                    ->where('status', 'active')->count();
                if ($activeCount >= $room->capacity) {
                    Toastr::error('Room is fully occupied (capacity: ' . $room->capacity . ')', 'Error');
                    return redirect()->back();
                }
            }
            SmHostelAllocation::create([
                'hostel_id'  => $request->hostel_id,
                'room_id'    => $request->room_id,
                'student_id' => $request->student_id,
                'join_date'  => $request->join_date,
                'status'     => 'active',
                'school_id'  => $this->schoolId(),
            ]);
            SmHostelRoom::where('id', $request->room_id)->update(['status' => 'occupied']);
            Toastr::success('Room allocated successfully', 'Success');
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('hostel.allocation');
    }

    // GET: Room Allocation page
    public function hostelAllocation(): View
    {
        $allocations = SmHostelAllocation::with(['student', 'hostel', 'room'])->where('school_id', $this->schoolId())->latest()->get();
        $classes     = SmClass::where('school_id', $this->schoolId())->get();
        $hostels     = SmHostel::where('school_id', $this->schoolId())->get();
        $students    = SmStudent::where('school_id', $this->schoolId())->get();
        return view('backEnd.hostel.allocation', compact('allocations', 'classes', 'hostels', 'students'));
    }

    public function hostelFeeStore(Request $request): RedirectResponse
    {
        $request->validate([
            'student_id' => 'required|integer',
            'hostel_id'  => 'required|integer',
            'amount'     => 'required|numeric|min:0',
            'month'      => 'required|integer|between:1,12',
            'year'       => 'required|integer',
        ]);
        try {
            SmHostelFee::create([
                'student_id' => $request->student_id,
                'hostel_id'  => $request->hostel_id,
                'room_id'    => $request->room_id ?: null,
                'amount'     => $request->amount,
                'month'      => $request->month,
                'year'       => $request->year,
                'status'     => 'unpaid',
                'school_id'  => $this->schoolId(),
            ]);
            Toastr::success('Fee record added', 'Success');
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('hostel.fee');
    }

    // GET: Hostel Fee page
    public function hostelFee(): View
    {
        $fees    = SmHostelFee::with(['student', 'hostel'])->where('school_id', $this->schoolId())->latest()->get();
        $classes = SmClass::where('school_id', $this->schoolId())->get();
        $hostels = SmHostel::where('school_id', $this->schoolId())->get();
        $students = SmStudent::where('school_id', $this->schoolId())->get();
        return view('backEnd.hostel.fee', compact('fees', 'classes', 'hostels', 'students'));
    }

    public function hostelFeePay($id): RedirectResponse
    {
        try {
            SmHostelFee::where('school_id', $this->schoolId())->where('id', $id)
                ->update(['status' => 'paid', 'paid_at' => now()->toDateString()]);
            Toastr::success('Fee marked as paid', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Could not update', 'Error');
        }
        return redirect()->route('hostel.fee');
    }

    public function hostelMealStore(Request $request): RedirectResponse
    {
        try {
            SmHostelMeal::create([
                'hostel_id'   => $request->hostel_id,
                'meal_type'   => $request->meal_type,
                'description' => $request->description,
                'price'       => $request->price ?? 0,
                'date'        => $request->date,
                'school_id'   => $this->schoolId(),
            ]);
            Toastr::success('Meal record saved', 'Success');
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('hostel.meals');
    }

    public function hostelMealDelete($id): RedirectResponse
    {
        try {
            SmHostelMeal::where('school_id', $this->schoolId())->where('id', $id)->delete();
            Toastr::success('Meal deleted', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Could not delete', 'Error');
        }
        return redirect()->route('hostel.meals');
    }

    public function hostelVacate($id): RedirectResponse
    {
        try {
            $alloc = SmHostelAllocation::find($id);
            if ($alloc) {
                $alloc->update(['status' => 'vacated', 'leave_date' => now()->toDateString()]);
                SmHostelRoom::where('id', $alloc->room_id)->update(['status' => 'available']);
                Toastr::success('Student vacated successfully', 'Success');
            }
        } catch (\Exception $e) {
            \Log::error($e);
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->route('hostel.index');
    }

    public function hostelGetRooms(Request $request): \Illuminate\Http\JsonResponse
    {
        $rooms = SmHostelRoom::where('hostel_id', $request->hostel_id)
            ->where('school_id', $this->schoolId())
            ->select('id', 'room_no', 'room_type', 'capacity', 'fee_per_month', 'status')
            ->get();
        return response()->json($rooms);
    }

    public function hostelMeals(): View
    {
        $hostels = SmHostel::where('school_id', $this->schoolId())->get();
        $meals   = SmHostelMeal::with('hostel')->where('school_id', $this->schoolId())->latest()->get();
        return view('backEnd.hostel.meals', compact('hostels', 'meals'));
    }
}
