<?php
function getZodiacalSign($month, $day) {
$signs = array("capricorn", "aquarius", "pisces", "aries", "taurus", "gemini", "cancer", "leo", "virgo", "libra", "scorpio", "sagittarius");
$signsstart = array(1=>21, 2=>20, 3=>20, 4=>20, 5=>20, 6=>20, 7=>21, 8=>22, 9=>23, 10=>23, 11=>23, 12=>23);
return $day < $signsstart[$month + 1] ? $signs[$month - 1] : $signs[$month % 12];
}
function horoscop($user_id){
      $users_get_response = vkApi_usersGet($user_id);
      $user = array_pop($users_get_response);
      $bd = $user['bdate']; //D.M.YYYY 
      if ($bd!==null){
      $bd = explode(".", $bd);
      $sign = getZodiacalSign($bd[1], $bd[0]);
      $msg = mysql_horoscop("get",$sign,$sign);
      } else{
        $sign = getZodiacalSign(10, 28);
        $msg = mysql_horoscop("get",$sign,$sign);  
        $msg = "В связи с тем, что дата вашего рождения закрыта в настройках приватности, вам будет показан общий гороскоп: {$msg}";
      } 
      return $msg;
}
function mysql_horoscop($mod,$sign,$text){
$mysqli = new mysqli('localhost', 'teyhd', '258000', 'remind');
/* Проверка соединения */ 
if (mysqli_connect_errno()) { 
    printf("Подключение невозможно: %s\n", mysqli_connect_error()); 
    exit(); 
}  
    if($mod=="set"){
        $stmt = $mysqli->prepare("UPDATE horoscop SET `{$sign}`='{$text}' WHERE `{$sign}`=`{$sign}`"); 
        $stmt->bind_param('ss', $sign,$text); 
        $stmt->execute(); 
        $stmt->close();
    }
    if($mod=="get"){
        if ($stmt = $mysqli->prepare("SELECT {$sign} FROM horoscop WHERE {$sign}={$sign}")) { 
            $stmt->execute(); 
            $stmt->bind_result($col1); 
            while ($stmt->fetch()) { 
                $sign = $col1;
            } 
            $stmt->close(); 
        }
    }
$mysqli->close(); 
return $sign;
}
