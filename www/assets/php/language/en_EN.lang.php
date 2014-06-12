<?php
$_LANGUAGE['REGISTER'] = 'Register';

$_LANGUAGE['MENU_GALLERY'] = 'Gallery';
$_LANGUAGE['MENU_INDEX'] = 'Index';
$_LANGUAGE['MENU_PROFILE'] = 'Your books';
$_LANGUAGE['MENU_STATS'] = 'Statistics';
$_LANGUAGE['MENU_DISCONNECT'] = 'Log out';
$_LANGUAGE['MENU_DONATION'] = 'Donate';
$_LANGUAGE['MENU_MENTIONS'] = 'Legal mentions';
$_LANGUAGE['VIEWS'] = 'Views';
$_LANGUAGE['LIKES'] = 'Appreciation';
$_LANGUAGE['READERS'] = 'Readers';
$_LANGUAGE['DOWNLOADS'] = 'Downloads';
$_LANGUAGE['ERROR_UPLOAD_UNSUPORTED_NBT'] = 'We failed to parse your nbt file. Please send us the following:<hr/><br/>';
$_LANGUAGE['ERROR_UPLOAD_UNSUPORTED_FORMAT'] = 'Unsuported file format, only ".dat" files are accepted.';
$_LANGUAGE['ERROR_NO_BOOKS'] = 'No book found.';
$_LANGUAGE['BOOK_TITLE'] = 'Title';
$_LANGUAGE['BOOK_AUTHOR'] = 'Author';
$_LANGUAGE['BOOK_NEW_PAGE'] = 'New page';
$_LANGUAGE['BOOK_SAVE'] = 'Save';
$_LANGUAGE['BOOK_SAVED'] = 'Saved';
$_LANGUAGE['BOOK_DELETE'] = 'Delete';
$_LANGUAGE['BOOK_DELETE_CONFIRM'] = 'Are you sure you wish to delete this book ?';
$_LANGUAGE['BOOK_EDIT'] = 'Edit';
$_LANGUAGE['BOOK_UNTITLED'] = 'Untitled';
$_LANGUAGE['BOOK_LOAD'] = 'Full';
$_LANGUAGE['BOOK_LOAD_PREVIEW'] = 'Preview';
$_LANGUAGE['GALLERY_LASTEST'] = 'Lastest';
$_LANGUAGE['GALLERY_BESTVOTES'] = 'Best votes';
$_LANGUAGE['GALLERY_RANDOM'] = 'Random';
$_LANGUAGE['SECURITY_PUBLIC'] = 'Public';
$_LANGUAGE['SECURITY_PRIVATE'] = 'Private';
$_LANGUAGE['SECURITY_UNINDEXED'] = 'Unindexed';
$_LANGUAGE['PROFILE_LOAD'] = 'Search';
$_LANGUAGE['PROFILE_EMPTY'] = 'Unregistered member';
$_LANGUAGE['HELP_DL_BUTTON'] = 'By using this command in minecraft, you will download the book.<br/>You need to download the bookshop mod to use this command.';
$_LANGUAGE['HELP_UPLOAD_BUTTON'] = 'Select a .dat file from your map<br/> be sure the book is in your inventory.<br/>Leave empty to create a new (empty) book.';
$_LANGUAGE['UPLOADER_EMPTY'] = 'No file';
$_LANGUAGE['GENERIC_EMPTY'] = 'No data available';
$_LANGUAGE['UPLOADER_SUBMIT'] = 'Upload';
$_LANGUAGE['UPLOADER_NEW'] = 'New';
$_LANGUAGE['COPY'] = 'Copy';
$_LANGUAGE['COPIED'] = 'Copied';
$_LANGUAGE['LOG_IN'] = 'Se connecter';
$_LANGUAGE['LOGIN_INFO'] = 'No account yet ? <a href="http://mcnetwork.fr.nf/" target="_blank">Register !</a>';
$_LANGUAGE['USERNAME'] = 'Username';
$_LANGUAGE['PASSWORD'] = 'Password';
$_LANGUAGE['GEN_API'] = 'Generate an API key';
$_LANGUAGE['GEN_API_UNREGISTERED'] = '<p><b>You must be connected to generate a new API key</b></p>';
$_LANGUAGE['DOWNLOAD'] = 'Download';
$_LANGUAGE['INFOS'] = 'More informations';
$_LANGUAGE['MOD_INFO_HEAD'] = <<<mod
<h2>BOOKSHOP MOD</h2>
<p>Bookshop allows you to upload and download books directly from Minecraft. Though you need to install the Bookshop mod.</p>
mod;

$_LANGUAGE['COMMANDS_USE_INFO'] = <<<commands
<h4>Command list</h4>
<p>/bookshop download [id] <small>nick: /bookshop dl [id]</small><br/>
	/bookshop login [api key] [username <small>(optionnal)</small>]<br/>
	/bookshop upload [api key <small>(optionnal)</small>] [username <small>(optionnal)</small>] <small>nick: /bookshop up [api key <small>(optionnal)</small>] [username]</small><br/>
	/bookshop list [username <small>(optionnal)</small>]<br/>
	/bookshop search [book title]<br/>
	/bookshop lastest<br/>
	/bookshop random<br/>
	/bookshop wiki [page] <small>(experimental)</small><br/>
	/bookshop best <small>nick: /bookshop top</small></p>
commands;

$_LANGUAGE['MOD_USE_INFO'] = <<<mod
<h3 id="mod">Minecraft Mod</h3>
</p><p><a href="http://www.minecraftforum.net/topic/1498339-132ssp-bookshop-your-books-anywhere/">Mod topic</a></p>
<p>The mod requires <a href="http://www.minecraftforum.net/topic/514000-api-minecraft-forge/">Forge</a></p>
mod;

$_LANGUAGE['MOD_GUI_USE_INFO'] = <<<mod_gui
<h3 id="mod_gui">Mod Extension: Interface</h3>
<p>This extension adds a block allowing to load books using an ingame interface: The bookstore</p>
<p>Require the main mod !</p>
mod_gui;

$_LANGUAGE['PLUGIN_USE_INFO'] = <<<plugin
<h3 id="plugin">Bukkit Plugin</h3>
<p><a href="https://github.com/ralmn/BookShopPlugin">Plugin Source</a> - <a href="http://dev.bukkit.org/server-mods/bookshop-your-books-any-where/">Plugin topic</a></p>
<p>For any details about the plugin, please follow the links over here.</p>
plugin;

$_LANGUAGE['ABOUT'] = <<<about
<h2>About Bookshop</h2>
<p><b>Bookshop</b> is an online <a href="http://www.minecraft.net/">Minecraft</a> tool developed by <a href="http://ephys.fr.nf/">@EphysPotato</a>. It allow the players to save, edit and share their Minecraft books.</p>
<p>If you meet any bug or problem, please contact <a href="https://twitter.com/#!/EphysPotato">@EphysPotato</a> on Twitter.</p>
<p>If you appreciate this project, feel free to contribute by donating !</p>
<p>Thanks to <a href="https://twitter.com/#!/The_Shikaku">@The_Shikaku</a> for the design&nbsp;!</p>
<p>Thanks to <a href="http://ralmn.fr/">Ralmn</a> for his help with the bukkit plugin !</p>
<p>Check out our <a href="api/">API</a> !</p>
about;
?>