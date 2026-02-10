<?php

namespace App\Controllers\Additional;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;

class FileController extends BaseController
{
    public function serve($year = null, $month = null, $fileName = null)
    {
        $filePath = WRITEPATH . "uploads/{$year}/{$month}/{$fileName}";
        if (!file_exists($filePath)) {
            throw new PageNotFoundException("File not found.");
        }
        $mime = mime_content_type($filePath);
        $fileSize = filesize($filePath);

        if (isset($_GET['referer']) && $_GET['referer'] == 'email') {
            return $this->response
                ->setHeader('Content-Type', $mime)
                ->setHeader('Content-Length', filesize($filePath))
                ->setBody(file_get_contents($filePath));
        } elseif (session()->has('current_user')) {
            return $this->response
                ->setHeader('Content-Type', $mime)
                ->setHeader('Content-Length', $fileSize)
                ->setBody(file_get_contents($filePath));
        }

        return redirect()->to(base_url('login'));
    }
}
