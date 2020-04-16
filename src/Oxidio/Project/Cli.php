<?php declare(strict_types=1);
/**
 * Copyright (C) oxidio. See LICENSE file for license details.
 */

namespace Oxidio\Project;

use OxidEsales\EshopCommunity\Internal\Framework\Console\CommandsProvider\CommandsProviderInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ShopConfigurationDaoBridgeInterface;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\ContextInterface;
use Php;
use Oxidio;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Smarty;

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
        yield 'foo:bar' => function (Oxidio\DI\SmartyTemplateVars $stv, Smarty $smarty) {
            $res = Oxidio::call(function (Oxidio\DI\SmartyTemplateVars $stv, self $cli) {
                return 'ok';
            });
            yield $stv->smarty === $smarty ? $res : 'nok';
        };

        foreach ([LogLevel::ERROR, LogLevel::WARNING, LogLevel::INFO] as $level) {
            yield "log:$level" => function (LoggerInterface $logger, string ...$msg) use ($level) {
                foreach ($msg as $text) {
                    $logger->log($level, $text);
                }
            };
        }
        yield 'context:info' => function (ContextInterface $context) {
            yield Php::str('<fg=green>getContainerCacheFilePath:</> %s', $context->getContainerCacheFilePath());
            yield Php::str('<fg=green>getGeneratedServicesFilePath:</> %s', $context->getGeneratedServicesFilePath());
            yield Php::str('<fg=green>getConfigurableServicesFilePath:</> %s', $context->getConfigurableServicesFilePath());
            yield Php::str('<fg=green>getSourcePath:</> %s', $context->getSourcePath());
            yield Php::str('<fg=green>getModulesPath:</> %s', $context->getModulesPath());
            yield Php::str('<fg=green>getEdition:</> %s', $context->getEdition());
            yield Php::str('<fg=green>getCommunityEditionSourcePath:</> %s', $context->getCommunityEditionSourcePath());
            yield Php::str('<fg=green>getProfessionalEditionRootPath:</> %s', $context->getProfessionalEditionRootPath());
            yield Php::str('<fg=green>getEnterpriseEditionRootPath:</> %s', $context->getEnterpriseEditionRootPath());
            yield Php::str('<fg=green>getDefaultShopId:</> %s', $context->getDefaultShopId());
            yield Php::str('<fg=green>getAllShopIds:</> %s', implode(', ', $context->getAllShopIds()));
//            yield Php::str('<fg=green>getBackwardsCompatibilityClassMap:</> %s', implode(', ', $context->getBackwardsCompatibilityClassMap()));
            yield Php::str('<fg=green>getProjectConfigurationDirectory:</> %s', $context->getProjectConfigurationDirectory());
            yield Php::str('<fg=green>getConfigurationDirectoryPath:</> %s', $context->getConfigurationDirectoryPath());
            yield Php::str('<fg=green>getShopRootPath:</> %s', $context->getShopRootPath());
            yield Php::str('<fg=green>getConfigFilePath:</> %s', $context->getConfigFilePath());
            yield Php::str('<fg=green>getConfigTableName:</> %s', $context->getConfigTableName());
            yield '---';
            yield Php::str('<fg=green>getCurrentShopId:</> %s', $context->getCurrentShopId());
            yield Php::str('<fg=green>getLogLevel:</> %s', $context->getLogLevel());
            yield Php::str('<fg=green>getRequiredContactFormFields:</> %s', implode(', ', $context->getRequiredContactFormFields()));
            yield Php::str('<fg=green>getConfigurationEncryptionKey:</> %s', $context->getConfigurationEncryptionKey());
            yield Php::str('<fg=green>isEnabledAdminQueryLog:</> %s', $context->isEnabledAdminQueryLog());
            yield Php::str('<fg=green>isAdmin:</> %b', $context->isAdmin());
            yield Php::str('<fg=green>getAdminLogFilePath:</> %s', $context->getAdminLogFilePath());
            yield Php::str('<fg=green>getSkipLogTags:</> %s', implode(', ', $context->getSkipLogTags()));
//            yield Php::str('<fg=green>getAdminUserId:</> %s', $context->getAdminUserId());
        };

        yield 'module:list' => function (ShopConfigurationDaoBridgeInterface $shop) {
            foreach ($shop->get()->getModuleConfigurations() as $id => $module) {
                yield Php::str('<fg=yellow>##%s</>', $id);
                yield Php::str('<fg=green>getPath:</> %s', $module->getPath());
                yield Php::str('<fg=green>getVersion:</> %s', $module->getVersion());
                yield Php::str('<fg=green>getTitle:</> %s', implode(', ', $module->getTitle()));
                yield Php::str('<fg=green>getDescription:</> %s', implode(', ', $module->getDescription()));
                yield Php::str('<fg=green>getLang:</> %s', $module->getLang());
                yield Php::str('<fg=green>getThumbnail:</> %s', $module->getThumbnail());
                yield Php::str('<fg=green>isConfigured:</> %b', $module->isConfigured());
                yield Php::str('<fg=green>getAuthor:</> %s', $module->getAuthor());
                yield Php::str('<fg=green>getUrl:</> %s', $module->getUrl());
                yield Php::str('<fg=green>getEmail:</> %s', $module->getEmail());
                yield Php::str('<fg=green>hasClassExtensions:</> %b', $module->hasClassExtensions());
//                yield Php::str('<fg=green>getClassExtensions:</> %s', implode(', ', $module->getClassExtensions()));
                yield Php::str('<fg=green>hasTemplateBlocks:</> %b', $module->hasTemplateBlocks());
//                yield Php::str('<fg=green>getTemplateBlocks:</> %s', implode(', ', $module->getTemplateBlocks()));
                yield Php::str('<fg=green>hasTemplates:</> %b', $module->hasTemplates());
//                yield Php::str('<fg=green>getTemplates:</> %s', implode(', ', $module->getTemplates()));
                yield Php::str('<fg=green>hasControllers:</> %b', $module->hasControllers());
//                yield Php::str('<fg=green>getControllers:</> %s', implode(', ', $module->getControllers()));
                yield Php::str('<fg=green>hasSmartyPluginDirectories:</> %b', $module->hasSmartyPluginDirectories());
//                yield Php::str('<fg=green>getSmartyPluginDirectories:</> %s', implode(', ', $module->getSmartyPluginDirectories()));
                yield Php::str('<fg=green>hasEvents:</> %b', $module->hasEvents());
//                yield Php::str('<fg=green>getEvents:</> %s', implode(', ', $module->getEvents()));
                yield Php::str('<fg=green>hasClassWithoutNamespaces:</> %b', $module->hasClassWithoutNamespaces());
//                yield Php::str('<fg=green>getClassesWithoutNamespace:</> %s', implode(', ', $module->getClassesWithoutNamespace()));
                yield Php::str('<fg=green>hasModuleSettings:</> %b', $module->hasModuleSettings());
//                yield Php::str('<fg=green>getModuleSettings:</> %s', implode(', ', $module->getModuleSettings()));
                yield '';
            }
        };
    }
}
