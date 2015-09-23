import sys
from subprocess import Popen, PIPE

arg = "0"

if len(sys.argv) > 1:
    arg = sys.argv[1]

p1 = Popen("ls -t /var/www/html/VMleak/*xt*.txt | head -n "+arg, shell=True, stdout=PIPE)
output = p1.communicate()[0]
output = output.replace('\n', ' ').replace("/var/www/html/VMleak/", "")

result = 'python /var/www/html/VMleak/parse.py ' + output 
p2 = Popen( result, shell=True, stdout=PIPE )
result = p2.communicate()[0]

print result
