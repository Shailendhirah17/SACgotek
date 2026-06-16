@extends('backEnd.master')
@section('title') Canteen POS @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Point of Sale (POS)</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">Canteen</a>
                <a href="#">POS</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <!-- Left Side: Item Selection -->
            <div class="col-lg-8">
                <div class="white-box">
                    <h4 class="mb-20">Menu Items</h4>
                    <div class="row">
                        @foreach($items as $item)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-20">
                            <div class="card item-card text-center p-3 cursor-pointer shadow-sm border-0" 
                                 onclick="addToCart({{ $item->id }}, '{{ $item->item_name }}', {{ $item->price }})">
                                <i class="fas fa-utensils fa-2x mb-2 text-primary"></i>
                                <h6>{{ $item->item_name }}</h6>
                                <span class="text-success font-weight-bold">${{ number_format($item->price, 2) }}</span>
                                <small class="text-muted d-block">{{ $item->category->name ?? '' }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Side: Cart & Checkout -->
            <div class="col-lg-4">
                <div class="white-box bg-light border-primary" style="border-top: 3px solid #828bb2;">
                    <h4 class="mb-20 text-center"><i class="fas fa-shopping-cart"></i> Current Order</h4>
                    
                    <form action="{{ route('canteen.pos.process') }}" method="POST" id="posForm">
                        @csrf
                        <div class="form-group mb-3">
                            <label><strong>Student Wallet</strong> (Tap RFID or Select)</label>
                            <select name="wallet_id" class="form-control select2" required>
                                <option value="">Select Wallet</option>
                                @foreach($wallets as $wallet)
                                    <option value="{{ $wallet->id }}">{{ $wallet->student->full_name ?? 'Unknown' }} (Bal: ${{ number_format($wallet->balance, 2) }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="cart-items bg-white p-3 mb-3 border rounded" style="min-height: 200px; max-height: 300px; overflow-y: auto;">
                            <table class="table table-sm borderless" id="cartTable">
                                <tbody id="cartBody">
                                    <tr id="emptyCart"><td class="text-center text-muted py-4">Cart is empty</td></tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="cart-summary bg-white p-3 border rounded mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <strong>$<span id="subtotal">0.00</span></strong>
                            </div>
                            <div class="d-flex justify-content-between text-success">
                                <h5 class="mb-0">Total Due</h5>
                                <h5 class="mb-0">$<span id="grandTotal">0.00</span></h5>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg btn-block shadow font-weight-bold" id="payBtn" disabled>
                            <i class="fas fa-check-circle"></i> Pay Now
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.item-card { transition: all 0.2s ease; border: 1px solid #eee !important; border-radius: 8px; }
.item-card:hover { transform: scale(1.05); box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important; border-color: #828bb2 !important; }
.cursor-pointer { cursor: pointer; }
.borderless td, .borderless th { border: none; }
</style>

@endsection

@push('scripts')
<script>
let cart = {};
let total = 0;

function updateCartUI() {
    let tbody = $('#cartBody');
    tbody.empty();
    
    if(Object.keys(cart).length === 0) {
        tbody.append('<tr id="emptyCart"><td class="text-center text-muted py-4">Cart is empty</td></tr>');
        $('#payBtn').prop('disabled', true);
    } else {
        $('#payBtn').prop('disabled', false);
        let index = 0;
        for(let id in cart) {
            let item = cart[id];
            let itemTotal = item.price * item.quantity;
            tbody.append(`
                <tr>
                    <td>
                        <strong>${item.name}</strong><br>
                        <small class="text-muted">$${item.price.toFixed(2)} x ${item.quantity}</small>
                        <input type="hidden" name="items[${index}][id]" value="${id}">
                        <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                    </td>
                    <td class="text-right align-middle font-weight-bold">$${itemTotal.toFixed(2)}</td>
                    <td class="text-right align-middle">
                        <button type="button" class="btn btn-sm btn-danger px-2 py-0" onclick="removeFromCart(${id})"><i class="fas fa-times"></i></button>
                    </td>
                </tr>
            `);
            index++;
        }
    }
    
    $('#subtotal').text(total.toFixed(2));
    $('#grandTotal').text(total.toFixed(2));
}

function addToCart(id, name, price) {
    if(cart[id]) {
        cart[id].quantity += 1;
    } else {
        cart[id] = { name: name, price: price, quantity: 1 };
    }
    total += price;
    updateCartUI();
    
    // Play subtle sound for feedback
    toastr.success(name + ' added to cart');
}

function removeFromCart(id) {
    if(cart[id]) {
        total -= (cart[id].price * cart[id].quantity);
        delete cart[id];
        updateCartUI();
    }
}

$(document).ready(function() {
    if($('.select2').length) { $('.select2').select2(); }
});
</script>
@endpush
