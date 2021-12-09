@component('mail::message')
El producto {{ $product['product'] }} (código: {{ $product['code'] }}) tiene un stock restante de {{ $product['stock'] }} unidades.

@component('mail::button', ['url' => env('SPA_URL')])
Ir al panel
@endcomponent

<!-- Thanks,<br>
{{ config('app.name') }} -->
@endcomponent
