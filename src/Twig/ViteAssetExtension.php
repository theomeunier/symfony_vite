<?php


namespace App\Twig;


use Psr\Cache\CacheItemPoolInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ViteAssetExtension extends AbstractExtension
{
    //on crée une variable qui content les proprière des donner du manifest.json
    private ?array $manifestData = null;
    const CACHE_KEY = 'vite_manifest';

    private bool $isDev;
    private string $manisfest;
    private CacheItemPoolInterface $cache;

    public function __construct(bool $isDev, string $manifest, CacheItemPoolInterface $cache)
    {
        $this->isDev = $isDev;
        $this->manisfest = $manifest;
        $this->cache = $cache;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('vite_asset', [$this, 'asset'], ['is_safe' => ['html']])
        ];
    }

    /**
     * function qui défini si nous sommes ne dev ou en prod
     *
     * @param string $entry
     * @param array $deps
     */
    public function asset(string $entry, array $deps)
    {
        if ($this->isDev) {
            return $this->assetDev($entry, $deps);
        }
        return $this->assetProd($entry);

    }


    /**
     * function pour le dev
     *
     * @param string $entry
     * @param array $deps
     * @return string
     */
    public function assetDev(string $entry, array $deps): string
    {
        $html = <<<HTML
<script type="module" src="http://localhost:3000/assets/@vite/client"></script>
HTML;
        //si nous avons une dépence avec react
        if (in_array('react', $deps)) {
            $html .= '<script type="module">
    import RefreshRuntime from "http://localhost:3000/assets/@react-refresh"
    RefreshRuntime.injectIntoGlobalHook(window)
    window.$RefreshReg$ = () => {}
    window.$RefreshSig$ = () => (type) => type
    window.__vite_plugin_react_preamble_installed__ = true
</script>';
        }
        $html .= <<<HTML
<script type="module" src="http://localhost:3000/assets/{$entry}"></script>
HTML;

        return $html;
    }

    /**
     * @param string $entry
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function assetProd(string $entry): string
    {
        if ($this->manifestData === null) {
            //on met du cache
            $item = $this->cache->getItem(self::CACHE_KEY);
            if ($item->isHit()) {
                $this->manifestData = $item->get();
            } else {
                //on lit les information
                $this->manifestData = json_decode(file_get_contents($this->manisfest), true);
                $item->set($this->manifestData);
                //on sauvegard notre itemp
                $this->cache->save($item);
            }
        }
        $file = $this->manifestData[$entry]['file'];
        $css = $this->manifestData[$entry]['css'] ?? [];
        $imports = $this->manifestData[$entry]['imports'] ?? [];

        $html = <<<HTML
<script type="module" src="/assets/{$file}"></script>
HTML;
        //on récupere les fichier css
        foreach ($css as $cssFile) {
            $html .= <<<HTML
<link rel="stylesheet" media="screen" href="/assets/{$cssFile}">
HTML;
        }
        //on récupere les fichier import
        foreach ($imports as $import) {
            $html .= <<<HTML
<link rel="modulepreload" href="/assets/{$import}">
HTML;
        }

        return $html;
    }

}