<?php

namespace App\Controllers\UserDashboard;

use App\Controllers\BaseController;
use App\Models\DepositModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\Response;


class UserFilesController extends BaseController
{
    private object $user;
    private UserModel $userModel;

    public function __construct()
    {
        $this->user = user();

        $this->userModel = new UserModel;
    }

    const FILE_TYPES = [
        'deposit-receipts' => [
            'dir' => DepositModel::DEPOSIT_RECEIPT_DIRECTORY,
            'user_auth' => true
        ]
    ];



    public function index(string $filetype, string $filename): Response
    {
        if (
            !isset (self::FILE_TYPES[$filetype])
            or !($this->validateUser($filename))
        )
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



    private function validateUser(string $filename): bool
    {
        if (!$this->user or !isset ($this->user->id))
            return false;

        return str_starts_with($filename, "{$this->user->id}ts_");
    }


}
