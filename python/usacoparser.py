import zipfile
import urllib
import sys
import re
import os

class Problem():
	def __init__(self, zipfile, shortname):
		self.zipfile = zipfile
		self.shortname = shortname

def fetchContests(data):
	contests = []
	re1='(<)'	# Any Single Character 1
	re2='(a)'	# Variable Name 1
	re3='( )'	# White Space 1
	re4='(href)'	# Variable Name 2
	re5='(=)'	# Any Single Character 2
	re6='(")'	# Any Single Character 3
	re7='(index\\.php)'	# Fully Qualified Domain Name 1
	re8='(\\?)'	# Any Single Character 4
	re9='(page)'	# Word 1
	re10='.*?'	# Non-greedy match on filler
	re11='((?:[a-z][a-z]*[0-9]+[a-z0-9]*))'	# Alphanum 1
	re12='(")'	# Any Single Character 5
	re13='(>)'	# Any Single Character 6

	rg = re.compile(re1+re2+re3+re4+re5+re6+re7+re8+re9+re10+re11+re12+re13,re.IGNORECASE|re.DOTALL)
	m = rg.findall(data)
	if m:
		for mm in m:
			if mm[9] == "camp12":
				continue
			else:
				contests.append(mm[9])
	return contests


def fetchProblemsFromContest(data): #regex generated from txt2re
	problems = []
#get zip files
	re1='(current)'	# Word 1
	re2='(\\/)'	# Any Single Character 1
	re3='(data)'	# Word 2
	re4='(\\/)'	# Any Single Character 2
	re5='((?:[a-z0-9_]*))'	# Variable Name 1
	re6='(\\.)'	# Any Single Character 3
	re7='(zip)'	# Word 3
	rg = re.compile(re1+re2+re3+re4+re5+re6+re7,re.IGNORECASE|re.DOTALL)
	m = rg.findall(data)
	if m:
		for mm in m:
			problems.append(Problem("http://usaco.org/current/data/"+mm[4]+".zip", mm[4])) #zipname

	i = 0

	#prob names
	re1='(<b>)'	# Tag 1
	re2='(.*?)'	# Non-greedy match on filler
	re3='(<\\/b>)'	# Tag 2
	rg = re.compile(re1+re2+re3,re.IGNORECASE|re.DOTALL)
	m = rg.findall(data)
	for mm in m:
		problems[i].name = mm[1]
		i += 1
	#prob urls
	re1='(<)'	# Any Single Character 1
	re2='(a)'	# Any Single Word Character (Not Whitespace) 1
	re3='( )'	# White Space 1
	re4='(href)'	# Word 1
	re5='(=)'	# Any Single Character 2
	re6='.*?'	# Non-greedy match on filler
	re7='(index\\.php)'	# Fully Qualified Domain Name 1
	re8='(\\?)'	# Any Single Character 3
	re9='(page)'	# Word 2
	re10='.*?'	# Non-greedy match on filler
	re11='(viewproblem2)'	# Alphanum 1
	re12='.*?'	# Non-greedy match on filler
	re13='(cpid)'	# Word 3
	re14='(=)'	# Any Single Character 4
	re15='(\\d+)'	# Integer Number 1
	re16='.*?'	# Non-greedy match on filler
	re17='(>)'	# Any Single Character 5
	re18='(View)'	# Word 4
	re19='( )'	# White Space 2
	re20='(problem)'	# Word 5
	re21='(<)'	# Any Single Character 6
	re22='(\\/)'	# Any Single Character 7
	re23='(a)'	# Any Single Character 8
	re24='(>)'	# Any Single Character 9

	i = 0
	rg = re.compile(re1+re2+re3+re4+re5+re6+re7+re8+re9+re10+re11+re12+re13+re14+re15+re16+re17+re18+re19+re20+re21+re22+re23+re24,re.IGNORECASE|re.DOTALL)
	m = rg.findall(data)
	if m:
		for mm in m:
			proburl = "http://usaco.org/index.php?page=viewproblem2&cpid=" + mm[11]
			probhnd = urllib.urlopen(proburl);
			probdata = probhnd.read()

			if "Gold Division" in str(probdata):
				problems[i].level = "Gold"
			elif "Silver Division" in str(probdata):
				problems[i].level = "Silver"
			else:
				problems[i].level = "Bronze"

			re1='(<)'	# Any Single Character 1
			re2='(span)'	# Word 1
			re3='( )'	# White Space 1
			re4='(class)'	# Word 2
			re5='(=)'	# Any Single Character 2
			re6='("mono prewrap")'	# Double Quote String 1
			re7='( )'	# White Space 2
			re8='(id)'	# US State 1
			re9='(=)'	# Any Single Character 3
			re10='("probtext-text")'	# Double Quote String 2
			re11='(>)'	# Any Single Character 4
			re12='(.*?)'
			re13='(<\\/span>)'
			rg = re.compile(re1+re2+re3+re4+re5+re6+re7+re8+re9+re10+re11+re12+re13,re.IGNORECASE|re.DOTALL)
			ma = rg.search(probdata)
			if ma:
				problems[i].probtext = ma.group(12).lstrip()
			i += 1
	return problems
#main

sqlf = open("sqlquery.txt", "w")

if not os.path.exists("problems"):
	os.makedirs("problems")

os.chdir("problems")

hnd = urllib.urlopen("http://usaco.org/index.php?page=contests")
contestpage = hnd.read()

contests = fetchContests(contestpage)



for c in contests:
	c = c.replace("results", "problems")
	print "* Parsing Contest: " + c
	hnd = urllib.urlopen("http://usaco.org/index.php?page=" + c)
	data = hnd.read()
	p = fetchProblemsFromContest(data)
	for prob in p:
		if os.path.exists(prob.shortname):
			print "\t>> Same problem short-name: " + prob.shortname + ".  Skipping..."
			continue
		print "\t* Problem: " + prob.name
		print "\t\t* TestData: " + prob.zipfile
		os.makedirs(prob.shortname)
		os.chdir(prob.shortname)
		probtextf = open("problem.txt", "w")
		probtextf.write(prob.probtext)
		probtextf.close()

		ziph = urllib.urlopen(prob.zipfile)
		zipf = open("tmp.zip", "wb")
		zipf.write(ziph.read())
		zipf.close()

		zf = zipfile.ZipFile("tmp.zip")
		flist = zf.namelist()
		if len(flist) % 2 == 1:
			print "\t\t\t>> Found an odd number of test cases!"
		else:
			print "\t\t\t* Found " + str(len(flist) / 2)  + " test cases.";
			for f in flist:
				fp = open(f, "wb")
				fp.write(zf.read(f))
				fp.close()
			print "\t\t\t* Finished extraction."

		#write insertion query into file
		cid = 1;
		points = 100
		if prob.level == "Silver":
			cid = 4
			points = 50
		else:
			cid = 5
			points = 25

		date = "Open 2012"
		if c.find("mar12") == 0:
			date = "March 2012"
		elif c.find("feb12") == 0:
			date = "Februrary 2012"
		elif c.find("jan12") == 0:
			date = "January 2012"
		elif c.find("dec11") == 0:
			date = "December 2011"
		elif c.find("nov11") == 0:
			date = "November 2011"
		elif c.find("oct11") == 0:
			date = "October 2011"
		elif c.find("open12") == 0:
			date = "Open 2012"
		else:
			date = "ERROR"
		query = "INSERT INTO `problems` (`cid`, `level`, `code` , `date`, `name`, `point`, `timelimit`, `memlimit`) VALUES (" + str(cid) + ",'" + prob.level + "', '" + prob.shortname + "','" + \
			date + "','" + prob.name + "', " + str(points) + ", 1, 16);"
		sqlf.write(query + "\n") 

		print "\t\t* Generated SQL."
		zf.close()
		os.remove("tmp.zip")

		os.chdir("..")


sqlf.close()