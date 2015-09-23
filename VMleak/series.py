import sys

i = 0
data = ""

for arg in sys.argv:
    arg = arg.strip()

    if i > 0:
        data = data + "{ highlighter: { formatString: '"+arg+": %s, %s'}, label: '"+arg+"' },"
 
    i = i + 1
print data 
