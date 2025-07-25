<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checklist;
use Illuminate\Support\Facades\Auth;

class ChecklistController extends Controller
{
    // List checklist milik user
    public function index()
    {
        $checklists = Checklist::where('user_id', Auth::id())->get();
        return response()->json($checklists);
    }

    // Tambah checklist
    public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'title' => 'required|string',
        'status' => 'required|in:belum,sudah',
        'deadline' => 'nullable|date'
    ]);

    $checklist = Checklist::create([
        'user_id' => $request->user_id,
        'title' => $request->title,
        'status' => $request->status,
        'deadline' => $request->deadline
    ]);

    return response()->json([
        'message' => 'Checklist berhasil dibuat',
        'data' => $checklist
    ]);
}

    // Update checklist
    public function update(Request $request, $id)
    {
        $checklist = Checklist::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$checklist) {
            return response()->json(['message' => 'Checklist tidak ditemukan'], 404);
        }

        $request->validate([
            'title' => 'sometimes|required|string',
            'status' => 'sometimes|in:Belum Selesai,Selesai',
            'deadline' => 'nullable|date',
        ]);

        $checklist->update($request->only(['title', 'status', 'deadline']));

        return response()->json($checklist);
    }

    // Hapus checklist
    public function destroy($id)
    {
        $checklist = Checklist::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$checklist) {
            return response()->json(['message' => 'Checklist tidak ditemukan'], 404);
        }

        $checklist->delete();

        return response()->json(['message' => 'Checklist berhasil dihapus']);
    }

    // Tandai checklist sebagai selesai
    public function markAsDone($id)
    {
        $checklist = Checklist::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$checklist) {
            return response()->json(['message' => 'Checklist tidak ditemukan'], 404);
        }

        $checklist->status = 'Selesai';
        $checklist->save();

        return response()->json(['message' => 'Checklist ditandai sebagai selesai']);
    }

    // Admin: lihat semua checklist semua user
    public function allChecklists()
    {
        // Optional: tambahkan middleware/policy admin
        $checklists = Checklist::with('user:id,name,email')->get();
        return response()->json($checklists);
    }
}
