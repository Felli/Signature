<?php
// Copyright 2014 Tristan van Bokkem

if (!defined("IN_ESOTALK")) exit;

ET::$pluginInfo["Signature"] = array(
	"name" => "Signature",
	"description" => "Let users add their signature to posts.",
	"version" => "1.1.1",
	"author" => "Tristan van Bokkem",
	"authorEmail" => "tristanvanbokkem@gmail.com",
	"authorURL" => "http://www.bitcoinclub.nl",
	"license" => "GPLv2",
	"priority" => "0"
);

class ETPlugin_Signature extends ETPlugin {

	function setup()
	{
		ET::writeConfig(array("plugin.Signature.characters" => "110"));
		return true;
	}

	function handler_settingsController_initGeneral($sender, $form)
	{
		$form->addSection("signature", T("Signature"), array("after" => "privacy"));
		$form->setValue("signature", ET::$session->preference("signature"));
		$form->addField("signature", "signature", array($this, "fieldSignature"), array($this, "saveSignature"));
	}

	function fieldSignature($form)
	{
		if (!(ET::$session->preference("signature") == NULL))
		{
			return $form->input("signature", "text")." <small>(".T("Max charaters:")." ".C("plugin.Signature.characters").")</small><br /><br /><small>".ET::$session->preference("signature")."</small>";
		}
		else
		{
			return $form->input("signature", "text")." <small>(".T("Max charaters:")." ".C("plugin.Signature.characters").")</small><br /><br /><small>-</small>";
		}
	}

	public function saveSignature($form, $key, &$preferences)
	{
		$signature = $form->getValue($key);
		$preferences["signature"] = $signature;
	}

	public function handler_conversationController_renderBefore($sender)
	{
		$sender->addCSSFile($this->resource("signature.css"));
		$sender->addJSFile($this->resource("signature.js"));
	}

	public function handler_conversationController_formatPostForTemplate($sender, &$formatted, $post, $conversation)
	{
		if ($post["deleteMemberId"]) return;

		// Lets check if the Likes plugin is active,
		// if so we need to output the signature HTML a bit different.
		if (in_array("Likes", C("esoTalk.enabledPlugins")))
		{
			$signature = $post["preferences"]["signature"];
			addToArray($formatted["footer"], "<p class='signature'>".substr($signature,0,C("plugin.Signature.characters"))."</p>", 0);
		}
		else
		{
			$signature = $post["preferences"]["signature"];
			addToArray($formatted["footer"], "<p class='signature-no-likes'>".substr($signature,0,C("plugin.Signature.characters"))."</p>", 0);
		}
	}

	public function settings($sender)
	{
		// Set up the settings form.
		$form = ETFactory::make("form");
		$form->action = URL("admin/plugins/settings/Signature");

		// Set the values for the sitemap options.
		$form->setValue("characters", C("plugin.Signature.characters", "110"));

		// If the form was submitted...
		if ($form->validPostBack()) {

			// Construct an array of config options to write.
			$config = array();
			$config["plugin.Signature.characters"] = $form->getValue("characters");

			// Write the config file.
			ET::writeConfig($config);

			$sender->redirect(URL("admin/plugins"));
		}

		$sender->data("SignatureSettingsForm", $form);
		return $this->view("settings");
	}
}
