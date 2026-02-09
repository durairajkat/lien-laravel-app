SET SQL_MODE='ALLOW_INVALID_DATES';

CREATE TABLE admins (
    /* Indexes */
    id INT UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY(id),
    /* End Indexes */

    /* Account Info */
    first_name VARCHAR(64) NOT NULL,
    last_name  VARCHAR(64) NOT NULL,
    email      VARCHAR(255) NOT NULL,
    salt       CHAR(32) NOT NULL,
    password   CHAR(128) NOT NULL,
    status     ENUM('Active', 'Disabled') NOT NULL DEFAULT 'Active',
    access     ENUM('1', '2') NOT NULL DEFAULT '1',
    /* End Login Credentials */

    /* Tracking */
    last_ip    VARBINARY(16) NOT NULL, /* IN inet_pton() - OUT inet_ntop() */
    last_login TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
    modified   TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
    created    TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
    deleted    TIMESTAMP NULL DEFAULT NULL
    /* End Tracking */
)
ENGINE = InnoDB
CHARACTER SET utf8
COLLATE utf8_general_ci;

/* PW = testing */
INSERT INTO admins (
    first_name,
    last_name,
    email,
    salt,
    password,
    status,
    access,
    modified,
    created
) VALUES (
    'Bob',
    'Smith',
    'web.test@webservicescorp.com',
    'nHzGEfne1W5kVRbDwHQW8Xt26ICJaJOw',
    '03b9f73c4944656c103f841e564b34269ebded4adf6633f2e542f17f8b2beb92162b7586ff5b7767f392da220e5188450c42b85651d9c3968d1033febb97e671',
    'Active',
    '2',
    NOW(),
    NOW()
);

/* PW = testing */
INSERT INTO admins (
    first_name,
    last_name,
    email,
    salt,
    password,
    status,
    access,
    modified,
    created
) VALUES (
    'John',
    'Jacobs',
    'someone@somedomain.com',
    'nHzGEfne1W5kVRbDwHQW8Xt26ICJaJOw',
    '03b9f73c4944656c103f841e564b34269ebded4adf6633f2e542f17f8b2beb92162b7586ff5b7767f392da220e5188450c42b85651d9c3968d1033febb97e671',
    'Active',
    '1',
    NOW(),
    NOW()
);