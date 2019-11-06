<?php

function g_create($json,$user_id){
    $work_dir="/home/administrator/html/api/print-schedule";

if(!file_exists($work_dir."/temps")){
    if(!mkdir($work_dir."/temps", 0777))
	die("Не могу создать директорию temp");
}

$script_py = $work_dir."/main.py";
$script_input = $work_dir."/temps/in.json";
$script_output= $work_dir."/temps/out.jpg";

$fd = fopen($script_input, 'w') or die("Cant create");

fwrite($fd, $json);
fclose($fd);

$command = escapeshellcmd($script_py.' '.$script_input.' '.$script_output);
$output = shell_exec($command);
 if (file_exists($script_output)) sendPhoto($user_id);
 else return $script_output;

//return $script_output;
}
function _bot_uploadPhoto($user_id, $file_name) {
  $upload_server_response = vkApi_photosGetMessagesUploadServer($user_id);
  $upload_response = vkApi_upload($upload_server_response['upload_url'], $file_name);
  $photo = $upload_response['photo'];
  $server = $upload_response['server'];
  $hash = $upload_response['hash'];
  $save_response = vkApi_photosSaveMessagesPhoto($photo, $server, $hash);
  $photo = array_pop($save_response);
  return $photo;
}
function sendPhoto($user_id) {
    $work_dir="/home/administrator/html/api/print-schedule";
    $script_output= $work_dir."/temps/out.jpg";
    if (file_exists($script_output)) {
         $photo = _bot_uploadPhoto($user_id, $script_output);
      $attachments = array(
        'photo'.$photo['owner_id'].'_'.$photo['id']
      );
    
      $keyboard = keybrd(2);
     vkApi_messagesSend($user_id, '', $attachments,$keyboard);
    // unlink($script_output);
     return $script_output;
    } else {
        return "Произошла ошибка!!!";
    }

}
?>