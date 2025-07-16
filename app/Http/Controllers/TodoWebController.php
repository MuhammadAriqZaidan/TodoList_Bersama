<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoWebController extends Controller
{

    // public function index()
    // {
    //     $todos = Todo::with('user')->latest()->get();
    //     return view('todos.index', compact('todos'));
    // }

    // ✅ Tampilkan daftar To-Do
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

        // $todos = Todo::where('user_id', Auth::id())->latest()->paginate(10);

        return view('todos.index', compact('todos', 'filter'));
    }


    // ✅ Tampilkan form buat todo
    public function create()
    {
        return view('todos.create');
    }

    // ✅ Simpan todo baru
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp,txt|max:40960', // 40MB = 40960 KB,
        ]);

        $path = null;

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
        }

        Todo::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'attachment' => $path,
            'is_done' => false,
        ]);

        return redirect('/todos')->with('success', 'To-Do berhasil ditambahkan!');
    }

    // ✅ Tampilkan form edit
    public function edit($id)
    {
        $todo = Todo::findOrFail($id);
        return view('todos.edit', compact('todo'));
    }

    // ✅ Simpan perubahan
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'is_done' => 'boolean',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp,txt|max:40960', // 40MB = 40960 KB,
        ]);

        $todo = Todo::findOrFail($id);

        // Simpan file baru (kalau ada)
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $todo->attachment = $path;
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


    // ✅ Hapus todo
    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();

        return redirect('/todos')->with('success', 'To-Do berhasil dihapus!');
    }
}
