<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // Menampilkan daftar semua user
    public function index()
    {
        return response()->json(User::all());
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User tidak ditemukan.'
            ], 404);
        }

        return response()->json($user);
    }


    // Menyimpan user baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);

        // Upload file jika dikirim
        if ($request->hasFile('image')) {
            $user->image_path = $request->file('image')->store('uploads/images', 'public');
        }

        if ($request->hasFile('pdf')) {
            $user->pdf_path = $request->file('pdf')->store('uploads/pdfs', 'public');
        }

        if ($request->hasFile('excel')) {
            $user->excel_path = $request->file('excel')->store('uploads/excels', 'public');
        }

        $user->save();

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    // Mengupdate data user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:10048',
            'pdf'   => 'nullable|mimes:pdf|max:5120',
            'excel' => 'nullable|mimes:xls,xlsx|max:5120',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Upload ulang file jika ada
        if ($request->hasFile('image')) {
            if ($user->image_path && Storage::disk('public')->exists($user->image_path)) {
                Storage::disk('public')->delete($user->image_path);
            }
            $user->image_path = $request->file('image')->store('uploads/images', 'public');
        }

        if ($request->hasFile('pdf')) {
            if ($user->pdf_path && Storage::disk('public')->exists($user->pdf_path)) {
                Storage::disk('public')->delete($user->pdf_path);
            }
            $user->pdf_path = $request->file('pdf')->store('uploads/pdfs', 'public');
        }

        if ($request->hasFile('excel')) {
            if ($user->excel_path && Storage::disk('public')->exists($user->excel_path)) {
                Storage::disk('public')->delete($user->excel_path);
            }
            $user->excel_path = $request->file('excel')->store('uploads/excels', 'public');
        }

        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    // Menghapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Hapus file yang terkait jika ada
        foreach (['image_path', 'pdf_path', 'excel_path'] as $field) {
            if ($user->$field && Storage::disk('public')->exists($user->$field)) {
                Storage::disk('public')->delete($user->$field);
            }
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
