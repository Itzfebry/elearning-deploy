<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Repositories\AdminRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AdminController extends Controller
{
    protected $param;
    protected $paramUser;

    public function __construct(AdminRepository $admin, UserRepository $user)
    {
        $this->param = $admin;
        $this->paramUser = $user;
    }
    public function index(Request $request)
    {
        $limit = $request->has('page_length') ? $request->get('page_length') : 5;
        $search = $request->has('search') ? $request->get('search') : null;
        $admin = $this->param->getData($search, $limit);
        return view("pages.admin.index", compact("admin"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.admin.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $dataUser = $request->validate([
                'email' => 'required',
                'role' => 'required',
            ]);

            $data = $request->validate([
                'nip' => 'required|string|size:18|unique:guru,nip',
                'nama' => 'required|string',
                'jk' => 'required',
            ]);

            if (Admin::where('nip', $data['nip'])->exists()) {
                Alert::error("Terjadi Kesalahan", "NIP sudah terdaftar.");
                return back()->withInput();
            }

            $dataUser['pass'] = $request->nip;
            $user = $this->paramUser->store($dataUser);

            $data["user_id"] = $user->id;
            $this->param->store($data);
            Alert::success("Berhasil", "Data Berhasil di simpan.");
            return redirect()->route("admin");
        } catch (\Exception $e) {
            Alert::error("Terjadi Kesalahan", $e->getMessage());
            return back()->withInput();
        } catch (QueryException $e) {
            Alert::error("Terjadi Kesalahan", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admin = $this->param->find($id);
        return view("pages.admin.edit", compact("admin"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $dataUser = $request->validate([
                'email' => 'required',
            ]);

            $data = $request->validate([
                'nip' => 'required|string|size:18',
                'nama' => 'required|string',
                'jk' => 'required',
            ]);

            $this->paramUser->update($dataUser, $request->user_id);
            $this->param->update($data, $id);
            Alert::success("Berhasil", "Data Berhasil di ubah.");
            return redirect()->route("admin");
        } catch (\Exception $e) {
            Alert::error("Terjadi Kesalahan", $e->getMessage());
            return back()->withInput();
        } catch (QueryException $e) {
            Alert::error("Terjadi Kesalahan", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
