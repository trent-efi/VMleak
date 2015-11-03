#10.3.23.123
import sys
import os.path, time
import pdb

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


try:
    #pdb.set_trace()
    num = len(sys.argv)
    if num < 3 or num > 3:
        print "UNKNOWN SYSTEM ERROR: Too many or not enough args"
    else:
        name = sys.argv[1]
        block = int(sys.argv[2]) - 1
	if block < 0 and is_multirip(sys.argv[1]):
	    print "<div><div>File Name: <b>" + name + "</b></br>Line Number: <b>NA</b></br>Starting Test Name: <b>NA</b></br>Ending Test Name: <b>NA</b></br>Graph Index: <b>1</b></br>PID: <b>NA</b></br>Data Used: <b>0 KB</b></br>Date Created: <b>NA</b></div><center><button class=\"btn\" onclick=\"w2popup.close()\">OK</button></center></div>"
	else:     
            count = 0
            first = 1
            prev = curr = ""
            new_test = old_test = "" 
	    new_data = old_data = 0
            strbuffer = ""
            line_num = 1
	    start_line = 1

            f = open( name, 'rt')
            for line in f:
	    
                mylist = line.replace(' ','').split(',')
	        if len(mylist) > 3 and mylist[2] == "ripper.exe":
                    curr = mylist[3]
		    temp_test = str(mylist[0])
                    temp_data = int(mylist[4].strip().replace("KB", ""))

                    if first == 1:
	                first = 0

	                new_test = temp_test
                        old_test = new_test

		        new_data = temp_data
                        old_data = new_data

                        prev = curr
		        start_line = line_num
                    else:
		        if curr != prev:
		         
                            if count == block:
			        usage = new_data - old_data
			        old_test = old_test.replace('++(', '')
			        old_test = old_test[:-1]
			        new_test = new_test.replace('++(','')
			        new_test = new_test[:-1]
		                print "<div><div>File Name: <b>" + name + "</b></br>Line Number: <b>" + str(start_line) +"</b></br>Starting Test Name: <b>" + str(old_test) + "</b></br>Ending Test Name: <b>" + str(new_test) + "</b></br>Graph Index: <b>" + str(count + 2)+"</b></br>PID: <b>" + prev + "</b></br>Data Used: <b>" + str(usage) + " KB</b></br>Date Created: <b>"+time.ctime(os.path.getctime(name)) +"</b></div><center><button class=\"btn\" onclick=\"w2popup.close()\">OK</button></center></div>"
                            start_line = line_num
                            prev = curr

                            new_test = temp_test
		            old_test = new_test

                            new_data = temp_data
                            old_data = new_data 

			    count = count + 1
		        else:
		            new_test = temp_test
			    new_data = temp_data
                #end if
	        line_num = line_num + 1
            #end for
	    if count == block:
	        usage = new_data - old_data
                old_test = old_test.replace('++(', '')
	        old_test = old_test[:-1]
	        new_test = new_test.replace('++(','')
	        new_test = new_test[:-1]
                print "<div><div>File Name: <b>" + name + "</b></br>Line Number: <b>" + str(start_line) +"</b></br>Starting Test Name: <b>" + str(old_test) + "</b></br>Ending Test Name: <b>" + str(new_test) + "</b></br>Graph Index: <b>" + str(count + 1)+"</b></br>PID: <b>" + prev + "</b></br>Data Used: <b>" + str(usage) + " KB</b></br>Date Created: <b>"+time.ctime(os.path.getctime(name)) +"</b></div><center><button class=\"btn\" onclick=\"w2popup.close()\">OK</button></center></div>"
    #end else
    
finally:
    if block > 0:
        f.close()



