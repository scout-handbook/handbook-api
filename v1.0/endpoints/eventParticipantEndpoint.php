<?php declare(strict_types=1);
@_API_EXEC === 1 or die('Restricted access.');

use Skautis\Skautis;

use Skaut\HandbookAPI\v1_0\Endpoint;
use Skaut\HandbookAPI\v1_0\Role;
use Skaut\HandbookAPI\v1_0\Exception\InvalidArgumentTypeException;
use Skaut\HandbookAPI\v1_0\Exception\SkautISAuthorizationException;

$eventParticipantEndpoint = new Endpoint();

$listEventParticipants = function (Skautis $skautis, array $data) : array {
    $id = ctype_digit($data['parent-id']) ? intval($data['parent-id']) : null;
    if ($id === null) {
        throw new InvalidArgumentTypeException('id', ['Integer']);
    }

    // Set the right role
    $eventName = $skautis->Events->EventEducationDetail([
        'ID' => $id
    ])->DisplayName;
    $userID = $skautis->UserManagement->LoginDetail()->ID_User;
    $ISroles = $skautis->UserManagement->UserRoleAll([
        'ID_User' => $userID]);
    foreach ($ISroles as $ISrole) {
        if (mb_strpos($ISrole->DisplayName, '"' . $eventName . '"') !== false) {
            $response = $skautis->UserManagement->LoginUpdate([
                "ID_UserRole" => $ISrole->ID,
                "ID" => $skautis->getUser()->getLoginId()
            ]);
            $skautis->getUser()->updateLoginData(null, $ISrole->ID, $response->ID_Unit);
            break;
        }
    }

    try {
        $ISparticipants = $skautis->Events->ParticipantEducationAll([
            'ID_EventEducation' => $id,
            'IsActive' => true]);
    } catch (\Skautis\Exception $e) {
        if (
            mb_ereg(
                "Nemáte oprávnění k akci EV_ParticipantEducation_ALL_EventEducation nad záznamem ID=", // phpcs:ignore Generic.Files.LineLength.TooLong
                $e->getMessage()
            )
        ) {
            throw new SkautISAuthorizationException();
        }
        throw $e;
    }
    $participants = [];
    foreach ($ISparticipants as $participant) {
        if ($participant->IsAccepted == 'TRUE') {
            $participants[] = ['id' => $participant->ID_Person, 'name' => $participant->Person];
        }
    }
    return ['status' => 200, 'response' => $participants];
};
$eventParticipantEndpoint->setListMethod(new Role('editor'), $listEventParticipants);
