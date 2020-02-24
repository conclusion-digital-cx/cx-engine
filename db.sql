BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS "types" (
	"id"	INTEGER PRIMARY KEY AUTOINCREMENT UNIQUE,
	"name"	TEXT,
	"schema"	TEXT,
	"attributes"	JSON,
	"titleKey"	TEXT DEFAULT 'title',
	"core"	INTEGER,
	"showInNavigation"	INTEGER
);
CREATE TABLE IF NOT EXISTS "trips" (
	"id"	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	"title"	TEXT,
	"creator"	INTEGER,
	"company"	INTEGER
);
CREATE TABLE IF NOT EXISTS "companies" (
	"id"	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	"title"	TEXT
);
CREATE TABLE IF NOT EXISTS "users" (
	"id"	TEXT,
	"username"	TEXT,
	"email"	TEXT,
	"password"	TEXT,
	"role"	INTEGER
);
CREATE TABLE IF NOT EXISTS "permissions" (
	"id"	INTEGER PRIMARY KEY AUTOINCREMENT UNIQUE,
	"role"	INTEGER,
	"type"	INTEGER
);
CREATE TABLE IF NOT EXISTS "account" (
	"id"	INTEGER PRIMARY KEY AUTOINCREMENT UNIQUE,
	"role"	TEXT,
	"type"	TEXT
);
CREATE TABLE IF NOT EXISTS "roles" (
	"id"	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	"name"	TEXT,
	"description"	TEXT
);
CREATE TABLE IF NOT EXISTS "pages" (
	"id"	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	"title"	TEXT,
	"url"	TEXT NOT NULL UNIQUE,
	"body"	TEXT,
	"blocks"	TEXT,
	"cache"	INTEGER,
	"state"	TEXT,
	"createdAt"	REAL
);
CREATE TABLE IF NOT EXISTS "media" (
	"id"	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	"title"	TEXT,
	"image"	TEXT,
	"createdAt"	TEXT,
	"createdBy"	INTEGER
);
CREATE TABLE IF NOT EXISTS "menus" (
	"id"	INTEGER PRIMARY KEY AUTOINCREMENT UNIQUE,
	"title"	TEXT,
	"url"	TEXT
);
CREATE TABLE IF NOT EXISTS "news" (
	"id"	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	"title"	TEXT NOT NULL,
	"body"	TEXT,
	"author"	TEXT
);
COMMIT;
