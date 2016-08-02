<?php
namespace dompdfmodule\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

class dompdfFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Dompdf
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        // load user config
        $config = $serviceLocator->get('config');
        $userConfig = isset($config['dompdf']) ? $config['dompdf'] : array();

        // evaluate library directory
        $dompdfDir = isset($userConfig['DOMPDF_DIR']) ?
            $userConfig['DOMPDF_DIR'] :
            realpath('vendor/dompdf/dompdf');

        // merge default config with user config if necessary
        $dompdfConfig = count($userConfig) ?
            array_merge($this->createDefaultSettings($dompdfDir), $userConfig) :
            $this->createDefaultSettings($dompdfDir);

        // set options
        $options = new Options();
        foreach ($dompdfConfig as $settingName => $settingValue) {
            if (! defined($settingName)) {
                $options->set($settingName, $settingValue);
            }
        }

        return new Dompdf($options);
    }

    /**
     * Some settings can be evaluated by default.
     * @param string $dompdfDir DOMPDF library directory
     * @return array Default settings
     */
    protected function createDefaultSettings($dompdfDir)
    {
        return array(
            'logOutputFile'           => false,
            'defaultMediaType'        => 'screen',
            'defaultPaperSize'        => 'A4',
            'defaultFont'             => 'serif',
            'dpi'                     => 96,
            'pdfBackend'              => 'CPDF',
            'fontHeightRatio'         => 1.1,
            'isPhpEnabled'            => false,
            'isRemoteEnabled'         => false,
            'isJavascriptEnabled'     => false,
            'isHtml5ParserEnabled'    => true,
            'isFontSubsettingEnabled' => false,

            'debugPng'              => false,
            'debugKeepTemp'         => false,
            'debugCss'              => false,
            'debugLayout'           => false,
            'debugLayoutLines'      => false,
            'debugLayoutBlocks'     => false,
            'debugLayoutInline'     => false,
            'debugLayoutPaddingBox' => false,
        );
    }
}
