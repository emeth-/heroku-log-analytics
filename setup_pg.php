<?php
include("config.php");
include("func.php");

q('CREATE TABLE IF NOT EXISTS "track_browser" (
    "key" character varying(255),
    "count" bigint,
    PRIMARY KEY ("key")
)');

q('CREATE TABLE IF NOT EXISTS "track_os" (
    "key" character varying(255),
    "count" bigint,
    PRIMARY KEY ("key")
)');

q('CREATE TABLE IF NOT EXISTS "track_page" (
    "key" character varying(255),
    "count" bigint,
    PRIMARY KEY ("key")
)');

q('CREATE TABLE IF NOT EXISTS "track_refer" (
    "key" character varying(255),
    "count" bigint,
    PRIMARY KEY ("key")
)');
?>
