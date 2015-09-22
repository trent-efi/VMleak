import os
#print "TOP: external.py"
#f = os.popen('ls -t /var/www/html/VMleak/*.txt | head -n 3')
f = os.popen('ls -t /var/www/html/VMleak/*.txt | tail -n 10')

result = f.read()
result = result.replace("\n", " ").replace("/var/www/html/VMleak/", "")
result = 'python /var/www/html/VMleak/parse.py ' + result
#print result +"</br>"
#f = os.popen( 'python /var/www/html/VMleak/parse.py ' + result )
f = os.popen( result )

result = f.read()
#print "[["+result+"]]"
print result
#print "END: external.py"
