@component('mail::message')
# Introduction

The body of your message.

@foreach($data['sales'] as $sale)

    {{$sale['sale']}} - $ {{ number_format($sale['total'], 2, ',', '.') }}

@endforeach

Total diario: $ {{number_format($data['total'], 2, ',', '.')}}

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
