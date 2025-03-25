# Gestion des Utilisateurs avec l'Architecture MVC, PHP et MySQL

## Description du Projet

Ce projet consiste à développer une application web sécurisée pour la gestion des utilisateurs en utilisant l'architecture MVC (Modèle-Vue-Contrôleur), le langage PHP et une base de données MySQL. L'application permet aux administrateurs de gérer les comptes clients (ajout, modification, suppression, activation/désactivation) et offre aux clients la possibilité de consulter et modifier leurs informations personnelles. 

## Fonctionnalités Principales

### Authentification et Sécurité
- **Inscription et Connexion** : Système sécurisé avec hashage des mots de passe (bcrypt).
- **Gestion des Sessions** : Restrictions d'accès basées sur les rôles (Administrateur/Client).
- **Protection** : Validation des entrées, protection contre les attaques (SQL Injection, XSS, CSRF).

### Gestion des Profils
#### Administrateur
- Tableau de bord avec aperçu des utilisateurs.
- Création, modification, suppression et activation/désactivation des comptes.
- Gestion des droits et rôles.
- Consultation des logs de connexion.

#### Client
- Inscription et connexion.
- Accès et modification du profil personnel.
- Consultation de l'historique des connexions.

## Structure de la Base de Données

### Tables Principales
1. **roles**  
   - `id` (INT, PK, AUTO_INCREMENT)  
   - `name` (VARCHAR(50), UNIQUE, NOT NULL)  

2. **users**  
   - `id` (INT, PK, AUTO_INCREMENT)  
   - `username` (VARCHAR(50), UNIQUE, NOT NULL)  
   - `email` (VARCHAR(100), UNIQUE, NOT NULL)  
   - `password` (VARCHAR(255), NOT NULL)  
   - `role_id` (INT, FK référençant `roles.id`)  
   - `status` (ENUM('active', 'inactive'), DEFAULT 'active')  
   - `created_at` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)  

3. **sessions**  
   - `user_id` (INT, FK référençant `users.id`)  
   - Autres champs pertinents pour les logs de connexion.  

### Relations
- Un utilisateur a un rôle (`users.role_id → roles.id`).  
- Un utilisateur peut avoir plusieurs connexions (`sessions.user_id → users.id`).  

## Technologies Utilisées
- **Backend** : PHP (POO)  
- **Base de Données** : MySQL  
- **Architecture** : MVC personnalisée  
- **Frontend** : HTML5, CSS3 (avec framework CSS optionnel)  

## Installation et Configuration

1. **Cloner le dépôt** :  
   ```bash
   git clone [URL_du_dépôt]