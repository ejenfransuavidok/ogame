CREATE TABLE settings_types (id INTEGER PRIMARY KEY AUTO_INCREMENT, title varchar(100) NOT NULL, typeof varchar(10) NOT NULL);

INSERT INTO settings_types (title, typeof) VALUES ('Неопределено', 'UNDEFINED');
INSERT INTO settings_types (title, typeof) VALUES ('Целое', 'INT');
INSERT INTO settings_types (title, typeof) VALUES ('С плавающей точкой', 'FLOAT');
INSERT INTO settings_types (title, typeof) VALUES ('Строковое', 'STRING');
