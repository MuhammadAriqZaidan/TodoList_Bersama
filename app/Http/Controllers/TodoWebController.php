<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log; // Tambahkan ini


class TodoWebController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('status');
        $query = Todo::with('user');

        if ($filter === 'done') {
            $query->where('is_done', true);
        } elseif ($filter === 'not_done') {
            $query->where('is_done', false);
        }

        $todos = $query->latest()->get();
        return view('todos.index', compact('todos', 'filter'));
    }

    public function create()
    {
        return view('todos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp,txt|max:40960',
        ]);

        $attachmentUrl = null;

        if ($request->hasFile('attachment')) {
            try {
                $uploadedFile = Cloudinary::upload($request->file('attachment')->getRealPath(), [
                    'folder' => 'todo_attachments'
                ]);
                $attachmentUrl = $uploadedFile->getSecurePath();
            } catch (\Exception $e) {
                // Log error ke Laravel log (akan terlihat di log Railway)
                Log::error("Cloudinary Upload Error: " . $e->getMessage());

                // Redirect kembali dengan pesan error lebih spesifik
                // Anda bisa mengembalikan error validasi kustom di sini
                return back()->withInput()->withErrors(['attachment' => 'Gagal mengunggah lampiran: ' . $e->getMessage()]);
            }
        }

        Todo::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'attachment' => $attachmentUrl,
            'is_done' => false,
        ]);

        return redirect('/todos')->with('success', 'To-Do berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $todo = Todo::findOrFail($id);
        return view('todos.edit', compact('todo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'is_done' => 'boolean',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp,txt|max:40960',
        ]);

        $todo = Todo::findOrFail($id);

        if ($request->hasFile('attachment')) {
            $uploadedFile = Cloudinary::upload($request->file('attachment')->getRealPath(), [
                'folder' => 'todo_attachments'
            ]);
            $todo->attachment = $uploadedFile->getSecurePath();
        }

        $todo->title = $request->title;
        $todo->description = $request->description;
        $todo->due_date = $request->due_date;
        $todo->is_done = $request->is_done ?? false;
        $todo->save();

        return redirect('/todos')->with('success', 'To-Do berhasil diperbarui!');
    }

    public function toggleStatus($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->is_done = !$todo->is_done;
        $todo->save();

        return redirect('/todos');
    }

    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();

        return redirect('/todos')->with('success', 'To-Do berhasil dihapus!');
    }
}
