# 05_Contao4/ROOT/src/Vendor/Package/DependencyInjection/VendorPackageExtension.php
namespace Vendor\Package\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class VendorPackageExtension extends Extension
{
    // Lädt die Konfigurationsdateien des Bundles
    public function load(array $mergedConfig, ContainerBuilder $container)
    {
        // Pfad zu den Konfigurationsdateien des Bundles erstellen.
        $path = __DIR__.'/../Resources/config';

        // Erstellen des Loaders für das aktuelle Verzeichnis
        $loader = new YamlFileLoader($container, new FileLocator($path));

        // Laden von TL_ROOT/src/Vendor/Package/Resources/config/listener.yml
        $loader->load('listener.yml');

        // Setzen von globalen Variablen
        $container->setParameter('esit.testvalue', 'lorem ipsum ...');
    }
}
