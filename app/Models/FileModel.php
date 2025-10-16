<?php

namespace App\Models;

use CodeIgniter\Model;

class FileModel extends Model
{
    protected $table            = 'uploaded_pdfs';
    protected $primaryKey       = 'id';
    
    protected $allowedFields    = ['file_name', 'stored_name', 'file_type', 'extracted_text'];
}