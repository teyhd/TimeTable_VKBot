<?php
function bot_sendMessage($user_id,$body) {
    $mysqli = new mysqli("95.104.192.212", "vlad", "NtGKMgrq7SQ6UWvN", "vlad");
    $keyboard = keybrd(2);
    $users_get_response = vkApi_usersGet($user_id);
    $user = array_pop($users_get_response);
    $name = $user['first_name'];
    
    $textmsg = mb_strtolower($body);         
    $textmsg = rtrim($textmsg,"!?.,/");
    
    $dialog = user_info($user_id,'dialog','get','0',$mysqli);

    switch ($dialog) {
        case 'who':
          $msg = "Вас приветствует университет ИАТУ! Какая ваша должность? Студент / Преподаватель?";
          $keyboard = keybrd(1);
          user_info($user_id,'dialog','set','set_who',$mysqli);
        break;
        case 'set_who':
            switch ($textmsg) {
                case 'студент':
                    user_info($user_id,'who','set','student',$mysqli);
                    if(predict($user_id)=="NONE"){
                    $msg = "Напиши свою учебную группу";
                    user_info($user_id,'dialog','set','set_group',$mysqli);
                    } else{
                        $tempgroup = predict($user_id);
                        $msg = "Твоя группа: {$tempgroup}?";
                        user_info($user_id,'dialog','set','set_pred',$mysqli);
                    }
                break;
                case 'преподаватель':
                    $msg = "Введите, пожалуйста, вашу фамилию";
                    user_info($user_id,'who','set','prepod',$mysqli);
                    user_info($user_id,'dialog','set','teacher_set',$mysqli);
                break;
                
                default:
                    $msg = "Какая ваша должность? Студент / Преподаватель?";
                    $keyboard = keybrd(1);
                    break;
            }
        break;     
        case 'set_pred':
            if($textmsg=='да'){
                $body= predict($user_id);
              $temp =is_exist_gr($body);
              if($temp!=false){
                $part = explode("-", $body);
                $msg = "Чтобы узнать расписание на сегодня введите расписание!";
                user_info($user_id,'learn_group','set',$part[0],$mysqli);
                $part = $part[1]/10;
                $part = explode(".", $part);
                $tyear = now_year() - ($part[0]- 1);
                user_info($user_id,'podgroup','set',$part[1],$mysqli);
                user_info($user_id,'year','set',$tyear,$mysqli);
                user_info($user_id,'dialog','set','none',$mysqli);
          } else {
               $msg = "Напиши свою учебную группу";
               user_info($user_id,'dialog','set','set_group',$mysqli);
          }
            }
        break;
       case 'teacher_set':
           $temp =get_teach_id($body);
          if($temp!="NONE"){
          $msg = "Ваша фамилия найдена в базе данных! Чтобы узнать расписание на сегодня введите 'Расписание'!";
          user_info($user_id,'teach_id','set',$temp,$mysqli);
          user_info($user_id,'dialog','set','none',$mysqli);
          } else $msg="Такая фамилия не найдена";
        break;
       case 'set_group':
            $temp =is_exist_gr($body);
          if($temp!=false){
            $part = explode("-", $body);
            $msg = "Чтобы узнать расписание на сегодня введите расписание!";
            user_info($user_id,'learn_group','set',$part[0],$mysqli);
            $part = $part[1]/10;
            $part = explode(".", $part);
            $tyear = now_year() - ($part[0]- 1);
            user_info($user_id,'podgroup','set',$part[1],$mysqli);
            user_info($user_id,'year','set',$tyear,$mysqli);
            user_info($user_id,'dialog','set','none',$mysqli);
          } else $msg="Такая группа не найдена. Введите свою группу в формате: 'АИСТбд-11'";
        break;
        
        default:
            $parts = explode(" ",$textmsg);
            $countPar = count($parts);
            switch ($parts[0]) {
                case 'пары':
                case 'расписание':
                      $whoam = user_info($user_id,'who','get','student',$mysqli);
                      if($countPar>1){
                              $parts = explode(".",$parts[1]);
                              $date = now_year();
                              $date = "{$date}-{$parts[1]}-{$parts[0]}";
                          } else $date = date('Y-m-d');
                      if ($whoam=='student'){
                          $group    =  user_info($user_id,'learn_group','get',1,$mysqli);
                          $kyrs     =  now_year() - user_info($user_id,'year','get',1,$mysqli) + 1;
                          $podgroup =  user_info($user_id,'podgroup','get',1,$mysqli);
                          $groups   = "{$group}-{$kyrs}{$podgroup}"; 
                          //$msg = $date;
                          $msg = get_stud_raspis($groups,$date);
                      }
                          else {
                              $teach_id = user_info($user_id,'teach_id','get',1,$mysqli);
                              $msg = get_prep_rasp($teach_id,$date);
                          }
                break;
               case 'познакомиться':
               case 'знакомство':
                   $msg = "Вас приветствует университет ИАТУ! Какая ваша должность? Студент / Преподаватель?";
                   user_info($user_id,'dialog','set','set_who',$mysqli);
                   $keyboard = keybrd(1);
               break;
                default:
                    $msg = "Такой команды нет.";
                    break;
            }
        break;
    }
    
    vkApi_messagesSend($user_id, $msg, array(),$keyboard);
    $mysqli->close();  
}

function now_year(){
$currentYear=date("Y");
$currentMonth=date("m"); 
if($currentMonth >="08") 
     return ($currentYear);
if($currentMonth < "08")
     return ($currentYear-1);
}
