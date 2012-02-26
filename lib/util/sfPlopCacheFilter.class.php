<?php

class sfPlopCacheFilter extends sfFilter
{
  public function execute($filterChain)
  {
    if (sfConfig::get('sf_cache', false) !== false)
    {
      $context = $this->getContext();
      if (class_exists('sfPlop'))
        $lifetime = sfPlop::get('sf_plop_cache_lifetime');
      else
        $lifetime = 86400;

      $context->getViewCacheManager()->addCache('sfPlopCMS', '_slot', array(
        'lifeTime' => $lifetime,
        'contextual' => false,
        'withLayout' => false
      ));
    }

    $filterChain->execute();
  }
}