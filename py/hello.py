import os.path, time

file = "/var/www/html/VMleak/737361xt4.txt"
print "last modified: %s" % time.ctime(os.path.getmtime(file))
print "created: %s" % time.ctime(os.path.getctime(file))
