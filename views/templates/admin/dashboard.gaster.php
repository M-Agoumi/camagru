@layout('admin')
@section('title')Dashboard@endsection
@section('main')
<div class="row">
	<div class="col-lg-3 col-sm-6">
		<div class="card-box bg-blue">
			<div class="inner">
				<h3> {{totalUsers}} </h3>
				<p>Total Users (all time)</p>
			</div>
			<div class="icon">
				<i class="fa fa-graduation-cap" aria-hidden="true"></i>
			</div>
			<a href="/dashboard/users" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>

	<div class="col-lg-3 col-sm-6">
		<div class="card-box bg-green">
			<div class="inner">
				<h3> {{newUsers}} </h3>
				<p>New Users (48hr)</p>
			</div>
			<div class="icon">
				<i class="fa fa-money" aria-hidden="true"></i>
			</div>
			<a href="/dashboard/users" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>
	<div class="col-lg-3 col-sm-6">
		<div class="card-box bg-orange">
			<div class="inner">
				<h3> {{totalPosts}} </h3>
				<p> Total Posts (all time) </p>
			</div>
			<div class="icon">
				<i class="fa fa-user-plus" aria-hidden="true"></i>
			</div>
			<a href="/dashboard/posts" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>
	<div class="col-lg-3 col-sm-6">
		<div class="card-box bg-red">
			<div class="inner">
				<h3>{{newPosts}}</h3>
				<p>New Posts (48hr)</p>
			</div>
			<div class="icon">
				<i class="fa fa-users"></i>
			</div>
			<a href="/dashboard/posts" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>
</div>
@endsection
