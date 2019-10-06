<?php
function keybrd($param){
    switch ($param) {
        case 1:
            $keybrd = '{"one_time":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\": \"1\"}","label":"Студент"},"color":"positive"},{"action":{"type":"text","payload":"{\"button\": \"2\"}","label":"Преподаватель"},"color":"primary"}]]}';
        break;
        case 2:
         $keybrd = '{"one_time":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\": \"1\"}","label":"Расписание"},"color":"positive"},{"action":{"type":"text","payload":"{\"button\": \"2\"}","label":"Знакомство"},"color":"primary"}]]}';
         break; 
         default:
              $keybrd = '{"one_time":true,"buttons":[[{"action":{"type":"text","payload":"{\"button\": \"1\"}","label":"Расписание"},"color":"positive"}]]}';
         break;

    }
     return $keybrd;
}