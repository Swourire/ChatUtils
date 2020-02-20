<?php


namespace Swourire\chatutils\Commands;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;
use pocketmine\Server;
use Swourire\chatutils\CommuneVars;
use Swourire\chatutils\Loader;

class sayAllCommand extends Command
{
    private $loader;

    public function __construct(Loader $loader)
    {
        parent::__construct("sayall", "Make the chat say everything !", "Type \"/sayall help\" to show sayall's commands !", ["sa"]);
        $this->loader = $loader;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            $stringAllowed = $this->loader->getConfig()->getNested("allowed");
            $arrayAllowed = explode(",", $stringAllowed);
            global $isAllowed;
            $isAllowed = false;
            foreach ($arrayAllowed as $k => $playerName) {
                if ($playerName === $sender->getName()) {
                    $isAllowed = true;
                    break;
                }
            }
            if ($isAllowed) {
                if (isset($args[0])) {
                    foreach (Server::getInstance()->getOnlinePlayers() as $k => $onlinePlayer){
                        Server::getInstance()->getPluginManager()->callEvent($ev = new PlayerChatEvent($onlinePlayer, $args[0]));
                        if(!$ev->isCancelled()){
                            Server::getInstance()->broadcastMessage(Server::getInstance()->getLanguage()->translateString($ev->getFormat(), [$ev->getPlayer()->getDisplayName(), $ev->getMessage()]), $ev->getRecipients());
                            $playersOnline = count(Server::getInstance()->getOnlinePlayers());
                            $sender->sendMessage(CommuneVars::PREFIX . "§aMade {$playersOnline} players say {$args[0]}");
                        }
                    }
                } else {
                    $sender->sendMessage(CommuneVars::PREFIX . "§cYou need to set a message !");
                }
            }else{
                $sender->sendMessage(CommuneVars::PREFIX . "§cNo permission.");
            }
        }
        return false;
    }
}
