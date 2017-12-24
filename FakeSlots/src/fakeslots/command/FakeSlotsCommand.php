<?php

namespace fakeslots\command;

use fakeslots\FakeSlots;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;

/**
 * Class FakeSlotsCommand
 * @package FakeSlots\Command
 */
class FakeSlotsCommand extends Command implements PluginIdentifiableCommand {

    /** @var  FakeSlots $plugin */
    private $plugin;

    /**
     * FakeSlotsCommand constructor.
     * @param string $name
     * @param string $description
     * @param null $usageMessage
     * @param array $aliases
     */
    public function __construct($name = "fakeslots", $description = "FakeSlots commands", $usageMessage = null, $aliases = ["fs", "fakes", "fslots"]) {
        $this->plugin = FakeSlots::getInstance();
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if(!$sender->hasPermission("fs.cmd")) {
            $sender->sendMessage("§cYou have not permissions to use this command.");
            return;
        }

        if(empty($args[0]) || $args[0] != null) {
            $sender->sendMessage("§cUsage: §7/fs <mop|mp> <int: count>");
            return;
        }

        switch ($args[0]) {
            case "mop":
            case "maxonlineplayers":
                $this->plugin->configManager->setMaxOnlinePlayers(intval($args[1]));
                $sender->sendMessage("§aSlots updated to {$args[1]}!");
                break;
            case "mp":
            case "maxplayers":
                $this->plugin->configManager->setMaxPlayers(intval($args[1]));
                $sender->sendMessage("§aSlots updated to {$args[1]}!");
                break;
        }
    }

    /**
     * @return Plugin $fakeSlots
     */
    public function getPlugin(): Plugin {
        return FakeSlots::getInstance();
    }
}
