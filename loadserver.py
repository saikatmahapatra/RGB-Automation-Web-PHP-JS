import re

def loadconfig():

    #for line in open('/home2/'+SQA['vmg_user']+'/public_html/cgi-bin/vmg_lib/server.cfg',"r"):
    SQA=LIN=WIN=MAC=gige={}
    for line in open('server.cfg.170',"r"):
	if re.match('^#gige[0-9]*',line):
	    line=line.strip("#\n")
	    SQA[line]={}
        if not re.match('^#.*',line):
            line=line.strip()
	    
            if line!="":
                if re.search('"',line):
                    (key,val)=line.split()
                    val=val.strip('"')
		    if re.match('SQA\(gige[0-9]*_.*',line):
			pat= re.match('SQA\((gige[0-9]*)_.*\).*',line)
			if pat!=None:
			    gigeport=pat.group(1)
			    SQA[gigeport].update({key[4:-1]:val})
		
                    elif re.search('SQA.*',line):
                    #print "cmd"+cmd+"key"+key+"val"+val
                        SQA[key[4:-1]]=val
                    elif re.search('WIN.*',line):
                        WIN[key[4:-1]]=val
                    elif re.search('LIN.*',line):
                        LIN[key[4:-1]]=val
                    else:
                        MAC[key[4:-1]]=val
	

    print SQA
    """
    for key in SQA:
	print key+":"
	print SQA[key]
    """
    #print LIN,MAC,WIN
    return(SQA,LIN,WIN,MAC)

loadconfig()
