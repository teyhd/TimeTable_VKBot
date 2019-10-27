<?php
$work_dir=exec("pwd");

if(!file_exists($work_dir."/temps")){
    if(!mkdir($work_dir."/temps", 0777))
	die("Не могу создать директорию temp");
}

$script_py = $work_dir."/main.py";
$script_input = $work_dir."/temps/in.json";
$script_output= $work_dir."/temps/out.jpg";

$fd = fopen($script_input, 'w') or die("не удалось создать файл");
$json = '[
  {
    "subject": "yueueu",
    "type": "Л",
    "teacher": "aaaaa",
    "audience": "235a",
    "time_start": "09:20",
    "time_end": "13:55",
    "subgroup": "1"
  },
  {
    "subject": "rtyrtysf",
    "type": "Л",
    "teacher": "aaaaa",
    "audience": "237",
    "time_start": "09:20",
    "time_end": "13:55",
    "subgroup": "2"
  },
  {
    "subject": "rtyrtysf",
    "type": "Л",
    "teacher": "aaaaa",
    "audience": "237",
    "time_start": "12:20",
    "time_end": "13:55",
    "subgroup": "2"
  },
  {
    "subject": "dfgdfg",
    "type": "Л",
    "teacher": "bbbbb",
    "audience": "237",
    "time_start": "15:40",
    "time_end": "14:05",
    "subgroup": "0"
  }
]';
fwrite($fd, $json);
fclose($fd);

$command = escapeshellcmd($script_py.' '.$script_input.' '.$script_output);
$output = shell_exec($command);
echo $output;



echo '
<html>
 <head>
  <meta charset="utf-8">
  <title>Генератор расписания</title>
 </head>
 <body> 

  <img src="temps/out.jpg"  alt="расписание">

 </body>
</html>
';

?>