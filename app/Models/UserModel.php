<?php

namespace App\Models;

use App\Enums\UserIncomeStats;
use App\Enums\UserToken\UserTokenStatus;
use App\Libraries\UserLib;
use App\Services\InputService;
use App\Services\UserService;
use App\Services\ValidationRulesService;
use App\Twebsol\Plans;
use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\HTTP\Files\UploadedFile;
use App\Twebsol\Settings;
use Exception;
use stdClass;

class UserModel extends ParentModel
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'sponsor_id',
        // 'parent_id',
        // 'placement',
        // 'left_child_id',
        // 'right_child_id',
        'full_name',
        'email',
        'phone',
        'country',
        'country_code',
        'status',
        'user_type',
        'password',
        'is_password_hashed',
        'tpin',
        'is_tpin_hashed',
        'email_verified',
        'login_suspend',
        'roi_start_date',
        'profile_picture',
        'is_usd',
        'booster_club_income_eligibility',
        'activated_at',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    private ?TopupModel $topupModel = null;
    private ?UserIncomeModel $userIncomeModel = null;
    private ?WalletModel $walletModel = null;

    const AVATAR_IMAGE_DIRECTORY = 'avatars/';

    // Enums
    const LoginLogStatus_SUCCESS = 'success';
    const LoginLogStatus_FAIL = 'fail';

    public function tokensTable(): BaseBuilder
    {
        return $this->db->table('user_tokens');
    }
    public function loginLogsTable(): BaseBuilder
    {
        return $this->db->table('user_login_logs');
    }
    public function sessionsTable(): BaseBuilder
    {
        return $this->db->table('user_sessions');
    }
    public function userDetailsTable(): BaseBuilder
    {
        return $this->db->table('user_details');
    }


    // Models
    private function topupModel(): TopupModel
    {
        return $this->topupModel ??= new TopupModel;
    }
    private function userIncomeModel(): UserIncomeModel
    {
        return $this->userIncomeModel ??= new UserIncomeModel;
    }
    private function walletModel(): WalletModel
    {
        return $this->walletModel ??= new WalletModel;
    }

    /*
     *------------------------------------------------------------------------------------
     * Helpers
     *------------------------------------------------------------------------------------
     */
    public function countEmail(string $email): int
    {
        return $this->where('email', $email)->countAllResults(reset: true);
    }

    public function countPhoneNumber(string|int $phoneNumber): int
    {
        return $this->where('phone', $phoneNumber)->countAllResults(reset: true);
    }

    public function isTableEmpty()
    {
        return $this->select('id')->limit(1)->first() ? false : true;
    }

    public function getUserFullNameFromUserIdPk(int $user_id_pk): string|null
    {
        $user = $this->select('full_name')->find($user_id_pk);
        return $user ? $user->full_name : null;
    }
    public function getUserFullNameFromUserId(string $user_id): string|null
    {
        $user = $this->select('full_name')->where('user_id', $user_id)->first();
        return $user ? $user->full_name : null;
    }

    public function getUserFromUserId(string $user_id, string|array $columns = '*'): object|null
    {
        $user = $this->select($columns)->where('user_id', $user_id)->first();
        return $user ?? null;
    }


    public function getUserFromUserIdPk(int $user_id_pk, string|array $columns = '*'): object|null
    {
        $user = $this->select($columns)->find($user_id_pk);
        return $user ?? null;
    }
    public function getUserDetailsFromUserIdPk(int $user_id_pk, array $columns = ['*']): object|null
    {
        return $this->userDetailsTable()->select($columns, true)->where('user_id', $user_id_pk)->get()->getRowObject();
    }


    // the table on which you are gonna use this method, must have a user_id field with foreign key referncing to id column in users table
    public function isUserRecordExists(object $tableObject, int $user_id_pk)
    {
        return $tableObject->select('id', true)->where('user_id', $user_id_pk)->get(1)->getRow() ? true : false;
    }

    public function hasTpin(int $user_id_pk): bool
    {
        return ($user = $this->getUserFromUserIdPk($user_id_pk, ['tpin']) and isset($user->tpin) and $user->tpin);
    }
    public function hasDirectUser(int $user_id_pk): bool
    {
        $child = $this->select('id')->where('sponsor_id', $user_id_pk)->first();
        return ($child and isset($child->id));
    }

    public function hasDirectActiveUser(int $user_id_pk): bool
    {
        $child = $this->select('id')->where('sponsor_id', $user_id_pk)->where('status', true)->first();
        return ($child and isset($child->id));
    }

    public function getDirectReferralsCountFromUserIdPk(int $user_id_pk): int
    {
        return $this->where('sponsor_id', $user_id_pk)->countAllResults(reset: false);
    }
    public function getDirectActiveReferralsCountFromUserIdPk(int $user_id_pk): int
    {
        return $this->where(['sponsor_id' => $user_id_pk, 'status' => true])->countAllResults(reset: false);
    }
    public function getDirectUsersFromUserIdPk(int $user_id_pk, string|array $columns = '*'): array
    {
        return $this->select($columns)->where('sponsor_id', $user_id_pk)->get()->getResultObject();
    }
    public function getDirectActiveUsersFromUserIdPk(int $user_id_pk, string|array $columns = '*'): array
    {
        return $this->select($columns)->where(['sponsor_id' => $user_id_pk, 'status' => true])->get()->getResultObject();
    }
    public function getDirectFinalActiveUsers(int $user_id_pk, array $columns = ['*']): array
    {
        $columnStr = array_map(fn($col) => "users.$col", $columns);
        return $this->select($columnStr)->join('wallets', 'users.id = wallets.user_id', 'JOIN')
            ->where('users.sponsor_id', $user_id_pk)
            ->where('COALESCE(investment, 0) >=', 10000)->get()->getResult();
    }
    public function getDirectActivePaidUsersCount(int $user_id_pk): int|float
    {
        return $this->selectCount('users.id')->join('wallets', 'users.id = wallets.user_id', 'JOIN')
            ->where('users.sponsor_id', $user_id_pk)
            ->where('investment >', 0)
            ->get()->getRow()->id ?? 0;
    }

    public function isUserEligibleForIncome(int $user_id_pk): bool
    {
        return $this->walletModel()->getUserTotalInvestment($user_id_pk) >= 10000;
    }

    public function getTotalSelfBusiness(int $user_id_pk): float
    {
        $data = $this->topupModel()->selectSum('plan_price')->where('user_id', $user_id_pk)->get()->getRowObject();
        return ($data and isset($data->plan_price)) ? $data->plan_price : 0.00;
    }
    public function getTotalDirectBusiness(int $user_id_pk): float
    {
        $data = $this->topupModel()->selectSum('plan_price')
            ->join('users', 'users.id = topups.user_id')
            ->where('users.sponsor_id', $user_id_pk)
            ->get()->getRowObject();

        return ($data and isset($data->plan_price)) ? $data->plan_price : 0.00;
    }

    // setters
    public function activateUser(int $user_id_pk): bool
    {
        return $this->update($user_id_pk, [
            'status' => 1,
            'roi_start_date' => $this->dbDate(),
            'activated_at' => $this->dbDate()
        ]);
    }
    public function suspendLogin(int $user_id_pk): bool
    {
        return $this->update($user_id_pk, ['login_suspend' => 1]);
    }


    // setter
    public function setUserType(int $user_id_pk, string $userType)
    {
        $this->update(id: $user_id_pk, row: ['user_type' => $userType]);
    }




    public function isUserFromTeam(int $userIdPk, int $searchUserIdPk): bool
    {
        $q = new \SplQueue();
        $user = new stdClass();
        $user->id = $userIdPk;
        $q->push($user);

        while (!$q->isEmpty()) {
            $qSize = $q->count();

            for ($i = 0; $i < $qSize; $i++) {
                $node = $q->shift();

                if ($node->id == $searchUserIdPk) {
                    return true;
                }

                $query = $this->select(['id'])->where('sponsor_id', $node->id);

                $users = $query->get()->getResultObject();

                foreach ($users as &$user) {
                    $q->push($user);
                }

            }

        }

        return false;
    }


    /*
     *------------------------------------------------------------------------------------
     * Total Users in Level
     *------------------------------------------------------------------------------------
     */
    public function getTotalUsersFromLevel(int $user_id_pk, int $level, callable|null $user_query = null): array
    {
        if ($level <= 0)
            throw new Exception('Level can be minimum 1.');
        $q = new \SplQueue();
        $user = new stdClass();
        $user->id = $user_id_pk;
        $q->push($user);
        $levelTeamUsers = [];
        while (!$q->isEmpty()) {
            $qSize = $q->count();
            $levelTeamUsers = [];
            for ($i = 0; $i < $qSize; $i++) {
                $node = $q->shift();
                if ($level === 0) {
                    array_push($levelTeamUsers, $node);
                } else {

                    $query = ($user_query and is_callable($user_query)) ?
                        $user_query($this, $node) :
                        $this->select()->where('users.sponsor_id', $node->id)->orderBy('created_at', 'DESC')->orderBy("id", 'DESC');

                    $users = $query->get()->getResultObject();

                    foreach ($users as &$user) {
                        $q->push($user);
                    }
                }
            }
            if ($level === 0)
                return $levelTeamUsers;
            $level--;
        }
        return [];
    }




    /*
     *------------------------------------------------------------------------------------
     * Total Team Count
     *------------------------------------------------------------------------------------
     */
    public function getTotalTeamCount(int $user_id_pk, int $upto_level): int
    {
        $q = new \SplQueue();
        $user = new stdClass();
        $user->id = $user_id_pk;
        $q->push($user);
        $teamCount = 0;
        while (!$q->isEmpty()) {
            $qSize = $q->count();
            $levelTeamCount = $qSize;
            for ($i = 0; $i < $qSize; $i++) {
                $node = $q->shift();
                $users = $this->getDirectUsersFromUserIdPk(user_id_pk: $node->id, columns: ['id']);
                foreach ($users as &$user) {
                    $q->push($user);
                }
            }
            $teamCount += $levelTeamCount;

            if ($upto_level-- <= 0)
                break;
        }
        return $teamCount - 1; // -1 for self remove from team
    }

    /*
     *------------------------------------------------------------------------------------
     * Total Active Team Count
     *------------------------------------------------------------------------------------
     */
    public function getTotalActiveTeamCount(int $user_id_pk, int $upto_level): int
    {
        $q = new \SplQueue();
        $user = $this->getUserFromUserIdPk(user_id_pk: $user_id_pk, columns: ['id', 'status']);
        $user_status = $user->status;
        $q->push($user);
        $activeTeamCount = 0;
        $currentLevel = -1;
        while (!$q->isEmpty() && $currentLevel < $upto_level) {
            $currentLevel++;
            $qSize = $q->count();
            $levelActiveTeamCount = 0;
            for ($i = 0; $i < $qSize; $i++) {
                $node = $q->shift();
                if ($node->status)
                    $levelActiveTeamCount++;
                $users = $this->getDirectUsersFromUserIdPk(user_id_pk: $node->id, columns: ['id', 'status']);
                foreach ($users as &$user) {
                    $q->push($user);
                }
            }
            $activeTeamCount += $levelActiveTeamCount;
        }
        return $user_status ? $activeTeamCount - 1 : $activeTeamCount;
    }



    /*
     *------------------------------------------------------------------------------------
     * Level Team
     *------------------------------------------------------------------------------------
     */
    public function getLevelTeam(int $user_id_pk, int $upto_level): array
    {
        if ($upto_level <= 0)
            return [];
        // here $user_id_pk must be a valid user's id pk
        $q = new \SplQueue();
        $user = new stdClass();
        $user->id = $user_id_pk;
        $q->push($user);
        $levels = [];
        $levelNo = 1;
        while (!$q->isEmpty()) {
            $qSize = $q->count();
            $totalUsers = 0;
            $totalActiveUsers = 0;
            for ($i = 0; $i < $qSize; $i++) {
                $node = $q->shift();
                $users = $this->getDirectUsersFromUserIdPk(user_id_pk: $node->id, columns: ['id', 'status']);
                $totalUsers += count($users); // count of all users
                foreach ($users as &$user) {
                    $q->push($user);
                    $totalActiveUsers += ($user->status) ? 1 : 0; // counting active users
                }
            }
            if ($totalUsers > 0) {
                $level = new stdClass;
                $level->level = $levelNo;
                $level->totalUsers = $totalUsers;
                $level->totalActiveUsers = $totalActiveUsers;
                $level->totalInactiveUsers = $totalUsers - $totalActiveUsers;
                $levels[] = $level;
            }
            if ($levelNo >= $upto_level)
                break;
            $levelNo++;
        }
        return $levels;
    }


    public function getUserOpenLevel(int $user_id_pk, ?string $userId = null): int
    {
        $MAX_LEVEL_OPEN = 15;

        if ($userId && in_array($userId, Settings::SUPER_IDS)) {
            return $MAX_LEVEL_OPEN;
        }

        $count = $this->getDirectActiveReferralsCountFromUserIdPk($user_id_pk);

        return min($count, $MAX_LEVEL_OPEN);
    }


    /*
     *------------------------------------------------------------------------------------
     * Total Team Investment
     *------------------------------------------------------------------------------------
     */
    public function getTeamInvestment(int $user_id_pk, int $upto_level): float|string
    {
        $q = new \SplQueue();
        $user = new stdClass();
        $user->id = $user_id_pk;
        $userIncomeModel = $this->userIncomeModel();
        $q->push($user);
        $totalInvestment = 0;
        while (!$q->isEmpty()) {
            $qSize = $q->count();
            for ($i = 0; $i < $qSize; $i++) {
                $node = $q->shift();
                $totalInvestment += $this->walletModel()->getUserTotalInvestment($node->id);
                if ($upto_level > 0) {
                    $users = $this->getDirectUsersFromUserIdPk(user_id_pk: $node->id, columns: ['id']);
                    foreach ($users as &$user)
                        $q->push($user);
                }
            }
            if ($upto_level-- <= 0)
                break;
        }

        return $totalInvestment - $this->walletModel()->getUserTotalInvestment($user_id_pk); // - for self remove from team
    }


    /*
     *------------------------------------------------------------------------------------
     * Power Leg Business
     *------------------------------------------------------------------------------------
     */
    public function getPowerLegBusiness(int $user_id_pk): float|string
    {

        $userIncomeModel = $this->userIncomeModel();
        $walletModel = $this->walletModel();

        $directChilds = $this->getDirectUsersFromUserIdPk($user_id_pk, ['id']);

        $powerLegInvestment = 0;
        $lls = [];

        // iterative child of the user
        foreach ($directChilds as $childUser) {


            $childUserInvestment = $walletModel->getUserTotalInvestment($childUser->id);

            $childTeamInvestment = $this->getTeamInvestment($childUser->id, 9999999999); // infinine levels

            $legInvestment = $childUserInvestment + $childTeamInvestment;

            if ($legInvestment > $powerLegInvestment)
                $powerLegInvestment = $legInvestment;

            $lls[] = $childTeamInvestment;
        }

        return $powerLegInvestment;
    }



    /*
     *------------------------------------------------------------------------------------
     * Binary Plan Helpers
     *------------------------------------------------------------------------------------
     */
    public static function binary_getFieldFromLandR(string $leg): string
    {
        // expected legs are 'l' and 'r' only
        $field = null;
        if ($leg === 'l')
            $field = 'left_child_id';
        else if ($leg === 'r')
            $field = 'right_child_id';
        else
            throw new \Exception("The leg field is supposed to be only 'l' or 'r'.");

        return $field;
    }
    //get left right both childs in an array
    public function binary_getLeftRightChildFromUserIdPk(int $user_id_pk, array $columns = ['*']): array
    {
        $user = $this->select(['left_child_id', 'right_child_id'])->find($user_id_pk);
        return [
            isset($user->left_child_id) ? $this->getUserFromUserIdPk($user->left_child_id, $columns) : null,
            isset($user->right_child_id) ? $this->getUserFromUserIdPk($user->right_child_id, $columns) : null
        ];
    }
    // get left child object
    public function binary_getLeftChildFromUserIdPk(int $user_id_pk, array $columns = ['*']): object|null
    {
        $user = $this->select('left_child_id')->find($user_id_pk);
        return ($user and isset($user->left_child_id)) ? $this->getUserFromUserIdPk($user->left_child_id, $columns) : null;
    }
    //get right child object
    public function binary_getRightChildFromUserIdPk(int $user_id_pk, array $columns = ['*']): object|null
    {
        $user = $this->select('right_child_id')->find($user_id_pk);
        return ($user and isset($user->right_child_id)) ? $this->getUserFromUserIdPk($user->right_child_id, $columns) : null;
    }

    //get downmost(leftmost or rightmost) user of a user
    public function binary_getDownMostUserFromUserIdPk(int $user_id_pk, string $leg, array $columns = ['*'], bool $only_return_id_pk = false): object|int|null
    {
        $leg_field = UserModel::binary_getFieldFromLandR($leg);

        $user = $this->getUserFromUserIdPk($user_id_pk, $leg_field);

        $user_id = null;
        while ($user and isset($user->{$leg_field})) {
            $user_id = $user->{$leg_field};
            $user = $this->getUserFromUserIdPk($user->{$leg_field}, $leg_field);
        }

        if ($only_return_id_pk and $user)
            return $user_id ?? $user_id_pk;

        return $user ? $this->getUserFromUserIdPk($user->id, $columns) : null;
    }
    public function binary_getUserBinaryTreeFromUserIdPk(int $user_id_pk, int $uptoLevel = 3): null|array
    {
        if ($uptoLevel < 1)
            return [];

        $columns = ['id', 'user_id', 'left_child_id', 'right_child_id', 'full_name', 'status', 'profile_picture'];

        $result = [];

        $q = new \SplQueue();

        $root = $this->getUserFromUserIdPk($user_id_pk, $columns);

        if (!$root)
            return null;

        $q->push($root);

        $result[] = [$root];

        while (!$q->isEmpty()) {

            $qsize = $q->count();

            $lvArray = [];


            for ($i = 0; $i < $qsize; $i++) {

                $node = $q->shift() ?? null;

                $lc = $rc = null;

                if ($node and $node->left_child_id)
                    $lc = $this->getUserFromUserIdPk($node->left_child_id, $columns);

                if ($node and $node->right_child_id)
                    $rc = $this->getUserFromUserIdPk($node->right_child_id, $columns);


                $lvArray[] = $lc;
                $lvArray[] = $rc;

                $q->push($lc);
                $q->push($rc);
            }


            $result[] = $lvArray;

            if (--$uptoLevel === 0) {
                return $result;
            }
        }

        return [];
    }


    public function binary_getParentIdPkforNewUser(int $sponsor_id_pk, string $placement): int|null
    {
        return $this->binary_getDownMostUserFromUserIdPk($sponsor_id_pk, $placement, only_return_id_pk: true);
    }

    public function binary_setChildToAParentIdPk(int $parent_id_pk, int $child_id_pk, string $leg): bool
    {
        $leg_field = UserModel::binary_getFieldFromLandR($leg);

        return $this->update($parent_id_pk, [$leg_field => $child_id_pk]);
    }




    /*
     *------------------------------------------------------------------------------------
     * Single Level Plan Helpers
     *------------------------------------------------------------------------------------
     */
    public function single_getParentIdPkforNewUser(): int|null
    {
        $object = $this->select('id')->orderBy('id', 'DESC')->limit(1)->get()->getRowObject();
        return ($object and $object->id) ? $object->id : null; // last registered user_id_pk
    }
    public function single_getSingleLevelTeamCountFromUserIdPk(int $user_id_pk): int
    {
        return $this->where('id >', $user_id_pk)->countAllResults(reset: true);
    }






    /*
     *------------------------------------------------------------------------------------
     * REGISTRATION - Start
     *------------------------------------------------------------------------------------
     */
    public function register(?array $inputs = null, bool $isAdmin = false, bool $isFirstUser = false, $forOtp = false): array|object
    {
        // Return array if validation error or object if success

        $data = $inputs ? $inputs : InputService::inputRegistrationValues();


        $regCaptchaStatus = (!$isAdmin and _setting('registration_captcha')); // admin doesnt need captcha

        //labels
        $sponsorIdLabel = label('sponsor_id');

        if ($isFirstUser)
            unset($data['sponsor_id']);

        if (!$regCaptchaStatus)
            unset($data['captcha']);

        $validationErrors = validate($data, ValidationRulesService::userRegistrationRules($isAdmin, $isFirstUser));


        if ($validationErrors) {

            $inputAttribs = InputService::inputRegistrationValues_attribs();

            foreach ($validationErrors as &$error)
                $error = str_replace(array_keys($inputAttribs), $inputAttribs, $error);

            return ['validationErrors' => $validationErrors];
        }

        // $placement = $data['placement'] === 'left' ? 'l' : 'r'; // binary thing
        // unset($data['placement']); //necessary


        // Sponsor Id Check
        if ($isFirstUser) {
            $sponsor_id_pk = null;
        } else {
            $sponsor = $this->getUserFromUserId($data['sponsor_id'], ['id']);

            if (!$sponsor or !$sponsor->id)
                return ['validationErrors' => ['sponsor_id' => "$sponsorIdLabel doesn't exits."]];

            $sponsor_id_pk = $sponsor->id;
        }


        // Email Count Check ----------------------------------------------------------------------
        $emailLimit = _setting('registraton_same_email_limit', 1);

        $existingEmailCount = $this->countEmail($data['email']);

        if ($existingEmailCount >= $emailLimit) {
            if ($emailLimit > 1)
                $msg = "Email has been reached its limit of $emailLimit registrations.";
            else
                $msg = "Email has already been registered.";

            return ['validationErrors' => ['email' => $msg]];
        }

        unset($data['sponsor_id'], $data['cpassword'], $data['tnc'], $data['country']);


        $userId = UserLib::generateNewUserId();


        $userData = [
            'success' => true,
            'user_id' => $userId,
            'sponsor_id' => $sponsor_id_pk,
            'is_usd' => 1,
            ...$data,
            ...UserService::makePassword($data['password'])
        ];

        if ($forOtp) {
            return $userData;
        }

        $this->db->transBegin();

        try {
            $user_id_pk = $this->insert($userData, returnID: true);

            $this->db->transCommit();
        } catch (Exception $e) {

            $this->db->transRollback();

            throw $e;
        }


        $user = new stdClass;
        $user->userId = $userId;
        $user->joiningDate = f_date(time());

        return $user;
    }




    /*
     *------------------------------------------------------------------------------------
     * Login - Start
     *------------------------------------------------------------------------------------
     */
    public function login(): array|object
    {
        $isEmailLoginAllowed = _setting('allow_user_login_with_email', false);

        // Return array if validation error or object if success

        $data = InputService::inputLoginValues();


        $userIdLabel = label('user_id');

        $validationErrors = validate($data, ValidationRulesService::userLoginRules());

        if ($validationErrors) {

            $inputAttribs = InputService::inputRegistrationValues_attribs();

            foreach ($validationErrors as &$error)
                $error = str_replace(array_keys($inputAttribs), $inputAttribs, $error);

            return ['validationErrors' => $validationErrors];
        }

        $isEmailInput = false;

        if ($isEmailLoginAllowed) {
            if (is_email($data['username'])) {
                $isEmailInput = true;
                $user = $this->where('email', $data['username'])->first();
            } else {
                $user = $this->getUserFromUserId($data['username']);
            }
        } else {
            $user = $this->getUserFromUserId($data['user_id']);
        }

        if (!$user) {
            $message = $isEmailInput ? 'Invalid email or password.' : "Invalid $userIdLabel or Password.";
            return ['error' => $message];
        }


        if (!$this->verifyPassword($user, $data['password'])) {
            return [
                'error' => "Invalid $userIdLabel or Password.",
                'wrong_password' => $user->id
            ];
        }


        return $user;
    }



    /*
     *------------------------------------------------------------------------------------
     * User Profile 
     *------------------------------------------------------------------------------------
     */
    public function updateUser(int &$user_id_pk, bool $isAdmin = false): array|bool
    {
        $data = InputService::inputUserValues(isAdmin: $isAdmin);

        if (count($data) <= 0)
            return true;

        $validationErrors = validate($data, ValidationRulesService::userDataRules(isAdmin: $isAdmin));

        if ($validationErrors) {

            $inputAttribs = InputService::inputProfileValues_attribs();

            foreach ($validationErrors as &$error)
                $error = str_replace(array_keys($inputAttribs), $inputAttribs, $error);

            return ['validationErrors' => $validationErrors];
        }

        $filename = null;

        $this->db->transBegin();

        try {

            $file = $data['profile_picture'];

            if ($file and $file->isValid() and !$file->hasMoved()) {
                if ($pfpObj = $this->getUserFromUserIdPk($user_id_pk, 'profile_picture'))
                    $currentPfp = $pfpObj->profile_picture;
                $data['profile_picture'] = $filename = $this->uploadAvatar(imageFile: $file);
            } else
                unset($data['profile_picture']);

            if (!empty($data))
                $this->update($user_id_pk, $data);

            // re-setting the user session
            if ($isAdmin) {
                if ($filename)
                    memory('admin_pfp_updated', $filename);
            } else {
                UserService::loginSessionData(user: $this->getUserFromUserIdPk($user_id_pk));
            }

            // now finally remvoing old pfp
            if (isset($currentPfp))
                $this->deleteAvatarFromStorage(filename: $currentPfp);


            $this->db->transCommit();
        } catch (Exception $e) {

            $this->db->transRollback();

            if ($filename)
                $this->deleteAvatarFromStorage($filename);

            throw $e;
        }

        return true;
    }
    public function updateUserDetails(int &$user_id_pk, bool $isAdmin = false): array|bool
    {

        $data = InputService::inputProfileValues(isAdmin: $isAdmin);

        if (count($data) <= 0)
            return true;


        $validationErrors = validate($data, ValidationRulesService::userProfileRules(isAdmin: $isAdmin));

        if ($validationErrors) {
            $inputAttribs = InputService::inputProfileValues_attribs();

            foreach ($validationErrors as &$error)
                $error = str_replace(array_keys($inputAttribs), $inputAttribs, $error);

            return ['validationErrors' => $validationErrors];
        }


        foreach ($data as $key => $val)
            if (is_null($val))
                unset($data[$key]);

        $table = $this->userDetailsTable();

        if ($this->isUserRecordExists($table, $user_id_pk)) {

            return $table->where('user_id', $user_id_pk)->update([
                ...$data,
                ...$this->getTimestamps(3)
            ]);
        }


        if (array_has_non_null_values($data)) {

            return $table->insert([
                'user_id' => $user_id_pk,
                ...$data,
                ...$this->getTimestamps()
            ], true);
        }

        return true;
    }

    /*
     *------------------------------------------------------------------------------------
     * Change password
     *------------------------------------------------------------------------------------
     */
    public function changePassword(int $user_id_pk, bool $isAdmin = false): array|bool
    {
        $data = InputService::inputChangePasswordValues(isAdmin: $isAdmin);

        $validationErrors = validate($data, ValidationRulesService::userChangePasswordRules(isAdmin: $isAdmin));

        if ($validationErrors) {
            $inputAttribs = InputService::inputChangePasswordValues_attribs();
            foreach ($validationErrors as &$error)
                $error = str_replace(array_keys($inputAttribs), $inputAttribs, $error);
            return ['validationErrors' => $validationErrors];
        }


        $user = $this->getUserFromUserIdPk($user_id_pk, ['password', 'is_password_hashed']);

        if (!$isAdmin and !$this->verifyPassword($user, $data['cpassword']))
            return ['validationErrors' => ['cpassword' => 'Entered Password is wrong.']];


        $this->db->transBegin();

        try {

            $this->updateUserPasswordInTable(user_id_pk: $user_id_pk, password: $data['npassword']);

            $currTime = $this->dbDate();

            if (!$isAdmin)
                memory("{$user_id_pk}_last_password_change_at", $currTime);

            $this->db->transCommit();
        } catch (Exception $e) {

            $this->db->transRollback();

            throw $e;
        }

        return true;
    }



    /*
     *------------------------------------------------------------------------------------
     * Change TPIN
     *------------------------------------------------------------------------------------
     */
    public function changeTpin(int $user_id_pk, bool $hasTpin, bool $isAdmin = false): array|bool
    {
        $data = InputService::inputChangeTPinValues(hasTpin: $hasTpin, isAdmin: $isAdmin);

        $validationErrors = validate($data, ValidationRulesService::userChangeTpinRules(hasTpin: $hasTpin, isAdmin: $isAdmin));

        if ($validationErrors) {
            $inputAttribs = InputService::inputChangeTPinValues_attribs();
            foreach ($validationErrors as &$error)
                $error = str_replace(array_keys($inputAttribs), $inputAttribs, $error);
            return ['validationErrors' => $validationErrors];
        }

        $tpinLabel = label('tpin');

        $user = $this->getUserFromUserIdPk($user_id_pk, ['tpin', 'is_tpin_hashed']);

        // Only verify old tpin, if user has a tpin set up
        if (!$isAdmin and $hasTpin and !$this->verifyTpin($user, $data['ctpin']))
            return ['validationErrors' => ['ctpin' => "Entered $tpinLabel is wrong."]];

        $this->db->transBegin();

        try {
            $this->update($user_id_pk, UserService::makeTpin($data['ntpin']));

            // updating password change log
            $udTable = $this->userDetailsTable();

            $currTime = $this->dbDate();

            if ($this->isUserRecordExists($udTable, $user_id_pk)) {
                $udTable->where('user_id', $user_id_pk)->update([
                    'last_tpin_change_at' => $currTime,
                    ...$this->getTimestamps(3)
                ]);
            } else {
                $udTable->insert([
                    'user_id' => $user_id_pk,
                    'last_tpin_change_at' => $currTime,
                    ...$this->getTimestamps()
                ], true);
            }

            if (!$isAdmin)
                memory("{$user_id_pk}_last_tpin_change_at", $currTime);

            $this->db->transCommit();
        } catch (Exception $e) {

            $this->db->transRollback();

            throw $e;
        }

        return true;
    }



    /*
     *------------------------------------------------------------------------------------
     * Reset Password (From Token)
     *------------------------------------------------------------------------------------
     */
    public function resetPassword(stdClass $tokenObject)
    {
        //* $tokenObject must have props -> id, user_id and token string

        $user_id_pk = $tokenObject->user_id;

        $data = InputService::inputResetPassword();

        $validationErrors = validate($data, ValidationRulesService::userResetPasswordRules());

        if ($validationErrors) {
            $inputAttribs = InputService::PASSWORD_CREATE_ATTRIBS;
            foreach ($validationErrors as &$error)
                $error = str_replace(array_keys($inputAttribs), $inputAttribs, $error);
            return ['validationErrors' => $validationErrors];
        }

        $password = $data['password'];

        $this->db->transBegin();

        try {

            $this->updateUserPasswordInTable(user_id_pk: $user_id_pk, password: $password);

            // marking token as used
            $this->updateUserToken(token_id_pk: $tokenObject->id, data: ['status' => UserTokenStatus::USED]);

            $this->db->transCommit();
        } catch (Exception $e) {
            $this->db->transRollback();
            throw $e;
        }

        return true;
    }

    private function updateUserPasswordInTable(int $user_id_pk, string $password)
    {
        // updating password
        $this->update($user_id_pk, UserService::makePassword($password));

        // updating password change log
        $udTable = $this->userDetailsTable();
        $currTime = $this->dbDate();
        if ($this->isUserRecordExists($udTable, $user_id_pk)) {
            $udTable->where('user_id', $user_id_pk)
                ->update(['last_password_change_at' => $currTime, ...$this->getTimestamps(3)]);
        } else {
            $udTable->insert(['user_id' => $user_id_pk, 'last_password_change_at' => $currTime, ...$this->getTimestamps()], true);
        }
    }


    /*
     *------------------------------------------------------------------------------------
     * User Session
     *------------------------------------------------------------------------------------
     */
    public function saveUserSession(int $user_id_pk, string $session_id, string $rememberToken = null, int $rememberTokenExpire = null)
    {

        $table = $this->sessionsTable();


        $rememberTokenExpire = $rememberTokenExpire ? $this->dbDate(time() + $rememberTokenExpire) : null;

        if ($this->isUserRecordExists($table, $user_id_pk)) {
            return $table->where('user_id', $user_id_pk)->update([
                'session_id' => $session_id,
                'remember_token' => $rememberToken,
                'remember_token_expire' => $rememberTokenExpire,
                ...$this->getTimestamps(3)
            ]);
        }

        return $table->insert([
            'user_id' => $user_id_pk,
            'session_id' => $session_id,
            'remember_token' => $rememberToken,
            'remember_token_expire' => $rememberTokenExpire,
            ...$this->getTimestamps()
        ], true);
    }

    public function verifyRememberToken(string $token): object|null
    {
        $table = $this->sessionsTable();

        $tokenRecord = $table->select(['id', 'user_id', 'remember_token_expire'], true)
            ->where('remember_token', $token)
            ->get(1)
            ->getRow();

        if ($tokenRecord) {

            $expireTime = strtotime($tokenRecord->remember_token_expire);

            if ($expireTime > time()) {
                //token is valid
                return $this->getUserFromUserIdPk($tokenRecord->user_id);
            }
            // token expired, removing it
            $table->where('id', $tokenRecord->id)->delete();
        }

        return null;
    }
    // 
    public function removeUserSession(int $user_id_pk)
    {
        $this->sessionsTable()->where('user_id', $user_id_pk)->delete();
    }


    public function saveUserToken(int $user_id_pk, string $token_type, string $token, int $expireIn = 0): bool
    {
        return $this->tokensTable()->insert([
            'user_id' => $user_id_pk,
            'token_type' => $token_type,
            'token' => $token,
            'status' => UserTokenStatus::UNUSED,
            'expire_at' => $this->dbDate(time() + $expireIn),
            ...$this->getTimestamps()
        ], escape: true);
    }

    public function getUserTokenFromToken(string $token, string $token_type, string|array $columns = '*'): stdClass|null
    {
        // getting only valid unused token.
        return $this->tokensTable()->select($columns)
            ->where([
                'token_type' => $token_type,
                'token' => $token,
                'status !=' => UserTokenStatus::USED,
                'expire_at >' => $this->dbDate()
            ])->get()->getRowObject();
    }
    public function extendUserToken(int|stdClass $token, int $extend_seconds = 300)
    {
        // if $token is object recieved in function, then it must have id and expire_at prop
        if (is_int($token))
            $token = $this->tokensTable()->select(['id', 'expire_at'])->where('id', $token)->get()->getRowObject();

        if ($extend_seconds <= 0)
            throw new Exception('$extend_seconds cannot be less than or equal to 0');

        if ($token) {
            $this->updateUserToken(
                token_id_pk: $token->id,
                data: [
                    'status' => UserTokenStatus::EXTENDED,
                    'expire_at' => $this->dbDate(strtotime($token->expire_at) + $extend_seconds)
                ]
            );
        }
    }
    public function updateUserToken(int $token_id_pk, array $data)
    {
        return $this->tokensTable()->update(set: $data, where: ['id' => $token_id_pk]);
    }


    /*
     *------------------------------------------------------------------------------------
     * Login Logs
     *------------------------------------------------------------------------------------
     */
    public function getUserLastSuccessLoginFromUserIdPk(int $user_id_pk, int $lastHowMany = 1, string|array $columns = '*'): array|object|null
    {
        $query = $this->loginLogsTable()->select($columns)
            ->where(['user_id' => $user_id_pk, 'status' => UserModel::LoginLogStatus_SUCCESS])
            ->orderBy('id', 'DESC')
            ->limit($lastHowMany)
            ->get();

        if ($lastHowMany > 1)
            return $query->getResultObject();

        return $query->getRowObject();
    }
    public function getUserTotalSuccessLoginsFromUserIdPk(int $user_id_pk, ?string $status = null): int
    {
        $where['user_id'] = $user_id_pk;
        if (!is_null($status))
            $where['status'] = $status;

        return $this->loginLogsTable()->selectCount('id')->where($where)->get()->getRowObject()->id ?? 0;
    }
    public function saveLoginLog(int $user_id_pk, string $ip_address = null, string $os = null, string $browser = null, string $user_agent = null, $remember_login = false, string $status = self::LoginLogStatus_SUCCESS, string $message = null): bool
    {
        return $this->loginLogsTable()->insert([
            'user_id' => $user_id_pk,
            'ip_address' => $ip_address,
            'os' => $os,
            'browser' => $browser,
            'user_agent' => $user_agent,
            'status' => $status,
            'message' => $message,
            'remember_login' => $remember_login,
            ...$this->getTimestamps(2)
        ], true);
    }



    //
    public function verifyPassword(object $user, string $password, bool $fromDatabase = false): bool
    {
        if ($fromDatabase)
            $user = $this->getUserFromUserIdPk($user->id, ['password', 'is_password_hashed']);
        if (!$user->password)
            return false;
        // $user object must have $user->password, $user->is_password_hashed
        if ($user->is_password_hashed)
            return password_verify($password, $user->password);
        return $user->password === $password;
    }

    public function verifyTpin(object $user, string $tpin, bool $fromDatabase = false): bool
    {
        if ($fromDatabase)
            $user = $this->getUserFromUserIdPk($user->id, ['tpin', 'is_tpin_hashed']);
        if (!$user->tpin)
            return false;
        // $user object must have $user->tpin, $user->is_tpin_hashed
        if ($user->is_tpin_hashed)
            return password_verify($tpin, $user->tpin);
        return $user->tpin === $tpin;
    }


    // statics
    public static function getAvatarFromImageName(string $filename): string
    {
        return uploaded_file_url(UserModel::AVATAR_IMAGE_DIRECTORY . $filename);
    }
    public static function getDefaultAvatarImage(): string
    {
        return base_url('images/default-avatar.png') . '?v=1.1';
    }
    public static function getAvatarDirctoryPath(): string
    {
        return uploaded_file_url(UserModel::AVATAR_IMAGE_DIRECTORY); // avatar direcotry url
    }
    public static function getAvatar(object $user): string
    {
        if ($user->profile_picture)
            return uploaded_file_url(UserModel::AVATAR_IMAGE_DIRECTORY . $user->profile_picture);
        else
            return self::getDefaultAvatarImage();
    }
    public function uploadAvatar(UploadedFile $imageFile): string
    {
        load_helper_if_not_function('path', 'upload_dir');

        $dir = UserModel::AVATAR_IMAGE_DIRECTORY;

        $destination = upload_dir($dir);

        $filename = $imageFile->getRandomName();

        $imageFile->move($destination, $filename);

        return $filename;
    }
    public function deleteAvatarFromStorage(string $filename)
    {
        load_helper_if_not_function('path', 'upload_dir');

        $filepath = upload_dir(UserModel::AVATAR_IMAGE_DIRECTORY . $filename);

        if (file_exists($filepath))
            unlink($filepath);
    }
}
