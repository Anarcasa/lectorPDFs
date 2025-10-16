<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador de Documentos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #c0b8b860;
            /* Azul pastel muy claro */
            --container-bg: #ffffff;
            --primary-color: #a7c7e7;
            /* Azul pastel */
            --primary-hover: #4c94ddff;
            --text-color: #334e68;
            --subtle-text: #627d98;
            --border-color: #d8e2eb;
            --success-color: #6aab9c;
            /* Verde pastel */
            --error-color: #e57373;
            /* Rojo pastel */
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .main-wrapper {
            width: 100%;
            max-width: 900px;
        }

        h1,
        h2 {
            font-weight: 500;
            border: none;
            padding: 0;
            margin-bottom: 20px;
        }

        h1 {
            color: var(--text-color);
            text-align: center;
            font-size: 2.2em;
            margin-bottom: 30px;
        }

        .container {
            background-color: var(--container-bg);
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
        }

        .error,
        .success {
            padding: 10px;
            border-radius: 6px;
        }

        .error {
            color: var(--error-color);
            background-color: rgba(229, 115, 115, 0.1);
        }

        .success {
            color: var(--success-color);
            background-color: rgba(106, 171, 156, 0.1);
        }

        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            margin: 0;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 1em;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(167, 199, 231, 0.5);
        }

        button {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: var(--primary-hover);
        }

        /* ESTILO AÑADIDO AQUÍ */
        .button-clear {
            background-color: var(--subtle-text);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 1em;
            font-weight: 500;
            transition: background-color 0.3s;
            display: inline-flex; /* Para alinear verticalmente */
            align-items: center;
        }

        .button-clear:hover {
            background-color: var(--text-color);
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        li {
            background-color: #f8fafc;
            border: 1px solid var(--border-color);
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.2s;
        }

        li:hover {
            background-color: #eff4f8;
        }

        .file-list-container {
            max-height: 250px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .file-info small {
            color: var(--subtle-text);
            font-size: 0.8em;
            margin-left: 10px;
        }

        .result-actions a {
            color: var(--primary-hover);
            text-decoration: none;
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 6px;
            transition: background-color 0.2s, color 0.2s;
        }

        .result-actions a:hover {
            background-color: var(--primary-color);
            color: white;
        }

        /* --- Estilos para Arrastrar y Soltar --- */
        #drop-zone {
            display: block;
            border: 2px dashed var(--border-color);
            padding: 30px;
            text-align: center;
            cursor: pointer;
            border-radius: 8px;
            transition: border-color 0.3s, background-color 0.3s;
            margin-bottom: 15px;
        }

        #drop-zone.dragover {
            border-color: var(--primary-color);
            background-color: #eaf2f8;
        }

        input[type="file"] {
            display: none;
        }

        #file-list-display {
            margin-top: 10px;
            color: var(--subtle-text);
        }
    </style>
</head>

<body>
    <div class="main-wrapper">
        <h1>Buscador texto en Documentos (pdf, excel) 🔎</h1>

      

        <div class="container">
            <h2>Buscar en la base de datos coincidencias de texto en todos los docuemntos</h2>
            <form action="<?= base_url('/pdf') ?>" method="get" class="search-form">
                <input type="text" name="q" placeholder="Escribe el texto a buscar..." value="<?= esc($searchTerm ?? '') ?>" required>
                <button type="submit">Buscar</button>

                <?php if (!empty($searchTerm)): ?>
                    <a href="<?= base_url('/pdf') ?>" style="text-decoration: none;" class="button-clear">Limpiar 🧹</a>
                <?php endif; ?>

            </form>
        </div>

        <?php if (isset($results)): ?>
            <div class="container results">
                <h2>Resultados para "<?= esc($searchTerm) ?>"</h2>
                <?php if (empty($results)): ?>
                    <p>No se encontraron documentos.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($results as $result): ?>
                            <li>
                                <span class="file-info">
                                    <span> <?= (isset($result['file_type']) && $result['file_type'] === 'excel') ? '📊' : '📄' ?> <?= esc($result['file_name']) ?>
                                    </span>
                                    <div class="result-actions">
                                        <a href="<?= base_url('pdf/view/' . $result['stored_name']) ?>" target="_blank">Ver</a>
                                        <a href="<?= base_url('pdf/download/' . $result['stored_name']) ?>">Descargar</a>
                                    </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="container">
            <h2>Añadir nuevos archivos</h2>
            <?= form_open_multipart('/pdf/upload', ['id' => 'upload-form']) ?>
            <label for="file_uploads" id="drop-zone">
                <strong>Arrastra tus archivos aquí o haz clic para seleccionar</strong>
                <br>
                <small>(PDF, XLSX, XLS permitidos)</small>
            </label>
            <input type="file" name="pdf_files[]" id="file_uploads" accept=".pdf,.xlsx,.xls" multiple required onchange="updateFileList(this.files)">
            <div id="file-list-display">Ningún archivo seleccionado</div>
            <button type="submit" style="margin-top: 15px;">Subir y Procesar</button>
            <?= form_close() ?>
        </div>

        <div class="container">
            <h2>📚 Archivos en la Base de Datos (<?= count($all_files) ?> en total)</h2>
            <div class="file-list-container">
                <?php if (empty($all_files)): ?>
                    <p>Aún no se ha procesado ningún documento.</p>
                <?php else: ?>
                    <ul class="file-list">
                        <?php foreach ($all_files as $file): ?>
                            <li>
                                <span class="file-info">
                                    <span> <?= (isset($result['file_type']) && $result['file_type'] === 'excel') ? '📊' : '📄' ?> <?= esc($file['file_name']) ?>
                                        <small>(Subido: <?= date('d/m/Y', strtotime($file['uploaded_at'])) ?>)</small>
                                    </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('file_uploads');
        const fileDisplay = document.getElementById('file-list-display');
        const uploadForm = document.getElementById('upload-form');

        // Función para mostrar los nombres de los archivos seleccionados
        function updateFileList(files) {
            if (files.length > 0) {
                let fileNames = [];
                for (const file of files) {
                    fileNames.push(file.name);
                }
                fileDisplay.textContent = `${files.length} archivo(s) seleccionado(s): ${fileNames.join(', ')}`;
            } else {
                fileDisplay.textContent = 'Ningún archivo seleccionado';
            }
        }

        /*
        dropZone.addEventListener('click', () => fileInput.click());*/

        // Evento cuando un archivo está sobre la zona
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault(); // Prevenir el comportamiento por defecto
            dropZone.classList.add('dragover');
        });

        // Evento cuando el archivo sale de la zona
        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('dragover');
        });

        // Evento principal: cuando se suelta el archivo
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault(); // Prevenir que el navegador abra el archivo
            dropZone.classList.remove('dragover');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                // Asigna los archivos al input y sube el formulario automáticamente
                fileInput.files = files;
                updateFileList(files);
                // Opcional: Descomenta la siguiente línea si quieres que se suba automáticamente al soltar
                // uploadForm.submit(); 
            }
        });
    </script>
</body>

</html>