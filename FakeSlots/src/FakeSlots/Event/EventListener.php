<?php

namespace FakeSlots\Event;

use FakeSlots\FakeSlots;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\server\QueryRegenerateEvent;

/**
 * Class EventListener
 * @package FakeSlots\Event
 */
class EventListener implements Listener {

    /** @var FakeSlots $plugin */
    public $plugin;

    /**
     * EventListener constructor.
     * @param FakeSlots $plugin
     */
    public function __construct(FakeSlots $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @param PlayerKickEvent $event
     */
    public function onKick(PlayerKickEvent $event) {
        if($event->getReason() == "disconnectionScreen.serverFull") {
            if(count($this->plugin->getServer()->getOnlinePlayers()) <= $this->plugin->getMaxOnlinePlayers()) {
                $event->setCancelled(true);
                $this->plugin->update();
            }
        }
    }

    /**
     * @param QueryRegenerateEvent $event
     */
    public function onQuery(QueryRegenerateEvent $event) {
        $this->plugin->update();
    }
}