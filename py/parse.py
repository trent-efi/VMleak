
#//////////////////////////////////////////////////////////////////////////////
# Name:        parse.py
# Description: This program will parse out multiple log files compairing the 
#              change of memory usage from one test run to others.
#
#              Log files can be found at:
#              /efi/pdlfiles/eng/Calculus/vmusage/flame5.0daily-1rip/
#              flame50/FieryDemo/debug/1rip/
#
# Input:       Enter thefile names on the commandline arguments.
# Returns:     String of numbers seperated by something
#
# Author:      Trent Russell
# Date:        Sept 17th, 2015
#//////////////////////////////////////////////////////////////////////////////

import sys
import pdb

file_path = "/var/www/html/VMleak/"

#pdb.set_trace()
try:
    i = -1
    j = 0
    elements = []

    for arg in sys.argv:
        arg = arg.strip()
        #f = open(arg, 'rt')
	path = file_path + arg
	#print path
	
        if i >= 0:
	    elements.append([])
            #f = open( file_path + arg, 'rt')
	    f = open(arg, 'rt')
            first = 1
            prev = curr = ""
            new = old = 0
            strbuffer = ""
            #print "////////////////NEW/////////////////////////"
            for line in f:
                list = line.replace(' ','').split(',')
	        if len(list) > 3 and list[2] == "Fiery.exe":

                    curr = list[3]
                    temp = int(list[4].strip().replace("KB", ""))

                    if first == 1:

	                first = 0
		        new = temp
                        old = new
                        prev = curr

                    else:

		        if curr != prev:
		    
		            usage = new - old
                            strbuffer = strbuffer + str(usage) + ", "
			    elements[i].append(usage)
		            #mystr = "prev:" + prev + " curr:" + curr
		            #mystr = mystr + " new:" + str(new) + " old:" + str(old)
		            #mystr = mystr + " usage:" + str(usage) 
 
                            prev = curr
                            new = temp
		            old = new 
		            #print mystr

		        else:
		            new = temp
                    j = j + 1
            #end: for
            usage = new - old
            #mystr = "prev:" + prev + " curr:" + curr
            #mystr = mystr + " new:" + str(new) + " old:" + str(old)
            #mystr = mystr + " usage:" + str(usage) 
            #print mystr
	    elements[i].append(usage)
 
	    #print strbuffer + str(usage) 
	#end: if i > 0:
	i = i + 1
    #end: outer for loop
    print(elements)
finally:
    f.close()
    #print "END: parse.py"
    
   
