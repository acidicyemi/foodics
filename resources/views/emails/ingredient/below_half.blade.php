@component('mail::message')
# Introduction


@foreach ($ingredients as $ingredient)
    ingredient {{ $ingredient->name }} is less than 50% of its initial value 
@endforeach

Thanks,<br>
{{ config('app.name') }}
@endcomponent
