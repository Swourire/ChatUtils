<?php


namespace Swourire\chatutils;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;

class chatListener implements Listener
{

    private $loader;

    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
    }

    public function onChat(PlayerChatEvent $event){
        $player = $event->getPlayer();
        $stringAllowed = $this->loader->getConfig()->getNested("allowed");
        $arrayAllowed = explode(",", $stringAllowed);
        global $isAllowed;
        $isAllowed = false;
        foreach ($arrayAllowed as $k => $playerName){
            if($playerName === $player->getName()){
                $isAllowed = true;
                break;
            }
        }
        foreach (CommuneVars::$temporaryWhiteList as $k => $playerObject){
            if($playerObject instanceof Player){
                if($playerObject->getName() === $player->getName()){
                    $isAllowed = true;
                    break;
                }
            }
        }
        if($isAllowed) {
            if (CommuneVars::$isChatMuted) {
                $event->setCancelled();
                $player->sendMessage(CommuneVars::PREFIX . "§cChat is muted you can't talk !");
            }
        }
    }
}