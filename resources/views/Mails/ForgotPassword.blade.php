@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{-- logo --}}
            <img src="{{ DataSite('logo') }}" alt="logo" width="250px">
            {{-- header here --}}
        @endcomponent
    @endslot

    {{-- Body --}}
    <p style="font-weight: 700; font-size: 25px;">Xin chào bạn</p>
    <p style="font-weight: 700">Bạn vừa yêu cầu lấy lại mật khẩu tại {{ getDomain() }}.</p>

    @slot('subcopy')
        @component('mail::subcopy')
            <p style="font-weight: 700; color: rgb(160, 60, 60);">Mã xác nhận này sẽ hết hạn sau 5 phút.</p>
            <p style="font-weight: 700">Nếu bạn yêu cầu đặt lại mật khẩu, vui lòng nhấp vào liên kết bên dưới để xác minh.</p>
        @endcomponent

        @component('mail::button', ['url' => $url, 'color' => 'success'])
            Lấy lại mật khẩu
        @endcomponent
    @endslot

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            {{-- footer here --}}
            <p style="font-weight: 700; font-size: 15px; color: rgb(137, 138, 141);">© {{ date('Y') }} {{ getDomain() }}.</p>
        @endcomponent
    @endslot
@endcomponent
