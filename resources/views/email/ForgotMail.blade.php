<p>Dear {{ $params['name'] }},</p>

<p>We received a request to reset the password for your account associated with this email address. If you made this request, please click the link below to reset your password:
</p>

<p><a href="{{ $params['url']}}/resetpassword?email={{ $params['email'] }}"><b> Reset Password </b></a></p>
<p>If you did not request a password reset, please ignore this email or contact our support team with any concerns.
</p>
<p>For security reasons, the password reset link will expire in 24 hours. If the link has expired, you can request a new one by visiting our login page.
</p>
<p>Thanks & Regards, <br>
Bharat Club
</p>