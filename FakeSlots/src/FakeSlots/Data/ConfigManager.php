<?php

namespace FakeSlots\Data;

use FakeSlots\FakeSlots;

/**
 * Class ConfigManager
 * @package FakeSlots\Data
 */
class ConfigManager {

    /** @var  FakeSlots $plugin */
    public $plugin;

    /**
     * @var  int $maxPlayers
     * @var  int $maxOnlinePlayers
     */
    public $maxPlayers, $maxOnlinePlayers;

    /**
     * ConfigManager constructor.
     * @param FakeSlots $plugin
     */
    public function __construct(FakeSlots $plugin) {
        $this->plugin = $plugin;
        $this->maxPlayers = $this->plugin->getServer()->getQueryInformation()->getPlayerCount();
        $this->init();
        $this->reloadData();
    }

    function init() {
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
            $config->save();
        }
        else {
            $this->plugin->getLogger()->notice("Cloud not save data (File not found)");
        }
    }

    function reloadData() {
        if(is_dir($this->plugin->getDataFolder())) {
            $config = $this->plugin->getConfig();
            $this->maxPlayers = $config->get("maxPlayers");
            $this->maxOnlinePlayers = $config->get("maxOnlinePlayers");
        }
        else {
            $this->plugin->getLogger()->notice("Cloud not reload data (File not found)");
        }
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