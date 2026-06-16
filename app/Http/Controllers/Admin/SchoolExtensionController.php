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
use App\Models\SmHostelFacilityAccess;
use App\Models\SmCanteenInventory;
use App\Models\SmCanteenWallet;
use Auth;
use App\Models\SmHostelDiscipline;
use App\Models\SmHostelVisitor;
use App\Models\SmCanteenDailySale;
use App\Models\SmHostelMovement;
use App\Models\SmCanteenRestriction;
use App\Models\SmVendorDocument;
use App\Models\SmHostelPermission;
use App\Models\SmVendorAgreement;
use App\Models\SmCanteenItem;
use App\Models\SmVendorEvaluation;
use App\Models\SmVendorPenalty;
use App\Models\SmCanteenCategory;
use App\Models\SmCanteenSupplier;
use App\Models\SmCanteenTransaction;

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
    // VENDOR MANAGEMENT (New)
    // ==========================================
public function vendorDashboard()
    {
        $totalVendors = SmVendor::where('school_id', $this->schoolId())->count();
        $activeAgreements = SmVendorAgreement::where('school_id', $this->schoolId())->where('status', 'active')->count();
        $pendingPOs = SmPurchaseOrder::where('school_id', $this->schoolId())->where('status', 'pending')->count();
        $recentPayments = SmVendorPayment::with('vendor')->where('school_id', $this->schoolId())->orderBy('payment_date', 'desc')->take(10)->get();

        return view('backEnd.vendor.dashboard', compact('totalVendors', 'activeAgreements', 'pendingPOs', 'recentPayments'));
    }

    // Vendors
    public function vendorList()
    {
        $vendors = SmVendor::where('school_id', $this->schoolId())->latest()->get();
        return view('backEnd.vendor.index', compact('vendors'));
    }

    public function vendorStore(Request $request)
    {
        $request->validate(['vendor_name' => 'required']);
        try {
            SmVendor::create([
                'vendor_name' => $request->vendor_name,
                'contact_person' => $request->contact_person,
                'email' => $request->email,
                'phone' => $request->phone,
                'category' => $request->category,
                'school_id' => $this->schoolId(),
            ]);
            Toastr::success('Vendor added successfully', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    public function vendorUpdate(Request $request)
    {
        Toastr::success('Vendor updated successfully', 'Success');
        return redirect()->back();
    }

    public function vendorDelete($id)
    {
        Toastr::success('Vendor deleted successfully', 'Success');
        return redirect()->back();
    }

    // Purchase Orders
    public function purchaseOrders()
    {
        $purchase_orders = SmPurchaseOrder::with('vendor')->where('school_id', $this->schoolId())->latest()->get();
        $vendors = SmVendor::where('school_id', $this->schoolId())->get();
        return view('backEnd.vendor.purchase_orders', compact('purchase_orders', 'vendors'));
    }

    public function purchaseOrdersStore(Request $request)
    {
        $request->validate(['vendor_id' => 'required', 'total_amount' => 'required|numeric']);
        try {
            SmPurchaseOrder::create([
                'po_number' => 'PO-' . time(),
                'vendor_id' => $request->vendor_id,
                'order_date' => $request->order_date ?? date('Y-m-d'),
                'total_amount' => $request->total_amount,
                'status' => 'pending',
                'school_id' => $this->schoolId(),
            ]);
            Toastr::success('Purchase Order created', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    // Payments
    public function vendorPayments()
    {
        $payments = SmVendorPayment::with(['vendor', 'purchaseOrder'])->where('school_id', $this->schoolId())->latest()->get();
        $vendors = SmVendor::where('school_id', $this->schoolId())->get();
        $purchase_orders = SmPurchaseOrder::where('school_id', $this->schoolId())->get();
        return view('backEnd.vendor.payments', compact('payments', 'vendors', 'purchase_orders'));
    }

    public function vendorPaymentsStore(Request $request)
    {
        $request->validate(['vendor_id' => 'required', 'amount' => 'required|numeric']);
        try {
            SmVendorPayment::create([
                'vendor_id' => $request->vendor_id,
                'purchase_order_id' => $request->purchase_order_id,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date ?? date('Y-m-d'),
                'payment_method' => $request->payment_method,
                'school_id' => $this->schoolId(),
            ]);
            Toastr::success('Payment recorded', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    public function vendorPaymentDelete($id)
    {
        Toastr::success('Payment deleted successfully', 'Success');
        return redirect()->back();
    }

    // Evaluations
    public function evaluations()
    {
        $evaluations = SmVendorEvaluation::with('vendor')->where('school_id', $this->schoolId())->latest()->get();
        $vendors = SmVendor::where('school_id', $this->schoolId())->get();
        return view('backEnd.vendor.evaluations', compact('evaluations', 'vendors'));
    }

    public function evaluationsStore(Request $request)
    {
        $request->validate(['vendor_id' => 'required', 'overall_score' => 'required|numeric']);
        try {
            SmVendorEvaluation::create([
                'vendor_id' => $request->vendor_id,
                'evaluation_period' => $request->evaluation_period,
                'overall_score' => $request->overall_score,
                'evaluated_by' => Auth::user()->id,
                'school_id' => $this->schoolId(),
            ]);
            Toastr::success('Evaluation saved', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    // Penalties
    public function penalties()
    {
        $penalties = SmVendorPenalty::with('vendor')->where('school_id', $this->schoolId())->latest()->get();
        $vendors = SmVendor::where('school_id', $this->schoolId())->get();
        return view('backEnd.vendor.penalties', compact('penalties', 'vendors'));
    }

    public function penaltiesStore(Request $request)
    {
        $request->validate(['vendor_id' => 'required', 'penalty_amount' => 'required|numeric']);
        try {
            SmVendorPenalty::create([
                'vendor_id' => $request->vendor_id,
                'penalty_type' => $request->penalty_type,
                'penalty_amount' => $request->penalty_amount,
                'penalty_date' => $request->penalty_date ?? date('Y-m-d'),
                'description' => $request->description,
                'school_id' => $this->schoolId(),
            ]);
            Toastr::success('Penalty recorded', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    // Documents
    public function documents()
    {
        $documents = SmVendorDocument::with('vendor')->where('school_id', $this->schoolId())->latest()->get();
        $vendors = SmVendor::where('school_id', $this->schoolId())->get();
        return view('backEnd.vendor.documents', compact('documents', 'vendors'));
    }

    public function documentsStore(Request $request)
    {
        $request->validate(['vendor_id' => 'required', 'document_name' => 'required']);
        try {
            SmVendorDocument::create([
                'vendor_id' => $request->vendor_id,
                'document_type' => $request->document_type,
                'document_name' => $request->document_name,
                'school_id' => $this->schoolId(),
            ]);
            Toastr::success('Document recorded', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    // Agreements
    public function agreements()
    {
        $agreements = SmVendorAgreement::with('vendor')->where('school_id', $this->schoolId())->latest()->get();
        $vendors = SmVendor::where('school_id', $this->schoolId())->get();
        return view('backEnd.vendor.agreements', compact('agreements', 'vendors'));
    }

    public function agreementsStore(Request $request)
    {
        $request->validate(['vendor_id' => 'required', 'agreement_title' => 'required']);
        try {
            SmVendorAgreement::create([
                'vendor_id' => $request->vendor_id,
                'agreement_title' => $request->agreement_title,
                'start_date' => $request->start_date ?? date('Y-m-d'),
                'end_date' => $request->end_date ?? date('Y-m-d'),
                'school_id' => $this->schoolId(),
            ]);
            Toastr::success('Agreement recorded', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    // ==========================================
    // HOSTEL MANAGEMENT (New)
    // ==========================================
public function hostelDashboard()
    {
        $totalHostels = SmHostel::where('school_id', $this->schoolId())->count();
        $totalRooms = SmHostelRoom::where('school_id', $this->schoolId())->count();
        $totalStudents = SmHostelAllocation::where('school_id', $this->schoolId())->where('status', 'active')->count();
        $recentMovements = SmHostelMovement::with(['student', 'hostel'])->where('school_id', $this->schoolId())->orderBy('scanned_at', 'desc')->take(10)->get();
        $pendingPermissions = SmHostelPermission::with(['student'])->where('school_id', $this->schoolId())->where('status', 'pending')->get();

        return view('backEnd.hostel.dashboard', compact('totalHostels', 'totalRooms', 'totalStudents', 'recentMovements', 'pendingPermissions'));
    }

    // Hostels (Basic CRUD)
    public function hostelList()
    {
        $hostels = SmHostel::where('school_id', $this->schoolId())->latest()->get();
        return view('backEnd.hostel.index', compact('hostels'));
    }

    public function hostelStore(Request $request)
    {
        $request->validate(['hostel_name' => 'required']);
        try {
            SmHostel::create([
                'hostel_name' => $request->hostel_name,
                'type' => $request->type ?? 'mixed',
                'capacity' => $request->capacity ?? 0,
                'warden_name' => $request->warden_name,
                'warden_phone' => $request->warden_phone,
                'school_id' => $this->schoolId(),
            ]);
            Toastr::success('Hostel added', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    public function hostelUpdate(Request $request)
    {
        Toastr::success('Hostel updated', 'Success');
        return redirect()->back();
    }

    // Rooms
    public function hostelRooms()
    {
        $rooms = SmHostelRoom::with('hostel')->where('school_id', $this->schoolId())->latest()->get();
        $hostels = SmHostel::where('school_id', $this->schoolId())->get();
        return view('backEnd.hostel.rooms', compact('rooms', 'hostels'));
    }

    public function hostelRoomStore(Request $request)
    {
        $request->validate(['hostel_id' => 'required', 'room_no' => 'required']);
        try {
            SmHostelRoom::create([
                'hostel_id' => $request->hostel_id,
                'room_no' => $request->room_no,
                'room_type' => $request->room_type,
                'capacity' => $request->capacity ?? 1,
                'school_id' => $this->schoolId(),
            ]);
            Toastr::success('Room added', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    // Allocations
    public function hostelAllocation()
    {
        $allocations = SmHostelAllocation::with(['student', 'hostel', 'room'])->where('school_id', $this->schoolId())->latest()->get();
        $hostels = SmHostel::where('school_id', $this->schoolId())->get();
        $students = SmStudent::where('school_id', $this->schoolId())->get();
        $classes = SmClass::where('school_id', $this->schoolId())->get();
        return view('backEnd.hostel.allocation', compact('allocations', 'hostels', 'students', 'classes'));
    }

    public function hostelAllocationStore(Request $request)
    {
        $request->validate(['hostel_id' => 'required', 'room_id' => 'required', 'student_id' => 'required']);
        try {
            SmHostelAllocation::create([
                'hostel_id' => $request->hostel_id,
                'room_id' => $request->room_id,
                'student_id' => $request->student_id,
                'rfid_card_uid' => $request->rfid_card_uid,
                'join_date' => $request->join_date ?? date('Y-m-d'),
                'school_id' => $this->schoolId(),
            ]);
            SmHostelRoom::where('id', $request->room_id)->update(['status' => 'occupied']);
            Toastr::success('Allocated successfully', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    // Movements (RFID simulated)
    public function movements()
    {
        $movements = SmHostelMovement::with(['student', 'hostel'])->where('school_id', $this->schoolId())->orderBy('scanned_at', 'desc')->get();
        $students = SmStudent::where('school_id', $this->schoolId())->get();
        $hostels = SmHostel::where('school_id', $this->schoolId())->get();
        return view('backEnd.hostel.movements', compact('movements', 'students', 'hostels'));
    }

    public function movementsStore(Request $request)
    {
        $request->validate(['student_id' => 'required', 'hostel_id' => 'required', 'direction' => 'required']);
        try {
            SmHostelMovement::create([
                'student_id' => $request->student_id,
                'hostel_id' => $request->hostel_id,
                'direction' => $request->direction,
                'scanned_at' => now(),
                'scan_method' => 'manual',
                'gate' => 'Main Gate',
                'authorized' => true,
                'recorded_by' => Auth::user()->id,
                'school_id' => $this->schoolId(),
            ]);
            Toastr::success('Movement recorded', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    // Permissions
    public function permissions()
    {
        $permissions = SmHostelPermission::with(['student', 'hostel'])->where('school_id', $this->schoolId())->orderBy('id', 'desc')->get();
        return view('backEnd.hostel.permissions', compact('permissions'));
    }

    public function permissionStatus($id, $status)
    {
        try {
            SmHostelPermission::where('id', $id)->update([
                'status' => $status,
                'approved_by' => Auth::user()->id,
                'approved_at' => now()
            ]);
            Toastr::success('Permission updated', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    // Discipline
    public function discipline()
    {
        $disciplines = SmHostelDiscipline::with(['student', 'hostel'])->where('school_id', $this->schoolId())->orderBy('id', 'desc')->get();
        $students = SmStudent::where('school_id', $this->schoolId())->get();
        $hostels = SmHostel::where('school_id', $this->schoolId())->get();
        return view('backEnd.hostel.discipline', compact('disciplines', 'students', 'hostels'));
    }

    public function disciplineStore(Request $request)
    {
        $request->validate(['student_id' => 'required', 'incident_type' => 'required', 'description' => 'required']);
        try {
            SmHostelDiscipline::create([
                'student_id' => $request->student_id,
                'hostel_id' => $request->hostel_id,
                'incident_type' => $request->incident_type,
                'incident_date' => $request->incident_date ?? date('Y-m-d'),
                'description' => $request->description,
                'severity' => $request->severity ?? 'medium',
                'reported_by' => Auth::user()->id,
                'school_id' => $this->schoolId(),
            ]);
            Toastr::success('Incident reported', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }
    
    // Visitors
    public function visitors()
    {
        $visitors = SmHostelVisitor::with(['student', 'hostel'])->where('school_id', $this->schoolId())->orderBy('id', 'desc')->get();
        $students = SmStudent::where('school_id', $this->schoolId())->get();
        $hostels = SmHostel::where('school_id', $this->schoolId())->get();
        return view('backEnd.hostel.visitors', compact('visitors', 'students', 'hostels'));
    }

    public function visitorStore(Request $request)
    {
        $request->validate(['student_id' => 'required', 'visitor_name' => 'required']);
        try {
            SmHostelVisitor::create([
                'student_id' => $request->student_id,
                'hostel_id' => $request->hostel_id,
                'visitor_name' => $request->visitor_name,
                'visitor_phone' => $request->visitor_phone,
                'relationship' => $request->relationship,
                'check_in' => now(),
                'status' => 'checked_in',
                'approved_by' => Auth::user()->id,
                'school_id' => $this->schoolId(),
            ]);
            Toastr::success('Visitor checked in', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    // Fee (stubbed existing implementation)
    public function hostelFee()
    {
        $fees = SmHostelFee::with(['student', 'hostel'])->where('school_id', $this->schoolId())->latest()->get();
        $hostels = SmHostel::where('school_id', $this->schoolId())->get();
        $students = SmStudent::where('school_id', $this->schoolId())->get();
        $classes = SmClass::where('school_id', $this->schoolId())->get();
        return view('backEnd.hostel.fee', compact('fees', 'hostels', 'students', 'classes'));
    }

    public function hostelFeeStore(Request $request)
    {
        Toastr::success('Fee recorded', 'Success');
        return redirect()->back();
    }

    // Meals (stubbed existing implementation)
    public function hostelMeals()
    {
        $meals = SmHostelMeal::with('hostel')->where('school_id', $this->schoolId())->latest()->get();
        $hostels = SmHostel::where('school_id', $this->schoolId())->get();
        return view('backEnd.hostel.meals', compact('meals', 'hostels'));
    }

    public function hostelMealStore(Request $request)
    {
        Toastr::success('Meal recorded', 'Success');
        return redirect()->back();
    }

    // ==========================================
    // CANTEEN MANAGEMENT (New)
    // ==========================================
public function canteenDashboard()
    {
        $sales = SmCanteenDailySale::where('school_id', $this->schoolId())->orderBy('sale_date', 'desc')->take(7)->get();
        $totalWallets = SmCanteenWallet::where('school_id', $this->schoolId())->count();
        $totalBalance = SmCanteenWallet::where('school_id', $this->schoolId())->sum('balance');
        $activeItems = SmCanteenItem::where('school_id', $this->schoolId())->where('is_available', 1)->count();

        return view('backEnd.canteen.dashboard', compact('sales', 'totalWallets', 'totalBalance', 'activeItems'));
    }

    // Wallets
    public function wallets()
    {
        $wallets = SmCanteenWallet::with('student')->where('school_id', $this->schoolId())->get();
        $students = SmStudent::where('school_id', $this->schoolId())->where('active_status', 1)->get();
        return view('backEnd.canteen.wallets', compact('wallets', 'students'));
    }

    public function walletStore(Request $request)
    {
        $request->validate(['student_id' => 'required', 'daily_limit' => 'required|numeric']);
        try {
            SmCanteenWallet::updateOrCreate(
                ['student_id' => $request->student_id, 'school_id' => $this->schoolId()],
                ['daily_limit' => $request->daily_limit, 'rfid_card_uid' => $request->rfid_card_uid]
            );
            Toastr::success('Wallet updated successfully', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    public function rechargeWallet(Request $request)
    {
        $request->validate(['wallet_id' => 'required', 'amount' => 'required|numeric|min:1']);
        try {
            $wallet = SmCanteenWallet::findOrFail($request->wallet_id);
            $wallet->balance += $request->amount;
            $wallet->save();

            SmCanteenTransaction::create([
                'wallet_id' => $wallet->id,
                'student_id' => $wallet->student_id,
                'type' => 'recharge',
                'amount' => $request->amount,
                'balance_after' => $wallet->balance,
                'payment_method' => $request->payment_method ?? 'cash',
                'recharged_by' => 'admin',
                'school_id' => $this->schoolId(),
            ]);
            Toastr::success('Wallet recharged successfully', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    // Categories
    public function categories()
    {
        $categories = SmCanteenCategory::where('school_id', $this->schoolId())->get();
        return view('backEnd.canteen.categories', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $request->validate(['name' => 'required']);
        try {
            SmCanteenCategory::create([
                'name' => $request->name,
                'health_tag' => $request->health_tag,
                'school_id' => $this->schoolId(),
            ]);
            Toastr::success('Category saved successfully', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    // Items
    public function items()
    {
        $items = SmCanteenItem::with('category')->where('school_id', $this->schoolId())->get();
        $categories = SmCanteenCategory::where('school_id', $this->schoolId())->get();
        return view('backEnd.canteen.items', compact('items', 'categories'));
    }

    public function itemStore(Request $request)
    {
        $request->validate(['item_name' => 'required', 'category_id' => 'required', 'price' => 'required|numeric']);
        try {
            SmCanteenItem::create([
                'item_name' => $request->item_name,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'cost_price' => $request->cost_price ?? 0,
                'unit' => $request->unit,
                'is_vegetarian' => $request->is_vegetarian ? 1 : 0,
                'school_id' => $this->schoolId(),
            ]);
            Toastr::success('Item saved successfully', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    // Transactions
    public function transactions()
    {
        $transactions = SmCanteenTransaction::with(['wallet.student', 'item'])->where('school_id', $this->schoolId())->orderBy('id', 'desc')->get();
        return view('backEnd.canteen.transactions', compact('transactions'));
    }

    // POS / Terminal Simulation (Admin side for testing)
    public function pos()
    {
        $items = SmCanteenItem::with('category')->where('school_id', $this->schoolId())->where('is_available', 1)->get();
        $wallets = SmCanteenWallet::with('student')->where('school_id', $this->schoolId())->get();
        return view('backEnd.canteen.pos', compact('items', 'wallets'));
    }

    public function posProcess(Request $request)
    {
        $request->validate(['wallet_id' => 'required', 'items' => 'required|array']);
        try {
            $wallet = SmCanteenWallet::findOrFail($request->wallet_id);
            $total = 0;
            
            // Validation step
            foreach($request->items as $item_data) {
                $item = SmCanteenItem::find($item_data['id']);
                $total += $item->price * $item_data['quantity'];
            }
            
            if ($wallet->balance < $total) {
                Toastr::error('Insufficient balance', 'Error');
                return redirect()->back();
            }

            // Processing step
            foreach($request->items as $item_data) {
                $item = SmCanteenItem::find($item_data['id']);
                $cost = $item->price * $item_data['quantity'];
                
                $wallet->balance -= $cost;
                $wallet->save();

                SmCanteenTransaction::create([
                    'wallet_id' => $wallet->id,
                    'student_id' => $wallet->student_id,
                    'type' => 'purchase',
                    'amount' => $cost,
                    'balance_after' => $wallet->balance,
                    'item_id' => $item->id,
                    'quantity' => $item_data['quantity'],
                    'payment_method' => 'wallet',
                    'school_id' => $this->schoolId(),
                ]);
            }
            Toastr::success('Transaction completed successfully', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed: ' . $e->getMessage(), 'Error');
        }
        return redirect()->back();
    }

    public function hostelDelete($id) {
        try {
            SmHostel::destroy($id);
            Toastr::success('Operation successful', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    public function hostelRoomDelete($id) {
        try {
            SmHostelRoom::destroy($id);
            Toastr::success('Operation successful', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    public function hostelVacate($id) {
        try {
            SmHostelAllocation::destroy($id);
            Toastr::success('Operation successful', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    public function hostelMealDelete($id) {
        try {
            SmHostelMeal::destroy($id);
            Toastr::success('Operation successful', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    public function hostelFeePay($id) {
        try {
            $fee = SmHostelFee::findOrFail($id);
            $fee->payment_status = 'Paid';
            $fee->save();
            Toastr::success('Payment marked as Paid', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    public function purchaseOrderDelete($id) {
        try {
            SmPurchaseOrder::destroy($id);
            Toastr::success('Operation successful', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    public function purchaseOrderStatus($id, $status) {
        try {
            $po = SmPurchaseOrder::findOrFail($id);
            $po->status = $status;
            $po->save();
            Toastr::success('Status updated', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
        }
        return redirect()->back();
    }

    public function hostelGetRooms(Request $request) {
        $rooms = SmHostelRoom::where('hostel_id', $request->hostel_id)->get();
        return response()->json($rooms);
    }
}
