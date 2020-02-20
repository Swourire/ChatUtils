<?php

namespace Swourire\chatutils\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use Swourire\chatutils\CommuneVars;
use Swourire\chatutils\Loader;

class muteChatCommand extends Command
{

    private $loader;

    public function __construct(Loader $loader)
    {
        parent::__construct("mutechat", "Mute the chat !", "Type \"/mutechat help\" to show mutechat's commands !", ["mc"]);
        $this->loader = $loader;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($sender instanceof Player){
            $stringAllowed = $this->loader->getConfig()->getNested("allowed");
            $arrayAllowed = explode(",", $stringAllowed);
            global $isAllowed;
            $isAllowed = false;
            foreach ($arrayAllowed as $k => $playerName){
                if($playerName === $sender->getName()){
                    $isAllowed = true;
                    break;
                }
            }
            if($isAllowed){
                if(!isset($args[0])){
                    if(!CommuneVars::$isChatMuted){
                        CommuneVars::$isChatMuted = true;
                        $sender->sendMessage(CommuneVars::PREFIX . "§aSuccessfully muted the chat !");
                    }else{
                        CommuneVars::$isChatMuted = false;
                        $sender->sendMessage(CommuneVars::PREFIX . "§aSuccessfully unmuted the chat !");
                    }
                }else{
                    switch ($args[0]){
                        case "whitelist":
                            $player = Server::getInstance()->getPlayer($args[0]);
                            if($player !== null){
                                CommuneVars::$temporaryWhiteList[] = $player;
                            }else{
                                $sender->sendMessage(CommuneVars::PREFIX . "§cPlayer not found.");
                            }

                    }
                }
            }else{
                $sender->sendMessage(CommuneVars::PREFIX . "§cNo permission.");
            }
            return false;
        }
    }
}