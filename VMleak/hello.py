'''
import os
import sys

f = os.popen('ls -t /var/www/html/VMleak/*xt*.txt | head -n ' + sys.argv[1])
result = f.read()
result = result.replace("/var/www/html/VMleak/", "")
files = result.split()

data = ""

for line in files:
    data = data + "{ highlighter: { formatString: '"+line+": %s, %s'}, label: '"+line+"' },"

print data 
f.close()
'''



import sys
from subprocess import Popen, PIPE

arg = "0"

if len(sys.argv) > 1:
    arg = sys.argv[1]

p1 = Popen("ls -t /var/www/html/VMleak/*xt*.txt | head -n "+arg, shell=True, stdout=PIPE)
output = p1.communicate()[0]
output = output.replace("/var/www/html/VMleak/", "")
files = output.split()

data = ""

for line in files:
    data = data + "{ highlighter: { formatString: '"+line+": %s, %s'}, label: '"+line+"' },"

print data 
