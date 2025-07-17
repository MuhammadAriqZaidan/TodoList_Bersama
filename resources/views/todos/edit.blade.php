<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="{{ asset('ikon_todos.ico') }}">

    <title>Edit To-Do</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            padding: 2rem;

            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }

        .edit-container {
            background-color: #fffbe6;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            max-width: 500px;
            width: 100%;
        }

        h2 {
            margin-bottom: 1.5rem;
            color: #444;
            font-size: 1.75rem;
        }

        form {
            background-color: #fffbe6;
            padding: 1.5rem;
            border-radius: 8px;
            max-width: 500px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        label {
            display: block;
            margin-bottom: 1.25rem;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="date"],
        select {
            width: 100%;
            padding: 0.75rem;
            margin-top: 0.25rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
        }


        .button {
            padding: 0.6rem 1.2rem;
            background-color: #f5e663;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            color: #333;
            font-size: 1rem;
            margin-top: 1rem;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #e4d94e;
        }

        .back-link {
            display: inline-block;
            margin-top: 1rem;
            text-decoration: none;
            color: #444;
            font-size: 0.95rem;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .error-list {
            color: red;
            margin-bottom: 1rem;
            background: #ffe0e0;
            padding: 0.75rem;
            border-radius: 6px;
            list-style: square inside;
        }

        span {
            color: red;
            size: 5px;
        }



        @media (max-width: 600px) {
            body {
                padding: 1rem;
            }

            form {
                padding: 1rem;
            }
        }
    </style>
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
