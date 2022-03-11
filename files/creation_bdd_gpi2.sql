CREATE USER 'gpi2 '@'localhost' IDENTIFIED BY 'network';

CREATE DATABASE exercices DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE tp_videos DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE tp_films DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE tp_etudiants DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE tp_livres DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE exm_juin2019_1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE exm_juin2019_2 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE exm_juin2020_1 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE exm_juin2020_2 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

GRANT ALL PRIVILEGES ON exercices.* TO gpi2@localhost;

GRANT ALL PRIVILEGES ON tp_videos.* TO gpi2@localhost;
GRANT ALL PRIVILEGES ON tp_films.* TO gpi2@localhost;
GRANT ALL PRIVILEGES ON tp_etudiants.* TO gpi2@localhost;
GRANT ALL PRIVILEGES ON tp_livres.* TO gpi2@localhost;

GRANT ALL PRIVILEGES ON exm_juin2019_1.* TO gpi2@localhost;
GRANT ALL PRIVILEGES ON exm_juin2019_2.* TO gpi2@localhost;
GRANT ALL PRIVILEGES ON exm_juin2020_1.* TO gpi2@localhost;
GRANT ALL PRIVILEGES ON exm_juin2020_2.* TO gpi2@localhost;

