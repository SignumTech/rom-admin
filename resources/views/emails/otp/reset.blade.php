@component('mail::message')
<h3 style="font-size:28px; margin-top:10px; text-align:center">Hello {{$name}}! you requested a password reset.</h3> 

<h4 style="text-align:center; margin-top:50px;">Your OTP is</h4>
<h1 style="font-size:38px; margin-top:10px; text-align:center"><strong>{{$otp}}</strong></h1>


Thanks,<br>
ROM Fashion
@endcomponent
