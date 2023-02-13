@component('mail::message')
# Introduction


@foreach ($ingredients as $ingredient)
    ingredient {{ $ingredient->name }} is almost finished 
@endforeach

Thanks,<br>
{{ config('app.name') }}
@endcomponent
