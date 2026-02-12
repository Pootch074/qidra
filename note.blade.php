I want you to know that these are the valus in steps, categories and windows table.

steps
[
id = 15
section_id = 52
step_number = 1
step_name = Pre-assessment
],
[
id = 16
section_id = 52
step_number = 2
step_name = Encode
],
[
id = 17
section_id = 52
step_number = 3
step_name = Assessment
],
[
id = 18
section_id = 52
step_number = 4
step_name = Release
],

categories
[
id = 1
step_id = 15
category_name = priority
],
[
id = 2
step_id = 15
category_name = regular
],
[
id = 3
step_id = 16
category_name = priority
],
[
id = 4
step_id = 16
category_name = regular
],
[
id = 5
step_id = 17
category_name = both
],
[
id = 6
step_id = 18
category_name = both
],

windows
[
id = 1
category_id = 1
window_number = 1
],
[
id = 2
category_id = 2
window_number = 1
],
[
id = 3
category_id = 2
window_number = 1
],
[
id = 4
category_id = 3
window_number = 1
],
[
id = 5
category_id = 4
window_number = 1
],

[
id = 6
category_id = 4
window_number = 1
],
[
id = 7
category_id = 5
window_number = 1
],
[
id = 8
category_id = 5
window_number = 1
],
[
id = 9
category_id = 5
window_number = 1
],
[
id = 10
category_id = 5
window_number = 1
],
[
id = 11
category_id = 6
window_number = 1
],
[
id = 12
category_id = 6
window_number = 1
],
[
id = 13
category_id = 6
window_number = 1
],

So if the current logged in user's assigned_category = 'regular'. It should select window_number with category_name =
'regular' in categories table for Step 1 and Step 2. The Step 3 and 4 captures window_number with category_name =
'both'.

STEP 1 Pre-assessment
Window 1 Window 2

STEP 2 Encode
Window 1 Window 2

STEP 3 Assessment
Window 1 Window 2
Window 3 Window 4

STEP 3 Release
Window 1 Window 2
Window 3
