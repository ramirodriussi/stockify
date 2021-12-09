@component('mail::message')
Cierre de Caja Diario | @php echo date('d-m-Y'); @endphp

<!-- <table style="width:100%">
  <tr>
    <th style="text-align:left"># Venta</th>
    <th style="text-align:left">Total</th>
  </tr>
  <tr>
    <td>00000001212121</td>
    <td>$ 5000</td>
  </tr>
  <tr>
    <td>00000001212121</td>
    <td>$ 8500</td>
  </tr>
</table> -->

<style>

    th {
        text-align: left;
    }

    .total-tr {
        line-height: 25px
    }

    .total-tr td {
        border-top: 1px solid #EDF2F7;
    }

</style>

<table style="width:100%">
  <tr>
    <th>Local</th>
    <th>Total</th>
  </tr>
  
@foreach($data['sales'] as $item)
<tr>
<td>{{ $item['store'] }}</td>
<td>$ {{ number_format($item['total'], 2,',','.') }}</td>
</tr>
@endforeach

  <tr class="total-tr">
    <td></td>
    <td style="font-weight:bold">$ {{ number_format($data['total'], 2,',','.') }}</td>
  </tr>

</table>

@component('mail::button', ['url' => env('SPA_URL')])
Ir al panel
@endcomponent

<!-- Thanks,<br>
{{ config('app.name') }} -->
@endcomponent
