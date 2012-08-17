#include <stdio.h>
#include <sys/wait.h>
#include <sys/user.h>
#include <sys/reg.h>
#include <sys/syscall.h>
#include <sys/prctl.h>
#include <sys/types.h>
#include <sys/wait.h>
#include <sys/ptrace.h>
#include <sys/time.h>
#include <unistd.h>
#include <assert.h>
#include <dirent.h>
#include <errno.h>
#include <stdlib.h>
#include <vector>
#include <string>
#include <algorithm>
#include <stdint.h>
#include <time.h>
#include <string.h>
#include <pthread.h>
#include <errno.h>
#include <signal.h>
#include <fcntl.h>
//Supervisor

using namespace std;

#define NOT_FINISHED 9001
#define FINISHED (~NOT_FINISHED)

void done(int s = 0)
{
	printf("ERROR %d\n", s);
	exit(s); 
}

int getdir (string dir, vector<string> &files)
{
    DIR *dp;
    struct dirent *dirp;
    if((dp  = opendir(dir.c_str())) == NULL) {
        return errno;
    }

    while ((dirp = readdir(dp)) != NULL) {
        files.push_back(string(dirp->d_name));
    }
    closedir(dp);
    return 0;
}

inline unsigned long time_milli(timespec& ts)
{
	return (unsigned long) ((ts.tv_sec * 1000UL)
            + (ts.tv_nsec / 1000000UL));
}

inline bool copyfile(char* src, char* dst)
{
	FILE* fin, *fout;
	fin = fopen(src, "rb");
	if (!fin) return -1;
	open(dst, O_CREAT, 0777);
	fout = fopen(dst, "wb");
	if (!fout)
	{
		fclose(fin);
		return -1;
	}
	fseek(fin, 0, SEEK_END);
	long fileSize = ftell(fin);
	rewind(fin);

	char *buf = (char*)malloc(fileSize);

	fread(buf, 1, fileSize, fin);
	fclose(fin);
	fwrite(buf, 1, fileSize, fout);

	fclose(fout);

	return 0;
}

void *execThread(void* ptr)
{
	pid_t pid = fork(); //fork for execve
	assert(pid >= 0);
	if (pid) //parent
	{
		int status = 0;
		waitpid(pid, &status, 0);
		if (WIFSTOPPED(status))
		{
			printf("Stopped\n");
			if (WSTOPSIG(status) == SIGSEGV) //Seg-fault, memory limit exceeded
			{
				printf("DAMN\n");
			}
		}
		*(sig_atomic_t*)ptr = FINISHED;
	}
	else
	{
		//Child process
		//idea; run as different user

		//Set memory limits
		rlimit memlimit = {0};
		memlimit.rlim_cur = 10;
		memlimit.rlim_max = 10;
		setrlimit(RLIMIT_AS, &memlimit);
		while (1) {}
		// char *path[2] = {"grader.exe", 0};
		// execve(*path, path, 0);
	}
}

//supervisor [jaildir] [exe] [grader] [tlimit(S)] [memlimit(M)] [#test] (test file list IN) (test data list OUT) 
int main(int argc, char** argv)
{
	int numtests;
	float cpuLimit, memLimit;
	sscanf(argv[4], "%f", &cpuLimit);
	sscanf(argv[5], "%f", &memLimit);
	cpuLimit *= 1000.f; //convert to milliseconds


	sscanf(argv[6], "%d", &numtests);
	if (chdir(argv[1]))
		done(-1);

	vector<string> files;
	if (getdir(".", files))
		done(-1);

	//clear directory
	for_each(files.begin(), files.end(), [&](string s) {
		if (s[0] != '.' && unlink(s.c_str()))
		{
			done(-11);
		}
	});

	if (copyfile(argv[2], "test.exe"))
		done(-2);

	if (copyfile(argv[3], "grader.exe"))
		done(-2);

	// //copy input files
	// for (int i = 0; i < numtests; i++)
	// {
	// 	char buf[100];
	// 	sprintf(buf, "in.%d", i);
	// 	if (symlink(argv[7 + i], buf))
	// 		done(-1);
	// }

	// //copy output files
	// for (int i = 0; i < numtests; i++)
	// {
	// 	char buf[100];
	// 	sprintf(buf, "out.%d", i);
	// 	if (symlink(argv[7 + numtests + i], buf))
	// 		done(-1);
	// }

	//Sym

	if (chroot("."))
		done(-3);

	struct timespec start, end;


	for (int i = 0; i < numtests; i++)
	{
		clock_gettime(CLOCK_MONOTONIC, &start);
		//unlink("grader_results.rslt");
		pthread_t thread;
		volatile sig_atomic_t finished =  NOT_FINISHED;
		pthread_create(&thread, NULL, execThread, (void*)&finished);

		for(;;)
		{
			clock_gettime(CLOCK_MONOTONIC, &end);
			unsigned long delta = time_milli(end) - time_milli(start);

			if (finished == FINISHED) //timed out
			{
				printf("Runtime = %ums\n", delta);
				break;
			}
			else if (delta > cpuLimit) //timed out
			{
				printf("Program timed out @ %f\n", (float)delta/1000.f);
				break;
			}
		}
	}

	exit(-1);

	return 0;
}