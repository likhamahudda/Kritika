<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Invoice#{{$data->invoice_id}}</title>
    <style type="text/css">
      @font-face {
  font-family: SourceSansPro;
  src: url(SourceSansPro-Regular.ttf);
}

.clearfix:after {
  content: "";
  display: table;
  clear: both;
}

a {
  color: #0087C3;
  text-decoration: none;
}

body {
  position: relative;
  width: 21cm;  
  height: 29.7cm; 
  margin: 0 auto; 
  color: #555555;
  background: #FFFFFF; 
  font-family: Arial, sans-serif; 
  font-size: 14px; 
  font-family: SourceSansPro;
}

header {
  padding: 10px 0;
  margin-bottom: 20px;
  border-bottom: 1px solid #AAAAAA;
}

#logo {
  float: left;
  margin-top: 8px;
}

#logo img {
  height: 70px;
}

#company {
  float: right;
  text-align: right;
}


#details {
  margin-bottom: 50px;
}

#client {
  padding-left: 6px;
  border-left: 6px solid #0087C3;
  float: left;
}

#client .to {
  color: #777777;
}

h2.name {
  font-size: 1.4em;
  font-weight: normal;
  margin: 0;
}

#invoice {
  float: right;
  text-align: right;
}

#invoice h1 {
  color: #0087C3;
  font-size: 2.4em;
  line-height: 1em;
  font-weight: normal;
  margin: 0  0 10px 0;
}

#invoice .date {
  font-size: 1.1em;
  color: #777777;
}

table {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
  margin-bottom: 20px;
}

table th,
table td {
  padding: 20px;
  background: #EEEEEE;
  text-align: center;
  border-bottom: 1px solid #FFFFFF;
}

table th {
  white-space: nowrap;        
  font-weight: normal;
}

table td {
  text-align: right;
}

table td h3{
  color: #57B223;
  font-size: 1.2em;
  font-weight: normal;
  margin: 0 0 0.2em 0;
}

table .no {
  color: #FFFFFF;
  font-size: 1.6em;
  background: #57B223;
}

table .desc {
  text-align: left;
}

table .unit {
  background: #DDDDDD;
}

table .qty {
}

table .total {
  background: #57B223;
  color: #FFFFFF;
}

table td.unit,
table td.qty,
table td.total {
  font-size: 1.2em;
}

table tbody tr:last-child td {
  border: none;
}

table tfoot td {
  padding: 10px 20px;
  background: #FFFFFF;
  border-bottom: none;
  font-size: 1.2em;
  white-space: nowrap; 
  border-top: 1px solid #AAAAAA; 
}

table tfoot tr:first-child td {
  border-top: none; 
}

table tfoot tr:last-child td {
  color: #57B223;
  font-size: 1.4em;
  border-top: 1px solid #57B223; 

}

table tfoot tr td:first-child {
  border: none;
}

#thanks{
  font-size: 2em;
  margin-bottom: 50px;
}

#notices{
  padding-left: 6px;
  border-left: 6px solid #0087C3;  
}

#notices .notice {
  font-size: 1.2em;
}

footer {
  color: #777777;
  width: 100%;
  height: 30px;
  position: absolute;
  bottom: 0;
  border-top: 1px solid #AAAAAA;
  padding: 8px 0;
  text-align: center;
}


    </style>
  </head>
  <body>
    <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td id="logo" align="left">
            <a href="{{Route('admin.index')}}"><img id="site_logo" src="{{getSettingValue('logo') ? url('uploads/logo').'/'.getSettingValue('logo') : URL::asset('images/logo.png')}}" class="logo-icon0" alt="logo icon" width="50" height="50"></a>
          </td>
          <td id="company">
            <h2 class="name">{{getSettingValue('company_name')}}</h2>
            <div>{{getSettingValue('address')}}</div>
            <div>{{getSettingValue('phone_no')}}</div>
            <div><a href="mailto:{{getSettingValue('company_email')}}">{{getSettingValue('company_email')}}</a></div>
          </td>
        </tr>
    </table>
    <main>
      <table border="0" cellspacing="0" cellpadding="0" id="details" class="clearfix">
        <tr>
            <td id="client" align="left">
              <div class="to">INVOICE TO:</div>
              <h2 class="name">{{$data->user_name}}</h2>
              <div class="address">{{$data->user_address}}</div>
              <div class="email"><a href="mailto:{{$data->user_email}}">{{$data->user_email}}</a></div>
            </td>
            <td id="invoice">
              <h3>INVOICE#{{$data->invoice_id}}</h3>
              <div><b>Transaction ID:</b> {{$data->transaction_id}}</div>
              <div class="date"><b>Invoice Date:</b> {{date('j F Y', strtotime($data->created_at))}}</div>
              <div class="date"><b>Plan Expiry Date:</b> {{date('j F Y', strtotime($data->expire_date))}}</div>
            </td>
        </tr>
      </table>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <!-- <th class="no">#</th> -->
            <th class="desc">PLAN DESCRIPTION</th>
            <th class="unit">MONTHLY PRICE</th>
            <th class="qty">DURATION<br>(In Month)</th>
            <th class="total">TOTAL</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <!-- <td class="no">03</td> -->
            <td class="desc">
                <div><b>Subscription Plan:</b> {{$data->plan_name}}</div>
                <div><b>Subscription Type:</b> @if($data->subscription_type==1) Account Plan @elseif($data->subscription_type==2) API Plan @endif</div>
                <div><b>Plan Type:</b> @if($data->subcription_price > 0) Paid @else Free @endif</div>
            </td>
            <td class="unit">{{$currency.$data->subcription_price}}</td>
            <td class="qty">{{$data->subscription_months}}</td>
            <td class="total">{{$currency.$data->subcription_price*$data->subscription_months}}</td>
          </tr>
        </tbody>
        <tfoot>
            <tr>
            <td>COUPON CODE APPLIED</td>
            <td colspan="2">COUPON0</td>
            <td>- {{$currency}}0.00</td>
          </tr>
          <tr>
            <td></td>
            <td colspan="2">GRAND TOTAL</td>
            <td>{{$currency.$data->total_subscription_price}}</td>
          </tr>
        </tfoot>
      </table>
    </main>
    <footer>
      
    </footer>
  </body>
</html>