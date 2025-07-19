<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Daftar User</title>
</head>
<body>
  <h2>Daftar User</h2>

  <a href="{{ route('users.create') }}">+ Tambah User</a>

  <table border="1" cellpadding="8" cellspacing="0">
    <thead>
      <tr>
        <th>Nama</th>
        <th>Email</th>
        <th>Gambar</th>
        <th>PDF</th>
        <th>Excel</th>
      </tr>
    </thead>
    <tbody>
      @foreach($users as $user)
        <tr>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td>
            @if($user->image_path)
              <img src="{{ $user->image_path }}" alt="foto" width="60">
            @endif
          </td>
          <td>
            @if($user->pdf_path)
              <a href="{{ $user->pdf_path }}" target="_blank">Lihat PDF</a>
            @endif
          </td>
          <td>
            @if($user->excel_path)
              <a href="{{ $user->excel_path }}" target="_blank">Download Excel</a>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
