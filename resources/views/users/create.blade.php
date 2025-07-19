<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tambah User</title>
</head>
<body>
  <h2>Form Tambah User</h2>

  @if ($errors->any())
    <div style="color: red;">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <p>
      <label>Nama:</label><br>
      <input type="text" name="name" value="{{ old('name') }}">
    </p>
    <p>
      <label>Email:</label><br>
      <input type="email" name="email" value="{{ old('email') }}">
    </p>
    <p>
      <label>Password:</label><br>
      <input type="password" name="password">
    </p>
    <p>
      <label>Gambar (jpg/png/jpeg):</label><br>
      <input type="file" name="image">
    </p>
    <p>
      <label>PDF:</label><br>
      <input type="file" name="pdf">
    </p>
    <p>
      <label>Excel (xlsx/xls/csv):</label><br>
      <input type="file" name="excel">
    </p>
    <p>
      <button type="submit">Simpan</button>
    </p>
  </form>
</body>
</html>
