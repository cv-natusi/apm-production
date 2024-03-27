<!DOCTYPE html>
<html>
<head>
	<title>Cetak {{$customer->NamaCust}}</title>
</head>
<style type="text/css">
/*@media print 
{
   	@page
   	{
	    size: 7.66cm 4.41cm ;
	    size: landscape;
  	}
}*/
@media print 
{
   	@page
   	{
	    size: 9.3cm 6cm ;
	    size: landscape;
  	}
}
</style>
<body>
	<!-- <div style="width: 8.66cm;height: 5.41cm;border:solid 1px black;"> -->
	<!-- <div style="width: 9cm;height: 6cm;border:solid 1px black;"> -->
	<div style="width: 9.3cm;height: 6cm;">
		<div style="padding: 0 15px;">
			<table style="width: 100%" border="0">
				<tr>
					<td width="60px" rowspan="2" align="center" style="padding-top: 5px;"><img src="{{ url('uploads/logo rsud.png') }}" width="80%"></td>
					<td width="250px" align="center" valign="bottom" style="font-weight: bold;font-size: 16px;">RSU Dr. Wahidin Sudiro Husodo</td>
				</tr>
				<tr>
					<td align="center" valign="top">Kota Mojokerto</td>
				</tr>
			</table>
		</div>
		<div style="width: 100%;padding: 0 5%;">
			<div style="border-bottom: solid 2px #474443;width: 90%"></div>
		</div>
		<div style="padding: 5px 15px;">
			<table>
				<tr>
					<td width="90" valign="top">No. RM</td>
					<td align="center" valign="top">:</td>
					<td valign="top">{{$customer->KodeCust}}</td>
				</tr>
				<tr>
					<td width="90" valign="top">Nama</td>
					<td align="center" valign="top">:</td>
					<!-- <td valign="top">{{$customer->NamaCust}}</td> -->
					<td valign="top">{{ ucwords(strtolower($customer->NamaCust)) }}</td>
				</tr>
				<tr>
					<td width="90" valign="top">Alamat</td>
					<td align="center" valign="top">:</td>
					<!-- <td valign="top">{{ ucwords($customer->Alamat) }}</td> -->
					<td valign="top">{{ ucwords(strtolower($customer->Alamat)) }}</td>
				</tr>
				<tr>
					<td width="90" valign="top">Tanggal Lahir</td>
					<td align="center" valign="top">:</td>
					<td valign="top">{{ date('d-m-Y', strtotime($customer->TglLahir)) }}</td>
				</tr>
			</table>
		</div>
	</div>
</body>
<script type="text/javascript">
	window.print();
</script>
</html>