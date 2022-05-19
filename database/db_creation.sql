/* Have to DROP TABLE(s) before we DROP DOMAIN(s) and TYPE(s) */
DROP TABLE IF EXISTS history;
DROP TABLE IF EXISTS players;
DROP TABLE IF EXISTS games;

--- Unsigned int type for 2 byte integer
DROP DOMAIN IF EXISTS uint2;
CREATE DOMAIN uint2 AS SMALLINT CHECK(VALUE>=0);

--- Unsigned int type for 4 byte integer
DROP DOMAIN IF EXISTS uint4;
CREATE DOMAIN uint4 AS INTEGER CHECK(VALUE>=0);

--- Unsigned int type for 8 byte integer
DROP DOMAIN IF EXISTS uint8;
CREATE DOMAIN uint8 AS BIGINT CHECK(VALUE>=0);

--- Enumaration for all kind of actions that a player may do
---  and we want to track
DROP TYPE IF EXISTS action_t;
CREATE TYPE action_t AS ENUM
(
    -- Standard projects
    'GOLD_GREENERY',
    'CITY',
    'OCEAN',
    'ENERGY_PROD',
    'GOLD_TEMP',
    'HEAT_TEMP',
    'LEAFS_GREENERY',
    -- Produceeeeeeeeeeeee
    'PRODUCE',
    -- Added or remove a resource
    'ADD',
    'REMOVE'
);

--- Enumaration for all kind of resources that a player may add or remove
---     same as the coresponding column name on player
DROP TYPE IF EXISTS resource_t;
CREATE TYPE resource_t AS ENUM
(
    'TR',
    'gold', 'goldprd',
    'plant', 'plantprod',
    'steel', 'steelprod',
    'titan', 'titanprod',
    'energy', 'energyprod',
    'heat', 'heatprod'
);

CREATE TABLE games
(
    "id" BIGSERIAL,
    "name" VARCHAR(64) NOT NULL,
    "maxplayers" uint2 NOT NULL,
    "currplayers" uint2 DEFAULT 1,
    "gen" uint2 DEFAULT 1,
    "salt" VARCHAR(16) NOT NULL,
    "password" VARCHAR(128) NOT NULL,
    PRIMARY KEY("id"),
    CONSTRAINT player_validation CHECK("currplayers"<="maxplayers")
);

CREATE TABLE players
(
    "id" BIGSERIAL,
    "name" VARCHAR(64) NOT NULL,
    "gid" uint8,
    "TR" uint2 DEFAULT 20,
    "gold" uint2 DEFAULT 0,
    "goldprod" SMALLINT DEFAULT 1,
    "plant" uint2 DEFAULT 0,
    "plantprod" uint2 DEFAULT 1,
    "steel" uint2 DEFAULT 0, 
    "steelprod" uint2 DEFAULT 1,
    "titan" uint2 DEFAULT 0,
    "titanprod" uint2 DEFAULT 1,
    "energy" uint2 DEFAULT 0,
    "energyprod" uint2 DEFAULT 1,
    "heat" uint2 DEFAULT 0,
    "heatprod" uint2 DEFAULT 1,
    PRIMARY KEY("id"),
    FOREIGN KEY("gid") REFERENCES games("id")
);

CREATE TABLE history
(
    "id" BIGSERIAL,
    "pid" uint8,
    "gid" uint8,
    "action" action_t NOT NULL,
    "ammount" uint2 0,
    "resource" resource_t DEFAULT NULL,
    PRIMARY KEY("id"),
    FOREIGN KEY("pid") REFERENCES players("id"),
    FOREIGN KEY("gid") REFERENCES games("id")
);