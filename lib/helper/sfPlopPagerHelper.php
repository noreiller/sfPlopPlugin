<?php

/*
 * This file is part of the sfPagerNavigation package.
 * (c) 2004-2006 Francois Zaninotto <francois.zaninotto@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @package    sfPagerNavigation
 * @author     Francois Zaninotto <francois.zaninotto@symfony-project.com>
 * @version    SVN: $Id$
 */


/**
 * Includes an AJAX navigation.
 * If you provide a sfPager object, the helper automatically stops the periodical call
 * wen reaching the last page
 *
 * <b>Options:</b>
 * - url - 'module/action' or '@rule' of the AJAX action
 * - update - id of the paginated list
 * - page_name - name of the page request parameter, defaults to 'name'
 * - frequency - number of seconds between each position check, defaults to 1 second
 * - trigger - height in pixels, calculated from the bottom of the page, which triggers the AJAX call
 * ...as well as the usual remote_ helpers options
 *
 * @param  array Ajax options
 * @param  object optional sfPager object of the current pager
 * @return string XHTML code containing links
 */
function remote_pager($options = array(), $pager = null)
{
  if ($pager && ($pager->getNextPage() == $pager->getPage() || $pager->getPage()!= 1))
  {
    return;
  }

  // name of the page request parameter (default 'page')
  $options['page_name'] = isset($options['page_name']) ? $options['page_name'] : 'page';

  // frequency of the scroll check (default 1 second)
  $options['frequency'] = isset($options['frequency']) ? $options['frequency'] : 1;

  // scroll offset (in pixels, from the bottom) triggering the remote call (default 30px)
  $options['trigger']   = isset($options['trigger']) ? $options['trigger'] : '30';

  $options['position']  = isset($options['position']) ? $options['position']  : 'before';

  use_helper('Javascript');

  sfContext::getInstance()->getResponse()->addJavascript(sfConfig::get('sf_prototype_web_dir') . '/js/prototype');

  $javascript_callback = 'ajax_pager_semaphore = 0; ajax_pager_page++;';
  if ($pager)
  {
    // build in the stop of the PeriodicalExecuter when the pager reaches the last page
    $javascript_callback .= 'if (ajax_pager_page>'.$pager->getLastPage().') { pager_watch.callback = function () {}; };';
  }
  $options['success']   = isset($options['success']) ? $options['success'].$javascript_callback : $javascript_callback;

  return javascript_tag("
  var ajax_pager_semaphore = 0;
  var ajax_pager_page = 2;

  function sf_ajax_next_page()
  {
    if (ajax_pager_semaphore == 0)
    {
      ajax_pager_semaphore = 1;
      new Ajax.Updater(
        '".$options['update']."',
        '".url_for($options['url']).'?'.$options['page_name']."='+ajax_pager_page,
        "._options_for_ajax($options)."
      );
    }
  }

  pager_watch = new PeriodicalExecuter(function()
  {
     var scrollpos = window.pageYOffset || document.body.scrollTop || document.documentElement.scrollTop;
     var windowsize = window.innerHeight || document.documentElement.clientHeight;
     var testend = document.body.clientHeight - (windowsize + scrollpos);

     if ( (testend < ".$options['trigger'].") )
     {
       sf_ajax_next_page();
     }
  }, ".$options['frequency'].");");

}

function stop_remote_pager()
{
  use_helper('Javascript');

  // until prototype implements a stop() method for the PeriodicalExecuter,
  // the following (almost a hack) is the only simple way to stop it

  return javascript_tag("pager_watch.callback = function () {};");
}

/**
 * Outputs a regular navigation navigation.
 * It outputs a series of links to the first, previous, next and last page
 * as well as to the 5 pages surrounding the current page.
 *
 * @param  object sfPager object of the current pager
 * @param  string 'module/action' or '@rule' of the paginated action
 * @param  string optionnal anchor for the links
 * @return string XHTML code containing links
 */
function pager_navigation($pager, $uri, $anchor = null)
{
  $navigation = '';
  if ($pager->haveToPaginate())
  {
    $navigation = '<div class="w-pager">';
    $uri .= (preg_match('/\?/', $uri) ? '&' : '?').'page=';

    // First and previous page
    if ($pager->getPage() != 1)
    {
      $navigation .= link_to(__('first'), $uri . '1' . $anchor, 'class="pager first"');
      $navigation .= link_to(__('previous'), $uri . $pager->getPreviousPage() . $anchor, 'class="pager prev"');
    }

    $navigation .= "<ol>";

    if ($pager->getPage() > 5)
    {
      $navigation .= '<li>' . link_to("1", $uri . '1' . $anchor) . '</li>';
      $navigation .= '<li>&hellip;</li>';
    }
    // Pages one by one
    foreach ($pager->getLinks() as $page)
    {
      if ($page == $pager->getPage())
        $navigation .= '<li class="current pager">'.$page;
      else
        $navigation .= '<li>' . link_to($page, $uri . $page . $anchor, 'class="pager"');
      $navigation .= '</li>';
    }

    if (($pager->getPage() < ($pager->getLastPage() - 5)))
    {
      $navigation .= '<li>&hellip;</li>';
      $navigation .= '<li>' .link_to($pager->getLastPage(), $uri . $pager->getLastPage() . $anchor, 'class="pager"') . '</li>';
    }

    $navigation .= "</ol>";

    // Next and last page
    if ($pager->getPage() != $pager->getLastPage())
    {
      $navigation .= link_to(__('next'), $uri . $pager->getNextPage() . $anchor, 'class="pager next"');
      $navigation .= link_to(__('last'), $uri . $pager->getLastPage() . $anchor, 'class="pager last"');
    }

    $navigation .= " </div>";
  }

  return $navigation;
}



/**
 * A pagination with ajax links
 *
 * @param sfPager $pager The pager
 * @param String $uri The url of the ajax link
 * @param String $div The id of the element to update
 * @return String
 */
function pager_navigation_ajax($pager, $uri, $div){

  $navigation = '';

  if ($pager->haveToPaginate())
  {
    $uri .= (preg_match('/\?/', $uri) ? '&' : '?').'page=';

    // First and previous page
    if ($pager->getPage() != $pager->getFirstPage())
    {
      $navigation .= link_to_remote(' <<', array(
        'update' => $div,
        'url'    => $uri.'1',
        'complete' => visual_effect('appear', $div),
      )) ;
      $navigation .= link_to_remote(' < ', array(
        'update' => $div,
        'url'    => $uri.$pager->getPreviousPage(),
        'complete' => visual_effect('appear', $div),
      )) ;
    }

    // Pages one by one
    $links = array();
    foreach ($pager->getLinks() as $page)
    {
      if ($page == $pager->getPage())
      {
        $links[] = $page;
      }
      else
      {
        $links[] = link_to_remote($page, array(
          'update' => $div,
          'url'    => $uri.$page,
          'complete' => visual_effect('appear', $div),
        ));
      }
    }
    $navigation .= join('&nbsp;&nbsp;', $links);

    // Next and last page
    if ($pager->getPage() != $pager->getCurrentMaxLink())
    {
      $navigation .= '&nbsp;'.link_to_remote(' > ', array(
        'update' => $div,
        'url'    => $uri.$pager->getNextPage(),
        'complete' => visual_effect('appear', $div),
      ));
      $navigation .= '&nbsp;'.link_to_remote('>> ', array(
        'update' => $div,
        'url'    => $uri.$pager->getLastPage(),
        'complete' => visual_effect('appear', $div),
      ));
    }
  }

  return $navigation;
}

/**
 * @todo : virer cette fonction
 * Enter description here ...
 * @param unknown_type $pager
 * @param unknown_type $uri
 * @param unknown_type $anchor
 * @param unknown_type $mode
 */
function plemi_pager_navigation_complete_from_array($pager, $uri, $anchor = null, $mode = 'complete')
{
  $url = '@'.$uri['sf_route'];
  $first = false;
  foreach ($uri as $param => $value)
  {
    if ($param != 'sf_route')
    {
      if (!$first)
      {
        $first = true;
        $url .= '?'.$param.'='.$value;
      }
      else
      {
        $url .= '&'.$param.'='.$value;
      }
    }
  }

  return plemi_pager_navigation_complete($pager, $url, $anchor, $mode);
}

/**
 * Outputs a regular navigation navigation.
 * It outputs a series of links to the first, previous, next and last page
 * as well as to the 5 pages surrounding the current page.
 *
 * @param  object sfPager object of the current pager
 * @param  string 'module/action' or '@rule' of the paginated action
 * @param  string optionnal anchor for the links
 * @param  string optionnal mode to have a simple view
 * @return string XHTML code containing links
 */
function plemi_pager_navigation_complete($pager, $uri, $anchor = null, $mode = 'complete')
{
  if ($pager->haveToPaginate())
  {
    $navigation = '<div class="widget-pagination">';

    $uri .= (preg_match('/\?/', $uri) ? '&' : '?').'page=';

    // First and previous page
    $navigation .= '<ul>';

    $navigation .= ('<li>' . link_to_if(
      ($pager->getPage() != 1),
      content_tag('span', __('Première page'), 'class="widget-icon icon-first"'),
      $uri . '1' . $anchor,
      array(
        'class' => 'pager first widget-button ' . (($pager->getPage() == 1) ? 'widget-disabled' : ''),
        'title' => sfConfig::get('app_wording_link_title_prefix') . __('page %s', array('%s' => '1'))
      )) . '</li>'
    );
    $navigation .= ('<li>' . link_to_if(
      ($pager->getPage() != 1),
      content_tag('span', __('Page précédente'), 'class="widget-icon icon-previous"'),
      $uri . $pager->getPreviousPage() . $anchor,
      array(
        'class' => 'pager prev widget-button ' . (($pager->getPage() == 1) ? 'widget-disabled' : ''),
        'title' => sfConfig::get('app_wording_link_title_prefix') . __('page %s', array('%s' => $pager->getPreviousPage()))
      )) . '</li>'
    );

    $navigation .= '</ul>';
    $navigation .= "<ol>";

    if (($mode == 'complete') && ($pager->getPage() > 3))
    {
      $navigation .= ('<li>' . link_to(
        '1',
        $uri . '1' . $anchor,
        array(
          'class' => 'pager widget-button',
          'title' => sfConfig::get('app_wording_link_title_prefix') . __('page %s', array('%s' => '1'))
        )) . '</li>'
      );
      $navigation .= '<li>&hellip;</li>';
    }

    // Pages one by one
    $nb_pager_links = ($mode == 'complete') ? 5 : 3;
    foreach ($pager->getLinks($nb_pager_links) as $page)
    {
      $navigation .= ('<li>' . link_to_if(
        ($page != $pager->getPage()),
        $page,
        $uri . $page . $anchor,
        array(
          'class' => 'pager widget-button',
          'title' => sfConfig::get('app_wording_link_title_prefix') . __('page %s', array('%s' => $page))
        )) . '</li>'
      );
    }

    if (($mode == 'complete') && ($pager->getPage() < ($pager->getLastPage() - 3)))
    {
      $navigation .= '<li>&hellip;</li>';
      $navigation .= ('<li>' . link_to(
        $pager->getLastPage(),
        $uri . $pager->getLastPage() . $anchor,
        array(
          'class' => 'pager widget-button',
          'title' => sfConfig::get('app_wording_link_title_prefix') . __('page %s', array('%s' => $pager->getLastPage()))
        )) . '</li>'
      );
    }
    $navigation .= "</ol>";

    // Next and last page
    $navigation .= '<ul>';
    $navigation .= ('<li>' . link_to_if(
      ($pager->getPage() != $pager->getLastPage()),
      content_tag('span', __('Page suivante'), 'class="widget-icon icon-next"'),
      $uri . $pager->getNextPage() . $anchor,
      array(
        'class' => 'pager next widget-button ' . (($pager->getPage() == $pager->getLastPage()) ? 'widget-disabled' : ''),
        'title' => sfConfig::get('app_wording_link_title_prefix') . __('page %s', array('%s' => $pager->getNextPage()))
      )) . '</li>'
    );
    $navigation .= ('<li>' . link_to_if(
      ($pager->getPage() != $pager->getLastPage()),
      content_tag('span', __('Dernière page'), 'class="widget-icon icon-last"'),
      $uri . $pager->getLastPage() . $anchor,
      array(
        'class' => 'pager last widget-button ' . (($pager->getPage() == $pager->getLastPage()) ? 'widget-disabled' : ''),
        'title' => sfConfig::get('app_wording_link_title_prefix') . __('page %s', array('%s' => $pager->getLastPage()))
      )) . '</li>'
    );
    $navigation .= '</ul>';

    $navigation .= '</div>';

    return $navigation;
  }


}