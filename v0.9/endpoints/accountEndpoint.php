<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
require_once($CONFIG->basepath . '/vendor/autoload.php');
require_once($CONFIG->basepath . '/v0.9/internal/Role.php');

require_once($CONFIG->basepath . '/v0.9/endpoints/userEndpoint.php');

use Ramsey\Uuid\Uuid;

use function Skaut\HandbookAPI\v0_9\skautisTry;
use Skaut\HandbookAPI\v0_9\Database;
use Skaut\HandbookAPI\v0_9\Endpoint;

$accountEndpoint = new Endpoint();

$listAccount = function (Skautis\Skautis $skautis, array $data) : array {
    $getAccount = function (Skautis\Skautis $skautis) use ($data) : array {
        $SQL = <<<SQL
SELECT group_id
FROM users_in_groups
WHERE user_id = :id;
SQL;

        $response = [];
        $loginDetail = $skautis->UserManagement->LoginDetail();
        $response['name'] = $loginDetail->Person;
        $response['role'] = HandbookAPI\getRole($loginDetail->ID_Person);
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
        return skautisTry($getAccount, false);
    } catch (HandbookAPI\AuthenticationException $e) {
        header('www-authenticate: SkautIS');
        return ['status' => 401];
    }
};
$accountEndpoint->setListMethod(new HandbookAPI\Role('guest'), $listAccount);

$addAccount = function (Skautis\Skautis $skautis) : array {
    global $userEndpoint;
    $loginDetail = $skautis->UserManagement->LoginDetail();
    $userData = ['id' => $loginDetail->ID_Person, 'name' => $loginDetail->Person];
    $userEndpoint->call('POST', new HandbookAPI\Role('user'), $userData);
    return ['status' => 200];
};
$accountEndpoint->setAddMethod(new HandbookAPI\Role('user'), $addAccount);
