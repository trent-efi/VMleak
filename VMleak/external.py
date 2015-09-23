import os
#print "TOP: external.py"
#f = os.popen('ls -t /var/www/html/VMleak/*.txt | head -n 3')
f = os.popen('ls -t /var/www/html/VMleak/*.txt | head -n 3')

result = f.read()
result = result.replace("\n", " ").replace("/var/www/html/VMleak/", "")
result = 'python /var/www/html/VMleak/parse.py ' + result
#print result 
f = os.popen( result )

result = f.read()
print result
f.close()
