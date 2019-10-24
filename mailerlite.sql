CREATE DATABASE IF NOT EXISTS mailerlite;

CREATE  TABLE IF NOT EXISTS mailerlite.subscriber (
	subscriber_id INT AUTO_INCREMENT, 
	email_address VARCHAR(255),
	name VARCHAR(255),
	state VARCHAR(75),
	source VARCHAR(75),
	fields VARCHAR(255),
	created_at DATETIME,
	PRIMARY KEY (subscriber_id) 
);

CREATE  TABLE IF NOT EXISTS mailerlite.fields (
	field_id INT AUTO_INCREMENT, 
	title VARCHAR(255),
	type VARCHAR(75),
	created_at DATETIME,
	PRIMARY KEY (field_id)
);

INSERT INTO mailerlite.fields (title, type) VALUES ('Age','Number');
INSERT INTO mailerlite.fields (title, type) VALUES ('DOB','Date');