<?php

namespace Sign\CloudSystem;

use pocketmine\math\Vector3;
use pocketmine\scheduler\Task;
use pocketmine\plugin\Plugin;
use pocketmine\tile\Sign;
use pocketmine\utils\Config;

class CloudSystemTask extends Task {
    private $plugin;

    public function __construct(Plugin $owner) {
        $this->plugin = $owner;
    }

    public function onRun(int $currentTick) {
        if ($this->plugin instanceof CloudSystem) {
            $cfg = $this->plugin->cfg;
            $cfg->reload();

            if ($this->plugin->threecount == 3) {
                $this->plugin->threecount = 1;
            } else {
                $this->plugin->threecount++;
            }

            if ($cfg instanceof Config) {
                $signs = $cfg->get('signs');
                foreach ($signs as $sign) {
                    $coords = explode(':', $sign['coords']);

                    $tile = $this->plugin->getServer()->getDefaultLevel()->getTile(new Vector3($coords[0], $coords[1], $coords[2]));

                    if ($tile instanceof Sign) {
                        if ($this->plugin->threecount == 1) {
                            $tile->setLine(3, ' ');
                        } elseif ($this->plugin->threecount == 2) {
                            $tile->setLine(3, ' ');
                        } elseif ($this->plugin->threecount == 3) {
                            $tile->setLine(3, ' ');
                        }

                        $query = new MinecraftQuery();
                        $query->connect($sign['ip'], $sign['port']);

                        if ($query->isOnline()) {
                            $info = $query->getInfo();
                            $playercount = $info['Players'];
                            $mplayers = $info['MaxPlayers'];

                            if ($playercount < $mplayers) {
                                $tile->setLine(1, '§7-= §aBetreten§7 =-');
                                $tile->setLine(2, '§c' . $playercount . " §7/ §c" . $mplayers);
                                $tile->setLine(3, '§aooo');
                            } else {
                                $tile->setLine(1, '§7-= §cVoll§7 =-');
                                $tile->setLine(2, '§c' . $playercount . " §7/ §c" . $mplayers);
                                $tile->setLine(3, '§6ooo');
                            }
                        } else {
                            $tile->setLine(1, '----*----');
                            $tile->setLine(2, '§eLade Server');
							$tile->setLine(3, '§4ooo');
                        }
                    }
                }
            }
        }
    }
}