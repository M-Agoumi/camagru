@layout('mail')
@section('title')Someone Commented On Your Post@endsection
@section('content')
		<div style="background: darkgray; width: 200px; text-align: center; margin: auto; padding: 15px 20px; border-radius: 5px">
			{{ name }} commented on your post
			<a style="text-decoration: none; color: chartreuse;"
			   href='<?=$postUrl?>'>
				go to post
			</a>
		</div>
@endsection
