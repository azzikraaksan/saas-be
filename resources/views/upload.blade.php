<!DOCTYPE html>
<html>

<head>
    <title>Test Upload</title>
</head>

<body>
    <form action="/test-upload" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" />
        <button type="submit">Upload</button>
    </form>

</body>

</html>