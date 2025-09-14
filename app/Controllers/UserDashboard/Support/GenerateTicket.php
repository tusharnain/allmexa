<?php

namespace App\Controllers\UserDashboard\Support;

use App\Services\UserService;
use App\Models\SupportTicketModel;
use App\Controllers\ParentController;

class GenerateTicket extends ParentController
{
    private array $vd = [];
    private SupportTicketModel $supportTicketModel;
    private array $tree;

    public function __construct()
    {
        $this->supportTicketModel = new SupportTicketModel;
    }

    private function handlePost()
    {
        try {

            // Tpin Verification // if errorArray is true, then validate error with is_array, else with is_string
            if (
                $tpinValidation = UserService::validateRequestTpin(errorArray: true)
                and is_array($tpinValidation)
            ) {
                return resJson($tpinValidation, 400);
            }




            $res = $this->supportTicketModel->createTicket(user_id_pk: user('id'));

            if (is_array($res))
                return resJson(['success' => false, 'errors' => $res], 400);

            return resJson([
                'success' => true,
                'title' => 'Ticket Generated!',
                'message' => 'Your support ticket has been generated, you will be notified once you get the reply.'
            ]);

        } catch (\Exception $e) {

            return server_error_ajax($e);

        }
    }


    public function index()
    {
        if ($this->request->is('post'))
            return $this->handlePost();


        $title = 'Generate Ticket';
        $this->vd = $this->pageData($title, $title, $title);


        if ($ticketCount = $this->supportTicketModel->hasUserOpenTicketLimitReached(user('id'))) {
            $this->vd['openTicketLimitReached'] = "You have $ticketCount tickets open, you can generate more tickets once they're closed.";
            $this->vd['blockForm'] = true;
        }


        return view('user_dashboard/support/generate_ticket', $this->vd); // return string
    }
}
