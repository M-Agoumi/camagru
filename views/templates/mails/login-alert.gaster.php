@layout('mail')
@section('title')Someone Commented On Your Post@endsection
@section('content')
		<div style="background: darkgray; width: 200px; text-align: center; margin: auto; padding: 15px 20px; border-radius: 5px">
			Hello {{ name }}, someone just signed in to your account ({{ time }}), if it was you,
			just ignore this email, if it was someone else, change your password now
			<a style="text-decoration: none; color: chartreuse;"
			   href={{link}}>
				change password
			</a> or
			<a style="text-decoration: none; color: chartreuse;" href="http://localhost/contactus">
				contact us
			</a>
		</div>
@endsection
