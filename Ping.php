<?php

namespace OpenSource; // replace your command directory here.

/*
This was coded by Lewis Brindley under the VGDevelopment Organisation.
By the MIT License, you can do whatever you want with this file with no restrictions unless implied in the License.
You cannot however remove this commented in citation of the authorship of the file. You must add this to any file using code from this file.
*/

use pocketmine\command\{
    CommandSender,
    ConsoleCommandSender, // used in-case you want to allow console running the command.
    PluginCommand
};

use pocketmine\Player;

use pocketmine\utils\TextFormat as Chat;
// >>>
use PluginObject as OS; // replace "PluginObject" with your "Main.php" without the extension ".php"

class Ping extends PluginCommand {
    
    private static $os = null;
    
    /**
     * Construct the command and parent.
     *
     * @param string $name
     * @param OS $plugin
     */
    public function __construct($name, OS $plugin) {
        parent::__construct($name, $plugin);
        self::$os = $plugin;
        $this->setDescription("Check your Ping");
        $this->setUsage("/p or /ping");
        $this->setPermission("plugin.pingcheck"); // replace plugin with name of your plugin
        $this->setAliases([
            "p"    
        ]);
    }
    
    /**
     * Called when player executes the command.
     * 
     * Custom coloring of the ping as well!
     *
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if ($sender instanceof Player) {
            $ping = $sender->getPing();
            if ($ping > 200) {
                $ping = Chat::RED . (string)$ping;
                $radar = Chat::RED . "." . Chat::BLACK . ":i";
            } else if ($ping > 100 && $ping < 200) {
                $ping = Chat::YELLOW . (string)$ping;
                $radar = Chat::YELLOW . ".:" . Chat::BLACK . "i";
            } else {
                $ping = Chat::GREEN . (string)$ping;
                $radar = Chat::GREEN . ".:i";
            }
            $sender->sendMessage(Chat::AQUA . "The server reported your ping to be:" . Chat::EOL . $ping . Chat::AQUA . "ms " . $radar);
        }
    }
    
}