<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="{{ asset('ikon_todos.ico') }}">

    <title>Edit To-Do</title>
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}">

</head>

<body>
    <div class="edit-container">
        <h2>✏️ Edit To-Do</h2>

        @if ($errors->any())
            <div>
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/todos/{{ $todo->id }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label>
                Judul:
                <input type="text" name="title" value="{{ $todo->title }}" required>
            </label>

            <label>
                Deskripsi:
                <input type="text" name="description" value="{{ $todo->description }}">
            </label>

            <label>
                Deadline:
                <input type="date" name="due_date"
                    value="{{ \Carbon\Carbon::parse($todo->due_date)->format('Y-m-d') }}">
            </label>

            <label>
                Status:
                <select name="is_done">
                    <option value="0" {{ !$todo->is_done ? 'selected' : '' }}>⏳ Belum Selesai</option>
                    <option value="1" {{ $todo->is_done ? 'selected' : '' }}>✔ Selesai</option>
                </select>
            </label>

            <label>
                Ganti Lampiran (Opsional):
                <input type="file" name="attachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp,.txt">
                <span>Pastikan file tidak lebih dari 40 MB</span>
                @if ($todo->attachment)
                    <p style="font-size: 0.9rem; color: #555;">📁 Lampiran saat ini:
                        <a href="{{ asset('storage/' . $todo->attachment) }}" target="_blank" style="color: #337ab7;">
                            {{ basename($todo->attachment) }}
                        </a>
                    </p>
                @endif

            </label>
            <small class="file-info">Biarkan kosong jika tidak ingin mengubah file.</small>


            <button type="submit" class="button">Simpan Perubahan</button>
        </form>

        <a href="/todos" class="back-link">← Kembali ke daftar</a>
    </div>
</body>

</html>
