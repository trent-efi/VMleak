import sys

num = len(sys.argv)
if num < 3 or num > 3:
    print "UNKNOWN SYSTEM ERROR:"
else:
    name = sys.argv[1]
    index = sys.argv[2]

    print "UP IN HEWRERERERER " + str(name) + " " + str(index) 
#end else
