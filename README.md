Eishockey Liveticker
====================

Der Eishockey Liveticker ist ein seit 2006 im Einstaz befindliches Projekt. Es wurde ursprünglich für den Verein 100% Pro Dornbirn, dem Support Verein für den EC Dornbirn, geschrieben. Ziel war es einen möglichst einfach zu administrierenden Liveticker zu produzieren, der ohne große Aufwände zu installieren, anzupassen und zu bedienen ist.

Weiters wurde sehr großer Wert auf gute Performance gelegt. Hierfür wurde ein spezieller Caching Layer gebaut, der nur dann auf die Datenbank geht, wenn tatsächlich was geändert wurde. Gehandhabt wird das über Dateien auf dem Filesystem. 

Abhängigkeiten
--------------

Der Eishockey Liveticker verwendet folgende Libraries:

 * [smarty](http://www.smarty.net/) eine templating engine, die für die Darstellung des Tickers verwendet wird.
 * [wordpress](http://www.wordpress.org) große Teile des Datenbanklayers sind von einer frühen Version von WordPress übernommen.


Fehlende Features
-----------------

Folgende Features fehlen noch im Ticker:

 * *i18n* - Aktuell gibt es den Ticker nur in Deutsch
 * Umstellung des Admin Interfaces:
   * OOP
   * Smarty Templates
   * Bessere Spieleverwaltung inkl. Löschen von Spielen
   * Automatische Erstellung einer Archivseite
   * Bessere Verwaltung von Strafen/Spielerlisten
   
Installation
------------

Bitte die [INSTALL.txt](INSTALL.txt) konsultieren. 
