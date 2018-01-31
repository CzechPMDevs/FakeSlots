<?php

namespace fakeslots\utils;

use fakeslots\FakeSlots;
use pocketmine\Server;
use pocketmine\utils\Config;

/**
 * Class ConfigManager
 * @package fakeslots\utils
 */
class ConfigManager {

    /** @var  FakeSlots $plugin */
    public $plugin;

    /**
     * @var  int $maxPlayers
     * @var  int $maxOnlinePlayers
     * @var  int $onlinePlayers
     */
    public $maxPlayers, $maxOnlinePlayers, $onlinePlayers;

    /**
     * @var bool $force
     */
    public $force = false;

    /**
     * @var Config $serverProperties
     */
    private $serverProperties;

    /**
     * ConfigManager constructor.
     * @param FakeSlots $plugin
     */
    public function __construct(FakeSlots $plugin) {
        $this->plugin = $plugin;
        $this->maxPlayers = $this->plugin->getServer()->getQueryInformation()->getPlayerCount();
        $this->maxOnlinePlayers = $this->plugin->getServer()->getQueryInformation()->getMaxPlayerCount();
        $this->onlinePlayers = $this->plugin->getServer()->getQueryInformation()->getPlayerCount();
        $this->serverProperties = new Config(Server::getInstance()->getDataPath()."/server.properties", Config::PROPERTIES);
        $this->init();
        $this->reloadData();
    }

    private function init() {
        if(!is_dir($this->plugin->getDataFolder())) {
            @mkdir($this->plugin->getDataFolder());
        }
        if(!is_file($this->plugin->getDataFolder()."/config.yml")) {
            $this->plugin->saveResource("/config.yml", true);
        }
    }

    public function saveData() {
        if(is_dir($this->plugin->getDataFolder())) {
            $config = $this->plugin->getConfig();
            $config->set("maxPlayers", $this->maxPlayers);
            $config->set("maxOnlinePlayers", $this->maxOnlinePlayers);
            $config->set("onlinePlayers", $this->onlinePlayers);
            $config->save();
        }
        else {
            $this->plugin->getLogger()->notice("Cloud not save data (File not found)");
        }
    }

    private function reloadData() {
        if(is_dir($this->plugin->getDataFolder())) {
            $config = $this->plugin->getConfig();
            $this->maxPlayers = $config->get("maxPlayers");
            $this->maxOnlinePlayers = $config->get("maxOnlinePlayers");
            $this->onlinePlayers = $config->get("onlinePlayers");
            $this->force = boolval($config->get("force"));
        }
        else {
            $this->plugin->getLogger()->notice("Cloud not reload data (File not found)");
        }
    }

    public function setOnlinePlayers(int $count) {
        $this->onlinePlayers = intval($count);
        $this->plugin->update();
    }

    /**
     * @param int $count
     */
    public function setMaxOnlinePlayers(int $count) {
        $this->maxOnlinePlayers = intval($count);
        $this->plugin->update();
    }

    /**
     * @param int $count
     */
    public function setMaxPlayers(int $count) {
        $this->maxPlayers = intval($count);
        if(!$this->force) {
            $this->serverProperties->set("max-players", $count);
            $this->serverProperties->save();
        }
        $this->plugin->update();
    }

    /**
     * @return int
     */
    public function getMaxOnlinePlayers():int {
        return $this->maxOnlinePlayers;
    }

    /**
     * @return int
     */
    public function getMaxPlayers():int {
        return $this->maxPlayers;
    }
}