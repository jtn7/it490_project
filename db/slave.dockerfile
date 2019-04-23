FROM mysql

COPY my.cnf /etc/mysql/my.cnf
COPY slave.cnf /etc/mysql/conf.d/slave.cnf

RUN chmod 555 /etc/mysql/ && \
	chmod -R 444 /etc/mysql/* && \
	chmod 555 /etc/mysql/conf.d

RUN mkdir /var/log/mysql && \
	chown mysql:mysql /var/log/mysql