<?php
function bot_sendMessage($user_id,$body,$from) {
    $mysqli = new mysqli(HOST_DB, LOGIN_DB, PASS_DB, "vlad");
    $keyboard = keybrd(2);
    $users_get_response = vkApi_usersGet($user_id);
    $user = array_pop($users_get_response);
    $name = $user['first_name'];
    $body = str_replace("[club178013145|@teyhdbot]", '', $body);
    $textmsg = mb_strtolower($body);         
    $textmsg = rtrim($textmsg,"!?.,/");
    $textmsg = ltrim($textmsg, " \t.");
    $textmsg = ltrim($textmsg, " ");
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
                        $keyboard = keybrd(3);
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
                user_info($user_id,'dialog','set','none',$mysqli);}
          } else {
               $msg = "Напиши свою учебную группу";
               user_info($user_id,'dialog','set','set_group',$mysqli);
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
       case 'settings':
           if( ($textmsg=="оболочку") || ($textmsg=="оболочка") || ($textmsg=="графическую оболочку") || ($textmsg=="графическая оболчка")){
               $msg = "Оболочка успешно установлена!";
               $gmod = user_info($user_id,'gmod','get',1,$mysqli);
               if($gmod==1){
                   user_info($user_id,'gmod','set',2,$mysqli);
               } else user_info($user_id,'gmod','set',1,$mysqli);
               user_info($user_id,'dialog','set','none',$mysqli);
               $keyboard = keybrd(2);
           }
           elseif($textmsg=="должность" || $textmsg=="группу" || $textmsg=="группа" || $textmsg=="должность/группу"){
               $msg = "Вас приветствует университет ИАТУ! Какая ваша должность? Студент / Преподаватель?";
               user_info($user_id,'dialog','set','set_who',$mysqli);
               $keyboard = keybrd(1);
           }
          elseif($textmsg=="ничего"){
               user_info($user_id,'dialog','set','none',$mysqli);
               $msg = "Ничего не настроено";
               $keyboard = keybrd(2);
           }
           else{
               $msg = "Такого пункта нет. Что вы хотите натсроить? Графическую оболочку[оболочку] | Должность/Группу | Ничего";
               $keyboard = keybrd(4);
           }
           
        break;        
        
        default:
            $parts = explode(" ",$textmsg);
            $countPar = count($parts);
            switch ($parts[0]) {

                case 'тест':
                    //$msg = 'Работает!!!';
                    //$msg = sendPhoto(120161867);
                    $msg = user_info($user_id,'gmod','get','5',$mysqli);
                   /* $msg = g_create('[
                            {
                            "subject": "Предмет",
                            "type": "L",
                            "teacher": "aaaaa",
                            "audience": "235a",
                            "time_start": "09:20",
                            "time_end": "13:55",
                            "subgroup": "1"
                            }
                            ]',120161867);*/
                    break;

                case 'пары':
                case 'расписание':
                      $graph = user_info($user_id,'gmod','get','5',$mysqli);
                      $whoam = user_info($user_id,'who','get','student',$mysqli);
                      if($countPar>1){
                          if(($parts[1]=='завтра')||($parts[2]=='завтра')){
                              $date = date('Y-m-d', strtotime(' +1 day'));
                          } else{
                              
                              if (strpos($parts[1], '.') !== false) {
                                    $parts = explode(".",$parts[1]);
                                } else $parts = explode(".",$parts[2]);
                              $date = now_year();
                              $date = "{$date}-{$parts[1]}-{$parts[0]}";
                              $partrs = explode("-",$date);
                              //checkdate ( int $month , int $day , int $year ) : bool
                              if (checkdate($partrs[1],$partrs[2],$partrs[0])==false){
                                  vkApi_messagesSend($from, "Неверная дата!!!", array(),$keyboard);
                                    return 'Error';
                              }
                          } 
                     } else $date = date('Y-m-d');
                      $part = explode("-", $date);
                      $temp_d = "Расписание на $part[2].$part[1]";
                      vkApi_messagesSend($from, $temp_d, array(),$keyboard);
                      if ($whoam=='student'){
                          $group    =  user_info($user_id,'learn_group','get',1,$mysqli);
                          $kyrs     =  now_year() - user_info($user_id,'year','get',1,$mysqli) + 1;
                          $podgroup =  user_info($user_id,'podgroup','get',1,$mysqli);
                          $groups   = "{$group}-{$kyrs}{$podgroup}"; 
                          //$msg = $date;
                          $msgr = get_stud_raspis($groups,$date,$graph,$user_id);
                      }
                          else {
                              $teach_id = user_info($user_id,'teach_id','get',1,$mysqli);
                              $msg = get_prep_rasp($teach_id,$date);
                          }
                break;
               case 'познакомиться':
               case 'знакомство':
               case 'настроить':
                   $msg ="Что вы хотите натсроить? Графическую оболочку | Должность/Группу | Ничего";
                   user_info($user_id,'dialog','set','settings',$mysqli);
                   $keyboard = keybrd(4);
               break;
                default:
                    $msg = "Такой команды нет. Чтобы узнать пары введи: 'Пары'. Чтобы узнать пары в определенный день напиши: 'Пары дд.мм'. Чтобы узнать пары на завтра введи: 'Пары завтра'. Чтобы сменить группу или должность введи: 'Настроить'";
                    break;
            }
        break;
    }
    if($msgr!=null){
       $msg = explode("*",$msgr); 
       foreach ($msg as $value) {
           vkApi_messagesSend($from, $value, array(),$keyboard);
       }
    }
    
    vkApi_messagesSend($from, $msg, array(),$keyboard);
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
