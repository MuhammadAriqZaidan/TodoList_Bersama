<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="{{ asset('ikon_todos.ico') }}">
    <title>Tambah To-Do</title>
    <link rel="stylesheet" href="{{ asset('css/create.css') }}">

</head>

<body>
    <div class="create-container">
        <h2>➕ Tambah To-Do Baru</h2>

        @if ($errors->any())
            <div>
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/todos" enctype="multipart/form-data">
            @csrf

            <label>
                Judul:
                <input type="text" name="title" required>
            </label>

            <label>
                Deskripsi:
                <input type="text" name="description">
            </label>

            <label>
                Deadline:
                <input type="date" name="due_date">
            </label>

            <label>
                Lampiran (Opsional):
                <input type="file" name="attachment" id="attachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp,.txt">
                <span>Pastikan file tidak lebih dari 40 MB</span>
            </label>

            <button type="submit" class="button">Tambah</button>
        </form>

        <a href="/todos" class="back-link">← Kembali ke daftar</a>
    </div>
</body>

</html>
