CREATE TABLE sessions (
    id CHAR(32) NOT NULL,
    data TEXT,
    last_accessed TIMESTAMP NOT NULL,
    PRIMARY KEY (id)
);

