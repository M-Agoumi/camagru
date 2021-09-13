@layout('mail')
@section('title')Email Verification@endsection
@section('content')
    <a style="text-decoration: none; color: chartreuse;"
       href='http://localhost:<?=$port ?? ''?>/verify-email/<?=$token ?? ''?>'>
        <div style="background: darkgray; width: 200px; text-align: center; margin: auto; padding: 15px 20px; border-radius: 5px">
            Verify Your Email
        </div>
    </a>
@endsection
