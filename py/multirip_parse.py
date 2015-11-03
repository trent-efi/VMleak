import sys
import pdb

import sys
import pdb

file_path = "/var/www/html/VMleak/"

#[[2002, 10200], [2003, 10800], [2004, 11200], [2005, 11800], [2006, 12400], [2007, 12800], [2008, 13200], [2009, 12600], [2010, 13100]]

#[[[0, a], [1, b], [2, c] ], [[0, aa], [1, bb], [2, cc]], [[0, aaa], [1, bbb], [2, ccc]]]
def is_multirip(arg):
    #return "HERE" 
    value = False
    try:
        temp = open(arg, 'rt')
        for line in temp:
            list = line.replace(' ','').split(',')
            if len(list) > 3 and list[2] == "ripper.exe":
	        value = True
		break
	temp.close()
    except:
        value = False

    return value;



#pdb.set_trace()
try:
    i = -1
    
    elements = []#array that will hold other arrays 

    for arg in sys.argv:
        arg = arg.strip()

        if i >= 0:
	    elements.append([])
    
            if is_multirip(arg):
	        elements[i].append(0)

	    f = open(arg, 'rt')

            first = 1
            prev = curr = ""
            new = old = 0
            strbuffer = ""
            j = 0  
            for line in f:
                list = line.replace(' ','').split(',')
	        if len(list) > 3 and list[2] == "ripper.exe":

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
			    j = j + 1
		            #print mystr

		        else:
		            new = temp 
            #end: for
            usage = new - old
            #mystr = "prev:" + prev + " curr:" + curr
            #mystr = mystr + " new:" + str(new) + " old:" + str(old)
            #mystr = mystr + " usage:" + str(usage) 
            #print mystr

	    elements[i].append(usage) 
	#end: if i > 0:
	i = i + 1
    #end: outer for loop
    print(elements)
except:
    e = sys.exc_info()[0]
    print "ERROR: "+str(e)
   


