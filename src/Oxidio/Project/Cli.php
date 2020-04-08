<?php declare(strict_types=1);
/**
 * Copyright (C) oxidio. See LICENSE file for license details.
 */

namespace Oxidio\Project;

use OxidEsales\EshopCommunity\Internal\Framework\Console\CommandsProvider\CommandsProviderInterface;
use Php;
use Oxidio;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class Cli extends Php\Cli
{
    public function __construct(CommandsProviderInterface $provider)
    {
        $di = static::di(Php\VENDOR\OXIDIO\PROJECT, Oxidio::di());
        parent::__construct($di);
        $this->addCommands($provider->getCommands());
    }

    protected function getCommands()
    {
        foreach ([LogLevel::ERROR, LogLevel::WARNING, LogLevel::INFO] as $level) {
            yield "log:$level" => function (LoggerInterface $logger, string ...$msg) use ($level) {
                foreach ($msg as $text) {
                    $logger->log($level, $text);
                }
            };
        }
    }
}
