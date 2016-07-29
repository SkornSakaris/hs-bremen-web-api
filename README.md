# Web-API | HS-Bremen @ SoSe 2016 

## Projekt
- Name: ModulManager
- Beschreibung: Stellt eine API zur Verwaltung von unterschiedlichen Studienmodulen zur Verfügung.
  Hierzu kann mittels JSON-Objekten oder über eine grafische, auf html basierende Oberfläche mit der API interagiert werden.

## Projektgruppe
- Jannik Busse (372525)
- Andreas Marquardt (373130)
- Nils Peters (373208)

## Dokumentation
- Framework: Swagger
- Swagger-JSON: http://web-api.vm/docs/swagger.json
- Swagger-UI: http://web-api.vm/docs/swagger

## Authentifizierung
- Komponente: Silex Security
- Verfahren: Token-Authentification
- Zugang: Benutzername -> Ole, Passwort -> foo

## Benötigte Software
- [Git](https://git-scm.com/) (Quellcodeverwaltung)
- [VirtualBox](https://www.virtualbox.org/) (Virtualisierungs Software)
- [Vagrant](https://www.vagrantup.com/) (Automatisierte VM Konfiguration)
- [Composer](https://getcomposer.org/) PHP Paketmanager (geht auch ohne, über die VM)

## Initiales Setup
1. Klone dieses Repository `git clone git@github.com:SkornSakaris/hs-bremen-web-api.git`
2. Wechsel in das Verzeichnis `cd hs-bremen-web-api`
3. Starte die VM mit `vagrant up`
4. Warte bis die VM erstellt wurde, währenddessen folgendes, als neue Zeile, in die Datei `C:\Windows\System32\drivers\etc\hosts` bzw. `/etc/hosts/` eintragen (Als Administrator/root bearbeiten):  
```
192.168.56.111 web-api.vm
```
6. Installiere die erforderlichen PHP Pakete: `composer install` (wenn lokale PHP Installation, sonst unter `/var/www/sources` per SSH auf der VM)
7. Browser öffnen und `http://web-api.vm/` eingeben.
8. Das `hs-bremen-web-api` Verzeichnis im Editor deiner Wahl öffnen.

## Vor jeder Session
1. `vagrant up` (dauert jetzt nicht mehr so lange)

## Nach jeder Session
1. `vagrant halt` (fährt die VM runter)

## Per SSH auf die VM
Host: localhost  
Port: 2222  
User: vagrant  
Private-Key: `./puphpet/files/dot/ssh/id_rsa`  
Kein Password

## Was ist auf der VM installiert?
- Ubuntu 14.04 LTS x64 (1 CPU, 512 MB RAM)
- IP: 192.168.56.111
- Offene Ports: TCP 9000 (xDebug) und TCP 3306 (MySQL)
- vim, htop
- nginx
- PHP 5.6
- Nodejs 5
- MariaDB 10.1 (user: root, pw: 123)



