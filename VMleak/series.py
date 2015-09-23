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

#    retStr = "{ highlighter: { formatString: '"+line+": %s, %s'}, label: '"+line+"' },"

'''
               {
                  highlighter: { formatString: 'First: %s, %s'},
		  color: 'red',
		  label: 'First'
              },
	      {
                  highlighter: { formatString: 'Second: %s, %s'},
		  color: 'blue',
		  label: 'Second'
              },
	      {
                  highlighter: { formatString: 'Third: %s, %s'},
		  color: 'green',
		  label: 'Third'
              }

'''
