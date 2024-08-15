<section class="sidebar">
	<ul class="sidebar-menu">
		<?php
			$lvl     = Auth::user()->lv_user;
			$ca      = $lvl=='countera';
			$cb      = $lvl=='counterb';
			$cc1     = $lvl=='counterc1';
			$cc2     = $lvl=='counterc2';
			$cc      = $lvl=='counterc';
			$cd      = $lvl=='counterd';
			$ce      = $lvl=='countere';
			$chd     = $lvl=='counterhd';
			$admin   = $lvl=='admin';
			$loket1  = $lvl=='loket1';
			$loket2  = $lvl=='loket2';
			$loket3  = $lvl=='loket3';
			$loket4  = $lvl=='loket4';
			$loket5  = $lvl=='loket5';
			$operasi = $lvl=='operasi';
			$fill    = $lvl=='filling';
			$pvclaim    = $lvl=='petugasvclaim';
		?>
		@if($admin)
			<li class='active'>
				<a href="{{ route('dashboardAdmin') }}">
					<i class="fa fa-dashboard"></i> <span>Dashboard</span></i>
				</a>
			</li>
			{{-- <li class=''>
				<a href="{{ route('pengaturan.jadwalPoli.main') }}">
					<i class="fa fa-desktop"></i> <span>Tanggal Libur</span></i>
				</a>
			</li> --}}
			<li class='treeview'>
				<a href="#"><i class="fa fa-globe"></i><span>SIMAPAN</span><i class="fa fa-angle-left pull-right"></i></a>
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
					{{-- <li class=''>
						<a href="{{ route('holiday') }}">
							<i class="fa fa-calendar"></i> <span>Tanggal Libur</span></i>
						</a>
					</li> --}}
					<li class=''>
						<a href="{{ route('bantuan') }}">
							<i class="fa fa-list"></i> <span>Data Bantuan</span></i>
						</a>
					</li>
				</ul>
			</li>
			<li class=''>
				<a href="{{ route('apm') }}">
					<i class="fa fa-desktop"></i> <span>Registrasi</span></i>
				</a>
			</li>
			<li class=''>
				<a href="{{ route('mainRataPelayanan') }}">
					<i class="fa fa-line-chart" aria-hidden="true"></i> <span>Rata Rata Pelayanan</span>
				</a>
			</li>
			<li class=''>
				<a href="{{ route('mainGrafikPelayanan') }}">
					<i class="fa fa-bar-chart" aria-hidden="true"></i> <span>Grafik Pelayanan</span>
				</a>
			</li>
		@endif

		@if($admin || $loket1 || $loket2 || $loket3 || $loket4 || $loket5)
			<li class=''>
				<a href="{{ route('antreanPanggil') }}">
					<i class="fa fa-bullhorn" aria-hidden="true"></i> <span>Pemanggilan Antrian</span>
				</a>
			</li>
			<li class='treeview'>
				<a href="#"><i class="fa fa-list"></i><span>Daftar Antrian Loket</span><i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">
					<li class=''>
						<a href="{{ route('loket.listAntrian') }}">
							<span><i class="fa fa-male"></i> Daftar Antrian </span>
						</a>
					</li>
					<li class=''>
						<a href="{{ route('displayLoket') }}" target="_blank">
							<span><i class="fa fa-desktop"></i> Display Loket</span>
						</a>
					</li>
				</ul>
			</li>
			<li class=''>
				<a href="{{ route('riwayatAntrian') }}">
					<i class="fa fa-history" aria-hidden="true"></i></i> <span>Riwayat Antrian</span>
				</a>
			</li>
		@endif

		@if($admin || $ca || $cb || $cc1 || $cc2 || $cc || $cd || $ce || $chd)
			<li class=''>
				<a href="{{ route('list-antrian-online') }}">
					<span><i class="fa fa-list"></i> Daftar Cetak SEP</span>
				</a>
			</li>
			<li class="treeview">
				<a href="javascript:void(0)">
					<i class="fa fa-list"></i><span>Konter Poli</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li class=''>
						<a href="{{route('counter.formListCounter')}}">
							<i class="fa fa-list" aria-hidden="true"></i> <span>Daftar Antrian</span>
						</a>
					</li>
					@if($admin || $ca)
					<li class=''>
						<a href="{{route('konterA')}}" target="_blank">
							<i class="fa fa-desktop" aria-hidden="true"></i> <span>Display Konter A</span>
						</a>
					</li>
					@endif

					@if($admin || $cb)
					<li class=''>
						<a href="{{route('konterB')}}" target="_blank">
							<i class="fa fa-desktop" aria-hidden="true"></i> <span>Display Konter B</span>
						</a>
					</li>
					@endif

					@if($admin || $cc1 || $cc)
					<li class=''>
						<a href="{{route('konterC1')}}" target="_blank">
							<i class="fa fa-desktop" aria-hidden="true"></i> <span>Display Konter C1</span>
						</a>
					</li>
					@endif

					@if($admin || $cc2 || $cc)
					<li class=''>
						<a href="{{route('konterC2')}}" target="_blank">
							<i class="fa fa-desktop" aria-hidden="true"></i> <span>Display Konter C2</span>
						</a>
					</li>
					@endif

					@if($admin || $cd)
					<li class=''>
						<a href="{{route('konterD')}}" target="_blank">
							<i class="fa fa-desktop" aria-hidden="true"></i> <span>Display Konter D</span>
						</a>
					</li>
					@endif

					@if($admin || $ce)
					<li class=''>
						<a href="{{route('konterE')}}" target="_blank">
							<i class="fa fa-desktop" aria-hidden="true"></i> <span>Display Konter E</span>
						</a>
					</li>
					@endif
				</ul>
			</li>
			<li class=''>
				<a href="{{ route('riwayatAntrianKonter') }}">
					<i class="fa fa-history" aria-hidden="true"></i></i> <span>Riwayat Antrian Konter Poli</span>
				</a>
			</li>
		@endif

		@if($admin||$loket1||$loket2||$loket3||$loket4||$loket5||$ca||$cb||$cc1||$cc2||$cc||$cd||$ce||$chd||$pvclaim)
			<li class='treeview'>
				<a href="#"><i class="fa fa-book"></i><span>Modul Vclaim</span><i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">
					<li class='treeview'>
						<!-- <a href="#"><i class="fa fa-asterisk"></i><span>SEP</span><i class="fa fa-angle-left pull-right"></i></a>
						<ul class="treeview-menu"> -->
							<li>
								<a href="{{ route('bridging') }}">
									<i class="fa fa-plus"></i> <span>Pembuatan SEP</span></i>
								</a>
							</li>
							@if($admin||$loket1||$loket2||$loket3||$loket4||$loket5)
							<li>
								<a href="{{ route('persetujuanSEP') }}">
									<i class="fa fa-thumbs-o-up"></i> <span>Persetujuan SEP</span></i>
								</a>
							</li>
							@endif
						<!-- </ul> -->
					</li>
					<li class='treeview'>
						<!-- <a href="#"><i class="fa fa-medkit"></i><span>Rujukan</span><i class="fa fa-angle-left pull-right"></i></a>
						<ul class="treeview-menu"> -->
							<li>
								<a href="{{ route('rujukan') }}">
									<i class="fa fa-circle-o"></i> <span>Form Rujukan</span></i>
								</a>
							</li>
							<li>
								<a href="{{ route('rujukan-khusus') }}">
									<i class="fa fa-circle-o"></i> <span>Rujukan Khusus</span></i>
								</a>
							</li>
						<!-- </ul> -->
					</li>
					<li class=''>
						<a href="{{ route('rencana-kontrol') }}">
							<i class="fa fa-clock-o"></i> <span>Kunjungan Kontrol / Rawat Inap</span></i>
						</a>
					</li>
					<li class=''>
						<a href="{{ route('rujuk-balik') }}">
							<i class="fa fa-send"></i> <span>Rujuk Balik (PRB)</span></i>
						</a>
					</li>
				</ul>
			</li>
		@endif

		@if($admin)
			<li class=''>
				<a href="{{ route('reg') }}">
					<i class="	fa fa-list"></i> <span>Data Register Kunjungan</span></i>
				</a>
			</li>
			<li class=''>
				<a href="{{ route('profilPasien') }}">
					<i class="	fa fa-list"></i> <span>Data Profil Pasien</span></i>
				</a>
			</li>
			<li class=''>
				<a href="{{ route('rfid') }}">
					<i class="	fa fa-credit-card"></i> <span>Data RFID</span></i>
				</a>
			</li>
			<li class=''>
				<a href="{{ route('EstimasiPelayanan') }}">
					<i class="fa fa-calendar"></i> <span>Estimasi Pelayanan</span></i>
				</a>
			</li>
			<li class=''>
				<a href="{{ route('masterpoli') }}">
					<i class="fa fa-bars" aria-hidden="true"></i> <span>Master Poli</span>
				</a>
			</li>
			<li class=''>
				<a href="{{ route('mstkonterpoli') }}">
					<i class="fa fa-bars" aria-hidden="true"></i> <span>Master Konter Poli</span>
				</a>
			</li>
		@endif

		@if($admin || $operasi)
			<li class='treeview'>
				<a href="#"><i class="fa fa-ticket"></i><span>Antrean Operasi</span><i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">
					@if($admin)
						<li class=''>
							<a href="{{ route('webRefJadDok') }}">
								<i class="fa fa-calendar"></i><span>Referensi Jadwal Dokter</span></i>
							</a>
						</li>
						<li class=''>
							<a href="{{ route('editJadDok') }}">
								<i class="fa fa-calendar"></i><span>Update Jadwal Dokter</span></i>
							</a>
						</li>
					@endif
					
					<li class=''>
						<a href="{{ route('operasi') }}">
							<i class="fa fa-calendar"></i><span>Jadwal Operasi</span></i>
						</a>
					</li>
				</ul>
			</li>
		@endif

		@if($admin || $fill)
			<li class=''>
				<a href="{{ route('dashboardFilling') }}">
					<i class="fa fa-dashboard"></i><span>Dashboard</span></i>
				</a>
			</li>
			<li class=''>
				<a href="{{ route('mainFilling') }}">
					<i class="fa fa-book"></i><span>Filling Berkas</span></i>
				</a>
			</li>
		@endif
	</ul>
</section>
