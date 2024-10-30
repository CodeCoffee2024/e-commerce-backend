<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        /* Fallback CSS for older email clients */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display:flex;
            width: 100%;
        }
        table {
            width: 100%;
            border-spacing: 0;
        }
        td {
            padding: 10px;
        }
        .title {
            background-color: #ed8a11;
            color: white;
            padding: 10px;
        }
        .bg-primary-highlight-color {
            background-color: #ed8a11;
        }
        .color-primary-color {
            color: #ed8a11;
        }
        .font-md {
            font-size: 15px;
        }
        .font-lg {
            font-size: 20px;
        }
        .mt-2 {
            margin-top: 1rem;
        }
        .mt-1 {
            margin-top: .5rem;
        }
        .pl-2 {
            padding-left: 15px;
        }
        .ml-2 {
            margin-left: 15px;
        }
        .pr-1 {
            padding-right: 5px;
        }
        .w-0 {
            width: 0px;
        }
        .pr-2 {
            padding-right: 15px;
        }
        .pt-4 {
            padding-top: 25px;
        }
        table {
            width: 100%;
        }
        .d-flex {
            display: flex;
        }
        .align-items-start {
            align-items: start;
        }
        .text-align-right {
            text-align: right;
        }
    </style>
</head>
<body style="background-color: #f4f4f4; margin: 0; padding: 0;">
    <div style="width: 100%; background-color: #f4f4f4; padding: 20px;">
        <div class="title">Thank you for your order!</div>
        <div class="font-md mt-2 pl-2"><strong>Reference #:</strong> {{ $order['reference_number'] }}</div>
        <div class="font-md mt-1 pl-2"><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($order['created_at'])->format('F d, Y') }}</div>
        <div class="font-lg mt-1 pl-2"><strong>Order(s)</strong></div>
        <table class="table">
            <tr>
                <th></th>
                <th class="text-align-right">Qty</th>
                <th class="text-align-right pr-2">Price</th>
            </tr> 
            @foreach ($items as $item)
                <tr>
                    <td class="d-flex align-items-start">
                        <img src="https://media.istockphoto.com/id/182177931/photo/picture-frame-isolated-on-white.jpg?s=612x612&w=0&k=20&c=xJDz9mhFhEccRSnaYZCx6-HnP1LwIk3G6oyMW7LAF8E=" width="70" height="70">
                        <div>
                            <div>{{ $item->product->description }}</div>
                            <div><strong>Merchant:</strong>{{ $item->product->merchant->name }}</div>
                        </div>
                    </td>
                    <td class="text-align-right">{{ $item->quantity }}</td>
                    <td class="text-align-right">{{ number_format($item->price, 2) }}</td>
                </tr>
            @endforeach
            <tfoot>
                <tr>
                    <td colspan="2">
                        <strong>Total</strong>
                    </td>
                    <td>
                        <div>
                            <div class="text-align-right"><strong>Subtotal: </strong>{{ number_format($subTotal, 2) }}</div>
                            <div class="text-align-right"><strong>Shipping Fee: </strong>{{ number_format($order['totalShipping'], 2) }}</div>
                            <div class="text-align-right">
                                <strong class="font-md color-primary-color">Grand Total: </strong> <span class="color-primary-color font-md">{{ number_format($order['totalShipping'] + $subTotal, 2) }}</span>
                            </div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="font-lg mt-1 pl-2"><strong>Order Status</strong></div>
        <div class="font-md mt-1 pl-2"><strong>Order has been placed</strong></div>
        <div class="font-md mt-1 pl-2 ">{{\Carbon\Carbon::parse($order['created_at'])->format('F d, Y h:mm A')}}</div>
        
        @foreach ($logs as $log)
            <div class="pt-4 bg-primary-highlight-color pr-1 ml-2 w-0"></div>
            <div class="font-md mt-1 pl-2 color-primary-color"><strong>{{$statusDescription[$log['status']]}}</strong></div>
            <div class="font-md mt-1 pl-2 ">{{\Carbon\Carbon::parse($log['created_at'])->format('F d, Y h:m A')}}</div>
        @endforeach
    </div>
</body>
</html>