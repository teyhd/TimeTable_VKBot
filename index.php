<?php

define('CALLBACK_API_EVENT_CONFIRMATION', 'confirmation');
define('CALLBACK_API_EVENT_MESSAGE_NEW', 'message_new');

require_once 'config.php';
require_once 'global.php';

require_once 'api/vk_api.php';
require_once 'api/yandex_api.php';

require_once 'bot/bot.php';
require_once 'bot/keyboard.php';
require_once 'bot/db_func.php';
require_once 'bot/functions.php';
require_once 'bot/gmodule.php';

date_default_timezone_set('Europe/Ulyanovsk');
if (!isset($_REQUEST)) {
  exit;
}

callback_handleEvent();

function callback_handleEvent() {
  $event = _callback_getEvent();

  try {
    switch ($event['type']) {
      //Подтверждение сервера
      case CALLBACK_API_EVENT_CONFIRMATION:
        _callback_handleConfirmation();
        break;

      //Получение нового сообщения
      case CALLBACK_API_EVENT_MESSAGE_NEW:
        _callback_handleMessageNew($event['object']);
        break;

      default:
        _callback_response('Unsupported event');
        break;
    }
  } catch (Exception $e) {
    log_error($e);
  }

  _callback_okResponse();
}

function _callback_getEvent() {
  return json_decode(file_get_contents('php://input'), true);
}

function _callback_handleConfirmation() {
  _callback_response(CALLBACK_API_CONFIRMATION_TOKEN);
}

function _callback_handleMessageNew($data) {
  $user_id = $data['from_id'];
  $body = $data['text'];
  $from = $data['peer_id'];
  bot_sendMessage($user_id,$body,$from);
  _callback_okResponse();
}

function _callback_okResponse() {
  _callback_response('ok');
}

function _callback_response($data) {
  echo $data;
  exit();
}


