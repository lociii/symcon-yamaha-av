# UNMAINTAINED
This project is not maintained anymore. Feel free to fork and use it.
No support nor development will be done!

# Yamaha AVR control
Ermittlung des Status und Steuerung eines Yamaha AV Receiver
Der Receiver muss die Fernsteuerung per API zulassen. Wenn ein Einschalten moeglich sein soll muss die Option "Network Standby" in den Einstellungen des Receivers aktiv sein.

Getestet mit: Yamaha RX-475

### Inhaltverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Einrichten der Instanzen in IP-Symcon](#2-einrichten-der-instanzen-in-ip-symcon)
3. [PHP-Befehlsreferenz](#3-php-befehlsreferenz)

### 1. Funktionsumfang

* Status des Receivers auslesen (Power, Stummschaltung, Lautstaerke, Input-Quelle)
* Status des Receivers veraendern (An-/Ausschalten, Stummschaltung ein/aus, Lautstaerke aendern, Input-Quelle auswaehlen)

### 2. Einrichten der Instanzen in IP-Symcon

* Unter 'Instanz hinzufuegen das 'YamahaAVR'-Modul auswaehlen und eine neue Instanz erzeugen
* IP-Adresse des Receivers angeben
* Aktualisierungsinterval waehlen

### 3. PHP-Befehlsreferenz

`LOCIYAVR_Update(id);`
Aktualisiert die Werte der Instanz

`LOCIYAVR_PowerOn($id);`
Schaltet den Receiver an

`LOCIYAVR_PowerOff($id);`
Schaltet den Receiver aus

`LOCIYAVR_MuteOn($id);`
Stummschaltung aktivieren

`LOCIYAVR_MuteOff($id);`
Stummschaltung deaktivieren

`LOCIYAVR_VolumeIncrease($id);`
Lautstaerke erhoehen

`LOCIYAVR_VolumeDecrease($id);`
Lautstaerke vermindern

`LOCIYAVR_SceneSet1($id);`
Input-Quelle 1 aktiv schalten

`LOCIYAVR_SceneSet2($id);`
Input-Quelle 2 aktiv schalten

`LOCIYAVR_SceneSet3($id);`
Input-Quelle 3 aktiv schalten

`LOCIYAVR_SceneSet4($id);`
Input-Quelle 4 aktiv schalten
