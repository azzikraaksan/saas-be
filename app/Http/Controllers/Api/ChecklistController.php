<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checklist;
use App\Models\User;

class ChecklistController extends Controller
{
    //checklist milik user tertentu
    public function index(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $checklists = Checklist::where('user_id', $request->user_id)->get();

        return response()->json($checklists);
    }

    //tambah checklist
    public function store(Request $request)
    {
        $request->validate([
            'user_id'  => 'required|exists:users,id',
            'title'    => 'required|string',
            'status'   => 'required|in:Belum Selesai,Selesai',
            'deadline' => 'nullable|date',
        ]);

        $checklist = Checklist::create([
            'user_id'  => $request->user_id,
            'title'    => $request->title,
            'status'   => $request->status,
            'deadline' => $request->deadline,
        ]);

        return response()->json([
            'message' => 'Checklist berhasil dibuat',
            'data'    => $checklist,
        ]);
    }

    //update checklist
    public function update(Request $request, $id)
    {
        $checklist = Checklist::findOrFail($id);

        $request->validate([
            'title'    => 'sometimes|required|string',
            'status'   => 'sometimes|required|in:Belum Selesai,Selesai',
            'deadline' => 'nullable|date',
        ]);

        $checklist->update($request->only(['title', 'status', 'deadline']));

        return response()->json($checklist);
    }

    //hapus checklist
    public function destroy($id)
    {
        $checklist = Checklist::findOrFail($id);
        $checklist->delete();

        return response()->json(['message' => 'Checklist berhasil dihapus']);
    }

    //tandai checklist sebagai selesai
    public function markAsDone($id)
    {
        $checklist = Checklist::findOrFail($id);
        $checklist->status = 'Selesai';
        $checklist->save();

        return response()->json(['message' => 'Checklist ditandai sebagai selesai']);
    }

    //lihat semua checklist semua user (admin)
    public function allChecklists()
    {
        $checklists = Checklist::with('user:id,name,email')->get();
        return response()->json($checklists);
    }
}
