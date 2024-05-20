<?php

namespace App\Modules\BatasAdministrasi\Models;

use App\Helpers\UsesUuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\TingkatWilayah\Models\TingkatWilayah;


class BatasAdministrasi extends Model
{
	use SoftDeletes;
	use UsesUuid;

	protected $casts      = ['deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
	protected $table      = 'batas_administrasi';
	protected $fillable   = ['*'];	

	public function tingkatWilayah(){
		return $this->belongsTo(TingkatWilayah::class,"id_tingkat_wilayah","id");
	}

}
