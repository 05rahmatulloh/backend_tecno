<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Tambah Produk</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f2f2f2;
            padding: 40px;
        }

        .form-container {
            background-color: #fff;
            padding: 30px 40px;
            max-width: 600px;
            margin: auto;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #555;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        textarea,
        select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            background-color: #fafafa;
            transition: all 0.3s ease;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #4CAF50;
            outline: none;
            background-color: #fff;
        }

        button {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="form-container">
        <h2>Form Tambah Produk</h2>
        <form action="/product" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name">Nama Produk</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label for="price">Harga</label>
                <input type="number" id="price" name="price" required>
            </div>

            <div class="form-group">
                <label for="is_available">Ketersediaan</label>
                <select id="is_available" name="is_available">
                    <option value="1">Tersedia</option>
                    <option value="0">Tidak Tersedia</option>
                </select>
            </div>

            <div class="form-group">
                <label for="image">Gambar Produk</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <div class="form-group">
                <label for="address">Alamat Produk</label>
                <input type="text" id="address" name="address">
            </div>

            @foreach ($category as $cat)
            <div class="form-check">
                <input class="form-check-input" type="radio" name="category" id="radio{{ $cat->id }}"
                    value="{{ $cat->name }}">
                <label class="form-check-label" for="radio{{ $cat->id }}">
                    {{ $cat->name }}
                </label>
            </div>
            @endforeach
            <button type="submit">Simpan Produk</button>
        </form>
    </div>

</body>

</html>
