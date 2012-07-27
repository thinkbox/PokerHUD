CREATE TABLE hand (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    id_fichier VARCHAR(100) NOT NULL,
    level VARCHAR(32) NOT NULL,    
    sb INTEGER NOT NULL,
    bb INTEGER NOT NULL,
    ante INTEGER NOT NULL,
    winner VARCHAR(250) NOT NULL,
    content TEXT NOT NULL,
    created DATETIME NOT NULL
);
 
CREATE INDEX "id" ON "hand" ("id");

CREATE TABLE action (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    id_hand INTEGER NOT NULL,
    name_player VARCHAR(250) NOT NULL,
	position VARCHAR(100) NOT NULL,
    action_preflop VARCHAR(250) NOT NULL,
    action_flop VARCHAR(250) NOT NULL,
    action_turn VARCHAR(250) NOT NULL,
    action_river VARCHAR(250) NOT NULL,
    resultat VARCHAR(250) NOT NULL,
    treated INTEGER NOT NULL  DEFAULT 0,
	updated DATETIME NOT NULL,
    created DATETIME NOT NULL
);
 
CREATE INDEX "id_action" ON "action" ("id");

CREATE TABLE player (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(250) NOT NULL,
	nb_hands INTEGER NOT NULL,
	updated DATETIME NOT NULL,
    created DATETIME NOT NULL
);
 
CREATE INDEX "name" ON "player" ("name");

CREATE TABLE stat (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    id_player INTEGER NOT NULL,
    type VARCHAR(250) NOT NULL,
    valeur VARCHAR(250) NOT NULL
);
 
CREATE INDEX "id_stat" ON "stat" ("id");