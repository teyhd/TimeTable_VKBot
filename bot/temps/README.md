# print-schedule

This program use json file to generate picture schedule.

### Install
```bash
pip install -r requirements.txt
```

### Usage<br>
`python main.py in.json out.jpg`

Json file should be an array with the following structure:
```javascript
[
  {
    "subject": "yueueu",   // subject name
    "type": "L",           // subject type: 'L' - lecture, 'S' - seminar etc. Should be as short as possible
    "teacher": "aaaaa",    // teacher's name
    "audience": "235a",    // audience number
    "time_start": "09:20", // lesson start time
    "time_end": "13:55",   // lesson end time
    "subgroup": "1"        // subgroup number, 0 if there are no subgroups for this lesson
  },
  ...
]
```

### Dependencies
Python script use [imgkit](https://github.com/jarrekk/imgkit) for rendering html. Additional info in readme for imgkit.
