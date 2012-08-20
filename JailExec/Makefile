all:
	g++ -c error.c -Wall -ansi -pedantic 
	g++ -c safe.c -Wall -ansi -pedantic 
	g++ -o os-detect os-detect.c -Wall -ansi -pedantic 
	g++ -lrt -pthread -c safeexec.c -Wall -ansi -pedantic 
	g++ -lrt -pthread -o safeexec error.o safeexec.o safe.o -Wall -ansi -pedantic -lrt 
	g++ -static illegal.cpp -o illegal 
	cp illegal testjail/run

clean:
	rm -rf *.o safeexec os-detect
