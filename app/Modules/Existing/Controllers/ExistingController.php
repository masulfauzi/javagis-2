<?php
namespace App\Modules\Existing\Controllers;

use Form;
use App\Helpers\Logger;
use Illuminate\Http\Request;
use App\Modules\Log\Models\Log;
use App\Modules\Existing\Models\Existing;
use App\Modules\JenisLahan\Models\JenisLahan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExistingController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Existing";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = Existing::query();
		if($request->has('search')){
			$search = $request->get('search');
			// $query->where('name', 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('Existing::existing', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		$ref_jenis_lahan = JenisLahan::all()->pluck('jenis_lahan','id');
		$ref_jenis_lahan->prepend('-PILIH SALAH SATU-', '');
		
		$data['forms'] = array(
			'nama' => ['Nama', Form::text("nama", old("nama"), ["class" => "form-control","placeholder" => ""]) ],
			'id_jenislahan' => ['Jenislahan', Form::select("id_jenislahan", $ref_jenis_lahan, null, ["class" => "form-control select2"]) ],
			'koordinat' => ['Koordinat', Form::file("koordinat", ["class" => "form-control","placeholder" => ""]) ],
			
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('Existing::existing_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$this->validate($request, [
			'nama' => 'required',
			'id_jenislahan' => 'required',
			'koordinat' => 'required',
			
		]);

		$fileName = time().'.'.$request->koordinat->extension();  

        $request->koordinat->move(public_path('assets/geojson/'), $fileName);

		$existing = new Existing();
		$existing->nama = $request->input("nama");
		$existing->id_jenislahan = $request->input("id_jenislahan");
		$existing->koordinat = $fileName;
		
		$existing->created_by = Auth::id();
		$existing->save();

		$text = 'membuat '.$this->title; //' baru '.$existing->what;
		$this->log($request, $text, ['existing.id' => $existing->id]);
		return redirect()->route('existing.index')->with('message_success', 'Existing berhasil ditambahkan!');
	}

	public function show(Request $request, Existing $existing)
	{
		$data['existing'] = $existing;

		$text = 'melihat detail '.$this->title;//.' '.$existing->what;
		$this->log($request, $text, ['existing.id' => $existing->id]);
		return view('Existing::existing_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, Existing $existing)
	{
		$data['existing'] = $existing;

		$ref_jenis_lahan = JenisLahan::all()->pluck('jenis_lahan','id');
		
		$data['forms'] = array(
			'nama' => ['Nama', Form::text("nama", $existing->nama, ["class" => "form-control","placeholder" => "", "id" => "nama"]) ],
			'id_jenislahan' => ['Jenislahan', Form::select("id_jenislahan", $ref_jenis_lahan, null, ["class" => "form-control select2"]) ],
			'koordinat' => ['Koordinat', Form::text("koordinat", $existing->koordinat, ["class" => "form-control","placeholder" => "", "id" => "koordinat"]) ],
			
		);

		$text = 'membuka form edit '.$this->title;//.' '.$existing->what;
		$this->log($request, $text, ['existing.id' => $existing->id]);
		return view('Existing::existing_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'nama' => 'required',
			'id_jenislahan' => 'required',
			'koordinat' => 'required',
			
		]);
		
		$existing = Existing::find($id);
		$existing->nama = $request->input("nama");
		$existing->id_jenislahan = $request->input("id_jenislahan");
		$existing->koordinat = $request->input("koordinat");
		
		$existing->updated_by = Auth::id();
		$existing->save();


		$text = 'mengedit '.$this->title;//.' '.$existing->what;
		$this->log($request, $text, ['existing.id' => $existing->id]);
		return redirect()->route('existing.index')->with('message_success', 'Existing berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$existing = Existing::find($id);
		$existing->deleted_by = Auth::id();
		$existing->save();
		$existing->delete();

		$text = 'menghapus '.$this->title;//.' '.$existing->what;
		$this->log($request, $text, ['existing.id' => $existing->id]);
		return back()->with('message_success', 'Existing berhasil dihapus!');
	}

}
