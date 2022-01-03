@layout('main')
@section('title')<?=$title ?? ''?>@endsection
@section('content')
    <?php /** @var $e Exception */ ?>
    <h1><?php echo $e->getCode() ?: ''; ?></h1>
	<h3 class="center"><?=$e->getMessage()?></h3>
    <pre>
        <?=$e->getTraceAsString()?>
	</pre>

@endsection
