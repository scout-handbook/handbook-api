<?php

declare(strict_types=1);

@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');

require_once($CONFIG->basepath . '/v1.0/endpoints/eventParticipantEndpoint.php');

use Skautis\Skautis;

use Skaut\HandbookAPI\v1_0\Endpoint;
use Skaut\HandbookAPI\v1_0\Role;

$eventEndpoint = new Endpoint();
$eventEndpoint->addSubEndpoint('participant', $eventParticipantEndpoint);

$listUsers = function (Skautis $skautis) : array {
    $ISevents = $skautis->Events->EventEducationAllMyActions();
    $events = [];
    foreach ($ISevents as $event) {
        $events[] = ['id' => $event->ID, 'name' => $event->DisplayName];
    }
    return ['status' => 200, 'response' => $events];
};
$eventEndpoint->setListMethod(new Role('editor'), $listUsers);
