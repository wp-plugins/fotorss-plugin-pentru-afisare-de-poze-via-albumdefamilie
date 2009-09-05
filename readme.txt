=== fotoRSS ===
Contributors: evolutica, eightface
Tags: poze, imagini, sidebar, widget, rss, romania, fotografii
Requires at least: 2.6
Tested up to: 2.7
Stable tag: 1.0

Poti integra poze de pe Albumdefamilie.ro in blog-ul tau folosind API-ul de feed rss. Suporta utilizatori si/sau tag-uri
 
== Description ==

Poti integra poze de pe Albumdefamilie.ro in blog-ul tau folosind API-ul de feed rss. Suporta utilizatori si/sau tag-uri. Acest plugin este dezvoltat pe baza flickrRss dezvoltat de eightface. Este foarte usor de instalat si configurat din panoul de administrare Wordpress.


== Installation ==

1. Pune fisierele fotoRSS in directorul plugins
2. Activeaza plugin-ul
4. Configureaza datele de configurare via Control Panel - Options
5. Adauga codul `<?php get_fotorss(); ?>` unde doresti in template-urile tale (ex: sidebar.php)


== Frequently Asked Questions ==

= Pot afisa imagini random? =
Nu, plugin e limitat de API-ul pt feed RSS public (doar cele mai recente poze - max:20)	

= Cand folosesc tag-uri multiple, de ce nu sunt afisate poze? =
E posibil sa nu fie nici o poza publica cu tag-urile respective (default: ia poze cu oricare poze tagmode='all', iar setarea pentru luarea tagmode='all' nu e deocamdata disponibila in panoul de configurare)

= Cum pun margini/spatiu la/intre poze? =
Trebuie sa editezi CSS-ul pentru acest lucru.

= De ce nu sunt afisate pozele mele? =
Verifica daca variabile din configurare sunt corecte. E posibil totusi ca AlbumdeFamilie sa fie cazut temporar.


== Feedback and Support ==

Viziteaza forumul de ajutor AlbumdeFamilie.ro (http://albumdefamilie.ro/discutii/) pentru ajutor in instalare/configurare, personalizare plugin etc.

== Plugin History ==

**Latest Release:** September 3, 2009

* 1.0 - Lansare plugin

