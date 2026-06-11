@extends('backend.master')

@section('title', 'Order Details')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Order #{{ $order->id }}</h3>
    </div>

    <div class="card-body">

        <div class="row mb-4">


            <div class="col-md-3">
                <strong>Total Items:</strong> {{ $order->total_item }}
            </div>

            <div class="col-md-3">
                <strong>Total:</strong> {{ $order->total }}
            </div>

            <div class="col-md-3">
                <strong>Paid:</strong> {{ $order->paid }}
            </div>

            <div class="col-md-3 mt-2">
                <strong>Due:</strong> {{ $order->due }}
            </div>



            <div class="col-md-3 mt-2">
                <strong>Date:</strong>
                {{ $order->created_at->format('Y-m-d h:i A') }}
            </div>
        </div>

        <hr>

        <h5>Products</h5>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->products as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->product->name ?? '-' }}</td>
                            <td>{{ $item->product->sku ?? '-' }}</td>
                            <td>{{ $item->price }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->sub_total }}</td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">Total</th>
                        <th>{{ $order->total }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
</div>
@endsection