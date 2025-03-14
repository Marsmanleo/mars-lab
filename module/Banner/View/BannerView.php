<?php


namespace Module\Banner\View;


use Illuminate\Support\Facades\View;

class BannerView
{
    public static function basic($position,
                                 $size = '1400x400',
                                 $ratio = null,
                                 $mobileRatio = null,
                                 $round = false,
                                 $container = false)
    {
        if (null === $size) {
            $size = '1400x400';
        }
        if (null === $ratio) {
            $ratio = '5-2';
        }
        $isMobile = 0;
        if (null === $mobileRatio) {
            $mobileRatio = $ratio;
        } else {
            $isMobile = 1;
        }
        if (null === $round) {
            $round = false;
        }
        if ($isMobile) {
            return View::make('module::Banner.View.inc.bannerm', [
                'position' => $position,
                'bannerSize' => $size,
                'bannerRatio' => $ratio,
                'mobileBannerRatio' => $mobileRatio,
                'round' => $round,
                'container' => $container,
            ])->render();
        } else {

            return View::make('module::Banner.View.inc.banner', [
                'position' => $position,
                'bannerSize' => $size,
                'bannerRatio' => $ratio,
                'mobileBannerRatio' => $mobileRatio,
                'round' => $round,
                'container' => $container,
            ])->render();
        }
    }
}
