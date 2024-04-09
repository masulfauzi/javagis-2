<?php
namespace App\Modules\PenggunaanLahan\Controllers;

use Form;
use App\Helpers\Logger;
use Illuminate\Http\Request;
use App\Modules\Log\Models\Log;
use App\Modules\PenggunaanLahan\Models\PenggunaanLahan;
use App\Modules\JenisLahan\Models\JenisLahan;
use App\Modules\Desa\Models\Desa;

use App\Http\Controllers\Controller;
use App\Modules\BatasAdministrasi\Models\BatasAdministrasi;
use Illuminate\Support\Facades\Auth;

class PenggunaanLahanController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Penggunaan Lahan";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = PenggunaanLahan::query();
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('PenggunaanLahan::penggunaanlahan', array_merge($data, ['title' => $this->title]));
	}

	public function survey(Request $request)
	{
		$data['jenis_lahan'] = JenisLahan::all();
		$data['batas_administrasi'] = BatasAdministrasi::all();

		return view('PenggunaanLahan::survey', $data);
	}

	public function create_survey(Request $request, $jenis)
	{
		$data['jenis_lahan'] = JenisLahan::all()->pluck('jenis_lahan', 'id')->prepend('-PILIH SALAH SATU-', '');;
		$data['desa'] = Desa::all()->pluck('nama_desa', 'id')->prepend('-PILIH SALAH SATU-', '');;
		return view('PenggunaanLahan::survey_create_polygon', $data);
	}

	public function store_survey(Request $request)
	{
		$penggunaanlahan = new PenggunaanLahan();

		$penggunaanlahan->id_jenislahan = $request->id_jenis_lahan;
		$penggunaanlahan->id_desa = $request->id_desa;
		$penggunaanlahan->nama = $request->nama;
		$penggunaanlahan->luas = $request->luas;
		$penggunaanlahan->koordinat = $request->koordinat;

		$penggunaanlahan->created_by = Auth::id();
        $penggunaanlahan->save();

		return redirect()->back();

	}

	public function create(Request $request)
	{
		$ref_jenis_lahan = JenisLahan::all()->pluck('jenis_lahan','id')->prepend('-PILIH SALAH SATU-', '');
		$ref_desa = Desa::all()->pluck('nama_desa','id')->prepend('-PILIH SALAH SATU-', '');
		
		$data['forms'] = array(
			'nama' => ['Nama', Form::text("nama", old("nama"), ["class" => "form-control","placeholder" => ""]) ],
			'id_jenislahan' => ['Jenislahan', Form::select("id_jenislahan", $ref_jenis_lahan, null, ["class" => "form-control select2"]) ],
			'id_desa' => ['Desa', Form::select("id_desa", $ref_desa, null, ["class" => "form-control select2"]) ],
			'luas' => ['Luas', Form::text("luas", old("luas"), ["class" => "form-control","placeholder" => "dalam meter persegi"]) ],
			'panjang' => ['Panjang', Form::text("panjang", old("panjang"), ["class" => "form-control","placeholder" => "dalam satuan meter"]) ],
			'koordinat' => ['Koordinat', Form::textarea("koordinat", old("koordinat"), ["class" => "form-control","placeholder" => ""]) ],
			
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('PenggunaanLahan::penggunaanlahan_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'id_jenislahan' => 'required',
			'id_desa' => 'required',
			'nama' => 'required',
			// 'luas' => 'required',
			// 'panjang' => 'required',
			'koordinat' => 'required',
			
		]);

		$penggunaanlahan = new PenggunaanLahan();
		$penggunaanlahan->id_jenislahan = $request->input("id_jenislahan");
		$penggunaanlahan->id_desa = $request->input("id_desa");
		$penggunaanlahan->nama = $request->input("nama");
		$penggunaanlahan->luas = $request->input("luas");
		$penggunaanlahan->panjang = $request->input("panjang");
		$penggunaanlahan->koordinat = $request->input("koordinat");
		
		$penggunaanlahan->created_by = Auth::id();
		$penggunaanlahan->save();

		$text = 'membuat '.$this->title; //' baru '.$penggunaanlahan->what;
		$this->log($request, $text, ['penggunaanlahan.id' => $penggunaanlahan->id]);
		return redirect()->route('penggunaanlahan.index')->with('message_success', 'Penggunaan Lahan berhasil ditambahkan!');
	}

	public function show(Request $request, PenggunaanLahan $penggunaanlahan)
	{
		$data['penggunaanlahan'] = $penggunaanlahan;

		$text = 'melihat detail '.$this->title;//.' '.$penggunaanlahan->what;
		$this->log($request, $text, ['penggunaanlahan.id' => $penggunaanlahan->id]);
		return view('PenggunaanLahan::penggunaanlahan_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, PenggunaanLahan $penggunaanlahan)
	{
		$data['penggunaanlahan'] = $penggunaanlahan;

		$ref_jenis_lahan = JenisLahan::all()->pluck('jenis_lahan','id');
		$ref_desa = Desa::all()->pluck('id_kecamatan','id');
		
		$data['forms'] = array(
			'id_jenislahan' => ['Jenislahan', Form::select("id_jenislahan", $ref_jenis_lahan, null, ["class" => "form-control select2"]) ],
			'id_desa' => ['Desa', Form::select("id_desa", $ref_desa, null, ["class" => "form-control select2"]) ],
			'nama' => ['Nama', Form::text("nama", $penggunaanlahan->nama, ["class" => "form-control","placeholder" => "", "id" => "nama"]) ],
			'luas' => ['Luas', Form::text("luas", $penggunaanlahan->luas, ["class" => "form-control","placeholder" => "", "id" => "luas"]) ],
			'panjang' => ['Panjang', Form::text("panjang", $penggunaanlahan->panjang, ["class" => "form-control","placeholder" => "", "id" => "panjang"]) ],
			'koordinat' => ['Koordinat', Form::text("koordinat", $penggunaanlahan->koordinat, ["class" => "form-control","placeholder" => "", "id" => "koordinat"]) ],
			
		);

		$text = 'membuka form edit '.$this->title;//.' '.$penggunaanlahan->what;
		$this->log($request, $text, ['penggunaanlahan.id' => $penggunaanlahan->id]);
		return view('PenggunaanLahan::penggunaanlahan_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'id_jenislahan' => 'required',
			'id_desa' => 'required',
			'nama' => 'required',
			'luas' => 'required',
			'panjang' => 'required',
			'koordinat' => 'required',
			
		]);
		
		$penggunaanlahan = PenggunaanLahan::find($id);
		$penggunaanlahan->id_jenislahan = $request->input("id_jenislahan");
		$penggunaanlahan->id_desa = $request->input("id_desa");
		$penggunaanlahan->nama = $request->input("nama");
		$penggunaanlahan->luas = $request->input("luas");
		$penggunaanlahan->panjang = $request->input("panjang");
		$penggunaanlahan->koordinat = $request->input("koordinat");
		
		$penggunaanlahan->updated_by = Auth::id();
		$penggunaanlahan->save();


		$text = 'mengedit '.$this->title;//.' '.$penggunaanlahan->what;
		$this->log($request, $text, ['penggunaanlahan.id' => $penggunaanlahan->id]);
		return redirect()->route('penggunaanlahan.index')->with('message_success', 'Penggunaan Lahan berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$penggunaanlahan = PenggunaanLahan::find($id);
		$penggunaanlahan->deleted_by = Auth::id();
		$penggunaanlahan->save();
		$penggunaanlahan->delete();

		$text = 'menghapus '.$this->title;//.' '.$penggunaanlahan->what;
		$this->log($request, $text, ['penggunaanlahan.id' => $penggunaanlahan->id]);
		return back()->with('message_success', 'Penggunaan Lahan berhasil dihapus!');
	}

}
