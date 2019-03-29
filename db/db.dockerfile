FROM mysql

COPY my.cnf /etc/mysql/my.cnf
RUN chmod 444 /etc/mysql/my.cnf