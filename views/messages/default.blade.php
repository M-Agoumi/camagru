@layout('main')
@section('title')default page@endsection
@section('content')
    <h1>Ops this is an empty page you didn't return anything besides <?=$value ? 'true' : 'false'?> :c</h1>
@endsection
