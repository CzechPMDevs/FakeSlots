<?php

namespace FakeSlots;

use FakeSlots\Command\FakeSlotsCommand;
use FakeSlots\Data\ConfigManager;
use FakeSlots\Event\EventListener;
use pocketmine\plugin\PluginBase;

/**
 * Class FakeSlots
 * @package FakeSlots
 * @author GamakCZ
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