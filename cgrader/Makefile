#compile as 32bits

all:
	g++ -m32 -static grader.cpp -o grader
	g++ -m32 -static illegal.cpp -o illegal
	g++ -m32 supervisor.cpp -o supervisor -std=c++0x -lrt
clean:
	rm -rf grader
	rm -rf illegal
	rm -rf supervisor