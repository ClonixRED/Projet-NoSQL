-- Créer la table effectif
CREATE TABLE IF NOT EXISTS effectif (
    id SERIAL PRIMARY KEY,
    password VARCHAR(100),
    nom VARCHAR(50),
    prenom VARCHAR(50),
    numero VARCHAR(15),
    mail VARCHAR(100)
);

-- Créer la table service
CREATE TABLE IF NOT EXISTS service (
    id SERIAL PRIMARY KEY,
    debutdeservice TIMESTAMP,
    findeservice TIMESTAMP,
    effectifid INTEGER,
    FOREIGN KEY (effectifid) REFERENCES effectif(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS clients (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50),
    prenom VARCHAR(50),
    mail VARCHAR(100),
    password VARCHAR(100),
    numero VARCHAR(15),
    adresse VARCHAR(255),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS commandes (
    id SERIAL PRIMARY KEY,
    client_id INTEGER REFERENCES clients(id) ON DELETE CASCADE,
    produit VARCHAR(100),
    quantite INTEGER,
    date_commande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(50) DEFAULT 'En cours',
    responsable VARCHAR(100)
);


-- Insérer un utilisateur de base dans la table effectif
INSERT INTO effectif (prenom, nom, numero, mail, password) VALUES
('Clonix', 'RED', '0123456789', 'effectif@clonixcorp.com', 'password');

INSERT INTO clients (prenom, nom, numero, mail, adresse, password) 
VALUES ('Jean', 'Dupont', '0123456789', 'jean.dupont@client.com', '123 Rue Exemple, Paris', 'password');


