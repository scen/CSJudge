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
#include <vector>
#include <string>
#include <dirent.h>
#include <errno.h>
#include <sys/socket.h>
#include <sys/types.h>
#include <netinet/in.h>
#include <netdb.h>
#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <unistd.h>
#include <errno.h>
#include <arpa/inet.h> 

void msleep (int ms)
{
	struct timeval tv;
	int v;
	do
	{
		tv.tv_sec = ms / 1000;
		tv.tv_usec = (ms % 1000) * 1000;
		v = select (0, NULL, NULL, NULL, &tv);
		/* The value of the timeout is undefined after the select returns */
	}
	while ((v < 0) && (errno == EINTR));
}
int main(int argc, char *argv[])
{
	printf("output\n");
	msleep(1900);
	return 0;
	char buf[1000] = {0};
	scanf("%s", buf);
	printf("buf=%s\n", buf);
	printf("LOL\n");
	return 0;
	for (int i =0; i < 2000000; i++)
	{
		asm("stc");
	}
	return 0;
	int sockfd = 0, n = 0;
	char recvBuff[1024];
	struct sockaddr_in serv_addr; 

	memset(recvBuff, '0',sizeof(recvBuff));
	if((sockfd = socket(AF_INET, SOCK_STREAM, 0)) < 0)
	{
		printf("\n Error : Could not create socket \n");
		return 1;
	} 

	memset(&serv_addr, '0', sizeof(serv_addr)); 

	serv_addr.sin_family = AF_INET;
	serv_addr.sin_port = htons(5000); 

	if(inet_pton(AF_INET, "173.194.33.8", &serv_addr.sin_addr)<=0)
	{
		printf("\n inet_pton error occured\n");
		return 1;
	} 
	printf("im still alive\n");
	if( connect(sockfd, (struct sockaddr *)&serv_addr, sizeof(serv_addr)) < 0)
	{
		printf("\n Error : Connect Failed \n");
		return 1;
	} 

	while ( (n = read(sockfd, recvBuff, sizeof(recvBuff)-1)) > 0)
	{
		recvBuff[n] = 0;
		if(fputs(recvBuff, stdout) == EOF)
		{
			printf("\n Error : Fputs error\n");
		}
	} 

	if(n < 0)
	{
		printf("\n Read error \n");
	} 

	return 0;
}