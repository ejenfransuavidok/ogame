CREATE TABLE events (
    id INTEGER PRIMARY KEY AUTO_INCREMENT, 
    name varchar(100) NOT NULL, 
    description TEXT NOT NULL,
    user integer,
    event_type integer,
    event_begin integer,
    event_end integer,
    target_star integer,
    target_planet integer,
    target_sputnik integer);
