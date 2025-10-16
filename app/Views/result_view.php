<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Texto Extraído del PDF</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 50px auto; }
        .result-container { border: 1px solid #ccc; padding: 20px; border-radius: 5px; background-color: #f9f9f9; }
        pre { white-space: pre-wrap; word-wrap: break-word; }
    </style>
</head>
<body>
    <h2>✅ Texto extraído de "<?= esc($file_name) ?>"</h2>

    <div class="result-container">
        <pre><?= esc($extracted_text) ?></pre>
    </div>

    <br>
    <a href="/pdf">Subir otro archivo</a>
</body>
</html>