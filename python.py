import urllib
l = []
for i in range(20):
    a = urllib.urlopen("http://www.tumblr.com").read()
    b = a.find("fullscreen_post_bg")
    c = a.find(":url(", b+20)
    d = a.find("'", c+30)
    string = a[c+6:d]
    l.append(string)
    print i

a = open("tumblr", "w")
a.write(str(l))
a.close()
