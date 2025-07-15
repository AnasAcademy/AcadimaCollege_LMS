@extends('web.default.layouts.email')

@section('body')
    <td class="social-title pb30"
        style="color:#ffffff; font-family: 'IBM Plex Sans', sans-serif; font-size:14px; line-height:22px; text-align:left; padding-bottom:30px;">
        <div mc:edit="text_33" style="color: #333; direction: ltr !important;">
            <p style="font-family: cairo, sans-serif; text-align: left;">
               Hi {{ $notification['user'] }}
            </p>
            <br><br>
            <p style="font-family: cairo, sans-serif; text-align: left;">
                {{ $notification['title'] }}
            </p>
            @dd($notification)
            <p style="font-family: cairo, sans-serif; direction: ltr !important; text-align: left;">
                {!! $notification['message'] !!}
            </p>
        </div>
    </td>
@endsection
