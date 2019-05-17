<?php
namespace App\Helpers;
use \stdClass;
use App\Notifications\NotifyUsers;

class Notify
{
	public static  function manageToNotify($postOwner, $user, $type, $actionId)
	{
		$notificationInfo = new stdClass();
		$notificationInfo->username = $user->first_name;
		$notificationInfo->type = $type;
		$notificationInfo->actionId = $actionId;

		$postOwner->notify(new NotifyUsers($notificationInfo));
	}
}