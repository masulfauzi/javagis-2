<?php

namespace App\Modules\PenggunaanLahan\Models;

use App\Helpers\UsesUuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Jenislahan\Models\Jenislahan;
use App\Modules\Desa\Models\Desa;


class PenggunaanLahan extends Model
{
	use SoftDeletes;
	use UsesUuid;

	protected $casts      = ['deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
	protected $table      = 'penggunaan_lahan';
	protected $fillable   = ['*'];	

	public function jenislahan(){
		return $this->belongsTo(Jenislahan::class,"id_jenislahan","id");
	}
public function desa(){
		return $this->belongsTo(Desa::class,"id_desa","id");
	}

}
