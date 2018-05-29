# Event-Driven-Development


## Einleitung

### Was sind Events?
> Ein Ereignis (englisch event) dient in der Softwaretechnik – bei Entwicklung nach dem ereignisorientieren Programmierparadigma – zur Steuerung des Programmflusses. Das Programm wird nicht linear durchlaufen, sondern es werden spezielle Ereignisbehandlungsroutinen (engl. listener, observer, event handler) immer dann ausgeführt, wenn ein bestimmtes Ereignis auftritt.

Quelle:
[https://de.wikipedia.org/wiki/Ereignis_(Programmierung)](https://de.wikipedia.org/wiki/Ereignis_%28Programmierung%29)

Mit Events ist es also möglich, den Programmablauf dynamisch zu beeinflussen. Es kann somit sehr flexibel auf geänderte Anforderungen reagiert werden. Zusätzlich ist es für Drittentwickler möglich, nachträglich Änderungen und Erweiterungen der Software vorzunehmen.

### Und warum will man das?
Durch die lose Kopplung der Komponenten steigt die Flexibilität. Es entsteht besser zu testender Quelltext, die Codequalität steigt. Prinzipien wie SOLID, Clean Code und Test-Driven Development sind prädestiniert dafür, mit Events umgesetzt zu werden.


### Beispiele aus der Praxis

__Universelle Import-/Exportmodule__  
Da man nicht voraussetzen kann, dass es für alle Erweiterungen Contao-Models, oder Doctrine-Entities gibt, wird man hier auf SQL zurückgreifen müssen. Die Querys kann man sehr gut über Events erstellen. So kann leicht auf geänderte Rahmenbedingungen reagiert werden.

__Frontend-Ansichten__  
Für die Ausgabe im Frontend müssen oft Werte aus der DB konvertiert werden. Schreibt man allgemeingültige Module, weiß man natürlich im Vorfeld nicht, welche Daten damit angezeigt werden und wie die Daten konvertiert werden sollen. Stellt man nun ein entsprechendes Event zur Verfügung, kann der Nutzer hier bequem seine individuelle Konvertierung einfügen, indem er einfach seinen Listener registriert.

__Berechnungen__  
Auch Berechnungen für Bestellsysteme oder Konfiguratoren lassen sich hervorragend mit Events umsetzten. Zum einen können die einzelnen Bestandteile besser aufgeteilt werden, zum anderen kann auch nachträglich noch sehr einfach auf geänderte Brechungsgrundlagen reagiert werden.

Es gibt sicher zahlreiche weitere Beispiele. Für diese Einführung soll diese aber erst einmal reichen.

## Grundlagen

Bevor wir nun zu den Events kommen, will ich noch ganz kurz auf einige Grundlagen eingehen. Die hier gezeigten Grundlagen sind bewusst sehr einfach gehalten und stellen in der Regel die kleinst mögliche Lösung dar. Es wird in Kauf genommen, dass es sich nicht um optimale Lösungen handelt. Im Laufe der Ausführungen werden bessere Lösungen erarbeitet.

### Autoloading
In der Regel wird man in seinem Projekt verschiedene Abhängigkeiten haben. In diesem Fall wird vermutlich [composer](https://getcomposer.org/) zum Einsatz kommen. Dieser kann dann auch gleich das Autoloading für uns übernehmen. Wenn wir eine Klasse `Foo\\Bar\\Baz` haben, die wir in `src/Bar/Baz.php` gespeichert haben, reicht folgender Eintrag im `autoload`-Abschnitt unserer `composer.json`:

```json
{
    "autoload": {
        "psr-4": {
            "Foo\\": "src/"
        }
    }
}
```

Der Vendornamespace `Foo` wird nicht berücksichtigt und der Rest wird auf die Ordnerstruktur unter `src/` abgebildet.
Da bei mir in der Regel der Vendornamespace unter `src/` liegt, sieht der Eintrag bei mir so aus:

```json
{
    "autoload": {
        "psr-4": {
            "": "src/"
        }
    }
}
```

Wenn eine Klasse den Namen `\Esit\SuperBundle\Classes\Helper\Url` trägt, bezieht sich dies auf die Datei  `ROOT/src/Esit/SuperBundle/Classes/Helper/Url.php`. Hier wird also nichts weggelassen.
Nutzt man nicht `composer`, kann man auch folgenden Code benutzen.

```php
<?php
// Your custom class dir
define('CLASS_DIR', 'src/'); // An eigene Anforderungen anpassen!

// Add your class dir to include path
set_include_path(get_include_path() . PATH_SEPARATOR . CLASS_DIR);

// You can use this trick to make autoloader look for commonly used "My.php" type filenames
spl_autoload_extensions('.php');

// Use default autoload implementation
spl_autoload_register();
```

Quelle: [simast at gmail dot com @ php.net](http://php.net/manual/de/function.spl-autoload.php#92767)

### Braucht man für Events Dependency Injection?
Nein, aber es hilft! Oftmals benötigen die Klassen, die sich um die Verarbeitung der Events kümmern andere Klassen. Will man sich nicht selbst um die Instanzierung komplexer Abhängigkeitshierarchien kümmern, kann man einen Dependency Injection Container nutzen. Wikipedia meint hierzu:

> Als Dependency Injection (englisch dependency ‚Abhängigkeit‘ und injection ‚Injektion‘; Abkürzung DI) wird in der objektorientierten Programmierung ein Entwurfsmuster bezeichnet, welches die Abhängigkeiten eines Objekts zur Laufzeit reglementiert: Benötigt ein Objekt beispielsweise bei seiner Initialisierung ein anderes Objekt, ist diese Abhängigkeit an einem zentralen Ort hinterlegt – es wird also nicht vom initialisierten Objekt selbst erzeugt.

Quelle: [https://de.wikipedia.org/wiki/Dependency_Injection](https://de.wikipedia.org/wiki/Dependency_Injection)

Ein sehr einfacher Dependency Injection Container ist z.B. [Dice](https://r.je/dice.html). Hier ist keine zusätzliche Konfiguration nötig, da `dice` die Type Hints nutzt, um die Abhängigkeiten ausfindig zu machen.

```php
<?php
class A {
    private $b;
    public function __construct(B $b) {
        $this->b = $b;
    }
}

class B {
    private $c,$d;
    public function __construct(C $c, D $d) {
        $this->c = $c;
        $this->d = $d;
    }
}

class C {}

class D {
    private $e;
    public function __construct(E $e) {
        $this->e = $e;
    }
}

class E {}

$dice   = new \Dice\Dice;
$a      = $dice->create('A');
```

Quelle: [r.je/dice.html](https://r.je/dice.html#example1-1)

Wie man sieht, reicht auch bei komplexen Vererbungsstrukturen ein einfacher Aufruf von ` $dice->create('...');`. Installiert wird dice einfach mit `composer require level-2/dice` im ROOT des Projekts.

### EventSubscriber
EventSubscriber sind Klassen, die sich selbst für ein Event registrieren. Es gibt also keine zentrale Konfigurationsdatei mehr. Dies macht die Sache noch wesentlich flexibler, aber auch sehr unübersichtlich. Um herauszufinden in welcher Reihenfolge die Listener aufgerufen werden, ist hier mit unter sehr viel Aufwand nötig.

Ich setze deshalb EventSubscriber nicht mehr ein. Dies ist aber vermutlich Geschmackssache.


## Events ganz einfach

Da wir nun alle nötigen Grundlagen haben, machen wir uns an ein erstes Beispiel für einen simplen Event Dispatcher. Wir gehen davon aus, dass alle Klassen unter `src/` liegen und per Autoload gefunden werden.

### Konfiguration
Als erstes kümmern wir uns um die Konfiguration. In der Datei `ROOT/config/events.php` tragen wir die Listener ein, die bei einem bestimmten Event aufgerufen werden sollen.

```php
<?php
# 03_SimpleEvent/ROOT/config/events_demo.php
/*
 * Diese Datei dient nur der Demonstration!
 * Im Projekt wird nur die Datei 03_SimpleEvent/ROOT/config/events.php verwendet!
 * (s. "Einfügen eines weiteren Listeners" weiter unten)
 */
$events['greeting.event'][1024] = ['Esit\Listener\OnGreetingListner', 'generateGreeting'];

return $events;
```

### Event Dispatcher
Hier wird ein sehr einfacher Event Dispatcher gezeigt. Es wird an dieser Stelle bewusst auf Feinheiten verzichtet.

```php
<?php
# 03_SimpleEvent/ROOT/src/Helper/EventHelper.php
namespace Esit\Helper;

class EventHelper
{
    public static function dispatch($eventName, $event)
    {
        $di             = new \Dice\Dice();
        $allListener    = include __DIR__ . '/../../../config/events.php';

        if (is_array($allListener) && array_key_exists($eventName, $allListener)) {
            ksort($allListener[$eventName]);

            foreach ($allListener[$eventName] as $listner) {
                if (count($listner) == 2) {
                    $class   = $di->create($listner[0]);
                    $methode = $listner[1];

                    if ($class && method_exists($class, $methode)) {
                        $class->$methode($event);
                    }
                }
            }
        }
    }
}
```

### Definition eines Events
Mit diesem Event wird eine Grußbotschaft erstellt. Beim Erstellen wird ihm ein Name übergeben und der Listener erstellt dann die Grußbotschaft. Es gibt zwei Eigenschaften: `name` für den Namen und `message` für die Grußbotschaft. Zusätzlich wird noch eine Konstante definiert (`EVENTNAME`). Hierbei handelt es sich um den Namen, mit dem das Event aufgerufen wird. Wichtig ist, dass der __Name einzigartig sein muss__!

```php
<?php
# 03_SimpleEvent/ROOT/src/Events/OnGreetingEvent.php
namespace Esit\Events;

class OnGreetingEvent
{
    /**
     * Name des Events
     */
    const NAME = 'greeting.event';

    /**
     * Name der Person, die gegrüßt werden soll.
     * @var string
     */
    protected $name = '';

    /**
     * Fertige Grußbotschaft
     * @var array
     */
    protected $message = '';

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }
}
```

### Listener
Die Verarbeitung ist sehr einfach, es wird einfach der Methode das Event übergeben. Da Objekte immer als Referenz übergeben werden, muss man sich nicht um die Rückgabe kümmern.

```php
<?php
# 03_SimpleEvent/ROOT/src/Listener/OnGreetingListner.php
namespace Esit\Listener;

class OnGreetingListner
{
    public function generateGreeting(\Esit\Events\OnGreetingEvent $greetingEvent)
    {
        $name       = $greetingEvent->getName();
        $message    = $greetingEvent->getMessage();
        $message   .= "Hallo $name!";
        $greetingEvent->setMessage($message);
    }
}
```

### Aufruf eines Events
Will man nun ein Event aufrufen, benötigt man das Event und den Event Dispatcher.

```php
<?php
# 03_SimpleEvent/ROOT/src/Content/EventCaller.php
namespace Esit\Content;

class EventCaller
{
    public static function callEvent()
    {
        $event = new \Esit\Events\OnGreetingEvent();
        $event->setName('Leo');
        \Esit\Helper\EventHelper::dispatch($event::NAME, $event);
        echo $event->getMessage();  # Print: Hallo Leo!
    }
}
```

### Einfügen eines weiteren Listeners
Wollen wir der Grußbotschaft nun z.B. später noch etwas hinzufügen, registrieren wir in `ROOT/config/events.php` einfach einen weiteren Listener. Dieser kann in der selben Datei, oder ganz woanders sein.

```php
<?php
# 03_SimpleEvent/ROOT/config/events.php
$events['greeting.event'][1024] = ['Esit\Listener\OnGreetingListner', 'generateGreeting'];
$events['greeting.event'][2048] = ['Esit\Listener\OnGreetingListnerTwo', 'modifyGreeting']; # new!

return $events;
```

Nun erstellen wir den neuen Listener und führen die gewünschten Operationen durch.

```php
<?php
# 03_SimpleEvent/ROOT/src/Listener/OnGreetingListnerTwo.php
namespace Esit\Listener;

class OnGreetingListnerTwo
{
    public function modifyGreeting(\Esit\Events\OnGreetingEvent $greetingEvent)
    {
        $message = $greetingEvent->getMessage();
        $message.= ' Contao ist toll! ';
        $greetingEvent->setMessage($message);
    }
}
```

Nun würde in der Klasse `ROOT/src/Helper/EventCaller.php` am Ende nicht mehr _"Hallo Leo!"_ ausgegeben, sondern _"Hallo Leo! Contao ist toll!"_.

Selbstverständlich ist es auch möglich, Listener zu löschen, oder vor bzw. zwischen bestehenden Listenern neue einzufügen. Des Weiteren ist es meist so, dass die Listener ihrerseits wieder Events erstellen und somit andere Listener aufrufen. Der Phantasie sind hier keine Grenzen gesetzt. Zusätzlich führt diese Art der Programmierung zu besser wartbarem und vor allem besser testbarem Code.

### Verzeichnisstruktur
Zur Verdeutlichung der Vorgänge hier noch einmal die komplette Verzeichnisstruktur:

```txt
ROOT/
├── composer.json                       <-- dice muss hier eingetragen werden
├── config
│   └── events.php                      <-- Konfiguration der Event Lsitener
└── src
    ├── Content
    │   └── EventCaller.php             <-- Aufrufende Klasse (beispielhaft)
    ├── Events
    │   └── OnGreetingEvent.php         <-- Event
    ├── Helper
    │   └── EventHelper.php             <-- Event Dispatcher
    └── Listener
        ├── OnGreetingListner.php       <-- 1. Event Listener
        └── OnGreetingListnerTwo.php    <-- 2. Event Listener
```

Alle relevanten Dateien sind auch auf [Github](https://github.com/eS-IT/EventDrivenDevelopment_ck2018) zu finden.  Sie liegen im Ordner `03_SimpleEvent`. Dort kann alles nachvollzogen werden.


## Events unter Contao 3?

Da Contao 3 nicht mehr lange gepflegt wird, soll hier nur der Vollständigkeit halber auf die Hooks und Callbacks eingegangen werden. Das Autoloading geschieht über `ROOT/system/moduel/ERWEITERUNG/config/autoload.php`. Die Klassen können im Prinzip überall gespeichert werden (z.B. unter `ROOT/system/moduel/ERWEITERUNG/src/`).

### Events, Hooks und Callbacks?
Was ist nun der Unterschied zwischen Events, Hooks und Callbacks? Die in Contao verwendeten Hooks und Callbacks sind Spezialformen von Events. Der größte Unterschied ist, dass hier statt mit Event-Objekten, mit Parametern gearbeitet wird. Mit den Hooks beeinflusst man den grundsätzlichen Programmablauf, mit den Callbacks greift man in die Verarbeitung des DCA ein.

### Hooks
Die Hooks werden unter `04_Contao3/ROOT/system/moduel/ERWEITERUNG/config/config.php` konfiguriert.

```php
<?php
// CONTENT ELEMENTS
$GLOBALS['TL_CTE']['esit']['greeting'] = '\Esit\GreetingEvent\Classes\Contao\Elements\ContentGreeting';

// HOOKS
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('\Esit\GreetingEvent\Classes\Contao\Hooks\MyHook', 'myReplaceInsertTags');
```

__Auf das Autoloading soll an dieser Stelle nicht eingegangen werden!__

Der Listener wäre in diesem Fall die Klasse `MyHook`. Sie könnte z.B. so aussehen:

```php
<?php
# 04_Contao3/ROOT/system/moduel/ERWEITERUNG/src/MyHook.php
namespace Esit\GreetingEvent\Classes\Contao\Hooks;

class MyHook
{
    public function myReplaceInsertTags($strTag)
    {
        if ($strTag == 'mytag') {
            return 'mytag replacement';
        }

        return false;
    }
}
```

Quelle: [Contao Handbuch](https://docs.contao.org/books/api/extensions/hooks/replaceInsertTags.html#example)


### Callbacks
Im Gegensatz zu _Hooks_ werden die _Callbacks_ im DCA konfiguriert.

```php
<?php
# 04_Contao3/ROOT/system/modules/GreetingEvent/dca/tl_content.php
/* Set Tablename: tl_content */
$strName = 'tl_content';

/* Callback */
$GLOBALS['TL_DCA'][$strName]['list']['label']['label_callback'] = array('\Esit\GreetingEvent\Classes\Contao\Callbacks\MyCallback', 'myLabelCallback');

/* Palettes */
$GLOBALS['TL_DCA'][$strName]['palettes']['greeting'] = '{type_legend},type,headline,myname;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';

/* Fields */
$GLOBALS['TL_DCA'][$strName]['fields']['myname'] = array
(
   'label'                   => &$GLOBALS['TL_LANG'][$strName]['myname'],
   'exclude'                 => true,
   'inputType'               => 'text',
   'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
   'sql'                     => "varchar(255) NOT NULL default ''"
);
```

Die Klasse `MyCallback` könnte wie folgt aussehen:

```php
<?php
# 04_Contao3/ROOT/system/moduel/GreetingEvent/Classes/Contao/Callbacks/MyCallback.php
namespace Esit\GreetingEvent\Classes\Contao\Callbacks;

class MyCallback
{
    public function myLabelCallback($row, $label){
        $newLabel = $row['id'] . ': ' . $label;
        return $newLabel;
    }
}
```

Quelle: [e@sy Solutions IT - Blog](http://easysolutions-it.de/artikel/contao-callbacks.html)

### EventHelper
Es spricht nichts dagegen, das eben gezeigt Vorgehen mit der Klasse `\Esit\Helper\EventHelper` unter Contao 3 einzusetzen. Ich habe eine ähnliche Klasse bereits im Einsatz, es funktioniert sehr gut. Die Klasse mit dem Aufruf (`\Esit\Content\EventCaller`) könnte dann z.B. die Klasse eines Inhaltselements oder Module sein.

Hier ein Beispiel für den Aufruf des oben definierten Events in einem normalen Inhaltselement:

```php
<?php
# 04_Contao3/ROOT/system/modules/GreetingEvent/Classes/Contao/Elements/ContentGreeting.php
namespace Esit\GreetingEvent\Classes\Contao\Elements;

use Esit\GreetingEvent\Classes\Events\OnGreetingEvent;

class ContentGreeting extends \ContentElement
{
    protected $strTemplate = 'ce_greetings';

    protected function compile()
    {
        if (TL_MODE == 'BE') {
            $this->strTemplate        = 'be_wildcard';
            $this->Template           = new \BackendTemplate('be_wildcard');
            $this->Template->wildcard = "### ContentGreeting ###";
        } else {
            $event = new OnGreetingEvent();
            $event->setName($this->myname);
            \Esit\GreetingEvent\Classes\Helper\EventHelper::dispatch($event::EVENTNAME, $event);
            $this->Template->content = $event->getMessage();
        }
    }
}
```

Wenn man die Grundlagen einmal verstanden hat, ist es ganz einfach unter Contao 3 mit Events zu arbeiten.

### Verzeichnisstruktur
_Damit dieses Beispiel funktioniert, müssen die Namespaces angepasst werden! Diese ergeben sich aus der folgenden Ordnerstruktur mit dem Vendornamespace `Esit`._

```txt
ROOT/
├── composer.json                       <-- dice muss hier eingetagen werden
└── system
  └── modules
    └── GreetingEvent                   <-- Root der Erweiterung
      ├── Classes
      │  ├── Contao
      │  │  └── Elements
      │  │    └── ContentGreeting.php   <-- Inhaltselement
      │  ├── Events
      │  │  └── OnGreetingEvent.php     <-- Event
      │  ├── Helper
      │  │  └── EventHelper.php         <-- EventDispatcher
      │  └── Listener
      │    └── OnGreetingListener.php   <-- Listener
      │    └── OnGreetingListenerTwo.php<-- 2. Listener
      ├── config
      │  ├── autoload.php               <-- Autoload von Contao
      │  ├── config.php                 <-- config.php von Contao
      │  └── events.php                 <-- Konfiguration der Lsitener
      ├── dca
      │  └── tl_content.php             <-- DCA-Konfiguration
      ├── languages
      │  └── de
      │    ├── default.php              <-- Übersetzung CTE
      │    └── tl_content.php           <-- Übersetzung DCA
      └── templates
        └── ce_greetings.html5          <-- Ausgabetemplate
```

Alle relevanten Dateien sind auch auf [Github](https://github.com/eS-IT/EventDrivenDevelopment_ck2018) zu finden. Sie liegen im Ordner `04_Contao3`. Dort kann alles nachvollzogen werden.

## Events unter Contao 4 mit Symfony

### Contao Manager
Damit das Bundle geladen wird, benötigen wir das Plugin des Contao Managers. Zunächst müssen wir den Ort des Plugins in die `composer.json` eintragen und die Einstellungen für das Autoloading unseres Bundles vornehmen. Wir ergänzen die Datei wie folgt:

```json
{
    "autoload": {
        "classmap": [
            "src/Vendor/Package/Classes/ContaoManager/ContaoManagerPlugin.php"
        ],
        "psr-4": {
            "": "src/"
        }
    }
}
```

Nun können wir unser Bundle für den Manager konfigurieren. Wichtig ist, dass die Klasse `ContaoManagerPlugin` heißt und im globalen Namespace liegt. Der Pfad wird in der `composer.json` unter `classmap` angegeben.

```php
<?php
// 05_Contao4/ROOT/src/Vendor/Package/Classes/ContaoManager/ContaoManagerPlugin.php
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

class ContaoManagerPlugin implements BundlePluginInterface
{

    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(\Vendor\Package\VendorPackageBundle::class)
                        ->setLoadAfter([\Contao\CoreBundle\ContaoCoreBundle::class])
        ];
    }
}
```

Weitere Detail findet man im [Handbuch](https://docs.contao.org/books/extending-contao4/managed-edition/plugins.html#the-interfaces).

Zusätzlich benötigen wir noch die unter Symfony obligatorische Bundle-Datei:

```php
<?php
# 05_Contao4/ROOT/src/Vendor/Package/VendorPackageBundle.php
namespace Vendor\Package;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class VendorPackageBundle extends Bundle
{
}
```

### Laden der Services
Für das Laden der Konfigurationen wird eine Extenstion-Datei benötigt. Diese wird vom `\Symfony\Component\DependencyInjection\Compiler\MergeExtensionConfigurationPass` beim Laden der ServiceContainer aufgerufen. Die Datei muss im Ordner `DependencyInjection` des Bundles liegen und nach dem Schema `VENDORNAMESPACE``BUNDLENAME``Extension` benannt sein. In unserem Beispiel ist dies `VendorPackageExtension`. Außerdem muss die Klasse von der Klasse `Symfony\Component\DependencyInjection\Extension\Extension` erben. Es können hier auch Werte im Container gespeichert werden. Diese sind ähnlich den Werten, die Contao in `$GLOBALS` speichert.

```php
<?php
// 05_Contao4/ROOT/src/Vendor/Package/DependencyInjection/VendorPackageExtension.php
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
```

### Konfigurieren der Services und Listener
Die Services und Listener werden in einer YAML-Datei konfiguriert. Der Name der Datei ist hier nicht so wichtig, da sie über die Extension-Klasse (`VendorPackageExtension`) geladen werden und dort die Namen angegeben werden können.

Über die Tags wird definiert, dass es sich bei einem Services um EventListener (`name: kernel.event_listener`) handelt und mit `event: greeting.event` wird der Name des Events angegeben, auf das der Listener reagiert.

Der Eintrag unter `class` zeigt die Klasse (z. B. `Vendor\Package\EventListener\OnGreetingListener`) die aufgerufen wird und der Tag `method` die Methode (z.B. `generateGreeting`). Der Name des Eintrags (`vendor_package_bundle.listener.greeting.listener.generateGreeting`) muss eindeutig sein, ist aber frei wählbar. Ich habe mir deshalb angewöhnt, hier eine Kombination aus Bandlename, Klassenname des Listeners, gefolgt von der aufzurufenden Methode zu verwenden. Dies ist zwar lang, aber ziemlich eindeutig.

```yml
# 05_Contao4/ROOT/src/Vendor/Package/Resources/config/listener.yml
services:
    ## ############# ##
    ## EventListener ##
    ## ############# ##

    # OnGreetingListener
    vendor_package_bundle.listener.greeting_listener.generate_greeting:
        class: Vendor\Package\Classes\Listener\OnGreetingListener
        arguments: []
        tags:
            - { name: kernel.event_listener, event: greeting.event, method: generateGreeting }

    vendor_package_bundle.listener.greeting_listener_two.generate_message:
        class: Vendor\Package\Classes\Listener\OnGreetingListenerTwo
        arguments: []
        tags:
            - { name: kernel.event_listener, event: greeting.event, method: generateMessage }
```

### Event
Das Event enthält wie immer den Namen des Events in der Konstante `NAME`, die Eigenschaften und die Getter und Setter. Es erbt von `\Symfony\Component\EventDispatcher\Event`.

```php
<?php
# 05_Contao4/ROOT/src/Vendor/Package/Classes/Events/OnGreetingEvent.php
namespace Vendor\Package\Classes\Events;

use Symfony\Component\EventDispatcher\Event;

class OnGreetingEvent extends Event
{
    const NAME          = 'greeting.event';

    protected $name     = '';

    protected $message  = '';

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }
}
```

### Listener
Der Listener kümmert sich um die eigentliche Verarbeitung. Mit dem Aufruf des ersten Listeners `OnGreetingListener::generateGreeting()` wird der Gruß erzeugt.

```php
<?php
# 05_Contao4/ROOT/src/Vendor/Package/Classes/Listener/OnGreetingListener.php
namespace Vendor\Package\Classes\Listener;

use Vendor\Package\Classes\Events\OnGreetingEvent;

class OnGreetingListener
{
    public function generateGreeting(OnGreetingEvent $greetingEvent)
    {
        $name       = $greetingEvent->getName();
        $message    = "Hallo $name!";
        $greetingEvent->setMessage($message);
    }
}
```

Mit dem Aufruf des zweiten Listeners `OnGreetingListenerTwo::generateMessage()` wird die Botschaft erzeugt.

```php
<?php
# 05_Contao4/ROOT/src/Vendor/Package/Classes/Listener/OnGreetingListenerTwo.php
namespace Vendor\Package\Classes\Listener;

use Vendor\Package\Classes\Events\OnGreetingEvent;

class OnGreetingListenerTwo
{
    public function generateMessage(OnGreetingEvent $greetingEvent)
    {
        $message    = $greetingEvent->getMessage();
        $message   .= " Contao 4 ist toll!";
        $greetingEvent->setMessage($message);
    }
}
```

### Aufurf
Um nun ein Event aufrufen zu können, benötigt man einen EventDispatcher. Dieser kann über die System-Klasse von Contao mit `\System::getContainer()->get('event_dispatcher')` bezogen werden. Dann wird wie immer eine Instanz des Events erstellt, mit Daten befüllt und ausgelöst.

```php
<?php
# 05_Contao4/ROOT/src/Vendor/Package/Classes/Contao/Elements/ContentGreeting.php
namespace Vendor\Package\Classes\Contao\Elements;

use Vendor\Package\Classes\Events\OnGreetingEvent;

class ContentGreeting extends \ContentElement
{
    protected $strTemplate = 'ce_greetings';

    protected function compile()
    {
        if (TL_MODE == 'BE') {
            $this->strTemplate        = 'be_wildcard';
            $this->Template           = new \BackendTemplate('be_wildcard');
            $this->Template->wildcard = "### ContentGreeting ###";
        } else {
            $dispatcher = \System::getContainer()->get('event_dispatcher'); // ACHTUNG: schon wieder deprecated!
            $event      = new OnGreetingEvent();
            $event->setName($this->myname);
            $dispatcher->dispatch($event::NAME, $event);
            $this->Template->content = $event->getMessage();
        }
    }
}
```

Der Rest wie DCA, Language-Dateien usw. bleibt wie bei Contao 3.

### Verzeichnisstruktur

Hier ein Listing der Verzeichnisstruktur des Bundles:

```txt
ROOT/
└── src/
  └── Vendor
    └── Package
      ├── Classes
      │    ├── Contao
      │    │    └── Elements
      │    │      └── ContentGreeting.php       <-- Inhaltselement
      │    ├── ContaoManager
      │    │    └── ContaoManagerPlugin.php     <-- ManagerPlugin
      │    ├── Events
      │    │    └── OnGreetingEvent.php         <-- Event
      │    └── Listener
      │      ├── OnGreetingListener.php         <-- Listener
      │      └── OnGreetingListenerTwo.php      <-- 2. Listener
      ├── DependencyInjection
      │    └── VendorPackageExtension.php       <-- Laden der Listener
      ├── Resources
      │    ├── config
      │    │    └── listener.yml                <-- Konfiguration: EventLsitener
      │    └── contao
      │      ├── config
      │      │    └── config.php                <-- Contao: config.php
      │      ├── dca
      │      │    └── tl_content.php            <-- DCA
      │      ├── languages
      │      │    └── de
      │      │      ├── default.php             <-- Übersetzung CTE
      │      │      └── tl_content.php          <-- Übersetzung DCA
      │      └── templates
      │        └── ce_greetings.html5           <-- Ausgabetemplate
      └── VendorPackageBundle.php               <-- Somfony-Bundle-Datei```

Alle relevanten Dateien sind auch auf [Github](https://github.com/eS-IT/EventDrivenDevelopment_ck2018) zu finden. Sie liegen im Ordner `05_Contao4`. Dort kann alles nachvollzogen werden.
