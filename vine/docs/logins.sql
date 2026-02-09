SET SQL_MODE='ALLOW_INVALID_DATES';

CREATE TABLE logins (
    id      INT UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY(id),
    row_id  INT UNSIGNED NOT NULL,
    email   VARCHAR(255) NOT NULL,
    token   CHAR(128) NOT NULL,
    genre   ENUM('Admin', 'User') NOT NULL DEFAULT 'User',
    created TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
    INDEX(email)
)
ENGINE = InnoDB
CHARACTER SET utf8
COLLATE utf8_general_ci;