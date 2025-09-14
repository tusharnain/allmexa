<?php

namespace App\Controllers\AdminDashboard;

use App\Controllers\BaseController;
use App\Models\DepositModel;
use CodeIgniter\HTTP\Response;


class AdminFilesController extends BaseController
{
    private bool $isAdmin = false;


    const FILE_TYPES = [
        'deposit-receipts' => [
            'dir' => DepositModel::DEPOSIT_RECEIPT_DIRECTORY,
            'user_auth' => true
        ]
    ];



    public function __construct()
    {
        $this->isAdmin = (bool) (($adminId = admin('id')) and is_numeric($adminId));

        if (!$this->isAdmin)
            show_404();
    }





    public function index(string $filetype, string $filename): Response
    {

        if (!isset (self::FILE_TYPES[$filetype]))
            show_404();


        $ft = self::FILE_TYPES[$filetype];

        $filepath = WRITEPATH . "{$ft['dir']}/{$filename}";


        if (!file_exists($filepath))
            show_404();

        $mimeType = mime_content_type($filepath);

        $file = file_get_contents($filepath);

        $response = $this->response
            ->setStatusCode(Response::HTTP_OK)
            ->setContentType($mimeType)
            ->setBody($file);


        if ($this->request->getGet('d') == 1) {
            $response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        }

        return $response;
    }

}
