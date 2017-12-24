<?php

namespace fakeslots;

use fakeslots\command\FakeSlotsCommand;
use fakeslots\utils\ConfigManager;
use fakeslots\event\EventListener;
use pocketmine\plugin\PluginBase;

/**
 * Class FakeSlots
 * @package fakeslots
 */
class FakeSlots extends PluginBase {

    /** @var  FakeSlots $instance */
    static $instance;

    /** @var  EventListener $eventListener */
    public $eventListener;

    /** @var  ConfigManager $configManager */
    public $configManager;

    public function onEnable() {
        self::$instance = $this;
        $this->configManager = new ConfigManager($this);
        $this->getServer()->getPluginManager()->registerEvents($this->eventListener = new EventListener($this), $this);
        $this->getServer()->getCommandMap()->register("fakeslots", new FakeSlotsCommand);
        $this->getLogger()->info("\n".
            "§c--------------------------------\n".
            "§6§lCzechPMDevs §r§e>>> §bFakeSlots\n".
            "§o§9FakeSlots ported to pocketmine\n".
            "§aAuthors: §7VixikCZ\n".
            "§aVersion: §7".$this->getDescription()->getVersion()."\n".
            "§aStatus: §7Loading...\n".
            "§c--------------------------------");
    }

    /**
     * @return int
     */
    public function getMaxPlayers():int {
        return $this->configManager->getMaxPlayers();
    }

    /**
     * @return int
     */
    public function getMaxOnlinePlayers():int {
        return $this->configManager->getMaxOnlinePlayers();
    }

    public function update() {
        $query = $this->getServer()->getQueryInformation();
        $query->setMaxPlayerCount($this->getMaxPlayers());
    }

    public static function getInstance():FakeSlots {
        return self::$instance;
    }
}