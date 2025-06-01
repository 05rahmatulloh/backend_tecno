<!DOCTYPE html>
<html>

<head>
    <title>Manajemen Produk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">ProdukApp</a>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

</body>

</html>
