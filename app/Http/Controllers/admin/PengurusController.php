<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengurus;
use App\Models\User;
use App\Models\Divisi;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PengurusController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengurus::with(['user', 'divisi', 'jabatan']);

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('divisi')) {
            $query->where('id_divisi', $request->divisi);
        }

        $pengurus = $query->paginate(10);
        $divisis = Divisi::all();

        return view('pages.admin.pengurus.index', compact('pengurus', 'divisis'));
    }

    public function create()
    {
        $divisis = Divisi::all();
        $jabatans = Jabatan::all();
        return view('pages.admin.pengurus.create', compact('divisis', 'jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:user,username',
            'email' => 'required|email|max:255|unique:user,email',
            'password' => 'required|string|min:8|confirmed',
            'id_divisi' => 'required|exists:divisi,id_divisi',
            'id_jabatan' => 'required|exists:jabatan,id_jabatan',
            'no_telpon' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'nama' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pengurus',
                'no_telpon' => $request->no_telpon,
            ]);

            Pengurus::create([
                'id_user' => $user->id_user,
                'id_divisi' => $request->id_divisi,
                'id_jabatan' => $request->id_jabatan,
            ]);

            DB::commit();
            return redirect()->route('admin.pengurus.index')->with('success', 'Pengurus berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan pengurus: ' . $e->getMessage());
        }
    }

    public function edit(Pengurus $pengurus)
    {
        $divisis = Divisi::all();
        $jabatans = Jabatan::all();
        return view('pages.admin.pengurus.edit', compact('pengurus', 'divisis', 'jabatans'));
    }

    public function update(Request $request, Pengurus $pengurus)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:user,username,' . $pengurus->id_user . ',id_user',
            'email' => 'required|email|max:255|unique:user,email,' . $pengurus->id_user . ',id_user',
            'password' => 'nullable|string|min:8|confirmed',
            'id_divisi' => 'required|exists:divisi,id_divisi',
            'id_jabatan' => 'required|exists:jabatan,id_jabatan',
            'no_telpon' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            $userData = [
                'nama' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'no_telpon' => $request->no_telpon,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $pengurus->user->update($userData);

            $pengurus->update([
                'id_divisi' => $request->id_divisi,
                'id_jabatan' => $request->id_jabatan,
            ]);

            DB::commit();
            return redirect()->route('admin.pengurus.index')->with('success', 'Data pengurus berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui data pengurus: ' . $e->getMessage());
        }
    }

    public function destroy(Pengurus $pengurus)
    {
        DB::beginTransaction();
        try {
            $user = $pengurus->user;
            $pengurus->delete();
            $user->delete();
            DB::commit();
            return redirect()->route('admin.pengurus.index')->with('success', 'Pengurus berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus pengurus: ' . $e->getMessage());
        }
    }
}
