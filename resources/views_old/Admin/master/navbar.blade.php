<div class="navbar-custom-menu">
	<?php
	$users = Auth::user();
	?>
	<ul class="nav navbar-nav">
		<li class="dropdown messages-menu">
			<a href="{{ route('dashboardAdmin') }}" target='_blank'>
				<i class="fa fa-road"></i>
				Lihat Web
				<!-- <span class="label label-success">4</span> -->
			</a>
		</li>
		<!-- User Account: style can be found in dropdown.less -->
		<li class="dropdown user user-menu">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				<!-- <img src="{!! url('AssetsAdmin/dist/img/user2-160x160.jpg') !!}" class="user-image" alt="User Image"> -->
				<i class="fa fa-user"></i>
				<span class="hidden-xs">User Admin</span>
				<i class='fa fa-caret-down' style='padding-left:5px'></i>
			</a>
			<ul class="dropdown-menu">
				<!-- User image -->
				<li class="user-header">
					<img src="{{url('uploads/users/'.$users->photo_user)}}" class="img-circle" alt="User Image">
					<p>
						User Admin
					</p>
				</li>
				<!-- Menu Footer-->
				<li class="user-footer">
					<!-- <div class="pull-left">
						<a href="{{route('profileAdmin')}}" class="btn btn-default btn-flat">Profile</a>
					</div> -->
					<div class="pull-right">
						<a href="{{url('logout')}}" class="btn btn-default btn-flat">Sign out</a>
					</div>
				</li>
			</ul>
		</li>
	</ul>
</div>