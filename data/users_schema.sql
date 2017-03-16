CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    login VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    email VARCHAR(150) DEFAULT NULL,
    firstname VARCHAR(150) DEFAULT NULL,
    lastname VARCHAR(150) DEFAULT NULL,
    description TEXT DEFAULT NULL, 
    amount_of_metall INTEGER DEFAULT NULL,
    amount_of_heavygas INTEGER DEFAULT NULL,
    amount_of_ore INTEGER DEFAULT NULL,
    amount_of_hydro INTEGER DEFAULT NULL,
    amount_of_titan INTEGER DEFAULT NULL,
    amount_of_darkmatter INTEGER DEFAULT NULL,
    amount_of_redmatter INTEGER DEFAULT NULL,
    amount_of_anti INTEGER DEFAULT NULL,
    amount_of_electricity INTEGER DEFAULT NULL
    );
