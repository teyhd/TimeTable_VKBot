#!/usr/bin/env python3

from jinja2 import Environment, FileSystemLoader
import imgkit
import json
import sys

input_file = open(sys.argv[1], "r")
output_file = sys.argv[2]

lessons = json.load(input_file)

templateLoader = FileSystemLoader(searchpath="./")
templateEnv = Environment(loader=templateLoader)
templateEnv.trim_blocks = True
templateEnv.lstrip_blocks = True

def max_length_of_groups(groups):
    return max(map(lambda x : max(map(lambda y: y['subgroup'], x.list)), groups))

def add_empty_subgroups(lessons):
    max_subgroup = max(int(x['subgroup']) for x in lessons)
    if max_subgroup == 0:
        return lessons
    subgroups_lessons = {int(x['subgroup']): x for x in lessons}
    for i in range(1, max_subgroup + 1):
        if i not in subgroups_lessons:
            subgroups_lessons[i] = None
    return [y for (x,y) in sorted(subgroups_lessons.items())]

templateEnv.filters['add_empty_subgroups'] = add_empty_subgroups
templateEnv.filters['max_length_of_groups'] = max_length_of_groups

template = templateEnv.get_template('schedule.html')
res = template.render(lessons=lessons)

css = ['./css/w3.css', './css/style.css']
imgkit.from_string(res, output_file, options={
    'quiet': '',
    # 'xvfb': '',
    # 'format': 'png'
    # 'crop-h': '300',
    # 'crop-w': '1366',
    # 'crop-x': '3',
    # 'crop-y': '3'
    'width': '1600',
    'height': '1000'
}, css=css)
