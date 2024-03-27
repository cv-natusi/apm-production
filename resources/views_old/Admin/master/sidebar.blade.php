<section class="sidebar">
	<!-- Sidebar user panel -->
	<!-- <hr>> -->
	<!-- sidebar menu: : style can be found in sidebar.less -->
	<ul class="sidebar-menu">
		<li class='active'>
			<a href="{{ route('dashboardAdmin') }}">
				<i class="fa fa-dashboard"></i> <span>Dashboard</span></i>
			</a>
		</li>
		<li class=''>
			<a href="{{ route('apm') }}">
				<i class="fa fa-desktop"></i> <span>APM</span></i>
			</a>
		</li>
		<li class=''>
			<a href="{{ route('bridging') }}">
				<i class="	fa fa-asterisk"></i> <span>Bridging</span></i>
			</a>
		</li>
		<li class=''>
			<a href="{{ route('reg') }}">
				<i class="	fa fa-list"></i> <span>Data Register</span></i>
			</a>
		</li>
		<li class=''>
			<a href="{{ route('rfid') }}">
				<i class="	fa fa-credit-card"></i> <span>Data RFID</span></i>
			</a>
		</li>
		<li class='treeview'>
			<a href="#"><i class="fa fa-globe"></i><span>Modul Web</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
				<!-- <li class=''>
					<a href="{{ route('users') }}">
						<i class="fa fa-users"></i> <span>Users</span></i>
					</a>
				</li> -->
				<li class=''>
					<a href="{{ route('KotakSaran') }}">
						<i class="fa fa-comments-o"></i> <span>Kotak Saran</span></i>
					</a>
				</li>
				<li class=''>
					<a href="{{ route('identitas') }}">
						<svg aria-hidden="true" data-prefix="fas" data-icon="address-card" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-address-card fa-w-16" style="width: 16px;margin-right: 5px;"><path fill="currentColor" d="M464 64H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V112c0-26.51-21.49-48-48-48zm-288 80c38.66 0 70 31.34 70 70s-31.34 70-70 70-70-31.34-70-70 31.34-70 70-70zm112 203c0 11.598-9.402 21-21 21H85c-11.598 0-21-9.402-21-21v-16.207c0-19.272 13.116-36.072 31.813-40.746l31.2-7.8c25.464 18.316 65.195 23.577 97.974 0l31.2 7.8C274.884 294.721 288 311.52 288 330.793V347zm160-39c0 6.627-5.373 12-12 12H332c-6.627 0-12-5.373-12-12v-8c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v8zm0-64c0 6.627-5.373 12-12 12H332c-6.627 0-12-5.373-12-12v-8c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v8zm0-64c0 6.627-5.373 12-12 12H332c-6.627 0-12-5.373-12-12v-8c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v8z" class=""></path></svg>
						<span>Identitas</span></i>
					</a>
				</li>
				<li class=''>
					<a href="{{ route('InfoPenyakit') }}">
						<i class="fa fa-question-circle"></i> <span>Informasi Penyakit</span></i>
					</a>
				</li>
				<li class=''>
					<a href="{{ route('PolaHidup') }}">
						<i class="fa fa-thumbs-o-up"></i> <span>Pola Hidup Sehat</span></i>
					</a>
				</li>
				<li class=''>
					<a href="{{ route('Device') }}">
						<i class="fa fa-mobile"></i> <span>Device</span></i>
					</a>
				</li>
				<li class=''>
					<a href="{{ route('holiday') }}">
						<i class="fa fa-calendar"></i> <span>Tanggal Libur</span></i>
					</a>
				</li>
				<li class=''>
					<a href="{{ route('bantuan') }}">
						<i class="fa fa-list"></i> <span>Data Bantuan</span></i>
					</a>
				</li>
			</ul>
		</li>
	</ul>
</section>