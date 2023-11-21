@component('mail::layout')
    {{-- Header --}}
     
            @slot('header')
                @component('mail::header', ['url' => config('app.url')])
                    <img src="{{ \Storage::url('app/default/logo.png') . getPictureVersion() }}" style="height:auto; max-width: 226px;" alt="{{ config('app.name') }}">
                @endcomponent
            @endslot

            {{-- Body --}}
            {{ $slot }}

            {{-- Subcopy --}}
            @isset($subcopy)
                @slot('subcopy')
                    @component('mail::subcopy')
                        {{ $subcopy }}
                    @endcomponent
                @endslot
            @endisset

            {{-- Footer --}}
            @slot('footer')
                @component('mail::footer')
                {{-- <img style="max-width:100%; margin-top:20px;" src="{{ \Storage::url('app/default/temp-f.jpg') . getPictureVersion() }}" alt="{{ config('app.name') }}"> --}}
                    © {{ date('Y') }} {{ config('app.name') }}. @lang('mail.All rights reserved.')
                @endcomponent
            @endslot
 
@endcomponent
