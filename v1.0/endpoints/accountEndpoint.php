<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');

require_once($CONFIG->basepath . '/v1.0/endpoints/userEndpoint.php');

use Ramsey\Uuid\Uuid;
use Skautis\Skautis;

use Skaut\HandbookAPI\v1_0\Database;
use Skaut\HandbookAPI\v1_0\Endpoint;
use Skaut\HandbookAPI\v1_0\Helper;
use Skaut\HandbookAPI\v1_0\Role;
use Skaut\HandbookAPI\v1_0\Exception\AuthenticationException;

$accountEndpoint = new Endpoint();

$listAccount = function (Skautis $skautis, array $data) : array {
    $getAccount = function (Skautis $skautis) use ($data) : array {
        $SQL = <<<SQL
SELECT `group_id`
FROM `users_in_groups`
WHERE `user_id` = :id;
SQL;

        $response = [];
        $loginDetail = $skautis->UserManagement->LoginDetail();
        $response['name'] = $loginDetail->Person;
        $response['role'] = Role::get($loginDetail->ID_Person);
        $response['groups'] = [];

        $db = new Database();
        $db->prepare($SQL);
        $db->bindParam(':id', $loginDetail->ID_Person, PDO::PARAM_INT);
        $db->execute();
        $uuid = '';
        $db->bindColumn('group_id', $uuid);
        while ($db->fetch()) {
            $response['groups'][] = Uuid::fromBytes($uuid)->toString();
        }

        if (!isset($data['no-avatar']) or $data['no-avatar'] == 'false') {
            $ISphotoResponse = $skautis->OrganizationUnit->PersonPhoto([
                'ID' => $loginDetail->ID_Person,
                'Size' => 'small']);
            if (isset($ISphotoResponse->PhotoContent) and $ISphotoResponse->PhotoContent != '') {
                $response['avatar'] = base64_encode($ISphotoResponse->PhotoContent);
            }
        }
        return ['status' => 200, 'response' => $response];
    };

    try {
        return Helper::skautisTry($getAccount, false);
    } catch (AuthenticationException $e) {
        header('www-authenticate: SkautIS');
        return ['status' => 401];
    }
};
$accountEndpoint->setListMethod(new Role('guest'), $listAccount);

$addAccount = function (Skautis $skautis) : array {
    global $userEndpoint;
    $loginDetail = $skautis->UserManagement->LoginDetail();
    $userData = ['id' => $loginDetail->ID_Person, 'name' => $loginDetail->Person];
    $userEndpoint->call('POST', new Role('user'), $userData);
    return ['status' => 200];
};
$accountEndpoint->setAddMethod(new Role('user'), $addAccount);
