CREATE DATABASE IF NOT EXISTS foodics;
CREATE USER IF NOT EXISTS foodics@localhost IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON *.* TO foodics@localhost WITH GRANT OPTION;