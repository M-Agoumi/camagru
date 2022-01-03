@layout('main')
@section('title')One last question@endsection
@section('content')
    <h1>You have logged out</h1>
    <p>
        if you are in your personnel device save your password so you won't have to enter it the next time you need to
        login,
        don't worry we will save it for you :D
    </p>
    <a href="<?=route('app.logout.save')?>">Save</a></br><a href="/">No</a>
@endsection
