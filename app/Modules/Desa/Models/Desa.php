<?php

namespace App\Modules\Desa\Models;

use App\Helpers\UsesUuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Kecamatan\Models\Kecamatan;


class Desa extends Model
{
	use SoftDeletes;
	use UsesUuid;

	protected $casts      = ['deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
	protected $table      = 'desa';
	protected $fillable   = ['*'];	

	public function kecamatan(){
		return $this->belongsTo(Kecamatan::class,"id_kecamatan","id");
	}

}
