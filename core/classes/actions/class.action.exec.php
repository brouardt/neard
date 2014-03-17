<?php

class ActionExec
{
    const QUIT = 'quit';
    const RESTART = 'restart';
    
    public function __construct($args)
    {
        global $neardCore, $neardLang, $neardBins, $neardWinbinder;
        
        if (file_exists($neardCore->getExec())) {
            $action = file_get_contents($neardCore->getExec());
            if ($action == self::QUIT) {
                Batch::exitApp();
            } elseif ($action == self::RESTART) {
                Batch::restartApp();
            }
            Util::unlinkAlt($neardCore->getExec());
        }
    }
}
