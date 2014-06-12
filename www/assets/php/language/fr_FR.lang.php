<?php
$_LANGUAGE['REGISTER'] = 'S\'inscrire';

$_LANGUAGE['MENU_GALLERY'] = 'Galerie';
$_LANGUAGE['MENU_INDEX'] = 'Accueil';
$_LANGUAGE['MENU_PROFILE'] = 'Créations';
$_LANGUAGE['MENU_STATS'] = 'Statistiques';
$_LANGUAGE['MENU_DISCONNECT'] = 'Déconnexion';
$_LANGUAGE['MENU_DONATION'] = 'Faire un don';
$_LANGUAGE['MENU_MENTIONS'] = 'Mentions légales';
$_LANGUAGE['VIEWS'] = 'Vues';
$_LANGUAGE['LIKES'] = 'Appréciation';
$_LANGUAGE['READERS'] = 'Lecteurs';
$_LANGUAGE['DOWNLOADS'] = 'Téléchargements';
$_LANGUAGE['ERROR_UPLOAD_UNSUPORTED_NBT'] = 'Le parsage de votre fichier a échoué. Merci de nous envoyer ce qui suit:<hr/><br/>';
$_LANGUAGE['ERROR_UPLOAD_UNSUPORTED_FORMAT'] = 'Type de fichier non supporté, seul les ".dat" sont acceptés.';
$_LANGUAGE['ERROR_NO_BOOKS'] = 'Aucun livre trouvé.';
$_LANGUAGE['BOOK_TITLE'] = 'Titre';
$_LANGUAGE['BOOK_AUTHOR'] = 'Auteur';
$_LANGUAGE['BOOK_NEW_PAGE'] = 'Ajouter une page';
$_LANGUAGE['BOOK_SAVE'] = 'Sauvegarder';
$_LANGUAGE['BOOK_SAVED'] = 'Sauvegardé';
$_LANGUAGE['BOOK_DELETE'] = 'Supprimer';
$_LANGUAGE['BOOK_DELETE_CONFIRM'] = 'Êtes vous sur de vouloir supprimer ce livre ?';
$_LANGUAGE['BOOK_EDIT'] = 'Éditer';
$_LANGUAGE['BOOK_UNTITLED'] = 'Sans titre';
$_LANGUAGE['BOOK_LOAD'] = 'Afficher';
$_LANGUAGE['BOOK_LOAD_PREVIEW'] = 'Preview';
$_LANGUAGE['GALLERY_LASTEST'] = 'Plus récents';
$_LANGUAGE['GALLERY_BESTVOTES'] = 'Meilleures notes';
$_LANGUAGE['GALLERY_RANDOM'] = 'Aléatoire';
$_LANGUAGE['SECURITY_PUBLIC'] = 'Publique';
$_LANGUAGE['SECURITY_PRIVATE'] = 'Privé';
$_LANGUAGE['SECURITY_UNINDEXED'] = 'Non répertorié';
$_LANGUAGE['PROFILE_LOAD'] = 'Chercher';
$_LANGUAGE['PROFILE_EMPTY'] = 'Ce membre n\'est pas enregistré';
$_LANGUAGE['HELP_DL_BUTTON'] = 'En utilisant cette commande dans minecraft, vous importerez direcrement le livre.<br/>Pour cela, il faut télécharger le mod Bookshop en cliquant sur ce bouton.';
$_LANGUAGE['HELP_UPLOAD_BUTTON'] = 'Sélectionnez un .dat de votre Map,<br/> assurez vous que le livre est dans votre inventaire.<br/>Laissez vide pour creér un nouveau livre.';
$_LANGUAGE['UPLOADER_EMPTY'] = 'Aucun fichier sélectionné';
$_LANGUAGE['GENERIC_EMPTY'] = 'Aucune donnée disponible';
$_LANGUAGE['UPLOADER_SUBMIT'] = 'Uploader';
$_LANGUAGE['UPLOADER_NEW'] = 'Créer';
$_LANGUAGE['COPY'] = 'Copier';
$_LANGUAGE['COPIED'] = 'Copié';
$_LANGUAGE['LOG_IN'] = 'Se connecter';
$_LANGUAGE['LOGIN_INFO'] = 'Pas encore de compte ? <a href="http://mcnetwork.fr.nf/" target="_blank">Inscrivez vous !</a>';
$_LANGUAGE['USERNAME'] = 'Pseudo';
$_LANGUAGE['PASSWORD'] = 'Mot de passe';

$_LANGUAGE['GEN_API'] = 'Générer une clé API';
$_LANGUAGE['GEN_API_UNREGISTERED'] = '<p><b>Vous devez vous connecter à Bookshop pour générer une clé API</b></p>';
$_LANGUAGE['DOWNLOAD'] = 'Télécharger';
$_LANGUAGE['INFOS'] = 'Plus d\'informations';
$_LANGUAGE['MOD_INFO_HEAD'] = <<<mod
<h2>BOOKSHOP MOD</h2>
<p>Bookshop vous permet d'uploader et de télécharger des livres directement depuis Minecraft. Pour celà il vous suffit d'installer le Mod fourni par Bookshop !</p>
mod;
$_LANGUAGE['COMMANDS_USE_INFO'] = <<<commands
<h3>Liste des commandes</h3>
<p>/bookshop download [id] <small>raccourci: /bookshop dl [id]</small><br/>
	/bookshop login [api key] [username <small>(optionnel)</small>]<br/>
	/bookshop upload [api key <small>(optionnel)</small>] [username <small>(optionnel)</small>] <small>raccourci: /bookshop up [api key <small>(optionnel)</small>] [username]</small><br/>
	/bookshop list [username <small>(optionnel)</small>]<br/>
	/bookshop search [book title]<br/>
	/bookshop lastest<br/>
	/bookshop random<br/>
	/bookshop wiki [name] <small>(experimental)</small><br/>
	/bookshop best <small>raccourci: /bookshop top</small></p>
commands;

$_LANGUAGE['MOD_USE_INFO'] = <<<mod
<h3 id="mod">Version Mod</h3>
<p><a href="http://www.wtcraft.com/forum/index.php?threads/1-3-2-bookshop-vos-livres-o%C3%B9-que-vous-soyez.12620/">Topic du mod</a></p>
<p>Le mod nécéssite <a href="http://www.minecraftforum.net/topic/514000-api-minecraft-forge/">Forge</a></p>
<p>Pour installer un mod Forge, il suffit de déposer le .zip ou le .jar dans le dossier "mods" de Minecraft</p>
mod;

$_LANGUAGE['MOD_GUI_USE_INFO'] = <<<mod_gui
<h3 id="mod_gui">Extention du mod: Interface</h3>
<p>Cette extention ajoute un bloc permettant de charger des livres via une interface: Le bookstore</p>
<p>Requière le mod principal !</p>
mod_gui;

$_LANGUAGE['PLUGIN_USE_INFO'] = <<<plugin
<h3 id="plugin">Version Plugin</h3>
<p><a href="https://github.com/ralmn/BookShopPlugin">Code source</a> - <a href="http://bukkit.fr/index.php?threads/bookshop-vos-livres-o%C3%B9-que-vous-soyez-1-3.3449/">Topic du plugin</a></p>
<p>Pour les détails sur le plugin, nous vous conseillons de vous référer aux liens ci-dessus</p>
plugin;

$_LANGUAGE['ABOUT'] = <<<about
<h2>à propos de Bookshop</h2>
<p><b>Bookshop</b> est une application internet pour <a href="http://www.minecraft.net/">Minecraft</a> développée par <a href="http://ephys.fr.nf/">@EphysPotato</a>. Elle permet aux joueurs d'enregistrer, de modifier et de partager leurs écrits provenant du jeu.</p>
<p>Si vous rencontrez des bugs ou des problèmes, merci de les signaler à <a href="https://twitter.com/#!/EphysPotato">@EphysPotato</a> sur Twitter.</p>
<p>Si vous appreciez ce projet, n'hésitez pas à faire un don !</p>
<p>Merci à <a href="https://twitter.com/#!/The_Shikaku">@The_Shikaku</a> pour le design de Bookshop</p>
<p>Merci à <a href="http://ralmn.me/">Ralmn</a> pour son aide pour le plugin bukkit de Bookshop</p>
<p>Jetez un oeil à notre <a href="api/">API</a> !</p>
about;
?>