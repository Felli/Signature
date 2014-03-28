## esoTalk – Signature plugin

- Let users add their signature to posts.
- You **must** install the adjustd Likes plugin together with this plugin to let it work correctly!
- Works together or individually with the Likes plugin. You can enable them both, or either one.
 
 ### Installation


Browse to your esoTalk plugin directory:
```
cd WEB_ROOT_DIR/addons/plugins/
```

Clone the Signature plugin repo into the plugin directory:
```
git clone git@github.com:esoTalk-plugins/Signature.git Signature
```

Backup the current Likes plugin and clone the adjusted Likes plugin repo into the plugin directory as well:
```
mv Likes Likes.bak
git clone git@github.com:esoTalk-plugins/Likese.git Likes
```

Chown the Signature and Likes plugin folder to the right web user:
```
chown -R apache:apache Signature/ Likes/
```

Navigate to the esoTalk /admin/plugins page and activate the Signature plugin!
And lastly, edit your signature in your profile settings page :smile:
