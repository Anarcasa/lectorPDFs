<?php

namespace App\Controllers;

use App\Models\FileModel;

class FileController extends BaseController
{
    public function index()
    {
        helper('form');
        $fileModel = new FileModel();
        $allFiles = $fileModel->orderBy('uploaded_at', 'DESC')->findAll();
        $data = [
            'searchTerm' => '',
            'results'    => null,
            'all_files'  => $allFiles
        ];
        $searchTerm = $this->request->getGet('q');
        if ($searchTerm) {
            $query = $fileModel;
            $words = explode(' ', $searchTerm);
            foreach ($words as $word) {
                if (!empty($word)) {
                    $query->like('extracted_text', $word);
                }
            }
            $data['results'] = $query->findAll();
            $data['searchTerm'] = $searchTerm;
        }
        return view('upload_form', $data);
    }

    public function upload()
    {
        $validationRule = [
            'pdf_files' => [
                'label' => 'Archivos',
                'rules' => 'uploaded[pdf_files]|max_size[pdf_files,20480]|ext_in[pdf_files,pdf,xlsx,xls]',
            ],
        ];
        if (!$this->validate($validationRule)) {
            return redirect()->to('/pdf')->with('upload_error', $this->validator->listErrors());
        }
        $files = $this->request->getFileMultiple('pdf_files');
        $processedCount = 0;
        foreach ($files as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(WRITEPATH . 'uploads/pdfs', $newName);
                $path = WRITEPATH . 'uploads/pdfs/' . $newName;
                $text = '';
                $fileType = '';
                $clientName = $file->getClientName();
                $extension = strtolower(pathinfo($clientName, PATHINFO_EXTENSION));
                try {
                    if ($extension === 'pdf') {
                        $parser = new \Smalot\PdfParser\Parser();
                        $pdf    = $parser->parseFile($path);
                        $text   = $pdf->getText();
                        $fileType = 'pdf';
                    } elseif ($extension === 'xlsx') {
                        if ($xlsx = \Shuchkin\SimpleXLSX::parse($path)) {
                            foreach ($xlsx->rows() as $row) {
                                $text .= implode(' ', $row) . ' ';
                            }
                            $fileType = 'excel';
                        }
                    }
                    if (!empty($fileType)) {
                        $fileModel = new \App\Models\FileModel();
                        $data = [
                            'file_name'      => $clientName,
                            'stored_name'    => $newName,
                            'file_type'      => $fileType,
                            'extracted_text' => $text,
                        ];
                        $fileModel->insert($data);
                        $processedCount++;
                    } else {
                        if (file_exists($path)) {
                            unlink($path);
                        }
                    }
                } catch (\Exception $e) {
                    if (file_exists($path)) {
                        unlink($path);
                    }
                    continue;
                }
            }
        }
        if ($processedCount > 0) {
            session()->setFlashdata('upload_success', "Se procesaron y guardaron {$processedCount} archivo(s).");
        } else {
            session()->setFlashdata('upload_error', "No se pudo procesar ningún archivo soportado (.pdf, .xlsx).");
        }
        return redirect()->to('/pdf');
    }

    public function view($filename)
    {
        $fileModel = new \App\Models\FileModel();
        $fileInfo = $fileModel->where('stored_name', $filename)->first();
        if (!$fileInfo) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Archivo no encontrado en la base de datos.');
        }

   
        if ($fileInfo['file_type'] !== 'pdf') {
            return $this->download($filename);
        }
   
        $path = WRITEPATH . 'uploads/pdfs/' . $fileInfo['stored_name'];
        if (!file_exists($path)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('El archivo físico no existe en el servidor.');
        }
        $pdfData = file_get_contents($path);
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . rawurlencode($fileInfo['file_name']) . '"')
            ->setBody($pdfData);
    }

    public function download($filename)
    {
        $fileModel = new \App\Models\FileModel();
        $fileInfo = $fileModel->where('stored_name', $filename)->first();
        if (!$fileInfo) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Archivo no encontrado.');
        }
        $path = WRITEPATH . 'uploads/pdfs/' . $fileInfo['stored_name'];
        return $this->response->download($path, null)->setFileName($fileInfo['file_name']);
    }
}
