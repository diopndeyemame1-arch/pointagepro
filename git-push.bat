@echo off
echo ========================================
echo    POUSSER LES MODIFICATIONS VERS GITHUB
echo ========================================
echo.

:: Aller dans le dossier du projet
cd /d "c:\xampp\htdocs\COUR-TELLY-TECH\pointagepro"

:: Voir les fichiers modifiés
echo Fichiers modifies :
echo -----------------
git status --short
echo.
echo ========================================

:: Demander le message de commit
set /p commit_msg="Message du commit : "

:: Ajouter tous les fichiers
git add .

:: Créer le commit
git commit -m "%commit_msg%"

:: Pousser vers GitHub
git push origin main

echo.
echo ========================================
echo    TERMINE !
echo ========================================
pause