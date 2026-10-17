<?php

namespace Composer\Autoload;

class ComposerStaticInit95b2e4a7bd4d4fa81972bddd9c6e9839
{
    public static $files = array (
        '2d88a0720960a2b70572088e035a7a1f' => __DIR__ . '/../..' . '/inc/template-tags.php',
        '77868adb9ce5210e35cfc636e6436087' => __DIR__ . '/../..' . '/inc/Lib/updater/theme-updater.php',
    );

    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'RT\\Clplace\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'RT\\Clplace\\' => 
        array (
            0 => __DIR__ . '/../..' . '/inc',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit95b2e4a7bd4d4fa81972bddd9c6e9839::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit95b2e4a7bd4d4fa81972bddd9c6e9839::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit95b2e4a7bd4d4fa81972bddd9c6e9839::$classMap;

        }, null, ClassLoader::class);
    }
}
