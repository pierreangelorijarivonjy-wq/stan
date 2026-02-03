# UNIVERSITE DE FIANARANTSOA
# ECOLE NATIONALE D’INFORMATIQUE

MEMOIRE DE FIN D’ETUDES POUR L’OBTENTION DU DIPLOME DE LICENCE PROFESSIONNELLE
Mention : Informatique
Parcours : Génie Logiciel et Base de Données

Intitulé :
Développement d'une Plateforme de Télé-enseignement : Focus sur les Paiements et Communications Vérifiables

Plateforme de Télé‑enseignement : EduPass-MG

Présenté le : 15 Janvier 2026
Par : STAN

Membres du Jury :
- Président : Monsieur MAHATODY Thomas, Docteur HDR
- Examinateur : Monsieur RABETAFIKA Louis Haja, Maître de Conférences
    - Monsieur RALAIVAO Jean Christian, Assistant d’Enseignement Supérieur (Encadreur pédagogique)
    - Monsieur RAZAFINDRAIBE, Ingénieur Principal (Encadreur professionnel)

Année Universitaire : 2025 - 2026

---

## Vitae
Étudiant en Licence Professionnelle à l'École Nationale d'Informatique (ENI), spécialisé en Génie Logiciel et Base de Données. Passionné par le développement d'applications web et mobiles, j'ai acquis des compétences solides en PHP (Laravel), JavaScript (React, React Native) et en gestion de bases de données relationnelles (PostgreSQL). Mon parcours académique et mes expériences de stage m'ont permis de développer une rigueur technique et une capacité d'adaptation aux nouvelles technologies.

---

## Remerciements
Je tiens tout d'abord à exprimer ma profonde gratitude à Monsieur le Président de l’Université de Fianarantsoa pour son dévouement constant à l'excellence académique et pour le cadre d'apprentissage privilégié qu'il offre aux étudiants. Mes remerciements s'adressent également à Monsieur le Directeur de l’Ecole Nationale d’Informatique (ENI) pour la qualité exceptionnelle de la formation dispensée au sein de son établissement.

Je souhaite exprimer ma reconnaissance à Monsieur le Directeur du CNTEMAD pour m'avoir accueilli au sein de son institution et pour la confiance qu'il m'a témoignée tout au long de ce stage. Je remercie également Monsieur le Chef de mention Informatique ainsi que Monsieur le Chef de parcours pour leur encadrement rigoureux et leurs précieux conseils.

Un grand merci à mes rapporteurs, Monsieur RALAIVAO Jean Christian et Monsieur RAZAFINDRAIBE, pour leur disponibilité constante, leur patience et leur encadrement technique qui ont été déterminants dans la réussite de ce projet. Enfin, j'adresse mes remerciements aux autres membres du Jury pour l'intérêt qu'ils portent à mon travail et pour le temps qu'ils consacrent à l'évaluation de ce mémoire.

---

## Sommaire
1. Introduction générale .................................................................................................. 1
2. PARTIE I. PRESENTATIONS .......................................................................................................................................
3. Chapitre 1. Présentation de l’ENI ...................................................................................................................................
4. Chapitre 2. Présentation de l’établissement d’accueil ..........................................
5. Chapitre 3. Description du projet....................................................................................................................................
6. PARTIE II. ANALYSE ET CONCEPTION ..................................................................................................
7. Chapitre 4. Analyse préalable..........................................................................................................................................
8. Chapitre 5. Analyse conceptuelle.....................................................................................................................................
9. Chapitre 6. Conception détaillée......................................................................................................................................
10. PARTIE III. REALISATION..........................................................................................................................................
11. Chapitre 7. Mise en place de l’environnement de développement ...............................................
12. Chapitre 8. Développement de l’application ..................................................................................................
13. Conclusion ..............................................................................................................................................

---

## Liste des figures
- Figure 1 : Ecole Nationale d’Informatique Fianarantsoa ................................................... 10
- Figure 2 : Organigramme de l’ENI ..................................................................................... 12
- Figure 3 : Architecture MVC de l'application ...................................................................... 25
- Figure 4 : Interface du Dashboard Administrateur .............................................................. 28
- Figure 5 : Processus de vérification par QR Code ............................................................... 30

## Liste des tableaux
- Tableau 1 : Organisation du système de formation pédagogique ....................................... 13
- Tableau 2 : Architecture des études (LMD) ........................................................................ 14
- Tableau 3 : Liste des formations existantes à l’ENI ............................................................ 14
- Tableau 4 : Débouchés professionnels ............................................................................... 16

## Liste des abréviations
- API : Application Programming Interface
- AUF : Agence Universitaire de la Francophonie
- CARI : Colloque Africain sur la Recherche en Informatique
- CIN : Carte d'Identité Nationale
- CITEF : Conférence Internationale des Ecoles de formation d’Ingénieurs et Technicien d’Expression Française
- CNH : Commission Nationale d’Habilitation
- CSV : Comma-Separated Values
- CUR : Centre Universitaire Régional
- ENI : Ecole Nationale d’Informatique
- IRD : Institut de Recherche pour le Développement
- IREMIA : Institut de Recherche en Mathématiques et Informatique Appliquées
- LMD : Licence – Master – Doctorat
- MVC : Modèle-Vue-Contrôleur
- OTP : One-Time Password
- PDF : Portable Document Format
- PRESUP : Programme de renforcement en l’Enseignement Supérieur
- QR Code : Quick Response Code
- SMS : Short Message Service
- SSII : Sociétés de Services en Ingénierie Informatique
- TIC : Technologies de l’information et de la communication
- UML : Unified Modeling Language

---

# Introduction générale
Le secteur de l'éducation à Madagascar, et plus particulièrement le domaine du télé-enseignement, traverse actuellement une phase de mutation profonde. Cependant, cette transition numérique se heurte encore à des défis majeurs liés à la gestion administrative et financière. Au sein des institutions de formation à distance, les processus traditionnels engendrent souvent des files d'attente interminables lors des périodes de paiement, ce qui pénalise l'expérience des étudiants. De plus, la difficulté de vérifier de manière instantanée et fiable l'authenticité des documents officiels, tels que les convocations aux examens, constitue un frein à la fluidité du parcours académique et expose l'administration à des risques de fraude.

C'est dans ce contexte que s'inscrit le projet EduPass-MG. Cette initiative vise à moderniser les interactions entre l'administration et les apprenants à travers une plateforme numérique intégrée. L'objectif central de notre travail est de mettre en place une solution sécurisée capable de gérer les flux financiers via des paiements en ligne, tout en automatisant la production de documents officiels sécurisés par des technologies de QR code et de signature numérique. Par ailleurs, l'intégration d'un système de rapprochement bancaire automatique permet d'alléger considérablement la charge de travail des services comptables.

Le présent rapport détaille les différentes phases de réalisation de ce projet. Nous débuterons par une présentation de l'institution d'accueil et du cadre général de l'étude. Ensuite, nous aborderons la phase d'analyse et de conception, où nous justifierons nos choix méthodologiques et techniques. Enfin, nous présenterons la mise en œuvre de la solution, les tests effectués et les résultats obtenus, avant de conclure sur les perspectives d'évolution de la plateforme.

---

# PARTIE I. PRESENTATIONS

## Chapitre 1. Présentation de l’ENI
Dans ce chapitre, nous présentons l’Ecole Nationale d’Informatique (ENI) à travers ses informations générales, son historique, ses missions, son organigramme, ses domaines de spécialisation et ses relations externes.

### 1.1. Informations d’ordre général
L’Ecole Nationale d’Informatique, en abrégé ENI, est un établissement d’enseignement supérieur rattaché académiquement et administrativement à l’Université de Fianarantsoa. Le siège de l’Ecole se trouve à Tanambao-Antaninarenina à Fianarantsoa.

Coordonnées :
- Adresse : Ecole Nationale d’Informatique (ENI) Tanambao, Fianarantsoa.
- Boîte Postale : 1487, Code postal 301.
- Téléphone : 020 75 508 01.
- Email : eni@univ-fianar.mg.
- Site Web : www.univ-fianar.mg/eni.

> [!NOTE]
> La Figure 1 présente l’Ecole Nationale d’Informatique en vue de face.
> Figure 1 : Ecole Nationale d’Informatique Fianarantsoa

### 1.2. Missions et historique
L’Ecole Nationale d’Informatique occupe une place prépondérante dans le paysage éducatif malgache, se positionnant comme le principal vecteur de diffusion des technologies de l'information et de la communication. Son histoire commence en 1983, lorsqu'elle fut créée par le décret N° 83-185 au sein du Centre Universitaire Régional de Fianarantsoa. Depuis sa création, elle demeure l’unique établissement universitaire public spécialisé exclusivement dans la formation de cadres de haut niveau en informatique, répondant ainsi à une demande croissante du marché de l'emploi.

Les missions de l'ENI s'articulent autour de trois axes fondamentaux. Tout d'abord, elle s'engage à fournir aux étudiants des connaissances théoriques solides, constituant le socle indispensable à toute expertise technique. Ensuite, l'école met un point d'honneur à transmettre un savoir-faire professionnel pratique, directement applicable dans le monde de l'entreprise. Enfin, elle joue un rôle crucial dans l'initiation à la recherche scientifique, encourageant l'innovation et le développement de nouvelles solutions technologiques adaptées au contexte local et international.

Au fil des décennies, l'ENI a su évoluer pour rester à la pointe du progrès. Après l'ouverture des premières filières en 1983, elle a rapidement intégré des formations d'ingénieurs et de maintenance. L'année 2002 a marqué un tournant avec le partenariat Cisco, suivi en 2007 par l'adoption du système LMD, alignant ainsi ses diplômes sur les standards internationaux. Plus récemment, en 2010, le lancement de la formation hybride a ouvert la voie à un enseignement plus flexible, combinant présence physique et apprentissage à distance.

### 1.3. Organigramme institutionnel de l’ENI
La structure organisationnelle de l’ENI est conçue pour garantir une gestion fluide et efficace tant sur le plan administratif que pédagogique. L’établissement est placé sous l'autorité d'un Conseil d’Ecole, organe délibérant, et est dirigé au quotidien par un Directeur qui assure la coordination générale.

L'organisation interne repose sur plusieurs piliers essentiels. Le Collège des Enseignants prend en charge la structuration et le suivi de l'organisation pédagogique, veillant à la qualité des enseignements dispensés. Parallèlement, le Conseil Scientifique définit les grandes orientations stratégiques en matière de recherche et de pédagogie. Enfin, le Secrétariat Principal assure la gestion administrative et financière, englobant les services de la scolarité, de la comptabilité et de l'intendance, garantissant ainsi le bon fonctionnement logistique de l'institution.

> [!NOTE]
> La Figure 2 présente l’organigramme actuel de l’Ecole.
> Figure 2 : Organigramme de l’ENI

### 1.4. Domaines de spécialisation
L’offre de formation de l’ENI est diversifiée et couvre les domaines les plus critiques de l'informatique moderne. Les activités de formation et de recherche se concentrent principalement sur le Génie Logiciel et la gestion des Bases de Données, ainsi que sur l'Administration des Systèmes et des Réseaux. L'école propose également un cursus en Informatique Générale et développe des compétences pointues en modélisation informatique et mathématique pour l'étude des systèmes complexes. Cette approche pluridisciplinaire permet de former des profils polyvalents, capables de s'adapter aux évolutions technologiques constantes.

Tableau 1 : Organisation du système de formation pédagogique de l'Ecole

| Formation théorique | Formation pratique |
| :--- | :--- |
| Enseignement théorique | Travaux de réalisation |
| Travaux dirigés | Projets / Projets tutorés |
| Travaux pratiques | Voyage d’études |
| Etude de cas | Stages |

### 1.5. Architecture des formations pédagogiques
Depuis l'adoption de la réforme LMD, l'architecture des études à l'ENI est structurée en trois cycles distincts, favorisant la mobilité internationale et la reconnaissance des diplômes. Le cycle de Licence, s'étalant sur six semestres (Bac + 3), constitue la première étape de la professionnalisation. Il est suivi par le cycle de Master, d'une durée de quatre semestres (Bac + 5), qui permet une spécialisation approfondie. Enfin, le cycle de Doctorat (Bac + 8) est dédié à la recherche avancée et à la production de nouvelles connaissances scientifiques.

Tableau 2 : Architecture des études correspondant au système LMD

| Niveau | Diplôme | Durée |
| :--- | :--- | :--- |
| L | Licence Professionnelle | 3 ans (L1, L2, L3) |
| M | Master (Pro ou Recherche) | 2 ans (M1, M2) |
| D | Doctorat | 3 ans après le Master |

Tableau 3 : Liste des formations existantes à l’ENI

| Caractéristique | Licence Professionnelle | Master |
| :--- | :--- | :--- |
| Condition d'admission | Concours National | Titulaire de Licence |
| Durée | 3 ans | 2 ans |
| Diplôme délivré | Licence Professionnelle | Master (Pro ou Recherche) |

### 1.6. Relations de l’ENI avec les entreprises
L’Ecole collabore avec plus de 300 partenaires (Orange, Telma, Airtel, Banques, Ministères, etc.) pour l'accueil en stage et le recrutement des diplômés.

### 1.7. Partenariat au niveau international
L’ENI entretient des relations étroites avec des institutions internationales (Université de Savoie, IRD, AUF, etc.) et participe à des programmes de recherche comme le CARI ou le projet TICEVAL.

### 1.8. Débouchés professionnels
Les formations dispensées à l’ENI ouvrent les portes à une vaste gamme de carrières professionnelles, tant dans le secteur public que privé. Les titulaires d’une Licence Professionnelle s'orientent généralement vers des postes techniques tels que ceux d'analyste programmeur, de développeur d'applications, d'administrateur web ou encore de technicien help desk.

Pour les diplômés du cycle Master, les opportunités s'étendent vers des fonctions d'encadrement et de conception stratégique. Ils occupent fréquemment des postes de chef de projet informatique, d'ingénieur réseau et système, de responsable de la sécurité des systèmes d'information, ou encore de consultant en transformation digitale. Certains accèdent également à des postes de direction, tels que Directeur des Systèmes d'Information (DSI), où ils pilotent la stratégie technologique de grandes organisations.

Tableau 4 : Débouchés professionnels éventuels des diplômés

| Niveau | Métiers / Postes |
| :--- | :--- |
| LICENCE | Analyste Programmeur, Administrateur web, Développeur, Intégrateur, Web designer, Technicien help desk, Administrateur de cybercafé. |
| MASTER | Chef de projet informatique, Ingénieur réseau, Architecte SI, Responsable sécurité, Directeur de projet, Consultant fonctionnel. |

### 1.9. Ressources humaines (Mise à jour 2025/2026)
La gestion de l’ENI s'appuie sur une équipe académique et administrative hautement qualifiée. La direction de l’établissement est assurée par Monsieur MAHATODY Thomas, Docteur HDR. La coordination pédagogique est structurée autour de plusieurs responsables de mentions et de parcours. Monsieur RABETAFIKA Louis Haja, Maître de Conférences, assure la responsabilité de la Mention Informatique, tandis que Monsieur DIMBISOA William Germain, également Maître de Conférences, dirige la Mention Intelligence Artificielle.

Au niveau des parcours spécialisés, Monsieur RALAIVAO Jean Christian coordonne le parcours Génie Logiciel et Base de Données, et Monsieur GILANTE Gesazafy est en charge du parcours Informatique Générale. La gouvernance et l'ingénierie de données sont placées sous la responsabilité de Madame RATIANANTITRA Volatiana Marielle, Maître de Conférences. Enfin, le parcours dédié aux Objets Connectés et à la Cybersécurité est dirigé par Monsieur RAZAFIMAHATRATRA Hajarisena, Maître de Conférences.

L'excellence de la formation est garantie par un corps enseignant composé de 15 permanents, incluant des professeurs titulaires, des docteurs HDR et des maîtres de conférences, complété par une dizaine d'enseignants vacataires issus du monde professionnel. Le bon fonctionnement quotidien de l'école est assuré par une équipe de 41 personnels administratifs dévoués.

## Chapitre 2. Présentation de l’établissement d’accueil

### 2.1. Présentation du CNTEMAD
Le Centre National de Télé-Enseignement de Madagascar, plus connu sous l'acronyme CNTEMAD, est l'institution de référence en matière d'enseignement à distance sur l'ensemble du territoire malgache. Placé sous la tutelle du Ministère de l'Enseignement Supérieur et de la Recherche Scientifique, il a pour mission principale de démocratiser l'accès à l'enseignement supérieur en offrant des formations académiques et professionnalisantes à des milliers d'étudiants, sans contrainte de déplacement géographique.

### 2.2. Missions et objectifs
Le CNTEMAD s'est fixé pour objectif de réduire la fracture éducative en permettant aux étudiants résidant dans les zones les plus reculées, ainsi qu'aux professionnels en activité, de poursuivre leurs études. Ses missions englobent la conception de supports pédagogiques adaptés, l'organisation de sessions d'examens décentralisées et la gestion d'un réseau de centres régionaux répartis dans les 22 régions de Madagascar. L'institution vise l'excellence académique à travers un suivi rigoureux des apprenants et une modernisation constante de ses outils de communication.

### 2.3. Structure organisationnelle
L'organisation du CNTEMAD est structurée de manière à assurer une coordination efficace entre le siège national et les antennes régionales. Elle est dirigée par un Directeur National, assisté par plusieurs directions spécialisées :
- La Direction des Affaires Administratives et Financières (DAAF), qui gère les ressources humaines et la comptabilité.
- La Direction Pédagogique, responsable de l'élaboration des programmes et du suivi des enseignants.
- La Direction des Systèmes d'Information, qui pilote la transformation numérique de l'institution.
- Les Centres Régionaux de Télé-Enseignement (CRTE), qui assurent le relais de proximité avec les étudiants.

## Chapitre 3. Description du projet

### 3.1. Formulation
Le projet que nous avons entrepris consiste en la conception et le développement d'une plateforme numérique intégrée, baptisée EduPass-MG. Cette solution, dont le slogan évocateur est “Payer l’école, sans la queue.”, a pour vocation première de digitaliser l'ensemble des processus de paiement et de communication au sein du Centre National de Télé-Enseignement de Madagascar. Il s'agit de transformer une gestion administrative jusqu'ici manuelle et physique en un système fluide, accessible à distance via des interfaces web et mobiles.

### 3.2. Objectif et besoins de l’utilisateur
L'objectif fondamental de cette plateforme est d'éliminer les files d'attente chronophages et de simplifier la gestion administrative en automatisant les flux d'information. En digitalisant les paiements et les communications officielles, telles que les convocations, les calendriers d'examens et la publication des résultats, nous visons une efficacité accrue. Le système intègre une vérification automatique des transactions financières et génère des convocations au format PDF hautement sécurisées, grâce à l'utilisation de QR codes et de signatures numériques uniques.

Le déploiement du projet est structuré en deux phases majeures. La première phase, considérée comme prioritaire (V1), se concentre sur l'implémentation des paiements en ligne via les services bancaires et le Mobile Money. Elle inclut également le développement d'un moteur de rapprochement bancaire automatique, la mise en place des portails dédiés aux étudiants et aux administrateurs, ainsi que l'envoi de communications numériques vérifiables. La seconde phase (V2) marquera une évolution vers un système de gestion de l'apprentissage (LMS), intégrant des ressources pédagogiques, des évaluations en ligne et un suivi personnalisé du parcours de l'apprenant.

Les bénéfices attendus de cette transformation sont multiples. Nous anticipons une réduction de plus de 80% des files d'attente physiques, une traçabilité financière sans faille et une lutte efficace contre la fraude documentaire. De plus, la plateforme garantit un accès permanent aux services administratifs, préparant ainsi l'institution aux exigences de l'université virtuelle de demain.

### 3.3. Moyens nécessaires à la réalisation du projet
La réussite de ce projet repose sur la mobilisation de ressources variées. Sur le plan humain, la réalisation a nécessité l'implication d'un développeur full-stack, encadré par un tuteur pédagogique pour les aspects académiques et un encadreur professionnel pour les exigences métier.

En ce qui concerne les ressources matérielles, le développement a été effectué sur des stations de travail performantes, complétées par des serveurs de test pour les phases de déploiement intermédiaire et divers terminaux mobiles (Android et iOS) pour garantir la compatibilité de l'application.

Le socle logiciel de la plateforme s'appuie sur des technologies modernes et robustes. Le backend est propulsé par le framework Laravel associé à une base de données PostgreSQL, tandis que Redis est utilisé pour la gestion efficace des files d'attente et des tâches asynchrones. Le frontend web est développé avec React et Vite pour une réactivité optimale, et la partie mobile repose sur React Native. L'infrastructure est conteneurisée via Docker et servie par Nginx, assurant ainsi une grande flexibilité et une facilité de maintenance.

### 3.4. Résultats attendus
À l'issue du développement, nous visons la livraison d'un écosystème complet. Le portail étudiant permettra une autonomie totale pour le paiement des frais, la consultation des reçus et le téléchargement sécurisé des convocations. Le portail administrateur offrira aux services de comptabilité et de scolarité des outils de pilotage avancés, notamment pour le suivi des transactions et le rapprochement bancaire en un clic. Enfin, un système de vérification publique accessible via une URL dédiée permettra de valider instantanément l'authenticité des documents par simple scan. Sur le plan de la performance, la plateforme est dimensionnée pour supporter au moins 500 connexions simultanées avec des temps de réponse inférieurs à trois secondes.

### 3.5. Chronogramme de travail
La réalisation du projet EduPass-MG s'est étalée sur une période de trois mois, structurée selon les phases suivantes :
- **Mois 1 : Analyse et Conception** - Cette phase a été consacrée à l'étude de l'existant, à la définition des besoins fonctionnels et à la modélisation UML (diagrammes de cas d'utilisation, de classes et de séquences).
- **Mois 2 : Développement Backend et API** - Durant cette période, nous avons mis en place l'architecture Laravel, configuré la base de données PostgreSQL et implémenté les modules de paiement et de génération de documents.
- **Mois 3 : Développement Frontend, Mobile et Tests** - La dernière phase a porté sur la création des interfaces web et mobiles avec React et React Native, suivie d'une phase de tests intensifs et de déploiement pilote.

---

# PARTIE II. ANALYSE ET CONCEPTION

## Chapitre 4. Analyse préalable

### 4.1. Analyse de l’existant
L'étude du système actuel révèle une dépendance quasi totale vis-à-vis des processus manuels et physiques. Actuellement, les étudiants doivent se déplacer physiquement vers les guichets de l'établissement ou vers des agences bancaires pour effectuer leurs paiements. Les preuves de paiement, sous forme de bordereaux papier, doivent ensuite être présentées aux services administratifs pour validation. Parallèlement, la diffusion des convocations aux examens repose sur une impression massive et une distribution manuelle, ou un affichage local dans les centres de formation.

### 4.2. Critique de l’existant
Bien que le système actuel soit maîtrisé par le personnel et ne dépende pas d'une infrastructure technologique complexe, il présente des lacunes critiques. La principale faiblesse réside dans la lenteur extrême des traitements, particulièrement lors des pics d'affluence qui génèrent des tensions et des erreurs de saisie. Le manque de traçabilité en temps réel complique le travail des comptables, tandis que la vulnérabilité des documents papier à la perte ou à la falsification représente un risque sécuritaire majeur pour l'institution.

### 4.3. Conception avant projet
Face à ces constats, nous avons envisagé deux orientations stratégiques. La première solution consistait en une digitalisation partielle, se limitant uniquement à la gestion des paiements. Cependant, cette approche aurait laissé subsister les problèmes liés à la distribution des documents officiels. Nous avons donc opté pour la seconde solution : une digitalisation complète et intégrée.

Le périmètre retenu pour la version initiale (V1) englobe le portail étudiant pour les transactions et les documents, le portail administrateur pour la gestion comptable et académique, ainsi que l'intégration des services de Mobile Money et l'importation des relevés bancaires. Le système de vérification par QR code constitue le pilier de la sécurisation des échanges. Les fonctionnalités liées à la gestion des cours et à la dématérialisation des diplômes sont quant à elles reportées à la phase ultérieure du projet.

## Chapitre 5. Analyse conceptuelle

### 5.1. Présentation de la méthode utilisée
Pour garantir la robustesse et la pérennité de la plateforme EduPass-MG, nous avons adopté une approche méthodologique rigoureuse basée sur le Processus Unifié (UP) et le langage de modélisation UML (Unified Modeling Language). Cette méthode nous a permis de structurer le développement de manière itérative et incrémentale, en mettant l'accent sur les cas d'utilisation pour capturer précisément les besoins des utilisateurs. L'utilisation d'UML offre une représentation visuelle standardisée des différents aspects du système, facilitant ainsi la communication entre les parties prenantes et assurant une transition fluide entre l'analyse et l'implémentation technique.

### 5.2. Dictionnaire des données
Le dictionnaire des données constitue le référentiel central de toutes les informations manipulées par le système. Il définit avec précision chaque entité, telle que l'étudiant, le paiement, la convocation ou la session d'examen, en précisant leurs attributs et leurs types. Ce document technique assure la cohérence sémantique du projet et sert de base à la construction du modèle logique de données.

### 5.3. Règles de gestion
Le fonctionnement de la plateforme est régi par un ensemble de règles de gestion strictes qui garantissent l'intégrité des processus métier. Par exemple, une règle fondamentale stipule qu'un étudiant ne peut accéder au téléchargement de sa convocation officielle que si, et seulement si, son paiement a été préalablement validé par le système ou par un administrateur. De même, chaque transaction financière doit impérativement être associée à une référence unique fournie par l'opérateur de paiement pour permettre un traçage sans ambiguïté. Enfin, pour des raisons de sécurité et de responsabilité, seul le profil comptable est habilité à valider manuellement les cas d'exception lors du rapprochement bancaire.

### 5.4. Représentation et spécification des besoins
La spécification des besoins s'appuie sur des diagrammes de cas d'utilisation qui illustrent les interactions entre les différents acteurs (étudiants, comptables, scolarité, administrateurs) et les fonctionnalités du système. Chaque cas d'utilisation, comme "Effectuer un paiement" ou "Générer une convocation", fait l'objet d'une description textuelle détaillée précisant les pré-conditions, le scénario nominal et les éventuels scénarios d'exception. Cette démarche garantit que chaque fonctionnalité développée répond à un besoin métier réel et documenté.

### 5.5. Spécification des besoins techniques
Sur le plan technique, la plateforme doit répondre à des exigences élevées en matière de sécurité et de performance. La protection des données est assurée par l'utilisation du protocole HTTPS/TLS pour tous les échanges, le hachage des mots de passe avec l'algorithme Argon2 et le chiffrement des informations sensibles au repos. La performance est optimisée par l'utilisation de Redis pour la gestion des tâches asynchrones, permettant par exemple de générer des reçus et des convocations en moins de dix secondes, même lors de fortes charges. Enfin, l'accessibilité est prise en compte à travers un mode bas débit, optimisant le poids des images et des fichiers PDF pour les utilisateurs disposant d'une connexion limitée.

## Chapitre 6. Conception détaillée

### 6.1. Architecture système
L'architecture technique d'EduPass-MG repose sur le patron de conception MVC (Modèle-Vue-Contrôleur), qui permet une séparation claire entre la logique métier, la gestion des données et l'interface utilisateur. Cette structure facilite la maintenance et l'évolution de l'application. Pour la partie mobile, nous avons adopté une architecture basée sur les composants avec React Native, assurant une expérience utilisateur fluide et native sur les plateformes Android et iOS. L'ensemble communique via une API REST sécurisée, garantissant une interopérabilité parfaite entre les différents modules du système.

### 6.2. Diagramme de classe de conception global
Le diagramme de classe de conception traduit la structure statique du système en définissant les objets, leurs attributs et les relations qui les unissent. Il met en évidence les interactions entre les classes principales telles que User, Student, Payment, Convocation et ExamSession. Ce modèle sert de guide direct pour l'implémentation de la base de données et des modèles de données au sein du framework Laravel, assurant ainsi une correspondance exacte entre la conception théorique et la réalisation technique.

---

# PARTIE III. REALISATION

## Chapitre 7. Mise en place de l’environnement de développement

### 7.1. Installation et configuration des outils
La phase de mise en œuvre a débuté par la configuration d'un environnement de développement moderne et cohérent. Nous avons procédé à l'installation de PHP 8.2, nécessaire pour exploiter les dernières fonctionnalités du framework Laravel, ainsi que de Node.js pour la gestion des dépendances frontend. Le système de gestion de base de données PostgreSQL a été configuré pour assurer la persistance des données avec une intégrité transactionnelle forte. L'utilisation de Composer a permis de structurer le projet backend, tandis que l'environnement mobile a été initialisé via Expo, facilitant ainsi le déploiement et les tests rapides sur terminaux réels.

### 7.2. Architecture de l’application
L'application est structurée de manière modulaire pour séparer les responsabilités. Le backend agit comme une API robuste, gérant la logique métier, la sécurité et les interactions avec la base de données. Le frontend web, conçu avec React, offre une interface d'administration riche et réactive, tandis que l'application mobile React Native assure la mobilité des étudiants. Cette séparation claire permet une maintenance facilitée et offre la possibilité de faire évoluer chaque composant indépendamment sans impacter l'ensemble du système.

## Chapitre 8. Développement de l’application

### 8.1. Création de la base de données
La conception de la base de données a abouti à une structure relationnelle optimisée comprenant neuf tables principales. Ces tables gèrent de manière interconnectée les profils utilisateurs, les informations académiques des étudiants, les sessions d'examens et l'historique complet des transactions financières. L'utilisation des migrations Laravel a permis de versionner le schéma de la base de données, garantissant ainsi une cohérence parfaite entre les différents environnements de développement et de production.

### 8.2. Codage de l’application
Le processus de codage s'est concentré sur l'implémentation des fonctionnalités critiques définies lors de l'analyse. L'un des aspects les plus complexes a été la génération sécurisée des convocations. Voici un extrait du contrôleur `ConvocationController` illustrant la logique de génération du PDF incluant un QR code unique et une signature numérique :

```php
private function generatePDF(Convocation $convocation)
{
    $student = $convocation->student;
    $session = $convocation->examSession;

    // Génération du QR code contenant l'URL de vérification
    $result = \Endroid\QrCode\Builder\Builder::create()
        ->data(route('verify.convocation', ['code' => $convocation->qr_code]))
        ->size(150)
        ->margin(10)
        ->build();

    $qrCodeImage = $result->getDataUri();

    // Chargement de la vue PDF avec les données nécessaires
    $pdf = PDF::loadView('pdf.convocation', compact('convocation', 'student', 'session', 'qrCodeImage'));

    // Stockage du fichier sur le serveur
    $path = "convocations/{$convocation->qr_code}.pdf";
    Storage::put($path, $pdf->output());

    // Génération d'une signature numérique basée sur le hash du contenu
    $signature = hash('sha256', $pdf->output() . config('app.key'));

    $convocation->update([
        'pdf_url' => $path,
        'signature' => $signature,
    ]);
}
```

### 8.3. Présentation de l’application
L'application finale se présente sous la forme d'un tableau de bord intuitif pour les administrateurs, offrant une vue d'ensemble sur l'état des paiements et la programmation des sessions. Pour les étudiants, l'interface mobile simplifie radicalement le parcours, de l'inscription au téléchargement de la convocation. Les tests utilisateurs ont montré une satisfaction élevée, notamment grâce à la suppression totale du besoin de déplacement physique pour les formalités administratives.

---

# Conclusion
La réalisation du projet EduPass-MG a permis de répondre de manière concrète aux défis de modernisation du Centre National de Télé-Enseignement de Madagascar. En intégrant des technologies de pointe telles que Laravel, React Native et les systèmes de paiement mobile, nous avons pu concevoir une solution qui non seulement élimine les contraintes physiques liées aux files d'attente, mais renforce également la sécurité et la transparence des processus administratifs.

Ce stage a été pour moi une expérience formatrice exceptionnelle. Il m'a permis de mettre en pratique mes compétences techniques en génie logiciel dans un contexte réel et complexe, tout en me confrontant aux enjeux stratégiques de la transformation digitale dans le secteur public. Les résultats obtenus, notamment en termes de fiabilité du rapprochement bancaire et de sécurisation des documents officiels, démontrent l'impact positif que peut avoir le numérique sur l'efficacité des institutions éducatives. Au-delà de la réussite technique, ce projet ouvre des perspectives intéressantes pour l'évolution vers un système de gestion de l'apprentissage complet, consolidant ainsi les bases d'une éducation plus accessible et moderne à Madagascar.

## Bibliographie
[1] Taylor Otwell, "Laravel Documentation", 2025, Laravel LLC.
[2] Grady Booch, "UML User Guide", 2005, Addison-Wesley.

## Webographie
[3] https://laravel.com, Site officiel de Laravel, consulté le 05 janvier 2026.
[4] https://reactnative.dev, Documentation React Native, consulté le 05 janvier 2026.

## Glossaire
- Backend : Partie serveur d'une application (Laravel).
- Frontend : Partie interface utilisateur (React/React Native).
- LMS : Learning Management System (Gestion de l'apprentissage).
- OTP : One-Time Password (Mot de passe à usage unique pour la vérification).
- RBAC : Role-Based Access Control (Contrôle d'accès basé sur les rôles).
- Webhook : Appel automatique envoyé par le fournisseur de paiement pour confirmer une transaction.

## Mots-clés
Digitalisation, Éducation, Laravel, Paiement Mobile, QR Code, Télé-enseignement, Rapprochement Bancaire, LMS.
