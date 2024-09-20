<?php

namespace app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;
use App\Http\Models\rsu_poli;

use Illuminate\Http\Request;

use App\Helpers\apm as Help;

class Holidays extends Model
{
	protected $table = 'holidays';
	protected $primaryKey = 'id_holiday';
	public $timestamps = false;
	protected $fillable = [
		'id_holiday',
		'tanggal',
		'keterangan',
		'hari',
		'is_hari',
		'is_active',
		'jam',
		'poli_id',
		'poli_bpjs_id',
		'kuota_kiosk',
		'kuota_wa',
		'kategori',
	];
	protected $append = [
		'hari_temp',
		'tanggal_temp',
		'timestamps',
		'nama_hari',
	];

	/**
	 * Get the poli that owns the Holidays
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function poli()
	{
		return $this->belongsTo(rsu_poli::class, 'poli_id', 'KodePoli');
	}

	### Scope start
	public function scopeWhereDateWhatsapp($query,$request)
	{
		return $query->where(
			fn($q)=>$q->where(
				fn($q) => $q->whereBetween('tanggal',[$request->dt_plus_1, $request->dt_plus_3])->whereNull('hari')
			)->orWhere(
				fn($q) => $q->whereNull('tanggal')->whereIn('hari',$request->array_hari)
			)
		);
		return $query;
	}
	public function scopeWhereDateKiosk($query,$request)
	{
		$date = $request->tanggal_berobat ? date('Y-m-d',strtotime($request->tanggal_berobat)) : date('Y-m-d');
		$request->merge(['date_num' => (int) date('N',strtotime($date))]);
		$query->where(
			fn($q)=>$q
			// ->where(fn($q)=>$q->where('hari',$dayInNum)->whereNull('tanggal'))
			// ->orWhere(fn($q)=>$q->where('tanggal',$dateNow)->whereNull('hari'))
			->where(
				fn($q)=>$q->where('hari',$request->date_num)->whereNull('tanggal')
			)->orWhere(
				fn($q)=>$q->where('tanggal',$date)->whereNull('hari')
			)
		);
		// $query->where(
		// 	fn($q)=>$q->where(
		// 		fn($q) => $q->whereBetween('tanggal',[$request->dt_plus_1, $request->dt_plus_3])->whereNull('hari')
		// 	)->orWhere(
		// 		fn($q) => $q->whereNull('tanggal')->whereIn('hari',$request->array_hari)
		// 	)
		// );
		return $query;
	}
	### Scope end

	### Accessors start
	public function getTanggalTempAttribute($value)
	{
		if ($this->attributes['is_hari'] === 1) {
			$timestamps = Help::numberToTimestamps(
				new Request(['date_number' => $this->attributes['hari']])
			);
			if ($timestamps < strtotime('now')) {
				$timestamps = strtotime(date("Y-m-d",$timestamps)." +1 week");
			}
			return date('Y-m-d', $timestamps);
			// return date(
			// 	'Y-m-d',
			// 	Help::numberToTimestamps(
			// 		new Request(['date_number' => $this->attributes['hari']])
			// 	)
			// );
		}
		return $this->attributes['tanggal'];
	}
	public function getTimestampsAttribute($value)
	{
		if ($this->attributes['is_hari'] === 1) {
			$hari = $this->attributes['hari'];
			$timestamps = Help::numberToTimestamps(
				new Request(['date_number' => $hari])
			);
			if ($timestamps < strtotime('now')) {
				$timestamps = strtotime(date("Y-m-d",$timestamps)." +1 week");
			}
			return $timestamps;
		}
		return strtotime($this->attributes['tanggal']);
	}
	public function getNamaHariAttribute($value)
	{
		$tanggal = $this->attributes['tanggal'];
		$hari = $this->attributes['hari'];

		$num = (int) ($tanggal 
			? date('N', strtotime($tanggal)) 
			: $hari
		);

		return Help::namaHariID(new Request(['nama_hari_en' => $num]));
	}
	public function getHariTempAttribute($value)
	{
		return ($this->attributes['is_hari'] === 0 || $this->attributes['tanggal'])
			? (int) date('N', strtotime($this->attributes['tanggal']))
			: $this->attributes['hari'];
	}
	### Accessors end


	public static function getJson($input)
	{
		$table  = 'holidays';
		$select = 'holidays.*';
		
		$replace_field  = [
			// ['old_name' => 'image', 'new_name' => 'photo_user'],
		];
		
		$param = [
			'input'         => $input->all(),
			'select'        => $select,
			'table'         => $table,
			'replace_field' => $replace_field
		];
		$datagrid = new Datagrid;
		$data = $datagrid->datagrid_query($param, function($data){
			return $data->where('kategori', 'Libur Nasional');
		});
		return $data;
	}

	public static function getJsonKuotaPoli($input)
	{
		$table  = 'holidays';
		$select = '*';
		
		$replace_field  = [
			// ['old_name' => 'image', 'new_name' => 'photo_user'],
		];
		
		$param = [
			'input'         => $input->all(),
			'select'        => $select,
			'table'         => $table,
			'replace_field' => $replace_field
		];
		$datagrid = new Datagrid;
		$data = $datagrid->datagrid_query($param, function($data){
			return $data->leftjoin('tm_poli', 'tm_poli.kode_poli', '=', 'holidays.poli_id')->where('kategori', 'Kuota Poli');
		});
		return $data;
	}

	public static function getJsonLiburPoli($input)
	{
		$table  = 'holidays';
		$select = '*';
		
		$replace_field  = [
			// ['old_name' => 'image', 'new_name' => 'photo_user'],
		];
		
		$param = [
			'input'         => $input->all(),
			'select'        => $select,
			'table'         => $table,
			'replace_field' => $replace_field
		];
		$datagrid = new Datagrid;
		$data = $datagrid->datagrid_query($param, function($data){
			return $data->leftjoin('tm_poli', 'tm_poli.kode_poli', '=', 'holidays.poli_id')->where('kategori', 'Libur Poli');
		});
		return $data;
	}
}