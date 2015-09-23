import os

f = os.popen('ls -t /var/www/html/VMleak/*.txt | head -n 3')
result = f.read()
result = result.replace("/var/www/html/VMleak/", "")
files = result.split()

data = ""

for line in files:
    data = data + "{ highlighter: { formatString: '"+line+": %s, %s'}, label: '"+line+"' },"

print data 
f.close()
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

