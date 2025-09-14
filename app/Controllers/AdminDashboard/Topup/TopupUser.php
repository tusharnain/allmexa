<?php

namespace App\Controllers\AdminDashboard\Topup;

use App\Controllers\ParentController;
use App\Models\TopupModel;
use App\Twebsol\Plans;

class TopupUser extends ParentController
{
    private array $vd = [];
    private TopupModel $topupModel;

    public function __construct()
    {
        $this->topupModel = new TopupModel;
    }

    private function submitTopup()
    {
        try {

            $res = $this->topupModel->topupUser();

            if (is_array($res))
                return resJson(['success' => false, 'errors' => $res], 400);


            return resJson([
                'success' => true,
                'title' => 'Topup Successful!',
                'message' => 'Topup has been done!',
            ]);

        } catch (\Exception $e) {
            return server_error_ajax($e);
        }

    }


    public function handlePost()
    {
        $action = inputPost('action');

        switch ($action) {

            case 'submit_topup':
                return $this->submitTopup();
        }

        return ajax_404_response();
    }


    public function index()
    {

        if ($this->request->is('post'))
            return $this->handlePost();

        $title = "Topup " . label('user');
        $this->vd = $this->pageData($title, $title, $title);

        return view('admin_dashboard/topup/topup_user', $this->vd);
    }

}