<?php

class ActionSwitchStatus
{
    public function __construct($args)
    {
        global $neardConfig, $neardBins;
        
        if (isset($args[0]) && !empty($args[0])) {
            $putOnline = $args[0] == Config::STATUS_ONLINE;
            
            $this->switchApache($putOnline);
            $this->switchXlight($putOnline);
            $neardConfig->replace(Config::CFG_STATUS, $args[0]);
        }
    }
    
    private function switchApache($putOnline)
    {
        global $neardBins;
        
        $onlineContent = $neardBins->getApache()->getOnlineContent();
        $offlineContent = $neardBins->getApache()->getOfflineContent();
        
        $result = file_get_contents($neardBins->getApache()->getConf());
        if ($putOnline) {
            $result = str_replace($offlineContent, $onlineContent, $result);
        } else {
            $result = str_replace($onlineContent, $offlineContent, $result);
        }
        
        file_put_contents($neardBins->getApache()->getConf(), $result);
    }
    
    private function switchXlight($putOnline)
    {
        global $neardBins;
        
        $offlineContent = 'AllowedIPList:"127.0.0.1|::1"';
        
        $result = '';
        if ($putOnline) {
            $fileContent = file_get_contents($neardBins->getXlight()->getConfOption());
            $result = str_replace($offlineContent, '', $fileContent);
        } else {
            $fileContent = file($neardBins->getXlight()->getConfOption());
            foreach ($fileContent as $row) {
                $row = trim($row);
                if (!empty($row)) {
                    $result .= $row . PHP_EOL;
                }
            }
            $result .= $offlineContent . PHP_EOL;
        }
        
        file_put_contents($neardBins->getXlight()->getConfOption(), $result);
    }

}
