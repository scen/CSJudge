#include <stdio.h>
#include <sys/wait.h>
#include <sys/user.h>
#include <sys/reg.h>
#include <sys/syscall.h>
#include <sys/prctl.h>
#include <sys/types.h>
#include <sys/wait.h>
#include <sys/ptrace.h>
#include <unistd.h>
#include <assert.h>
#include <stdlib.h>

#define ARRSIZE(x) (sizeof(x)/sizeof(x[0]))

//Supervisor should call this file, this jails itself and traces the process using ptrace


//Banned syscalls (check asm/unistd.h for more sys call EAX #s)
int bannedCalls[]={2,14,12,15,26,37,38,39,39,40,41,42,46,47,48,49,50,60,61,63,64,72,83,88,120,102,182,183,190};


// Args
// grader [executable] [testfile]
// STDOUT

bool fileExists(const char* file)
{
	FILE *f = fopen(file, "r");
	if (!f) return false;
	fclose(f);
	return true;
}

int main(int argc, char** argv)
{
	// if (argc != 3) exit();
	// char* file = argv[1];
	// char* testData = argv[2];

	// if (!fileExists(file) || !fileExists(testData))
	// 	exit();
	sleep(2);
	long origEax;
	int status;
	struct user_regs_struct registers;
	pid_t pid = fork();


	if (!pid)
	{
		prctl(PR_SET_PDEATHSIG, SIGKILL);
		ptrace(PTRACE_TRACEME, 0, 0, 0);
		ptrace(PTRACE_SETOPTIONS, pid, 0, PTRACE_SYSCALL | PTRACE_O_TRACEFORK);
		execlp("./illegal", "./illegal", 0);
	}
	else
	{
		while (1)
		{
			wait(&status);
			if (WIFEXITED(status))
			{
				//exited
				break;
			}
			if (WSTOPSIG(status) == SIGTRAP)
			{
				int event = (status >> 16) & 0xffff;
				if (event == PTRACE_EVENT_FORK)
				{
					printf("Fork detected\n");
					pid_t newpid;
					ptrace(PTRACE_GETEVENTMSG, pid, 0, (long)&newpid);
					kill(newpid, SIGKILL);
					kill(pid, SIGKILL);
					abort();
					break;
				}
			}
			ptrace(PTRACE_GETREGS, pid, 0, &registers);
			printf("System call, %d\n", registers.orig_eax);
			for (int i = 0; i < ARRSIZE(bannedCalls); i++)
			{
				if (registers.orig_eax == bannedCalls[i])
				{
					printf("\t Illegal system call\n");
					abort();
				}
			}
			ptrace(PTRACE_SYSCALL, pid, 0, 0);
		}
	}

	return 0;
}