@layout('main')
@section('title')we on baby@endsection
@section('content')
    <h1>Hello World Baby</h1>
    {{ test }}
    @if($test == 1)
        yes
    @elseif($test == 2)
        no
    @else
        ops
    @endif
@endsection
