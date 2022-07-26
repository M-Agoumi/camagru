@layout('mail')
@section('title')Email Verification@endsection
@section('content')
	<?php use Simfa\Framework\Application;?>
    <a style="text-decoration: none; color: chartreuse;"
       href="http://<?= Application::$ENV['APP_URL']; ?>:<?=$port ?? ''?><?=route('confirm-email', $token ?? '')?>">
        <div style="background: darkgray; width: 200px; text-align: center; margin: auto; padding: 15px 20px; border-radius: 5px">
            Confirm Your Email
        </div>
    </a>
@endsection
