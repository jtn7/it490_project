FROM mysql

COPY my.cnf /etc/mysql/my.cnf
COPY master.cnf /etc/mysql/conf.d/master.cnf

RUN chmod 555 /etc/mysql/ && \
	chmod -R 444 /etc/mysql/*

RUN mkdir /var/log/mysql && \
	chown mysql:mysql /var/log/mysql