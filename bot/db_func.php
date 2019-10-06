<?php
 //$mysqli = new mysqli("95.104.192.212", "vlad", "NtGKMgrq7SQ6UWvN", "vlad");
function user_add($user_id,$mysqli){
/* Проверка соединения */ 
if (mysqli_connect_errno()) { 
    printf("Подключение невозможно: %s\n", mysqli_connect_error()); 
    exit(); 
}     
    $Dial = 'who';
    $stmt = $mysqli->prepare("INSERT INTO users VALUES (0, ?, 0, ?, 0, 0, 0, 0)"); 
    $stmt->bind_param('ds', $user_id,$Dial); 
    $stmt->execute(); 
    $stmt->close(); 
    
}
function user_info($user_id,$what,$mod,$value,$mysqli){
if (mysqli_connect_errno()) { 
    printf("Подключение невозможно: %s\n", mysqli_connect_error()); 
    exit(); 
}     
 if (!$mysqli->set_charset("utf8")) {
    printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqlis->error);
    exit();
    } 
if ($mod=='set'){
    $stmt = $mysqli->prepare("UPDATE users SET {$what}='{$value}' WHERE vk_id={$user_id}"); 
    $stmt->bind_param('ssd', $what,$value,$user_id); 
    $stmt->execute(); 
    $stmt->close(); 
    $temp = "OK";
}
if ($mod=='get'){
    if ($stmt = $mysqli->prepare("SELECT {$what} FROM users WHERE vk_id={$user_id}")) { 
    $stmt->execute(); 
    $stmt->bind_result($col1); 
    while ($stmt->fetch()) { 
       $temp = $col1;
    } 
    $stmt->close(); 
    }   
    if ($temp==null){
     user_add($user_id,$mysqli);
     $temp = "who";
    }
}
    
   
return $temp;
    
}

function get_teach_id($teach_name){
    $mysqlis = new mysqli("95.104.192.212", "vlad", "NtGKMgrq7SQ6UWvN", "raspisanie");
    if (!$mysqlis->set_charset("utf8")) {
    printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqlis->error);
    exit();
    } 
if (mysqli_connect_errno()) { 
    printf("Подключение невозможно: %s\n", mysqli_connect_error()); 
    exit(); 
}     

    if ($stmt = $mysqlis->prepare("SELECT `ID` FROM `prepodavatel_original` WHERE `FIO` LIKE '%{$teach_name}%'")) { 
    $stmt->execute(); 
    $stmt->bind_result($col1); 
    
    while ($stmt->fetch()) { 
       $temp = $col1;
    } 
    $stmt->close(); 
    }   
    if ($temp==null){
     $temp = "NONE";
    }
$mysqlis->close();  
return $temp;
} //Узнаем id препода
function get_teach($teach_name){
    $mysqlis = new mysqli("95.104.192.212", "vlad", "NtGKMgrq7SQ6UWvN", "raspisanie");
    if (!$mysqlis->set_charset("utf8")) {
    printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqlis->error);
    exit();
    } 
if (mysqli_connect_errno()) { 
    printf("Подключение невозможно: %s\n", mysqli_connect_error()); 
    exit(); 
}     

    if ($stmt = $mysqlis->prepare("SELECT `Full_FIO` FROM `prepodavatel_original` WHERE `ID` = {$teach_name}")) { 
    $stmt->execute(); 
    $stmt->bind_result($col1); 
    
    while ($stmt->fetch()) { 
       $temp = $col1;
    } 
    $stmt->close(); 
    }   
    if ($temp==null){
     $temp = "NONE";
    }
$mysqlis->close();  
return $temp;
} //Препода по id
function is_exist_gr($ygroup){
    $mysqlis = new mysqli("95.104.192.212", "vlad", "NtGKMgrq7SQ6UWvN", "raspisanie");
    if (!$mysqlis->set_charset("utf8")) {
    printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqlis->error);
    exit();
    } 
if (mysqli_connect_errno()) { 
    printf("Подключение невозможно: %s\n", mysqli_connect_error()); 
    exit(); 
}     

    if ($stmt = $mysqlis->prepare("SELECT `ID` FROM `groups` WHERE `naimenovanie`='{$ygroup}'")) { 
    $stmt->execute(); 
    $stmt->bind_result($col1); 
    
    while ($stmt->fetch()) { 
       $temp = $col1;
    } 
    $stmt->close(); 
    }   
    if ($temp==null){
     $temp = false;
    } else $temp = true;
$mysqlis->close();  
return $temp;
} //Проверяем существование группы

function get_stud_raspis($group,$dates){
    $mysqlis = new mysqli("95.104.192.212", "vlad", "NtGKMgrq7SQ6UWvN", "raspisanie");
    if (!$mysqlis->set_charset("utf8")) {
    printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqlis->error);
    exit();
    } 
if (mysqli_connect_errno()) { 
    printf("Подключение невозможно: %s\n", mysqli_connect_error()); 
    exit(); 
} 
  if ($stmt = $mysqlis->prepare("SELECT timeStart, timeStop, discipline, type,teacher, cabinet, subgroup FROM `timetable` WHERE `class`='{$group}' AND `date`='{$dates}'")) { 
    $stmt->execute(); 
    $stmt->bind_result($col1,$col2,$col3,$col4,$col5,$col6,$col7);
    $num = 1;
    while ($stmt->fetch()) { 
        $col1 = normal($col1);
        $col2 = normal($col2);
        $temp ="$temp\n\n [$num] [$col1-$col2] $col3 Аудитория: [$col6]; Подгруппа: [$col7]; Учитель: [$col5] [{$col4}]";
        $num++;
    } 
    $stmt->close(); 
    }   
    if ($temp==null){
        $temp = "Сегодня пар нет";
    }

$mysqlis->close();  
return $temp;

} //Вывод для студентов
function get_prep_rasp($teach,$dates){
    $teach= get_teach($teach); 
    $mysqlis = new mysqli("95.104.192.212", "vlad", "NtGKMgrq7SQ6UWvN", "raspisanie");
    if (!$mysqlis->set_charset("utf8")) {
    printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqlis->error);
    exit();
    } 
if (mysqli_connect_errno()) { 
    printf("Подключение невозможно: %s\n", mysqli_connect_error()); 
    exit(); 
} 
  if ($stmt = $mysqlis->prepare("SELECT timeStart, timeStop, discipline, type,class, cabinet, subgroup FROM `timetable` WHERE `teacher`='{$teach}' AND `date`='{$dates}'")) { 
    $stmt->execute(); 
    $stmt->bind_result($col1,$col2,$col3,$col4,$col5,$col6,$col7);
    $num = 1;
    while ($stmt->fetch()) { 
        $col1 = normal($col1);
        $col2 = normal($col2);
        $temp ="$temp\n\n [$num] [$col1-$col2] $col3 Аудитория: [$col6]; Подгруппа: [$col7]; Группа: [$col5] [{$col4}]";
        $num++;
    } 
    $stmt->close(); 
    }   
    if ($temp==null){
        $temp = "Сегодня пар нет";
    }

$mysqlis->close();  
return $temp;

} //Вывод для преподов
function predict($user_id){
    $mysqlis = new mysqli("95.104.192.212", "vlad", "NtGKMgrq7SQ6UWvN", "users_iatu");
    if (!$mysqlis->set_charset("utf8")) {
    printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqlis->error);
    exit();
    } 
if (mysqli_connect_errno()) { 
    printf("Подключение невозможно: %s\n", mysqli_connect_error()); 
    exit(); 
}   
    $users_get_response = vkApi_usersGet($user_id);
    $user = array_pop($users_get_response);
    $name = $user['first_name'];
    $famil = $user['last_name'];
    if ($stmt = $mysqlis->prepare("SELECT `Group`, `miniGroup`, `Year_of_receipt` FROM `users` WHERE `Surname` LIKE '%{$famil}%' AND `Name` LIKE '%{$name}%'")) { 
    $stmt->execute(); 
    $stmt->bind_result($col1,$col2,$col3); 
    
    while ($stmt->fetch()) { 
       $Group   = $col1;
       $minigr  = $col2;
       $year    = $col3;
    } 
    $stmt->close(); 
    }   
    
      $group    =  $Group;
      $kyrs     =  now_year() - $year + 1;
      $podgroup =  $minigr;
      $groups   = "{$group}-{$kyrs}{$podgroup}"; 
    
    if ($groups==null){
     $groups = "NONE";
    }
    
$mysqlis->close();  
return $groups;
}
function normal($times){
    $parts = explode(":",$times);
    return "$parts[0]:$parts[1]";
} //Убираем секунды из строки

echo(predict(120161867));

//echo(get_prep_rasp(22,'2019-10-01'));
