CREATE TABLE IF NOT EXISTS known
				(id INTEGER PRIMARY KEY, 
       			uri text UNIQUE NOT NULL,
       			valid integer NOT NULL);