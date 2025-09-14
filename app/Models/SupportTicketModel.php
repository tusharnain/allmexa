<?php

namespace App\Models;

use App\Services\InputService;
use App\Services\ValidationRulesService;


class SupportTicketModel extends ParentModel
{
    protected $table = 'support_tickets';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $protectFields = true;
    protected $allowedFields = ['ticket_id', 'user_id', 'subject', 'message', 'admin_reply', 'status', 'closed_at', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';


    //helpers
    public function getUserOpenTicketsCountFromUserIdPk(int $user_id_pk): int
    {
        return $this->selectCount('id')->where(['user_id' => $user_id_pk, 'status' => 0])->first()->id;
    }
    public function getTicketFromTicketIdPk(int $ticket_id_pk, string|array $columns = '*'): null|object
    {
        return $this->select($columns)->find($ticket_id_pk);
    }
    public function getTicketFromTicketId(string $ticket_id, string|array $columns = '*'): null|object
    {
        return $this->select($columns)->where('ticket_id', $ticket_id)->first();
    }
    public function getUserTicketFromTicketIdPkAndUserIdPk(int $ticket_id_pk, int $user_id_pk, string|array $columns = '*'): null|object
    {
        return $this->select($columns)->where(['id' => $ticket_id_pk, 'user_id' => $user_id_pk])->first();
    }


    public function hasUserOpenTicketLimitReached(int $user_id_pk): null|int
    {
        //null means->"limit not reached"
        //int means->"limit reached"

        $userOpenTicketLimit = _setting('max_open_tickets_for_user', 3);
        if ($userOpenTicketLimit and $userOpenTicketLimit > 0) {
            $userOpenTickets = $this->getUserOpenTicketsCountFromUserIdPk($user_id_pk);
            if ($userOpenTickets >= $userOpenTicketLimit)
                return $userOpenTickets;
        }
        return null;
    }


    public function createTicket(int $user_id_pk): array|int
    {
        $data = InputService::inputTicketValues();

        $validationErrors = validate($data, ValidationRulesService::userTicketRules());

        if ($validationErrors) {
            return ['validationErrors' => $validationErrors];
        }

        if ($ticketCount = $this->hasUserOpenTicketLimitReached($user_id_pk)) {
            return ['error' => "You have $ticketCount open tickets, you can generate more ticket once they are closed."];
        }

        $pk_id = $this->insert([
            'user_id' => $user_id_pk,
            'status' => 0,
            ...$data // subject, message
        ], returnID: true);

        if ($pk_id) {
            $ticketId = 1000000 + $pk_id;
            $this->update($pk_id, ['ticket_id' => "$ticketId"]);
        }
        return 1;
    }

    public function postAdminReply(int $ticket_id_pk): array|int
    {
        $adminReply = inputPost('reply');

        if ($validationErrors = validate(['reply' => $adminReply], ['reply' => ['required', 'string', 'min_length[10]', 'max_length[2000]']])) {
            return ['validationErrors' => $validationErrors];
        }

        $data = [
            'admin_reply' => $adminReply,
            'status' => 1,
            'closed_at' => $this->dbDate()
        ];

        $this->update($ticket_id_pk, $data);
        memory('_admin_ticket_reply', $data);
        return 1;
    }


}