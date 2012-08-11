#include <stdio.h>
#include <sys/ptrace.h>
#include <sys/wait.h>
#include <sys/user.h>
#include <unistd.h>
#include <assert.h>
#include <stdlib.h>

//PHP script should call this file

void Done(int,float,float); //Status, CPU time, MEM usage


// Args
// grader [executable] [testfile] [timelimit (s)] [memorylimit (mb)]
// STDOUT

void Done(int status = -1, float cputime = 0.0f, float mem = 0.0f)
{
	printf("%d,%f,%f\n", status, cputime, mem);
	exit(status);
}

int main(int argc, char** argv)
{
	return 0;
}