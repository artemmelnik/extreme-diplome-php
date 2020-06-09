CREATE TABLE users
(
  id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  password varchar(32),
  email varchar(255),
  first_name varchar(255),
  last_name varchar(255),
  middle_name varchar(255),
  photo varchar(255),
  position varchar(255),
  job_date date NOT NULL,
  role_id int(2) DEFAULT '0' NOT NULL,
  token varchar(255),
  created_at datetime NOT NULL,
  updated_at timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE tests (
  id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  title VARCHAR(255),
  created_at DATETIME NOT NULL,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE test_logs (
  id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  user_id INT(11),
  test_id INT(11),
  question_id INT(11),
  answer_id INT(11),
  correctly TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE questions (
  id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  test_id INT(11),
  title VARCHAR(255),
  created_at DATETIME NOT NULL,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE answers (
  id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  question_id INT(11),
  title VARCHAR(255),
  correctly TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
