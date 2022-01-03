@layout('mail')
@section('title')Reset Your Password @endsection
@section('content')
    <div>
        Someone just asked us to rest your password, if it's you please follow the next link
        <a style="text-decoration: none; color: chartreuse;" href='http://localhost:<?=$port?>/verify-token/<?=$token?>'>
            <div style="background: darkgray; width: 200px; text-align: center; margin: auto; padding: 15px 20px; border-radius: 5px">
                reset your password
            </div>
        </a>
        if it's not you, just ignore this email, or maybe click it to change your password to be secure :D
    </div>
@endsection
