<?xml version="1.0" encoding="utf-8"?>
<install type="module" version="1.5.0">
	<name>Ozio2 Module</name>
	<author>Ozio Team</author>
	<creationDate>Agosto 2009</creationDate>
	<copyright>Copyright (C) 2008-2009. All rights reserved.</copyright>
	<license>http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL</license>
	<authorEmail>oziogallery@joomla.it</authorEmail>
	<authorUrl>oziogallery@.joomla.it</authorUrl>
	<version>2.0</version>
	<description>Ozio Gallery 2 Module</description>
	<files>
		<filename module="mod_ozio2">mod_ozio2.php</filename>
		<filename>index.html</filename>
                <filename>helper.php</filename>
                <filename>tmpl/default.php</filename>
                <filename>tmpl/index.html</filename>
	</files>
  <params addpath="/administrator/components/com_oziogallery2/elements">
		<param name="code_id" type="item" default="0" label="Select Gallery" description="Select a Gallery for ID" />
		<param name="moduleclass_sfx" type="text" default="" label="Module Class Suffix" description="PARAMMODULECLASSSUFFIX" />
	</params>
</install>