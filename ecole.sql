CREATE DATABASE IF NOT EXISTS ecole
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE ecole;

DROP TABLE IF EXISTS etudiant;

CREATE TABLE etudiant (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    classe VARCHAR(50) NOT NULL,
    date_naissance DATE DEFAULT NULL,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uk_etudiant_email UNIQUE (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO etudiant (nom, prenom, email, classe, date_naissance) VALUES
    ('Alami', 'Youssef', 'y.alami@email.com', 'ILCS-1A', '2004-03-15'),
    ('Benani', 'Fatima', 'f.benani@email.com', 'ILCS-1A', '2005-07-22'),
    ('Cherkaoui', 'Omar', 'o.cherkaoui@email.com', 'ILCS-1B', '2004-11-08'),
    ('Dahbi', 'Sara', 's.dahbi@email.com', 'ILCS-1B', '2005-01-30'),
    ('El Fassi', 'Karim', 'k.elfassi@email.com', 'ILCS-2A', '2004-09-12');
