import sys

i = 0
data = ""
num = len(sys.argv)
for arg in sys.argv:
    arg = arg.strip()

    if i > 0:
        if i+1 < num:
            #data = data + '{\"highlighter\":[{\"formatString\":\"'+arg+':%s,%s\"},{\"label\":\"'+arg+'\"}]},'
            data = data + "{ highlighter: { formatString: '"+arg+": %s, %s'}, label: '"+arg+"' },"
	    #data = data + '{label:\'ABC\'},'

	else:
	    #data = data + '{\"highlighter\":[{\"formatString\":\"'+arg+':%s,%s\"},{\"label\":\"'+arg+'\"}]}' 
            data = data + "{ highlighter: { formatString: '"+arg+": %s, %s'}, label: '"+arg+"' }"	    
	    #data = data + '{label:\'123\'}'
    i = i + 1
print data 
