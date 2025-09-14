<?php

namespace App\Controllers\UserDashboard\Support;

use App\Libraries\MyLib;
use Config\Services;
use App\Models\SupportTicketModel;
use App\Controllers\ParentController;

class TicketHistory extends ParentController
{
    private array $vd = [];
    private SupportTicketModel $supportTicketModel;
    private array $pageLengths = [15, 30, 50, 100];
    private int $pageLength = 15;

    public function __construct()
    {
        $this->supportTicketModel = new SupportTicketModel;
    }


    private function handlePost()
    {
        $action = inputPost('action');

        switch ($action) {

            case 'get_user_ticket':
                return $this->getUserTicket();

        }

        return ajax_404_response();
    }


    private function getUserTicket()
    {
        $ticket_id_pk = inputPost('ticket_id');

        if (
            !$ticket_id_pk or
            !is_numeric($ticket_id_pk) or
            !($ticket = $this->supportTicketModel->getUserTicketFromTicketIdPkAndUserIdPk($ticket_id_pk, user('id')))
        ) {
            return ajax_404_response();
        }

        $view = MyLib::minifyHtmlCssJs(view('user_dashboard/support/__ticket_modal_content', ['ticket' => &$ticket]));

        return resJson(['success' => true, 'view' => &$view, 'ticket_id' => $ticket->ticket_id]);
    }


    private function setupTable()
    {
        $query = new SupportTicketModel;
        $pager = Services::pager(); // pager

        $query
            ->select(['id', 'ticket_id', 'subject', 'status', 'created_at', 'closed_at'])
            ->where('user_id', user('id'));

        // page length
        $plen = inputGet('pageLength');
        $this->pageLength = ($plen and $plen > 0 and $plen <= 200) ? $plen : $this->pageLength;

        //search
        $search = inputGet('search');

        if ($search) {
            $query->groupStart()
                ->orLike('ticket_id', $search)
                ->groupEnd();

            $this->vd['search'] = $search;
        }

        // sorting
        $query
            ->orderBy('status', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC');

        $this->vd['tickets'] = $query->paginate($this->pageLength);

        $this->vd['pager'] = $pager;
    }


    public function index()
    {
        if ($this->request->is('post'))
            return $this->handlePost();



        $title = 'Support Ticket History';
        $this->vd = $this->pageData($title, $title, $title);

        $this->setupTable();

        $this->vd['pageLengths'] = $this->pageLengths;
        $this->vd['pageLength'] = $this->pageLength;

        return view('user_dashboard/support/ticket_history', $this->vd); // return string
    }
}
