CREATE TABLE planets_types 
    (
        id INTEGER PRIMARY KEY AUTO_INCREMENT, 
        name VARCHAR(256), 
        description TEXT DEFAULT NULL, 
        mineral_metall INTEGER DEFAULT NULL, 
        mineral_heavygas INTEGER DEFAULT NULL,
        mineral_ore INTEGER DEFAULT NULL,
        mineral_hydro INTEGER DEFAULT NULL,
        mineral_titan INTEGER DEFAULT NULL,
        mineral_darkmatter INTEGER DEFAULT NULL,
        mineral_redmatter INTEGER DEFAULT NULL,
        mineral_anti INTEGER DEFAULT NULL
    );
