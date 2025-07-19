<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class UserController extends Controller
{
    public function create()
    {
        return view('users.create');
    }

    // method menampilkan semua user
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // Menampilkan daftar semua user
    // public function index()
    // {
    //     return response()->json(User::all());
    // }

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
            $file = $request->file('image');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $user->image_path = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'users/images',
                'public_id' => $filename,
            ])->getSecurePath();
        }

        if ($request->hasFile('pdf')) {
            $file = $request->file('pdf');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $pdfUrl = Cloudinary::uploadFile(
                $file->getRealPath(),
                [
                    'folder' => 'users/pdfs',
                    'public_id' => $filename,
                    'resource_type' => 'raw',
                    'access_mode' => 'public',
                ]
            )->getSecurePath();

            $user->pdf_path = $pdfUrl;
        }

        if ($request->hasFile('excel')) {
            $file = $request->file('excel');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();

            if (in_array($extension, ['xlsx', 'xls', 'csv'])) {
                $excelUrl = Cloudinary::uploadFile(
                    $file->getRealPath(),
                    [
                        'folder' => 'users/excels',
                        'public_id' => $filename,
                        'resource_type' => 'raw',
                        'format' => $extension,
                        'access_mode' => 'public',
                    ]
                )->getSecurePath();

                $user->excel_path = $excelUrl;
            } else {
                return back()->with('error', 'File Excel tidak valid.');
            }
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
            $file = $request->file('image');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $user->image_path = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'users/images',
                'public_id' => $filename,
            ])->getSecurePath();
        }

        if ($request->hasFile('pdf')) {
            $file = $request->file('pdf');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $pdfUrl = Cloudinary::uploadFile(
                $file->getRealPath(),
                [
                    'folder' => 'users/pdfs',
                    'public_id' => $filename,
                    'resource_type' => 'raw',
                    'access_mode' => 'public',
                ]
            )->getSecurePath();

            $user->pdf_path = $pdfUrl;
        }

        if ($request->hasFile('excel')) {
            $file = $request->file('excel');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();

            if (in_array($extension, ['xlsx', 'xls', 'csv'])) {
                $excelUrl = Cloudinary::uploadFile(
                    $file->getRealPath(),
                    [
                        'folder' => 'users/excels',
                        'public_id' => $filename,
                        'resource_type' => 'raw',
                        'format' => $extension,
                        'access_mode' => 'public',
                    ]
                )->getSecurePath();

                $user->excel_path = $excelUrl;
            } else {
                return back()->with('error', 'File Excel tidak valid.');
            }
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

        // Hapus file dari Cloudinary
        foreach (['image_path', 'pdf_path', 'excel_path'] as $field) {
            if ($user->$field) {
                // Extract public_id dari URL
                $url = $user->$field;
                $publicId = basename(parse_url($url, PHP_URL_PATH)); // dapet nama file.ext
                $publicId = pathinfo($publicId, PATHINFO_FILENAME); // tanpa ekstensi

                $folder = match ($field) {
                    'image_path' => 'users/images',
                    'pdf_path'   => 'users/pdfs',
                    'excel_path' => 'users/excels',
                    default      => '',
                };

                $resourceType = in_array($field, ['pdf_path', 'excel_path']) ? 'raw' : 'image';

                Cloudinary::destroy("{$folder}/{$publicId}", [
                    'resource_type' => $resourceType
                ]);
            }
        }


        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
