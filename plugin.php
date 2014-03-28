<?php
// Copyright 2014 Tristan van Bokkem

if (!defined("IN_ESOTALK")) exit;

ET::$pluginInfo["Signature"] = array(
	"name" => "Signature",
	"description" => "Let users add their signature to posts.",
	"version" => "1.0.0",
	"author" => "Tristan van Bokkem",
	"authorEmail" => "tristanvanbokkem@gmail.com",
	"authorURL" => "http://www.bitcoinclub.nl",
	"license" => "GPLv2",
	"priority" => "0"
);

class ETPlugin_Signature extends ETPlugin {

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
			return $form->input("signature", "text")." <small>(".T("Max charaters:")." 110)</small><br /><br /><small>".ET::$session->preference("signature")."</small>";
		}
		else
		{
			return $form->input("signature", "text")." <small>(".T("Max charaters:")." 110)</small><br /><br /><small>-</small>";
		}
       	}

	public function saveSignature($form, $key, &$preferences)
	{
       		$signature = $form->getValue($key);
        	$preferences["signature"] = $signature;
	}

	public function handler_conversationController_renderBefore($sender)
	{
		$sender->addCSSFile($this->getResource("signature.css"));
	}

	public function handler_conversationController_formatPostForTemplate($sender, &$formatted, $post, $conversation)
	{
		if ($post["deleteMemberId"]) return;

		// Lets check if the Likes plugin is active,
		// if so we need to output the signature HTML a bit different.
		if (in_array("Likes", C("esoTalk.enabledPlugins")))
		{
			$liked = array_key_exists(ET::$session->userId, $post["likes"]);

			if ($liked)
			{
               			$signature = "<p class='signature likes liked'>".$post["preferences"]["signature"];
               			$formatted["body"] .= $signature;
			}
			else
			{
                               	$signature = "<p class='signature likes'>".$post["preferences"]["signature"];
                               	$formatted["body"] .= $signature;
			}
		}
		else
		{
			$signature = "<p class='signature'>".$post["preferences"]["signature"]."</p>";
			$formatted["body"] .= $signature;
		}
	}
}
