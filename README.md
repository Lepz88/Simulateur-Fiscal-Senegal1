# Simulateur-Fiscal-Senegal 
# 🇸🇳 Modélisation Algorithmique : Fiscalité Sénégalaise (IR)

> **Projet L2 IAGE** | **Stack :** PHP, HTML, CSS | **Focus :** Business Logic & Data Analysis

Ce projet est une application de simulation financière permettant de calculer l'Impôt sur le Revenu (IR) selon le **Code Général des Impôts du Sénégal**. 
Il ne s'agit pas d'un simple calculateur, mais d'une **traduction algorithmique de règles métier complexes** (gestion des tranches, plafonnements légaux et quotient familial).

---

## 🎯 Objectif Technique
L'objectif est de démontrer la capacité à **traduire des contraintes légales en code fonctionnel**, une compétence clé pour un Data Analyst opérant dans le secteur bancaire ou financier.

### Règles de gestion implémentées :
1.  **Abattement Forfaitaire :** Calcul automatique des 30% avec application stricte du **plafond légal de 900 000 FCFA**.
2.  **Barème Progressif :** Algorithme de segmentation du revenu net imposable selon les 6 tranches officielles (de 0% à 40%).
3.  **Quotient Familial :** Système de réduction d'impôt dynamique basé sur le nombre de parts, intégrant les **bornes Minimum et Maximum** définies par la loi.

---

## ✅ Scénarios de Test Validés (Use Cases)
Pour garantir la fiabilité des calculs, l'algorithme a été éprouvé à travers plusieurs profils types :

### 1. Le Cas "Junior" (Revenu Moyen, Célibataire)
* **Test :** Salaire annuel de 6 000 000 FCFA / 1 part.
* **Validation :** Le système applique correctement le **plafonnement de l'abattement** à 900 000 FCFA (au lieu de 1,8M théorique), respectant ainsi la rigueur fiscale.

### 2. Le Cas "Chef de Famille" (Revenu Moyen, Marié + 2 enfants)
* **Test :** Salaire annuel de 6 000 000 FCFA / 3 parts.
* **Validation :** Le système calcule la réduction d'impôt et applique le **minimum légal** (plancher) correspondant à 3 parts, optimisant ainsi le revenu net final.

### 3. Le Cas "Revenu Modeste"
* **Test :** Salaire annuel < 900 000 FCFA.
* **Validation :** Le système identifie que le revenu net imposable tombe dans la première tranche et renvoie correctement un impôt de **0 FCFA** (Exonération).

---

## 🛠 Installation et Utilisation
1.  **Cloner le dépôt :**
    ```bash
    git clone [https://github.com/VOTRE_USERNAME/Simulateur-Fiscal-Senegal.git](https://github.com/VOTRE_USERNAME/Simulateur-Fiscal-Senegal.git)
    ```
2.  **Lancer le serveur :**
    Placez le dossier dans votre répertoire `htdocs` (XAMPP/MAMP) ou `www` (WAMP).
3.  **Accéder à l'outil :**
    Ouvrez votre navigateur sur `http://localhost/Simulateur-Fiscal-Senegal`.

---

## 👤 Auteur
**Moulaye Idrisse Haidara**
*Étudiant en Informatique Appliquée à la Gestion des Entreprises (IAGE)*
*Passionné par l'analyse de données et le développement de solutions métier.*

