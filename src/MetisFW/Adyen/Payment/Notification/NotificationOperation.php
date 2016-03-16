<?php

namespace MetisFW\Adyen\Payment\Notification;

use Nette\Application\Responses\JsonResponse;
use Nette\Application\Responses\TextResponse;
use Nette\Http\Request;

interface NotificationOperation {

  /**
   * @param Request $request
   * @return void
   */
  public function handleNotification(Request $request);

  /**
   * @return JsonResponse
   */
  public function getSuccessResponse();

  /**
   * @return TextResponse
   */
  public function getErrorResponse();

}
