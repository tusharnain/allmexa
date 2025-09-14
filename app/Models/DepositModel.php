<?php

namespace App\Models;

use App\Enums\WalletTransactionCategory as TxnCat;
use App\Services\InputService;
use App\Services\ValidationRulesService;
use CodeIgniter\Database\MySQLi\Builder;


class DepositModel extends ParentModel
{
    protected $table = 'deposits';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $protectFields = true;
    protected $allowedFields = ['track_id', 'user_id', 'amount', 'deposit_mode_id', 'utr', 'receipt_file', 'remarks', 'status', 'admin_remarks', 'admin_resolution_at'];

    const TRACK_ID_INIT_NUMBER = 1000000;
    const TRACK_ID_PREFIX_WORD = 'DEP';

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    private ?Builder $depositModesTable = null;

    private ?WalletModel $walletModel = null;

    // Deposit Status Enums
    const DEPOSIT_STATUS_PENDING = 'pending';
    const DEPOSIT_STATUS_COMPLETE = 'complete';
    const DEPOSIT_STATUS_REJECT = 'reject';

    //Constants
    const DEPOSIT_RECEIPT_DIRECTORY = 'uploads/deposit-receipts';

    public function walletModel(): WalletModel
    {
        return $this->walletModel ??= new WalletModel;
    }


    public static function isValidDepositStatus(string $status): bool
    {
        return in_array($status, [self::DEPOSIT_STATUS_COMPLETE, self::DEPOSIT_STATUS_PENDING, self::DEPOSIT_STATUS_REJECT]);
    }


    public function getDepositFromTrackId(string $track_id, array|string $columns = '*'): object|null
    {
        return $this->select($columns)->where('track_id', $track_id)->get()->getRowObject();
    }

    public function getTotalDepositSumFromUserIdPk(int $user_id_pk, ?string $status = null): float|string
    {
        $where['user_id'] = $user_id_pk;
        if ($status)
            $where['status'] = $status;

        return $this->selectSum('amount')->where($where)->get()->getRowObject()->amount ?? 0;
    }

    public function getDepositFromDepositIdPkAndUserIdPk(int $deposit_id_pk, int $user_id_pk, string|array $columns = '*'): object|null
    {
        return $this->select($columns)->where(['id' => $deposit_id_pk, 'user_id' => $user_id_pk])->first();
    }


    public function creditDepositAmountToFundWallet(int $deposit_id_pk)
    {
        $deposit = $this->select(['track_id', 'user_id', 'amount'])->find(id: $deposit_id_pk);

        if ($deposit) {
            $this->walletModel()->deposit(
                user_id_pk: $deposit->user_id,
                amount: $deposit->amount,
                wallet_field: 'fund',
                category: TxnCat::ADMIN,
                details: [
                    'remarks' => "Credited against deposit #{$deposit->track_id}."
                ]
            );
        }
    }


    public function makeDeposit(int $user_id_pk, object $mode, string $amount, ?string $user_id = null): array|int
    {
        $rcFile = (isset($mode->data->receipt_upload) and $mode->data->receipt_upload) ? true : false;
        $_remarks = (isset($mode->data->allow_remarks) and $mode->data->allow_remarks) ? true : false;

        $data = InputService::inputDepositValues(rcFile: $rcFile, remarks: $_remarks);

        $validationErrors = validate($data, ValidationRulesService::userDepositRules(rcFile: $rcFile, remarks: $_remarks));

        if ($validationErrors) {
            $inputAttribs = InputService::inputDepositValues_attribs();
            foreach ($validationErrors as &$error)
                $error = str_replace(array_keys($inputAttribs), $inputAttribs, $error);
            return ['validationErrors' => $validationErrors];
        }

        $file = $data['receipt_file'] ?? null;
        $utr = $data['utr'];
        $remarks = $data['remarks'];

        $fileName = null;

        $this->db->transBegin();

        try {
            // Saving file, if its uploaded and allowed
            if ($file and $file->isValid()) {
                $randomName = $file->getRandomName();
                $fileName = "{$user_id_pk}ts_{$randomName}";
                $file->move(WRITEPATH . self::DEPOSIT_RECEIPT_DIRECTORY, $fileName);
            }

            $id_pk = $this->insert([
                'user_id' => $user_id_pk,
                'amount' => _cm($amount),
                'deposit_mode_id' => $mode->id,
                'utr' => $utr,
                'remarks' => $remarks,
                'receipt_file' => $fileName,
                'status' => DepositModel::DEPOSIT_STATUS_PENDING
            ], returnID: true);

            $trackIdNumber = self::TRACK_ID_INIT_NUMBER + $id_pk;
            $trackId = self::TRACK_ID_PREFIX_WORD . $trackIdNumber;

            $this->update($id_pk, ['track_id' => $trackId]);


            $this->db->transCommit();

        } catch (\Exception $e) {

            $this->db->transRollback();

            if (!is_null($fileName)) {
                $del_filePath = WRITEPATH . self::DEPOSIT_RECEIPT_DIRECTORY . '/' . $fileName;
                if (file_exists($del_filePath)) {
                    unlink($del_filePath);
                }
            }

            throw $e;
        }

        return 1;
    }





    /*
     *------------------------------------------------------------------------------------
     * Update Deposit Status (Admin)
     *------------------------------------------------------------------------------------
     */
    public function updateDepositStatus(int $depositId): int|array
    {
        $data = InputService::admin_inputUpdateDepositStatusValues();

        $validationErrors = validate($data, ValidationRulesService::admin_inputUpdateDepositStatusRules());

        if ($validationErrors) {
            return ['validationErrors' => $validationErrors];
        }

        $status = $data['status'];
        $remarks = $data['remarks'];

        $currDateTime = $this->dbDate();

        $this->db->transBegin();

        try {

            $this->update($depositId, [
                'status' => $status,
                'admin_remarks' => $remarks,
                'admin_resolution_at' => $currDateTime
            ]);


            // if 'credit_to_wallet' is true, then adding in to their fund wallet
            if ($data['credit_to_wallet']) {
                $this->creditDepositAmountToFundWallet($depositId);
            }


            $info = [
                'status' => $status,
                'admin_resolution_at' => $currDateTime,
                'f_admin_resolution_at' => f_date($currDateTime),
                'remarks_given' => (bool) ($remarks and is_string($remarks) and (strlen($remarks) > 0))
            ];

            memory('admin_deposit_status_update_info', $info);

            $this->db->transCommit();

        } catch (\Exception $e) {

            $this->db->transRollback();

            throw $e;
        }

        return 1;
    }







    /*
     *------------------------------------------------------------------------------------
     * Deposit Modes
     *------------------------------------------------------------------------------------
     */
    public function depositModesTable(): Builder
    {
        return $this->depositModesTable ??= $this->db->table('deposit_modes');
    }
    public function getDepositNameFromIdPk(int $id_pk): string|null
    {
        return $this->depositModesTable()->select('title')->where('id', $id_pk)->get()->getRow()->title ?? null;
    }
    public function getDepositModeFromName(string $name, string|array $columns = '*'): object|null
    {
        return $this->depositModesTable()->select($columns)->where('name', $name)->get()->getRowObject();
    }
    public function getAllDepositModes(string|array $columns = '*'): array
    {
        return $this->depositModesTable()->select($columns)->get()->getResultObject();
    }
    public function getAllVisibleDepositModes(string|array $columns = '*'): array
    {
        return $this->depositModesTable()->select($columns)->where('visibility', 1)->get()->getResultObject();
    }
    public function saveDepositMode(string $name, string $title, array $data, bool $visibility = true)
    {
        $table = $this->depositModesTable();

        $modeData = [
            'name' => $name,
            'title' => $title,
            'visibility' => $visibility,
            'data' => json_encode($data)
        ];

        $_mode = $table->select('id')->where('name', $name)->get()->getRowObject();

        if ($_mode and isset($_mode->id)) {
            $table->where('id', $_mode->id)->update([...$modeData, ...$this->getTimestamps(3)]);
        } else {
            $table->insert([...$modeData, ...$this->getTimestamps()]);
        }
    }

}