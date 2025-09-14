<?php

namespace App\Controllers\AdminDashboard\Support;

use App\Models\SupportTicketModel;
use App\Controllers\ParentController;
use App\Models\UserModel;

class Ticket extends ParentController
{
    private array $vd = [];
    private object $ticket;
    private object $user;
    private UserModel $userModel;
    private SupportTicketModel $supportTicketModel;

    public function __construct()
    {
        $this->userModel = new UserModel;
        $this->supportTicketModel = new SupportTicketModel;
    }

    private function validateTicket(string &$ticket_id, bool $validateUser = true)
    {
        if (!($ticket = $this->supportTicketModel->getTicketFromTicketId($ticket_id))) {
            return $this->request->is('post') ? ajax_404_response() : show_404();
        }

        $this->ticket = &$ticket;

        //fetching user
        if ($validateUser)
            $this->user = $this->userModel->getUserFromUserIdPk($this->ticket->user_id, ['id', 'user_id', 'full_name']);
    }



    private function saveAdminReply()
    {
        try {
            //if ticket is already closed
            if ($this->ticket->status) {
                return resJson(['f_redirect' => route('admin.support.ticket', $this->ticket->ticket_id)], 400);
            }

            $res = $this->supportTicketModel->postAdminReply(ticket_id_pk: $this->ticket->id);

            if (is_array($res))
                return resJson(['success' => false, 'errors' => $res], 400);


            $mdata = memory('_admin_ticket_reply');

            $this->ticket->status = $mdata['status'];
            $this->ticket->admin_reply = $mdata['admin_reply'];
            $this->ticket->closed_at = $mdata['closed_at'];

            return resJson([
                'success' => true,
                'view' => view('admin_dashboard/support/__ticket_page_content', [
                    'ticket' => &$this->ticket,
                    'user' => $this->userModel->getUserFromUserIdPk($this->ticket->user_id, ['id', 'user_id', 'full_name'])
                ]),
                'message' => 'Reply has been posted!'
            ]);

        } catch (\Exception $e) {

            return server_error_ajax($e);

        }
    }

    private function handlePost(string $ticket_id)
    {
        $this->validateTicket($ticket_id, validateUser: false);

        $action = inputPost('action');

        switch ($action) {

            case 'admin_reply':
                return $this->saveAdminReply();

        }

        return ajax_404_response();
    }



    public function index(string $ticket_id)
    {
        if ($this->request->is('post'))
            return $this->handlePost($ticket_id);

        $this->validateTicket($ticket_id);


        $title = "Support Ticket / {$this->ticket->ticket_id}";
        $this->vd = $this->pageData($title, $title, $title);

        $this->vd['ticket'] = &$this->ticket;
        $this->vd['user'] = &$this->user;
        return view('admin_dashboard/support/ticket', $this->vd); // return string
    }
}
